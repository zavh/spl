<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Client;
use App\Enquiry;
use DB;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $punalloc = Project::where('status', NULL)->get();
        foreach ($punalloc as $index=>$project){
            $client = Project::find($project->id)->client->organization;
            $enquiries = Project::find($project->id)->enquiries;
            $punalloc[$index]['client'] = $client;
            $punalloc[$index]['enq_count'] = count($enquiries);
        }
        $projects = Project::where('status', 0)->get();
        return view('projects.index')->with(['projects'=>$projects, 'punalloc'=>$punalloc]);
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
            'description' => 'required',
        ]);

        // Create Project
        $project = new Project;
        $project->project_name = $request->input('project_name');
        $project->client_id = $request->input('client_id');
        $project->user_id = Auth::User()->id;
        $project->deadline = $request->input('deadline');
        $project->description = $request->input('description');

        $project->save();
        $project_id = $project->id;

        // Create First Enquiry
        $enquiry_dat = $request->all();
        unset($enquiry_dat['_token']);
        $enquiry_dat = json_encode($enquiry_dat);

        $enquiryCreate = Enquiry::create([
            'project_id' => $project_id,
            'details' => $enquiry_dat
        ]);

        return redirect('/projects')->with('success', 'Project Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $assignment = Project::find($id);
        $assignment['client'] = Project::find($id)->client;
        $assignment['postedby'] = Project::find($id)->user->name;
        $assignment['tasks'] = Project::find($id)->tasks;
        $x = Project::find($id)->enquiries;
        for($i=0;$i<count($x);$i++){
            $enquiries[$i] = json_decode($x[$i]['details']);
        }
        $assignment['enquiries'] = $enquiries;
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
