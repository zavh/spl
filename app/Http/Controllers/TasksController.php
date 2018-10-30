<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Task;
use App\User;
use App\TaskUser;
use DB;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project_id = null)
    {
        if($project_id == null){
            abort(404);
        }
        $tasks = Task::where('project_id',$project_id)->get();
        return view('tasks.index', ['tasks'=>$tasks, 'project_id'=>$project_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id = null)
    {
        if($project_id == null){
            abort(404);    
        }
        $users = User::all();
        return view('tasks.create',['users'=>$users, 'project_id'=>$project_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $messages = [
            'task_name.required' => 'Please enter the task name',
            'task_name.min' => 'Task name must be minimum 2 characters',
            'task_name.max' => 'Task name cannot be more than 191 characters',
            'task_name.unique' => 'This task name has already been taken',
            'task_description.required' => 'Please enter the task description',
            'task_description.max' => 'Task name cannot be more than 3000 characters',
            'user_id.required' => 'please select an user',
            'task_date_assigned.required' => 'please pick a assignment date',
            'task_date_assigned.date' => 'The date assigned must be a valid date',
            'task_date_assigned.before_or_equal' => 'the date assigned cannot be after the deadline', 
            'task_deadline.required' => 'please pick a deadline',
            'task_deadline.date' => 'The deadline must be a valid date',
            'task_deadline.after_or_equal' => 'the deadline cannot be after the date assigned',
            'task_deadline.after' => 'the deadline cannot be before the system date'
        ];

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|min:2|max:191|unique:tasks,task_name',
            'task_description' => 'required|max:3000',
            'user_id' => 'required',
            'task_date_assigned' => 'required|date|before_or_equal:task_deadline',
            'task_deadline' => 'required|date|after_or_equal:task_date_assigned'
        ],$messages);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        
        $task = new Task;
        $task->task_name = $request->input('task_name');
        $task->task_description = $request->input('task_description');
        $task->project_id = $request->input('project_id');
        $task->task_date_assigned = $request->input('task_date_assigned');
        $task->task_deadline = $request->input('task_deadline');
        $task->weight = $request->input('weight');
        $task->save();

        $this->addTaskUser($task->id, $request->get('user_id'));
        
        return back()->with('success', 'Task Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($assignment == null)
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
        $users = User::where('id', '>', '1')->get();
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
        //dd($userarr[0]['detail']->id);
        return view('tasks.edit',['users'=>$userarr])->with('task', $task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'task_name.required' => 'Please enter the task name',
            'task_name.min' => 'Task name must be minimum 2 characters',
            'task_name.max' => 'Task name cannot be more than 191 characters',
            'task_description.required' => 'Please enter the task description',
            'task_description.max' => 'Task name cannot be more than 3000 characters',
            'user_id.required' => 'please select an user',
            'task_date_assigned.required' => 'please pick a assignment date',
            'task_date_assigned.date' => 'The date assigned must be a valid date',
            'task_date_assigned.before_or_equal' => 'the date assigned cannot be after the deadline', 
            'task_deadline.required' => 'please pick a deadline',
            'task_deadline.date' => 'The deadline must be a valid date',
            'task_deadline.after_or_equal' => 'the deadline cannot be after the date assigned',
            'task_deadline.after' => 'the deadline cannot be before the system date'
        ];

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|min:2|max:191',
            'task_description' => 'required|max:3000',
            'user_id' => 'required',
            'task_date_assigned' => 'required|date|before_or_equal:task_deadline',
            'task_deadline' => 'required|date|after_or_equal:task_date_assigned|after:today'
        ],$messages);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $task = Task::find($id);
        $task->task_name = $request->input('task_name');
        $task->task_description = $request->input('task_description');
        $task->user_id = $request->input('user_id');
        $task->task_date_assigned = $request->input('task_date_assigned');
        $task->task_deadline = $request->input('task_deadline');

        
        $task->save();

        return redirect('/tasks')->with('success', 'Task Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        // Check for correct user
        
        $task->delete();
        return redirect('/tasks')->with('success', 'Task Removed');
    }

    private function addTaskUser($task_id, $users){
        foreach($users as $user){
            $tuCreate = TaskUser::create([
                'task_id' => $task_id,
                'user_id' => $user
            ]);
        }
    }
}
