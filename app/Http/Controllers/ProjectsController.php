<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
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
        $breadcrumb[0]['title'] = 'Dashboard';
        $breadcrumb[0]['link'] = '/home';
        $breadcrumb[0]['style'] = '';
        $breadcrumb[1]['title'] = 'Project';
        $breadcrumb[1]['link'] = 'none';
        $breadcrumb[1]['style'] = 'active';
        $projects = Project::where('status', 0)->get();
        return view('projects.index', ['projects'=>$projects, 'punalloc'=>$punalloc, 'breadcrumb'=>$breadcrumb]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        $breadcrumb[0]['title'] = 'Dashboard';
        $breadcrumb[0]['link'] = '/home';
        $breadcrumb[0]['style'] = '';
        $breadcrumb[1]['title'] = 'Project';
        $breadcrumb[1]['link'] = '/projects';
        $breadcrumb[1]['style'] = '';
        $breadcrumb[2]['title'] = 'Create Project';
        $breadcrumb[2]['link'] = 'none';
        $breadcrumb[2]['style'] = 'active';
        $client_id = Session::get('client_id');
        $contacts = Session::get('contacts');
        $report_id = Session::get('report_id');
        
        if($client_id == null)
            return view('projects.create', ['clients'=>$clients,'breadcrumb'=>$breadcrumb]);
        else {
            if(is_null($report_id))
            return view('projects.create', ['clients'=>$clients,
                                            'breadcrumb'=>$breadcrumb, 
                                            'client_id'=>$client_id, 
                                            'contacts'=>implode(",",$contacts),
                                            'preload'=>'1']);
            else 
            return view('projects.create', ['clients'=>$clients,
                                            'breadcrumb'=>$breadcrumb, 
                                            'client_id'=>$client_id, 
                                            'contacts'=>implode(",",$contacts),
                                            'preload'=>'1',
                                            'report_id'=>$report_id]);
        }
            
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required',
            'client_id' => 'required',
            'deadline' => 'required|date|after_or_equal:today',
            ]);
        // Create Project
        if($validator->fails()){
            $response['status'] = 'failed';
            $response['messages'] = $validator->errors()->messages();
        }
        else {
            $project = new Project;
            $project->project_name = $request->input('project_name');
            $project->client_id = $request->input('client_id');
            $project->user_id = Auth::User()->id;
            $project->deadline = $request->input('deadline');
            $project->contacts = json_encode($request->input('contacts'));
            $project->allocation = 0;
    
            $project->save();
            //$project_id = $project->id;
            $response['status'] = 'success';
            $response['project_id'] = $project->id;
        }

        //return redirect('/projects')->with('success', 'Project Created');
        return response()->json(['response'=>$response]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);

        foreach($project->enquiries as $index=>$enquiry){
            $project->enquiries[$index]->details = json_decode($enquiry->details);
        }
        $project->contacts = json_decode($project->contacts);
        
        $breadcrumb[0]['title'] = 'Dashboard';
        $breadcrumb[0]['link'] = '/home';
        $breadcrumb[0]['style'] = '';
        $breadcrumb[1]['title'] = 'Project';
        $breadcrumb[1]['link'] = '/projects';
        $breadcrumb[1]['style'] = 'active';
        $breadcrumb[2]['title'] = $project->project_name;
        $breadcrumb[2]['link'] = 'none';
        $breadcrumb[2]['style'] = 'active';

        return view('projects.show',['project'=>$project, 'breadcrumb'=>$breadcrumb]);
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

        return redirect('/projects'.'/'.$project->id)->with('success', 'Project Updated');
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
	
	public function enquiries($project_id = null){
		$x = Project::find($project_id)->enquiries;
		$enquiries = array();
        for($i=0;$i<count($x);$i++){
            $enquiries[$i] = json_decode($x[$i]['details']);
        }
		//dd($enquiries);
		return view('projects.enquiries', ['enquiries'=>$enquiries]);
    }
    
    public function createclient(){
        if(Auth::Check())
            return view('clients.newclient');
        else 
            return view('partial.sessionexpired');
    }
}
