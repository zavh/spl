<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Salary;
use App\SalaryStructure;
use App\User;

class SalariesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $users = User::all();
        $salary = array();
        $tabheads = array('Basic', 'Name');
        $flag = 0;
        $count = 0;
        foreach($users as $index=>$user)
        {
            $pid = $user->salaryprofile;
            $sstructure = SalaryStructure::find($pid);

            $x = Salary::where('user_id',$user->id)->get();
            foreach($x as $salaries){
                $salary[$count]['basic'] = $salaries->basic;
                $salary[$count]['name'] = $salaries->user->name;
            }
            
            if(empty($sstructure)){
                $salary[$count] = null;
            }
            else {
                $ss = json_decode($sstructure->structure);
                
                foreach($ss as $breakdown=>$value){
                    if($breakdown == 'structurename') continue;
                    if($flag == 0)
                        $tabheads[count($tabheads)]=$breakdown;
                    $salary[$count][$breakdown] = ($salary[$count]['basic'] * $value)/100;
                }
                $flag = 1;
                $count++;
            }
        }        

       return view('salaries.index',['salaries'=>$salary, 'heads'=>$tabheads]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($page=NULL)
    {
        //
        $users = User::all();
        return view('salaries.create',['page'=>$page,'users'=> $users]);
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
        // dd($request);
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
            $salary = new Salary;
            $salary->user_id = $request->input('user_id');
            $salary->basic = $request->input('basic');
            
            $salary->save();
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
            $users = User::all();
            return view('salaries.show',['salaries'=>$salaries,'users'=> $users]);
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
        $user = User::where('id','=',$salaries->user_id)->get();
        return view('salaries.edit',['page'=>$page,'salaries'=>$salaries,'user'=>$user]);
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
        $user = User::where('id','=',$request->user_id)->get();
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
            $salaries->basic = $request->input('basic');
            $salaries->user_id = $request->input('user_id');
            $salaries->save();
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
