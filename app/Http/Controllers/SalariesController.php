<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Salary;

class SalariesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $salaries = Salary::all();
        return view('salaries.index')->with('salaries', $salaries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('salaries.create',['page'=>$page]);
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
            'basic.required' => 'basic|Required Field',
            'basic.unique' => 'sructurename|Must be unique',
            'basic.max' => 'basic|Maximum 255 characters',
            'basic.min' => 'basic|Minimum 3 Characters',
        ];

        $validator = Validator::make($request->all(), [
            'basic'=>'required'
            ],$messages);
        
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else {
            $salarystructure = new SalaryStructure;
            $salarystructure->basic = $request->input('basic');
            $salarystructure->save();
        }
        if($request->page == null)
            return redirect()->route('salaries.index');
        else{
            return redirect()->route('salaries.index')->with('success', 'Salary Created');
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
            $salaries = Salary::all();
            return view('salaries.show',['salaries'=>$salaries]);
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
        //
        $salaries = Salary::find($id);
        return view('salaries.edit',['page'=>$page,'salaries'=>$salaries]);
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
            'basic.required' => 'basic|Required Field',
            'basic.unique' => 'sructurename|Must be unique',
            'basic.max' => 'basic|Maximum 255 characters',
            'basic.min' => 'basic|Minimum 3 Characters',
        ];

        $validator = Validator::make($request->all(), [
            'basic'=>'required'
            ],$messages);
        
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else {
            $salaries = Salary::find($id);
            $salarystructure->basic = $request->input('basic');
            $salarystructure->save();
        }
        if($request->page == null)
            return redirect()->route('salaries.index');
        else{
            return redirect()->route('salaries.index')->with('success', 'Salary Created');
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
            return redirect('/salaries')->with('success', 'Salary deleted');
        }
        return back()->withInput()->with('success', 'Salary  could not be deleted');
    }
}
