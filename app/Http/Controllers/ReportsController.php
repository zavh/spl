<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Report;
use App\Client;
use App\Clientcontact;
use Carbon\Carbon;
use DateTime;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $report_of_month = array();
        $visit_date = array();
        $i=0;
        $j=0;
        $months = ['January', 'February', 'March', 
                    'April', 'May', 'June', 
                    'July','August', 'September', 
                    'October', 'November', 'December'];
        
        $current_month = date("Y-m");
        // dd($month);
        if(Auth::User()->role_id == 1){
            $reports = Report::where('completion',1)->get();
            $visits = Report::where('completion',0)->get();
        }
        else {
            $reports = Report::where(['completion'=>1, 'user_id'=>Auth::User()->id])->get();
            $visits = Report::where(['completion'=>0, 'user_id'=>Auth::User()->id])->get();            
        }
        foreach($reports as $report){
            $report->report_data = json_decode($report->report_data);
            $visit_date[$i] = $report->report_data->report_data->visit_date;
            $key = date("Y-m", strtotime($visit_date[$i]));
            if(isset($report_of_month[$key][$visit_date[$i]]))
                $j = count($report_of_month[$key][$visit_date[$i]]);
            else $j = 0;
            $report_of_month[$key][$visit_date[$i]][$j]['data'] = $report->report_data;
            $report_of_month[$key][$visit_date[$i]][$j]['id'] = $report->id;
            $i++; 
        }
        foreach($visits as $visit){
            $visit->report_data = json_decode($visit->report_data);
        }

        ksort($report_of_month);
        foreach($report_of_month as $month=>$date)
        {
             ksort($report_of_month[$month]); 
        }
        if(isset($report_of_month[$current_month])){
            $current_month_report = $report_of_month[$current_month];
            unset($report_of_month[$current_month]);
        }
        else 
        $current_month_report = null;
        // dd($report_of_month);
        return view('reports.index',['reports'=>$reports, 'visits'=>$visits, 
                            'months'=>$months,'current_month_report'=>$current_month_report,
                            'report_of_month'=>$report_of_month]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::Check()){
            $user = Auth::User();
            if($user->fname && $user->sname && $user->designation_id >0 && $user->designation_id >0)
                return view('reports.create');
            else {
                $messages = [
                    'fname.required' => 'Required for Visit Report creation',
                    'fname.min' => 'First name must be minimum 2 characters',
                    'fname.max' => 'first name cannot be more than 191 characters',
                    
                    'sname.required' => 'Required for Visit Report creation',
                    'sname.min' => 'surname must be minimum 2 characters',
                    'sname.max' => 'surname cannot be more than 191 characters',
                ];
                $x['fname'] = $user->fname;
                $x['sname'] = $user->sname;

                $validator = Validator::make($x, [
                    'fname'=>'required|min:2|max:191',
                    'sname'=>'required|min:2|max:191',
                ],$messages);
                return redirect("/users/".Auth::User()->id."/edit")->withErrors($validator);
            }
                
        }
        else 
        return redirect("/login");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::Check()){
            $input = $request->all();
            $report_data = json_encode($input);
            $client_id = $input['client_data']['id'];
            $reportCreate = Report::create([
                'user_id' => Auth::User()->id,
                'report_data' => $report_data,
                'client_id' => $client_id,
                'stage' => 1,
                'completion' => 0,
                'acceptance' => 0,
                ]);

            $response['view'] = view('reports.stage2')->render();
            $response['status'] = 'success';
            $response['report_id'] = $reportCreate->id;
            return response()->json(['result'=>$response]);
        }
        else {
            $response['status'] = 'failed';
            $response['message'] = 'not logged in';
        }
    }

    public function show($id)
    {
        $report = Report::find($id);
        if($report->project)
            $ids['project'] = $report->project->id;
        else 
            $ids['project'] = NULL;
        $ids['report'] = $id;
        
        $report_data = json_decode($report->report_data);
        $report_dates['created'] = date("d-M-Y",strtotime($report->created_at));
        $report_dates['submitted'] = date("d-M-Y",strtotime($report->updated_at));
        return view('reports.show',['report'=>$report_data, 'dates'=>$report_dates , 'ids'=>$ids]);
    }

    public function edit($id)
    {
        if(Auth::Check()){
            $report = Report::find($id);
            if($report->completion == 0){
                if($report->user_id == Auth::User()->id || Auth::User()->role_id == 1){
                    $rc_user = User::find($report->user_id);
                    return view('reports.edit', ['report_id'=>$id, 'rc_user'=>$rc_user]);
                }
                else 
                    return redirect('/home');
            }
            else {
                return view('reports.show', ['report'=>$report]);
            }
        }
        else 
            return redirect('/login');
    }

    public function update(Request $request, $id)
    {
        $x = $request->stage;
        $report = Report::find($id);
        
        $report->report_data = json_encode($request->all());
        
        $report->stage = $x;

        if(isset($request->report_data)){
            $report->visit_date = $request->report_data['visit_date'];
            $report->organization = $request->client_data['organization'];
        }
        else if(isset($request->visit_date)){
            $report->visit_date = $request->report_data['visit_date'];
        }
        if(isset($request->report_data->client_data)){
            $report->organization = $request->report_data->client_data['organization'];
        }
        $report->save();
        $result['status'] = 'success';
        $result['function'] = 'update';
        $result['stage'] = $request->stage;
        $result['report_id'] = $report->id;
        //determining the stage of the report
        
        if($x == 2){
            $z = json_decode($report->report_data);
            $result['view'] = view('reports.stage2',['report_data'=>$z->report_data])->render();
        }
        else $result['view'] = view('reports.stage2')->render();
        $result['data'] = $request->all();
        return response()->json(['result'=>$result]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $report = Report::find($id);

        if($report->delete()){
            return back()
            ->with('success', 'Report deleted successfully');
        }

        return back()->withInput()->with('error', 'Report could not be deleted');
    }

    public function clientdetails($id)
    {
        $report = Report::find($id);
        return response()->json(['result'=>$report]);
    }

    public function stage2view($id){
        $report = Report::find($id);
        $y = $report->report_data;
        $x = json_decode($y);
        return view('reports.stage2',['report_data'=>$x->report_data]);
    }

    public function submit(Request $request, $id){
        $report_data = $request->all();
        $messages = [
            'visit_date.max' => 'Visit date cannot be in future',
        ];
        $validator = Validator::make($report_data, [
            'visit_date'=>'required|date|before_or_equal:today',
            'meeting_issue'=>'required|min:4',
            'requirement_details'=>'required|min:4',
            'product_discussed'=>'required|min:4',
            'outcome_brief'=>'required|min:4',
        ],$messages);
        if($validator->fails()){
            $result['status'] = 'failed';
            $result['messages'] = $validator->errors()->messages();
        }
        else {
            $report = Report::find($id);  
            $orig_report_data = json_decode($report->report_data);
            unset($orig_report_data->_token);
            unset($orig_report_data->_method);
            unset($orig_report_data->stage);
            unset($orig_report_data->client_index);

            $client = $orig_report_data->client_data;
            unset($client->contactview);
            if(array_key_exists('created_at',$client)){
                $client->contacts = $this->setReportContacts($client->contacts, $client->id);
            }
            else {
                $clientCreate = Client::create([
                    'organization' => $client->organization,
                    'address' => $client->address,
                    'background' => $client->background,
                    'department_id' => Auth::User()->department_id,
                    ]);
                    $client->contacts = $this->setReportContacts($client->contacts, $clientCreate->id);
                $report->client_id = $clientCreate->id;
            }
            $orig_report_data->client_data = $client;
            $orig_report_data->report_data = $report_data;
            $orig_report_data->rc_user_id = Auth::User()->id;
            $orig_report_data->rc_user_name = Auth::User()->fname." ".Auth::User()->sname;
            $orig_report_data->rc_user_department = Auth::User()->department->name;
            $orig_report_data->rc_user_designation = Auth::User()->designation->name;
            
            $report->report_data = json_encode($orig_report_data);
            $report->completion = 1;
            $report->stage = 2;
            $report->save();
            $result['status'] = 'success';
        }
        return response()->json(['result'=>$result]);
    }

    private function setReportContacts($contacts, $client_id){
        $sortedContacts = array();
        $count = 0;
        for($i=0;$i<count($contacts);$i++){
            if($contacts[$i]->selected){
                $sortedContacts[$count] = $contacts[$i];
                if(!$contacts[$i]->dbflag){
                    $contactCreate = Clientcontact::create([
                        'name' => $contacts[$i]->name,
                        'designation' => $contacts[$i]->designation,
                        'contact' => $contacts[$i]->contact,
                        'client_id' => $client_id,
                        ]);
                    $sortedContacts[$count] = $contactCreate;
                }
                $count++;
            }
        }
        return $sortedContacts;
    }

    public function rtop($report_id){
        $report = Report::find($report_id);
        $rd = json_decode($report->report_data);
        $client_id = $rd->client_data->id;
        $cc = $rd->client_data->contacts;
        for($i=0;$i<count($cc);$i++){
            $contacts[$i] = $cc[$i]->id;
        }
        return redirect()->route('projects.create')->with(['client_id'=>$client_id, 'contacts'=>$contacts, 'report_id'=>$report_id]);
    }
    public function widget(){
        $x = User::find(Auth::User()->id)->reports;
        $complete = array();
        $incomplete = array();
        foreach($x as $index=>$report){
            if($report->completion == 0){
                $incomplete[$index]['data'] = json_decode($report->report_data);
                $incomplete[$index]['id'] = $report->id;
            }
            else {
                $complete[$index]['data'] = json_decode($report->report_data);
                $complete[$index]['id'] = $report->id;
            }
        }
        return view('reports.widget', ['complete'=>$complete, 'incomplete'=>$incomplete]);
    }
    public function search(Request $request)
    {
        $searched_month_report = array();
        $visit_date = array();
        $i=0;
        $j=0;
        $current_month = date("Y-m");
        $criteria = $request->all();
        $wc = array();
        $whereclause = "";
        $user = User::where('name','=',$criteria['reportuser'])->get();
        if(count($user)>0)
        {
            $user_id = $user->first()->id;
        }
        else
        {
            $user_id = 0;
        }
        // dd($user);
         $messages = [
            'reportmonthstart.required' => 'valid',
            'reportmonthend.required' => 'valid',
            'reportuser.required' => 'valid',
            'reportorganization.required' =>'valid'
        ];
        $validator = Validator::make($criteria, [
            'reportmonthstart'=>'required|date',
            'reportmonthend'=>'required|date|after_or_equal:reportmonthstart',
            'reportorganization'=>'required',
            'reportuser'=>['required',
                function($attribute, $value, $fail) use($user_id){
                    if($user_id == 0)
                        $fail('Invalid user');
                }
            ]
         ],$messages);

        //validating start and end date starts
        if($validator->errors()->has('reportmonthstart')){
            $x = $validator->errors()->first('reportmonthstart');
            // error_log("reportmonthstart->".$x);
            if($x != 'valid'){
                $result['msgs'] = $validator->errors();
                $result['status'] = 'failed';
                return response()->json(['result'=>$result]);
            }
            else {
                $start = false;
            }
        }
        else $start = $criteria['reportmonthstart'];

        if($validator->errors()->has('reportmonthend')){
            $x = $validator->errors()->first('reportmonthend');
            // error_log("reportmonthend->".$x);
            if($x != 'valid'){
                $result['msgs'] = $validator->errors();
                $result['status'] = 'failed';
                return response()->json(['result'=>$result]);
            }
            else {
                $end = $start;
            }
        }
        else $end = $criteria['reportmonthend'];

        if($start != false)
            $wc[count($wc)] = "visit_date BETWEEN '$start' AND '$end'";

        //validating users
        if($validator->errors()->has('reportuser')){
            $x = $validator->errors()->first('reportuser');
            // error_log("reportuser->".$x);
            if($x != 'valid'){
                $result['msgs'] = $validator->errors();
                $result['status'] = 'failed';
                return response()->json(['result'=>$result]);
            }
        }
        else $wc[count($wc)] = "user_id = ".$user_id;

        //validating organization
        if($validator->errors()->has('reportorganization')){
            $x = $validator->errors()->first('reportorganization');
            // error_log("reportorganization->".$x);
            if($x != 'valid'){
                $result['msgs'] = $validator->errors();
                $result['status'] = 'failed';
                return response()->json(['result'=>$result]);
            }
            else unset($x);
        }
        else $wc[count($wc)] = "organization LIKE '%".$criteria['reportorganization']."%'";

        // echo "<pre>";
        // echo count($wc);
        // // echo $wc[count($wc)];
        // echo "</pre>";
        //no imput received
        if(count($wc) == 0){
            // $result['msgs'] = $validator->errors();
            $result['msgs'] = "No input found!";
            $result['flag'] = 0;
            $result['status'] = 'failed';
            return response()->json(['result'=>$result]);
        }

        $whereclause = implode(' and ',$wc);
        
/////////////////////where function implementation///////////////////////
        $reports = DB::table('reports')->whereRaw($whereclause)->get();
            if(count($reports)>0){

            foreach($reports as $report){
                $report->report_data = json_decode($report->report_data);
                $visit_date[$i] = $report->report_data->report_data->visit_date;
   //             $key = date("Y-m", strtotime($visit_date[$i]));
                if(isset($searched_month_report[$visit_date[$i]]))
                    $j = count($searched_month_report[$visit_date[$i]]);
                else $j = 0;
                $searched_month_report[$visit_date[$i]][$j]['data'] = $report->report_data;
                $searched_month_report[$visit_date[$i]][$j]['id'] = $report->id;
                $i++; 
            }
            //ksort($searched_month_report);
            foreach($searched_month_report as $month=>$date)
            {
                 ksort($searched_month_report[$month]); 
            }
}
            // dd($searched_month_report);
/////////////////////////////////////////under construction////////////////////////////////////
                //$target = $reports->first()->id;
            $result['status'] = 'success';
            $result['data'] = $searched_month_report;
            $result['view'] =  view('reports.showreportlist', ['current_month_report'=>$searched_month_report])->render();
                //$result['target'] = $target;
            // $result1['count'] = count($reports);
            return response()->json(['result'=>$result]);
/////////////////////////////////////////under construction////////////////////////////////////
        
    }
}