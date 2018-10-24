<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Task;
use App\User;
use DB;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index')->with('tasks',$tasks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $users = User::all();
        return view('tasks.create',['users'=>$users]);
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
            'task_name.min' => 'Minimum 2 characters'
            //'new-password.required' => 'Please enter password',
            //'new-password.min' => 'New Password needs to be at least 8 characters long',
            //'password_confirmation.same' => 'Password Confirmation and New Password must match'
        ];

        $sabit = Validator::make($request->all(), [
            'task_name' => 'required|min:2|max:191|unique:tasks,task_name',
            'task_description' => 'required|max:3000',
            'user_id' => 'required',
            'task_date_assigned' => 'required|date|before_or_equal:task_deadline',
            'task_deadline' => 'required|date|after_or_equal:task_date_assigned'
        ],$messages);

        if($sabit->fails()){
            return back()->withErrors($sabit)->withInput();
        }
        
        $task = new Task;
        $task->task_name = $request->input('task_name');
        $task->task_description = $request->input('task_description');
        $task->user_id = $request->input('user_id');
        $task->task_date_assigned = $request->input('task_date_assigned');
        $task->task_deadline = $request->input('task_deadline');

        
        $task->save();

        return redirect('/tasks')->with('success', 'Task Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $assignment = Task::find($id);
        return view('tasks.show')->with('assignment',$assignment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $assignment = Task::find($id);
        return view('tasks.edit')->with('assignment', $assignment);
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
        $this->validate($request, [
            'task_name' => 'required',
            'task_description' => 'required',
            'user_id' => 'required',
            'task_date_assigned' => 'required',
            'task_deadline' => 'required'
        ]);

        // Create Post
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
}
