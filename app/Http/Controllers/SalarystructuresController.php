<?php

namespace App\Http\Controllers;

use App\SalaryStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SalarystructuresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function create($page=null)
    {
        //
        return view('salarystructures.create',['page'=>$page]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $messages = [
            'structurename.required' => 'structurename|Required Field',
            'structurename.unique' => 'sructurename|Must be unique',
            'structurename.max' => 'structurename|Maximum 255 characters',
            'structurename.min' => 'structurename|Minimum 3 Characters',

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
            'structurename'=>'required|min:3|max:255|unique:salarystructures,structurename',
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
            $salarystructure = new SalaryStructure;
            $salarystructure->structurename = $request->input('structurename');
            $salarystructure->houserent = $request->input('houserent');
            $salarystructure->medicalallowance = $request->input('medicalallowance');
            $salarystructure->conveyance = $request->input('conveyance');
            $salarystructure->providentfundcompany = $request->input('pf_company');
            $salarystructure->providentfundself = $request->input('pf_self');
            $salarystructure->save();
        }
        if($request->page == null)
            return redirect()->route('salarystructures.index');
        else{
            return redirect()->route('salarystructures.index')->with('success', 'Salary Structure Created');
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
        //
        if(Auth::Check()){ 
            $salarystructure = SalaryStructure::find($id);
            return view('salarystructures.show',['salarystructure'=>$salarystructure]);
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
    public function edit($id,$page=null)
    {
        //
        $salarystructure = SalaryStructure::find($id);
        return view('salarystructures.edit',['page'=>$page,'salarystructure'=>$salarystructure]);
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
            $salarystructure->structurename = $request->input('structurename');
            $salarystructure->houserent = $request->input('houserent');
            $salarystructure->medicalallowance = $request->input('medicalallowance');
            $salarystructure->conveyance = $request->input('conveyance');
            $salarystructure->providentfundcompany = $request->input('pf_company');
            $salarystructure->providentfundself = $request->input('pf_self');
            $salarystructure->save();
        }
        if($request->page == null)
            return redirect()->route('salarystructures.index');
        else{
            return redirect()->route('salarystructures.index')->with('success', 'Salary Structure Created');
        }
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
        $salarystructure = SalaryStructure::find($id);
        // dd($department);
        if($salarystructure->delete())
        {
            return redirect('/salarystructures')->with('success', 'Salary Structure deleted');
        }
        return back()->withInput()->with('success', 'Salary Structure could not be deleted');
    }
}
