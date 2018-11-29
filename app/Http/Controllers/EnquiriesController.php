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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project_id)
    {
        //
        if($project_id == null)
            abort(404);
        // dd($project_id);
        $enquiries = Project::find($project_id)->enquiries;
 
        for($i=0;$i<count($enquiries);$i++){
            $enquiries[$i]['details'] = json_decode($enquiries[$i]['details']);
        }

        return view('enquiries.index')->with('enquiries',$enquiries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id=null)
    {
        //
        return view('enquiries.create')->with('project_id',$project_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project_id = $request->get('project_id');
        $enquiry_dat = $request->all();
        // dd($request);
        unset($enquiry_dat['_token']);
        

        $project_id = $enquiry_dat['project_id'];
        $type = $enquiry_dat['type'];
        if(isset($enquiry_dat['surftype'])) 
        {
            $surftype = $enquiry_dat['surftype'];
            $subtype = NULL;
        }
        else
        {
            $surftype = NULL;
            $subtype = $enquiry_dat['subtype'];
        }
        $pumphead = $enquiry_dat['pumphead'];
        $pumpcap = $enquiry_dat['pumpcap'];
        $liquid = $enquiry_dat['liquid'];
        $liqtemp = $enquiry_dat['liqtemp'];
        $description = $enquiry_dat['description'];

        // dd($project_id,$type,$surftype,$subtype,$pumphead,$pumpcap,$liquid,$liqtemp,$description);

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'surftype' => 'required_without:subtype',
            'subtype' => 'required_without:surftype',
            'pumphead' => 'required',
            'pumpcap' => 'required',
            'liquid' => 'required',
            'liqtemp' => 'required',
            'description' => 'required'

        ]);

        if($validator->fails()){

            $response['status'] = 'failed';
            $response['messages'] = $validator->errors()->messages();
            return response()->json(['result'=>$response]);
        }
        
        $enquiry_dat = json_encode($enquiry_dat);
        $enquiry = new Enquiry;
        $enquiry->project_id = $project_id;
        $enquiry->details =  $enquiry_dat;
        
        $enquiry->save();

        $response['status'] = 'success';
        $response['project_id'] = $project_id;
        return response()->json(['result'=>$response]);
        // return redirect('/projects/'.$project_id)->with('success', 'Enquiry saved');
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
    public function edit($id)
    {
        //

        $enquiry = Enquiry::find($id);
        // dd($id);
        $x = $enquiry->details;
        // for($i=0;$i<count($x);$i++){
        //     $enquiries[$i]['details'] = json_decode($x[$i]['details']);
        // }
        $details = json_decode($x);
        //dd($details);
        $project_id = $details->project_id;
        // dd($project_id);
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
        // dd($enquiry1);
        $enquiry->details = $details;
        // dd($enquiry);
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
