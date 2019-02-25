<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Task;
use App\User;
use App\Enquiry;
use App\Project;
use App\TaskUser;
use DB;

class EnquiriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $enquiries = Enquiry::all();
        for($i=0;$i<count($enquiries);$i++){
            $enquiries[$i]['details'] = json_decode($enquiries[$i]['details']);
        }
        return view('enquiries.index')->with('enquiries',$enquiries);
    }
    public function projectenq($project_id=null)
    {
        if($project_id == null)
            abort(404);
     
        $enquiries = Project::find($project_id)->enquiries;
 
        for($i=0;$i<count($enquiries);$i++){
            $enquiries[$i]['details'] = json_decode($enquiries[$i]['details']);
        }
        return view('projectenqindex.index')->with('enquiries',$enquiries);
    }

    public function projectenqcreate($project_id=null)
    {
        if($project_id == null)
            abort(404);
        $project = Project::find($project_id)->project_name;
        $breadcrumb[0]['title'] = 'Project';
        $breadcrumb[0]['link'] = '/projects';
        $breadcrumb[0]['style'] = '';
        $breadcrumb[1]['title'] = $project;
        $breadcrumb[1]['link'] = '/projects'.'/'.$project_id;
        $breadcrumb[1]['style'] = '';
        $breadcrumb[2]['title'] = 'Create Enquiry';
        $breadcrumb[2]['link'] = 'none';
        $breadcrumb[2]['style'] = 'active';
        return view('enquiries.create', ['breadcrumb'=>$breadcrumb,'project_id'=>$project_id]);
    }

    public function store(Request $request)
    {
        // Create First Enquiry
        $project_id = $request->get('project_id');
        $enquiry_dat = $request->all();
        unset($enquiry_dat['_token']);
        $enquiry_dat = json_encode($enquiry_dat);

        $enquiry = new Enquiry;
        $enquiry->project_id = $project_id;
        $enquiry->details =  $enquiry_dat;
        
        $enquiry->save();

        return redirect('/projects'.'/'.$project_id)->with('success', 'Enquiry created');
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
        if($assignment == null)
            abort(404);
        $assignment = Enquiry::find($id);
        return view('enquiries.show')->with('assignment',$assignment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function projectenqedit($id)
    {
        $enquiry = Enquiry::find($id);
        $x = $enquiry->details;
        $details = json_decode($x);
        $project_id = $details->project_id;
        return view('enquiries.edit',['project_id'=>$project_id,'enquiry'=>$enquiry,'details'=>$details]);
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
        //
        $enquiry_id = $id;//got enquiry id
        $project_id = $request->project_id;//got project id

        $details = $request->all();
        unset($details['_token']);
        unset($details['_method']);
        $details = json_encode($details);//got details

        $enquiry = Enquiry::find($id);
        $enquiry->details = $details;
        
        $enquiry->save();
        return back()->with('success', 'Enquiry Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $findEnq = Enquiry::find($id);
        // dd($findEnq);
        if($findEnq->delete()){
            return back()->with('success', 'Enquiry deleted successfully');
        }

        return back()->withInput()->with('error', 'Enquiry could not be deleted');
    }
}
