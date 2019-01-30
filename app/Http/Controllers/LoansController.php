<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;///////////////
use App\Project;
use App\Client;
use App\User;
use App\Enquiry;
use App\Report;
use App\Loan;
use App\Salary;
use DB;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        // $loans = Loan::all();
        return view('loans.index', ['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('loans.create');
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
        if(Auth::check()){
            // $messages = [
            //     'role_name.required' => 'Please enter the role name',
            //     'role_name.min' => 'role name must be minimum 2 characters',
            //     'role_name.max' => 'role name cannot be more than 191 characters',
            //     'role_name.unique' =>'role name has already been taken',

            //     'role_description.required' => 'Please enter the email',
            //     'role_description.max' => 'role description cannot be more than 3000 characters'
            // ];

            // $validator = Validator::make($request->all(), [
            //     'role_name' =>'required|min:2|max:191|unique:roles,role_name',
            //     'role_description'=>'required|max:3000'
            // ],$messages);

            // if($validator->fails()){
            //     return back()->withErrors($validator)->withInput();
            //     //$abc = back()->withErrors($validator)->withInput();
            //     //dd($abc);
            // }
            $loan = new loan;
            $username = $request->username;
            $user = User::where('name',$username)->get()->first();
            $uid = $user->id;
            $salary = Salary::where('user_id',$uid)->get()->first();
            $sid = $salary->id;
            
            $loan->salary_id = $sid;
            $loan->loan_name =  $request->input('loan_name');
            $loan->amount =  $request->input('amount');
            $loan->start_date =  $request->input('start_date');
            $loan->end_date =  $request->input('end_date');
            $loan->installments =  $request->input('installments');
            $loan->loan_type =  $request->input('loan_type');
            $loan->interest =  $request->input('interest');

            $loan->save();

            return redirect('/loans')->with('success', 'New Loan Created');
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
        $loan = Loan::find($id);
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
        $users = User::all();
        return view('loans.create');
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
    }
}
