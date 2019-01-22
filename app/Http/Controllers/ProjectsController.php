<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;///////////////
use App\Project;
use App\Client;
use App\User;
use App\Enquiry;
use App\Report;
use DB;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $i=0;
        $today = date("Y-m-d");
        $searched_project = array();
        $open = Project::where('status', 0)->where("deadline", ">=", $today)->get();
        $expired = Project::where("status",0)->where("deadline", "<", $today)->orderBy('id', 'asc')->get();
        $closedprojects = Project::where("status",">",0)->orderBy('id', 'asc')->take(5)->get();
        $breadcrumb[0]['title'] = 'Dashboard';
        $breadcrumb[0]['link'] = '/home';
        $breadcrumb[0]['style'] = '';
        $breadcrumb[1]['title'] = 'Project';
        $breadcrumb[1]['link'] = 'none';
        $breadcrumb[1]['style'] = 'active';
        $projects = Project::where('allocation','=', 100)->get();
        return view('projects.index', ['projects'=>$projects, 'open'=>$open, 'breadcrumb'=>$breadcrumb,'closed'=>$closedprojects,'expired'=>$expired]);
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
        if($report_id ==  NULL) $report_id = 0;
        if($client_id == null)
            return view('projects.create', ['clients'=>$clients,'breadcrumb'=>$breadcrumb, 'report_id'=>$report_id]);
        else {
            return view('projects.create', ['clients'=>$clients,
                                            'breadcrumb'=>$breadcrumb, 
                                            'client_id'=>$client_id, 
                                            'contacts'=>implode(",",$contacts),
                                            'preload'=>'1',
                                            'report_id'=>$report_id,]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required',
            'client_id' => 'required',
            'start_date' => 'required|date|before:deadline',
            'deadline' => 'required|date|after_or_equal:start_date',
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
            $project->start_date = $request->input('start_date');
            $project->deadline = $request->input('deadline');
            $project->contacts = json_encode($request->input('contacts'));
            $project->report_id = $request->input('report_id');;
            $project->allocation = 0;
            $project->department_id = Auth::User()->department_id;
    
            $project->save();
            if($request->input('report_id')>0){
                $report = Report::find($request->input('report_id'));
                $report->acceptance = 1;
                $report->save();
            }

            $response['status'] = 'success';
            $response['project_id'] = $project->id;
        }
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
        // dd($request);
        $this->validate($request, [
            'status' => 'required'
        ]);
        
        $project = Project::find($id);
        $project->status = $request->input('status');

        $project->save();
        // dd($project);

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

    public function timeline($project_id){
        $x = Project::find($project_id);
        return view('projects.projecttimeline',['project'=>$x]);
    }

    public function searchform(){
        if(Auth::Check())
            return view('projects.searchform');
        else 
            return view('partial.sessionexpired');
    }
    public function searchclient(){
        $clients = Client::all();
        for($i=0;$i<count($clients);$i++){
            $names[$i] = $clients[$i]->organization;
            $mapping[$clients[$i]->organization] = $clients[$i]->id;
        }
        $result['names'] = $names;
        $result['mapping'] = $mapping;
        return response()->json(['result'=>$result]);
    }

    public function search(Request $request)
    {
        $searched_project = array();
        $start_date = array();
        $i=0;
        $j=0;
        if($request['projectmonthstart'] == $request['projectmonthend'])
            $criteria = $request->except(['_token','projectmonthend']);
        else $criteria = $request->except('_token');
        $wc = array();
        $whereclause = "";
        
        $manager = User::where('name','=',$criteria['projectmanager'])->get();
        
        $client_id = $request->client_id;

        if(count($manager)>0)
        {
            $manager_id = $manager->first()->id;
        }
        else
        {
            $manager_id = 0;
        }

        $validator = Validator::make($criteria, [
            'projectmonthstart'=>'nullable|date',
            'projectmonthend'=>'nullable|date|after_or_equal:projectmonthstart',
            'projectclient'=>['nullable',
                function($attribute, $value, $fail) use($client_id){
                if($client_id < 0)
                    $fail('Invalid client');
            }
        ],
            'projectmanager'=>['nullable',
                function($attribute, $value, $fail) use($manager_id){
                    if($manager_id == 0)
                        $fail('Invalid manager');
                }
            ]
         ]);

        if($validator->fails()){
            $result['msgs'] = $validator->errors();
            $result['status'] = 'failed';
            return response()->json(['result'=>$result]);
        }

        $start = $request['projectmonthstart'];
        $end = $request['projectmonthend'];

        if($start != false)
            $wc[count($wc)] = "start_date BETWEEN '$start' AND '$end'";
        if($client_id>0)
            $wc[count($wc)] = "client_id = ".$client_id;

        if(count($wc) == 0){
            $result['msgs'] = "No input found!";
            $result['flag'] = 0;
            $result['status'] = 'failed';
            return response()->json(['result'=>$result]);
        }
        else {
            $wc[count($wc)] = "status > 0";
        }
        $whereclause = implode(' and ',$wc);
        
        $projects = Project::whereRaw($whereclause)->get();

        $result['status'] = 'success';
        $result['data'] = $projects;
        //$result['whereclause'] = $wc;
        $result['view'] =  view('projects.projectlist', ['projects'=>$projects,'status'=>'closed'])->render();

        return response()->json(['result'=>$result]);
        
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
