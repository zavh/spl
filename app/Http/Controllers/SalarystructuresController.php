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
        //
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
        $ss = SalaryStructure::where('structurename','config')->get()->first();
        
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

        //dd($structure);
        $validator = Validator::make($structure, $vlogic);
        
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else {
            $salarystructure = new SalaryStructure;
            $salarystructure->structure = json_encode($fields['data']);
            $salarystructure->structurename = $fields['structurename'];
            $salarystructure->save();

            echo "success";
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
        $salaryinfo = SalaryStructure::find($id);
        $salaryid = $salaryinfo->id;
    
        $salarystructure = json_decode($salaryinfo->structure);
        return view('salarystructures.show',['salarystructure'=>$salarystructure, 'id'=>$id, 'name'=>$salaryinfo->structurename]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$page=null)
    {
        //
        $salaryinfo = SalaryStructure::find($id);
        $salaryid = $salaryinfo->id;
        // dd($salary);
        $salarystructure = json_decode($salaryinfo->structure);
        // dd($salarystructure);
        return view('salarystructures.edit',['page'=>$page,'salaryinfo'=>$salaryinfo,'salarystructure'=>$salarystructure]);
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
        $messages = [
            'houserent.max' => 'houserent|Maximum 100',
            'houserent.min' => 'houserent|minimum 0',

            'medicalallowance.max' => 'medicalallowance|Maximum 100',
            'medicalallowance.min' => 'medicalallowance|minimum 0',

            'conveyance.max' => 'conveyance|Maximum 100',
            'conveyance.min' => 'conveyance|minimum 0',

            'pf_company.max' => 'pf_company|Maximum 100',
            'pf_company.min' => 'pf_company|minimum 0',

            'pf_self.max' => 'pf_self|Maximum 100',
            'pf_self.min' => 'pf_self|minimum 0',
        ];

        $validator = Validator::make($request->all(), [
            'houserent' => 'numeric|max:100|min:0',
            'medicalallowance' => 'numeric|max:100|min:0',
            'conveyance' => 'numeric|max:100|min:0',
            'pf_company' => 'numeric|max:100|min:0',
            'pf_self' => 'numeric|max:100|min:0'
            ],$messages);
        
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else {
            $salarystructure = SalaryStructure::find($id);
            $structure = $request->all();
            unset($structure['_token']);
            unset($structure['page']);
            $structure = json_encode($structure);
            $salarystructure->structure = $structure;
            $salarystructure->structurename = $request->input('structurename');
            $salarystructure->save();
        }
        if($request->page == null)
            return redirect()->route('salarystructures.index');
        else{
            return redirect()->route('salarystructures.index')->with('success', 'Salary Structure Created');
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
        $ss = SalaryStructure::where('structurename','config')->get();
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
        
        $ss = SalaryStructure::where('structurename','config')->get();
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
