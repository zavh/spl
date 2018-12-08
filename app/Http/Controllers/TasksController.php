<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Task;
use App\User;
use App\Project;
use App\TaskUser;
use DB;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function index($project_id = null)
    {
        if($project_id == null){
            abort(404);
        }
        $tasks = Task::orderBy('task_deadline','ASC')->where('project_id',$project_id)->get();
        return view('tasks.index', ['tasks'=>$tasks, 'project_id'=>$project_id]);
    }

    public function create($project_id = null)
    {
        if($project_id == null){
            abort(404);    
        }
        $users = User::all()->where('active',1);
        $project = Project::find($project_id);
        $allocation = $project->allocation;
        if($allocation>=100)
        {
            return redirect('tasks.index')->with('success', 'Allocation limit reached');
        }
        return view('tasks.create',['users'=>$users, 'project_id'=>$project_id,'allocation'=>$allocation]);
    }

    public function store(Request $request)
    {        
        $allocation = $request->allocation;
        $weight = $request->get('weight');
        $project = Project::find($request->input('project_id'));

        $project_deadline = $project->deadline;
        $project_start = $project->start_date;

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|min:2|max:191',
            'task_description' => 'required|max:300',
            'task_deadline' => ['required', 'date',
                            function($attribute, $value, $fail) use($project_deadline, $project_start){
                                $td = strtotime($value);
                                $pd = strtotime($project_deadline);
                                $ps = strtotime($project_start);
                                if($pd<$td)
                                    $fail('Task Deadline cannot be later than Project deadline');
                                if($td<$ps)
                                    $fail('Task Deadline cannot be earlier than Project start date');
                            }
                        ],
            'weight' => ['required','integer',
                            function($attribute, $value, $fail) use($allocation){
                                if($value + $allocation > 100)
                                    $fail($attribute.' results in allocation to exceed 100%');
                            }
                        ]
        ]);

        if($validator->fails()){
            $response['status'] = 'failed';
            $response['messages'] = $validator->errors()->messages();
            return response()->json(['result'=>$response]);
        }
        
        $task = new Task;
        $task->task_name = $request->input('task_name');
        $task->task_description = $request->input('task_description');
        $task->project_id = $request->input('project_id');
        $task->task_deadline = $request->input('task_deadline');
        $task->weight = $request->input('weight');
        $task->save();
        
        $project->allocation = $allocation+$weight;
        $project->save();

        $this->addTaskUser($task->id, $request->get('user_id'));
        $response['result'] = 'success';
        $response['project_id'] = $request->input('project_id');
        $response['new_alloc'] = $allocation + $weight;
        return response()->json(['result'=>$response]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id == null)
            abort(404);
        $assignment = Task::find($id);
        return view('tasks.show')->with('assignment',$assignment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($task_id)
    {
        //
        $users = User::where([['id', '>', '1'],['active','=','1']])->get();
        $task = Task::find($task_id);
        $userarr = array();
        $i = 0;
        foreach($users as $user){
            foreach($task->users as $taskuser)
            {   
                $userarr[$i]['detail'] = $user;
                if($user->id == $taskuser->id){
                    $userarr[$i]['selected'] = "selected";
                }
                else{
                    if(isset($userarr[$i]['selected'])){
                        if($userarr[$i]['selected'] != "selected")
                        $userarr[$i]['selected'] = "";
                    }
                    else 
                        $userarr[$i]['selected'] = "";
                } 
            }
            $i++;
        }
        
        $project = Project::find($task->project_id);
        $allocation = $project->allocation;
        return view('tasks.edit',['users'=>$userarr,'allocation'=>$allocation])->with('task', $task);
    }

    public function update(Request $request, $id)
    {
        $allocation = $request->allocation;
        $new_weight = $request->weight;
        $old_weight = $request->old_weight;
        $project = Project::find($request->project_id);
        $project_deadline = $project->deadline;
        $project_start = $project->start_date;

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|min:2|max:191',
            'task_description' => 'required|max:3000',
            'task_deadline' => ['required', 'date',
                function($attribute, $value, $fail) use($project_deadline, $project_start){
                    $td = strtotime($value);
                    $pd = strtotime($project_deadline);
                    $ps = strtotime($project_start);
                    if($pd<$td)
                        $fail('Task Deadline cannot be later than Project deadline');
                    if($td<$ps)
                        $fail('Task Deadline cannot be earlier than Project start date');
                }
            ],
            'weight' => ['required','integer',
                function($attribute, $value, $fail) use($allocation, $old_weight){
                    if($value + $allocation - $old_weight > 100)
                        $fail($attribute.' results in allocation to exceed 100%');
                }
            ]
        ]);

        if($validator->fails()){
            $response['status'] = 'failed';
            $response['messages'] = $validator->errors()->messages();
            return response()->json(['result'=>$response]);
        }

        $task = Task::find($id);
        $task->task_name = $request->input('task_name');
        $task->task_description = $request->input('task_description');
        $task->task_deadline = $request->input('task_deadline');
        $task->weight = $request->input('weight');

        $new_allocation = $allocation-$old_weight+$new_weight;
        $project->allocation = $new_allocation;
        $project->save();
        
        $task->save();
        $this->deleteTaskUser($task->id, $request->get('user_id'));
        $this->addTaskUser($task->id, $request->get('user_id'));

        $response['status'] = 'success';
        $response['project_id'] = $request->get('project_id');
        $response['new_alloc'] = $new_allocation;
        return response()->json(['result'=>$response]);
        
    }

    public function destroy($id)
    {       
        $findTask = Task::find($id);
        
        $weight = $findTask->weight;
        $project_id = $findTask->project_id;
        // dd($project_id);
        $project = Project::find($project_id);
        $project_allocation = $project->allocation;

        if($findTask){
            $project->allocation = $project_allocation - $weight;
            $project->save();
            $this->deleteTaskUser($id);
            $findTask->delete();
            return back()->with('success', 'Task deleted successfully');
        }
        return back()->withInput()->with('error', 'Task could not be deleted');
    }

    private function addTaskUser($task_id, $users){
        foreach($users as $user){
            $tuCreate = TaskUser::create([
                'task_id' => $task_id,
                'user_id' => $user
            ]);
        }
    }

    private function deleteTaskUser($task_id){
        $taskUsers = TaskUser::where('task_id',$task_id)->get();
        foreach($taskUsers as $taskuser){
            $taskuser->delete();
        }
    }

    public function completion(Request $request,$id)
    {
        // dd($request);
        
        $checkedstatus = $request['done-'.$id];
        $date = $request['done-date-'.$id];

        $task = Task::find($id);
        $weight = $task->weight;  

        $project = Project::find($task->project_id);
        $completed = $project->completed;

        $currentdate = date('Y-m-d');

        if ($checkedstatus == "on") 
        {
            if($task->completed == 0)
            {
                $task->completed = 1;
                $task->date_completed = $date;              
                $project->completed +=$weight;
                $task->save();
                $project->save();
                // dd($request->get('date_completed'));
                return back()->with('success', 'Task updated successfully');
            }
            else//task completed
            {
                return back()->withInput()->with('success', 'task already completed');
            }
        } 
        else//checkedstatus = off 
        {
            if($task->completed == 1)
            {
                $task->completed = 0;
                $task->date_completed = NULL;              
                $project->completed -=$weight;
                $task->save();
                $project->save();
                
                return back()->with('success', 'Task updated successfully');
            }
            else//task already incomplete
            {
                return back()->withInput()->with('success', 'Task already marked incomplete');
            }
        }
    }

    public function showcompletion()
    {
        $ctasks = User::find(Auth::User()->id)->tasks->where('completed',1);
        return view('users.usercompletedtasks')->with('tasks',$ctasks);
    }
}
