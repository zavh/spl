<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
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
        return view('tasks.create');
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
        $this->validate($request, [
            'task_name' => 'required',
            'task_description' => 'required',
            'user_id' => 'required',
            'task_date_assigned' => 'required',
            'task_deadline' => 'required'
        ]);

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
