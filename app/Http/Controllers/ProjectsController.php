<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Client;
use DB;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $projects = Project::all();
        //return view('projects.index', ['projects'=>$projects, 'roles'=>$roles]);
        return view('projects.index')->with('projects',$projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = DB::table('clients')->get();
        return view('projects.create', ['clients'=>$clients]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'project_name' => 'required',
            'client_id' => 'required',
            'deadline' => 'required',
        ]);
dd($request);
        // Create Project
        // $project = new Project;
        // $project->project_name = $request->input('project_name');
        // $project->client_id = $request->input('client_id');
        // $project->user_id = $request->input('user_id');
        // $project->manager_id = $request->input('manager_id');
        // $project->assigned = $request->input('assigned');
        // $project->deadline = $request->input('deadline');
        // $project->description = $request->input('description');

        // $project->save();

        // return redirect('/projects')->with('success', 'Project Created');
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
        $assignment = Project::find($id);
        return view('projects.show')->with('assignment',$assignment);
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
        $assignment = Project::find($id);
        return view('projects.edit')->with('assignment', $assignment);
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
        //$project = Project::find($id);
        //return dd($request);
        $this->validate($request, [
            'project_name' => 'required',
            'client_id' => 'required',
            'user_id' => 'required',
            'manager_id' => 'required',
            'assigned' => 'required',
            'deadline' => 'required',
            'description' => 'required',
            'status' => 'required',
            'state' => 'required'
        ]);

        //Create Project
        $project = Project::find($id);
        $project->project_name = $request->input('project_name');
        $project->client_id = $request->input('client_id');
        $project->user_id = $request->input('user_id');
        $project->manager_id = $request->input('manager_id');
        $project->assigned = $request->input('assigned');
        $project->deadline = $request->input('deadline');
        $project->description = $request->input('description');
        $project->status = $request->input('status');
        $project->state = $request->input('state');

        $project->save();

        return redirect('/projects')->with('success', 'Project Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        // Check for correct user
        
        $Project->delete();
        return redirect('/projects')->with('success', 'Project Removed');
    }
}
