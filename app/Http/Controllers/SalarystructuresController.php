<?php

namespace App\Http\Controllers;

use App\SalaryStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SalarystructuresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $structures = SalaryStructure::all();
        return view('salarystructures.index')->with('structures', $structures);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ss = SalaryStructure::withoutGlobalScope('excfg')->where('structurename','config')->get()->first();
        
        return view('salarystructures.create',["config"=>json_decode($ss->structure)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->all();
        $vlogic['structurename'] = 'required|min:3|max:255|unique:salarystructures,structurename';
        $structure['structurename'] = $fields['structurename'];
        for($i=0;$i<count($fields['data']);$i++){
            $vlogic[$fields['data'][$i]['param_name']] = 'numeric|max:100|min:0';
            $structure[$fields['data'][$i]['param_name']] = $fields['data'][$i]['value'];
        }

        $validator = Validator::make($structure, $vlogic);
        
        if($validator->fails()){
            $response['status'] = 'failed';
            $response['messages'] = $validator->errors()->messages();
            return response()->json(['result'=>$response]);
        }
        else {
            $salarystructure = new SalaryStructure;
            $salarystructure->structure = json_encode($fields['data']);
            $salarystructure->structurename = $fields['structurename'];
            $salarystructure->save();

            $response['status'] = 'success';
            return response()->json(['result'=>$response]);
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
        if(Auth::Check()){    
            $salaryinfo = SalaryStructure::find($id);
            $salaryid = $salaryinfo->id;
        
            $salarystructure = json_decode($salaryinfo->structure);
            return view('salarystructures.show',['salarystructure'=>$salarystructure, 'id'=>$id, 'name'=>$salaryinfo->structurename]);
        }
        else {
            return view('partial.sessionexpired');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salaryinfo = SalaryStructure::find($id);
        $salarystructure = json_decode($salaryinfo->structure);
        $sname = $salaryinfo->structurename;
    
        return view('salarystructures.edit',['sname'=>$sname,'sid'=>$id,'salarystructure'=>$salarystructure]);
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
        $fields = $request->all();
        $salarystructure = SalaryStructure::find($id);
        if($salarystructure->structurename != $fields['structurename']){
            $vlogic['structurename'] = 'required|min:3|max:255|unique:salarystructures,structurename';
        }
        $structure['structurename'] = $fields['structurename'];
        for($i=0;$i<count($fields['data']);$i++){
            $vlogic[$fields['data'][$i]['param_name']] = 'numeric|max:100|min:0';
            $structure[$fields['data'][$i]['param_name']] = $fields['data'][$i]['value'];
        }

        $validator = Validator::make($structure, $vlogic);
        
        if($validator->fails()){
            $response['status'] = 'failed';
            $response['messages'] = $validator->errors()->messages();
            return response()->json(['result'=>$response]);
        }
        else {
            $salarystructure->structure = json_encode($fields['data']);;
            $salarystructure->structurename = $request->input('structurename');
            $salarystructure->save();
            $response['status'] = 'success';
            return response()->json(['result'=>$response]);
        }
    }

    public function destroy($id)
    {
        $salarystructure = SalaryStructure::find($id);
        
        if($salarystructure->delete())
        {
            return redirect()->route('salarystructures.index')->with('success', 'Salary Structure deleted');
        }
        return back()->withInput()->with('success', 'Salary Structure could not be deleted');
    }

    public function config(){
        $ss = SalaryStructure::withoutGlobalScope('excfg')->where('structurename','config')->get();
        if(count($ss) == 0){
            $response['status'] = 'failed';
            $response['numfields'] = 0;
            $response['message'] = 'No Configuration found. Create one';
        }
        else{
            $config = $ss->first();
            $response['status'] = 'success';
            $data = json_decode($config->structure, true);
            $response['numfields'] = count($data);
            $response['data'] = $data;
        }
        return view('salarystructures.config',['response'=>$response]);
    }

    public function addparam(Request $request){
        return view('salarystructures.configsnippet',['data'=>$request->data,'index'=>$request->index]);
    }

    public function saveconfig(Request $request){
        
        $ss = SalaryStructure::withoutGlobalScope('excfg')->where('structurename','config')->get();
        if(count($ss) == 0){
            //creating the config
            $ssCreate = SalaryStructure::create([
                'structurename' => 'config',
                'structure' => json_encode($request->data)
                ]);
            
            if($ssCreate){
                echo "success";
            }
            else
             echo "failed";
        }
        else{
            $updatess = $ss->first();
            $updatess->structure = json_encode($request->data);
            $updatess->save();
        }
    }
}
