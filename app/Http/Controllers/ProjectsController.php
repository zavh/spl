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
////////////////////////////////////////////////////////////////////////////////////////
    public function search(Request $request)
    {
        $searched_project = array();
        $start_date = array();
        $i=0;
        $j=0;
        $current_month = date("Y-m");
        $criteria = $request->all();
        $wc = array();
        $whereclause = "";
        $client = Client::where('organization','=',$criteria['projectclient'])->get();
        $manager = User::where('name','=',$criteria['projectmanager'])->get();
        
        if(count($client)>0)
        {
            $client_id = $client->first()->id;
        }
        else
        {
            $client_id = 0;
        }
        if(count($manager)>0)
        {
            $manager_id = $manager->first()->id;
        }
        else
        {
            $manager_id = 0;
        }
         $messages = [
            'projectmonthstart.required' => 'valid',
            'projectmonthend.required' => 'valid',
            'projectclient.required' => 'valid',
            'projectmanager.required' =>'valid'
        ];
        $validator = Validator::make($criteria, [
            'projectmonthstart'=>'required|date',
            'projectmonthend'=>'required|date|after_or_equal:projectmonthstart',
            'projectclient'=>['required',
                function($attribute, $value, $fail) use($client_id){
                if($client_id == 0)
                    $fail('Invalid client');
            }
        ],
            'projectmanager'=>['required',
                function($attribute, $value, $fail) use($manager_id){
                    if($manager_id == 0)
                        $fail('Invalid manager');
                }
            ]
         ],$messages);

        //validating start and end date starts
        if($validator->errors()->has('projectmonthstart')){
            $x = $validator->errors()->first('projectmonthstart');
            if($x != 'valid'){
                $result['msgs'] = $validator->errors();
                $result['status'] = 'failed';
                return response()->json(['result'=>$result]);
            }
            else {
                $start = false;
            }
        }
        else $start = $criteria['projectmonthstart'];

        if($validator->errors()->has('projectmonthend')){
            $x = $validator->errors()->first('projectmonthend');
            if($x != 'valid'){
                $result['msgs'] = $validator->errors();
                $result['status'] = 'failed';
                return response()->json(['result'=>$result]);
            }
            else {
                $end = $start;
            }
        }
        else $end = $criteria['projectmonthend'];

        if($start != false)
            $wc[count($wc)] = "start_date BETWEEN '$start' AND '$end'";

        //validating users
        if($validator->errors()->has('projectclient')){
            $x = $validator->errors()->first('projectclient');
            if($x != 'valid'){
                $result['msgs'] = $validator->errors();
                $result['status'] = 'failed';
                return response()->json(['result'=>$result]);
            }
        }
        else $wc[count($wc)] = "client_id = ".$client_id;

        //validating organization
        //validating users
        if($validator->errors()->has('projectmanager')){
            $x = $validator->errors()->first('projectmanager');
            if($x != 'valid'){
                $result['msgs'] = $validator->errors();
                $result['status'] = 'failed';
                return response()->json(['result'=>$result]);
            }
        }
        else $wc[count($wc)] = "manager_id = ".$manager_id;
        $wc[count($wc)] = "state = 1";

        if(count($wc) == 0){
            $result['msgs'] = "No input found!";
            $result['flag'] = 0;
            $result['status'] = 'failed';
            return response()->json(['result'=>$result]);
        }

        $whereclause = implode(' and ',$wc);
        
/////////////////////where function implementation///////////////////////
        $projects = DB::table('projects')->whereRaw($whereclause)->get();//where clause works
        // echo json_encode($projects,JSON_PRETTY_PRINT);
        if(count($projects)>0)
        {
            foreach($projects as $project)
            {
                $start_date[$i] = $project->start_date;
                if(isset($searched_project[$start_date[$i]]))
                    $j = count($searched_project[$start_date[$i]]);
                else $j = 0;
                $searched_project[$start_date[$i]][$j] = $project;
                $i++; 
            }
            // foreach($searched_project as $month=>$date)
            // {
            //      ksort($searched_project[$month]); 
            // }
            //echo json_encode($searched_project,JSON_PRETTY_PRINT);
            // echo $projects; 
        }

        $result['status'] = 'success';
        $result['data'] = $projects;
        //$result['data'] = $searched_project;
        $result['view'] =  view('projects.showprojectlist', ['searched_project'=>$projects])->render();

        return response()->json(['result'=>$result]);
        
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
