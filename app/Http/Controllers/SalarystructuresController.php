<?php

namespace App\Http\Controllers;

use App\SalaryStructure;
use App\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SalarystructuresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
    }

    public function index()
    {
        return view('salarystructures.index');   
    }
    public function getall(){
        $s = SalaryStructure::all();
        
        for($i=0;$i<count($s);$i++){
            $s[$i]['structure'] = json_decode($s[$i]['structure']);
        }
        return response()->json($s);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ss = Configuration::where('name','headconfig')->first();
        if($ss == null){
            $response['status'] = 'failed';
            $response['message'] = 'Could not find Salary Structure configuration';
        }
        else {
            $response['status'] = 'success';
            $response['data'] = json_decode($ss->data);
        }
        return response()->json($response);
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
        $structure['structurename'] = $fields['structurename'];

        $namelogic['structurename'] = 'required|min:3|max:255|unique:salarystructures,structurename';

        $nv = Validator::make($structure, $namelogic);

        if($nv->fails()){
            $response['status'] = 'failed';
            $response['messages']['name'] = $nv->errors()->messages();
            // return response()->json(['result'=>$response]);
        }
        $structlogic[-1]['defualt_value'] = 'required|gte:0';
        $structlogic[0]['profile_field'] = 'required';

        $structlogic[1]['percentage'] = 'required|gte:0|lte:100|numeric';
        $structlogic[1]['threshold'] = 'required|gte:0|numeric';
        
        $structlogic[2]['fixed_value'] = 'required|gt:0|numeric';

        $structlogic[3]['default_valuetype'] = 'required|gt:0|numeric';

        $structlogic[4]['fnname'] = 'required|min:4';

        $structure = json_decode($fields['structure'], true);
        foreach($structure as $key=>$value){
            $dv = $structure[$key]['default_valuetype'];
            $temp = Validator::make($structure[$key], $structlogic[$dv]);
            $sv[$key] = $temp->errors()->messages();
        }
        
        // $sv = Validator::make($structure['basic'], $structlogic[0]);
        // if($sv->fails()){
        //     $response['messages']['basic'] = $sv->errors()->messages();
        // }
        return response()->json($sv);
        // $vlogic['structurename'] = 'required|min:3|max:255|unique:salarystructures,structurename';
        // $structure['structurename'] = $fields['structurename'];
        // for($i=0;$i<count($fields['data']);$i++){
        //     $vlogic[$fields['data'][$i]['param_name']] = 'numeric|max:100|min:0';
        //     $structure[$fields['data'][$i]['param_name']] = $fields['data'][$i]['value'];
        // }

        // $validator = Validator::make($structure, $vlogic);
        
        // if($validator->fails()){
        //     $response['status'] = 'failed';
        //     $response['messages'] = $validator->errors()->messages();
        //     return response()->json(['result'=>$response]);
        // }
        // else {
        //     $salarystructure = new SalaryStructure;
        //     $salarystructure->structure = json_encode($fields['data']);
        //     $salarystructure->structurename = $fields['structurename'];
        //     $salarystructure->save();

        //     $response['status'] = 'success';
        //     return response()->json(['result'=>$response]);
        // }
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
