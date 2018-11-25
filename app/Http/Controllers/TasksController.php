<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Task;
use App\User;
use App\Project;
use App\TaskUser;
use DB;

class TasksController extends Controller
{

    //catch allocation exceeded for create using js
    //catch allocation exceeded for edit using js

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
        $project = Project::find($project_id);
        $allocation = $project->allocation;
        // dd($allocation);
        if($allocation>=100)
        {
            return redirect('tasks.index')->with('success', 'Allocation limit reached');
        }
        return view('tasks.create',['users'=>$users, 'project_id'=>$project_id,'allocation'=>$allocation]);
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
        // $messages = [
        //     'task_name.required' => 'Please enter the task name',
        //     'task_name.min' => 'Task name must be minimum 2 characters',
        //     'task_name.max' => 'Task name cannot be more than 191 characters',
        //     'task_name.unique' => 'This task name has already been taken',
        //     'task_description.required' => 'Please enter the task description',
        //     'task_description.max' => 'Task name cannot be more than 3000 characters',
        //     'user_id.required' => 'please select an user',
        //     'task_date_assigned.required' => 'please pick a assignment date',
        //     'task_date_assigned.date' => 'The date assigned must be a valid date',
        //     'task_date_assigned.before_or_equal' => 'the date assigned cannot be after the deadline', 
        //     'task_deadline.required' => 'please pick a deadline',
        //     'task_deadline.date' => 'The deadline must be a valid date',
        //     'task_deadline.after_or_equal' => 'the deadline cannot be after the date assigned',
        //     'task_deadline.after' => 'the deadline cannot be before the system date'
        // ];

        // $validator = Validator::make($request->all(), [
        //     'task_name' => 'required|min:2|max:191|unique:tasks,task_name',
        //     'task_description' => 'required|max:3000',
        //     'user_id' => 'required',
        //     'task_date_assigned' => 'required|date|before_or_equal:task_deadline',
        //     'task_deadline' => 'required|date|after_or_equal:task_date_assigned'
        // ],$messages);

        // if($validator->fails()){
        //     return back()->withErrors($validator)->withInput();
        // }
        
        $allocation = $request->allocation;
        $weight = $request->get('weight');

        // dd('allocation',$allocation,'weight',$weight);

        if ($allocation+$weight>100) {
            return back()->with('success', 'total allocation crosses 100%');
        }
        $task = new Task;
        $task->task_name = $request->input('task_name');
        $task->task_description = $request->input('task_description');
        $task->project_id = $request->input('project_id');
        $task->task_date_assigned = $request->input('task_date_assigned');
        $task->task_deadline = $request->input('task_deadline');
        $task->weight = $request->input('weight');
        $task->save();
        $project = Project::find($request->input('project_id'));
        $project->allocation = $allocation+$weight;
        // dd($project->allocation);
        $project->save();

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
        //dd($userarr);
        $project = Project::find($task->project_id);
        $allocation = $project->allocation;
        // $project_id = $task->project_id;
        // dd($allocation);//getting corect allocation
        // if($allocation>=100)
        // {
        //     return redirect('tasks.index')->with('success', 'Allocation limit reached');
        // }
        return view('tasks.edit',['users'=>$userarr,'allocation'=>$allocation])->with('task', $task);
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
        //dd($request);
        // $messages = [
        //     'task_name.required' => 'Please enter the task name',
        //     'task_name.min' => 'Task name must be minimum 2 characters',
        //     'task_name.max' => 'Task name cannot be more than 191 characters',
        //     'task_description.required' => 'Please enter the task description',
        //     'task_description.max' => 'Task name cannot be more than 3000 characters',
        //     'user_id.required' => 'please select an user',
        //     'task_date_assigned.required' => 'please pick a assignment date',
        //     'task_date_assigned.date' => 'The date assigned must be a valid date',
        //     'task_date_assigned.before_or_equal' => 'the date assigned cannot be after the deadline', 
        //     'task_deadline.required' => 'please pick a deadline',
        //     'task_deadline.date' => 'The deadline must be a valid date',
        //     'task_deadline.after_or_equal' => 'the deadline cannot be after the date assigned',
        //     'task_deadline.after' => 'the deadline cannot be before the system date'
        // ];

        // $validator = Validator::make($request->all(), [
        //     'task_name' => 'required|min:2|max:191',
        //     'task_description' => 'required|max:3000',
        //     'user_id' => 'required',
        //     'task_date_assigned' => 'required|date|before_or_equal:task_deadline',
        //     'task_deadline' => 'required|date|after_or_equal:task_date_assigned|after:today'
        // ],$messages);

        // if($validator->fails()){
        //     return back()->withErrors($validator)->withInput();
        // }
        
        $allocation = $request->allocation;
        $new_weight = $request->get('weight');
        $old_weight = $request->old_weight;

        // dd('allocation',$allocation,'new_weight',$new_weight,'old_weight',$old_weight);
        if(($allocation-$old_weight+$new_weight)>100)
        {
            return back()->with('success', 'Allocation limit reached');
        }
        $task = Task::find($id);
        $task->task_name = $request->input('task_name');
        $task->task_description = $request->input('task_description');
        //$task->project_id = $request->input('project_id');
        $task->task_date_assigned = $request->input('task_date_assigned');
        $task->task_deadline = $request->input('task_deadline');
        $task->weight = $request->input('weight');

        $project = Project::find($request->project_id);
        // dd($project->id);
        $project->allocation = $allocation-$old_weight+$new_weight;
        // dd($project->allocation);
        $project->save();
        
        $task->save();
        $this->deleteTaskUser($task->id, $request->get('user_id'));
        $this->addTaskUser($task->id, $request->get('user_id'));

        return back()->with('success', 'Task Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        $findTask = Task::find($id);
        
        $weight = $findTask->weight;
        $project_id = $findTask->project_id;
        // dd($project_id);
        $project = Project::find($project_id);
        $project_allocation = $project->allocation;
        // dd($project_allocation);



        // dd('allocation',$project_allocation,'weight',$weight);
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
        // foreach($users as $user){
        //     $tasks = TaskUser::where('task_id', '=', $task_id)->get();
        //     //dd($task_id,$task);
        //     foreach($tasks as $task){
        //         $task->delete();
        //     }            
        // }

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

        // dd('checkedstatus',$checkedstatus,'date',$date,'weight',$weight,'completed',$completed,'currentdate',$currentdate);

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
                // dd($request->get('date_completed'));
                return back()->with('success', 'Task updated successfully');
            }
            else//task already incomplete
            {
                return back()->withInput()->with('success', 'Task already marked incomplete');
            }
        }
    }
}
