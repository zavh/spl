<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Report;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
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

            $reportCreate = Report::create([
                'user_id' => Auth::User()->id,
                'report_data' => json_encode($input),
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $x = $request->stage;
        $report = Report::find($id);
        $report->report_data = json_encode($request->all());
        $report->stage = $x;
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
}
