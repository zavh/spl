<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;///////////////
use App\User;
use App\Loan;
use App\Department;
use App\Salary;
use DB;
use Carbon\Carbon;

class LoansController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('loans.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salary_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value < 1) {
                        $fail('An employee has not been selected');
                    }
                }],
            'amount'=>'required|numeric',
            'loan_name'=>'required|max:32',
            'start_date'=>'required|date',
            'tenure'=>'required|integer',
            'interest'=>'required|numeric',
            'loan_type' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value == "0") {
                        $fail('A loan type has not been selected');
                    }
                }],
        ]);

        if($validator->fails()){
            $response['status'] = 'failed';
            $response['errors'] = $validator->errors()->messages();
        }
        else{
            $tenure = $request->tenure;
            $start_date = date("Y-m-01", strtotime($request->start_date));
            $end_date = date("Y-m-t", strtotime("+$tenure months",strtotime($start_date)));

            $loanCreate = Loan::create([
                'salary_id' => $request->salary_id,
                'loan_name' => $request->loan_name,
                'amount' => $request->amount,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'loan_type' => $request->loan_type,
                'interest' => $request->interest,
                'tenure' => $tenure,
                ]);

            $response['status'] = 'success';
            $response['loan']['name'] = $loanCreate->salary->user->name." - ".$loanCreate->salary->user->fname." ".$loanCreate->salary->user->sname;
            $response['loan']['id'] = $loanCreate->id;
            $response['loan']['params']['Loan Title'] = $loanCreate->loan_name;
            $response['loan']['params']['Amount'] = $loanCreate->amount;
            $response['loan']['params']['Start Date'] = $loanCreate->start_date;
            $response['loan']['params']['End Date'] = $loanCreate->end_date;
            $response['loan']['params']['Tenure'] = $loanCreate->tenure;
            $response['loan']['params']['Interest'] = $loanCreate->interest;
            $response['loan']['params']['Lone Type'] = $loanCreate->loan_type;
            
        }
        return response()->json($response);
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
        
        $loan = Loan::find($id);
        $response['department'] = $loan->salary->user->department->name;
        $response['name'] = "Employee ID : ".$loan->salary->user->name." | Employee Name : ".$loan->salary->user->fname." ".$loan->salary->user->sname;
        $response['data'] = $loan;
        return response()->json($response);
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
        $validator = Validator::make($request->all(), [
            'amount'=>'required|numeric',
            'loan_name'=>'required|max:32',
            'start_date'=>'required|date',
            'tenure'=>'required|integer',
            'interest'=>'required|numeric',
            'loan_type' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value == "0") {
                        $fail('A loan type has not been selected');
                    }
                }],
        ]);

        if($validator->fails()){
            $response['status'] = 'failed';
            $response['errors'] = $validator->errors()->messages();
        }
        else{
            $tenure = $request->tenure;
            $start_date = date("Y-m-01", strtotime($request->start_date));
            $end_date = date("Y-m-t", strtotime("+$tenure months",strtotime($start_date)));

            $loan = Loan::find($id);
            $loan->loan_name =  $request->loan_name;
            $loan->amount =  $request->amount;
            $loan->start_date =  $start_date;
            $loan->end_date =  $end_date;
            $loan->tenure =  $request->tenure;
            $loan->loan_type =  $request->loan_type;
            $loan->interest =  $request->interest;
            $loan->save();
            $response['status'] = 'success';
            $response['loan'] = $request->all();
        }
        return response()->json($response);
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
        $loan = Loan::find($id);
        // dd($department);
        if($loan->delete())
        {
            return redirect('/loans')->with('success', 'Loan Deleted');
        }
        return back()->withInput()->with('error', 'Loan could not be deleted');
    }

    public function active(){
        $loans = Loan::where('end_date', ">", date("Y-m-d") )->get();
        $response = [];
        for($i=0;$i<count($loans);$i++){
            $response[$i]['name'] = $loans[$i]->salary->user->name." - ".$loans[$i]->salary->user->fname." ".$loans[$i]->salary->user->sname;;
            $response[$i]['id'] = $loans[$i]->id;
            $response[$i]['params'] = array();
            $response[$i]['params'] = $this->loanStatus($loans[$i], $response[$i]['params']);
        }
        return response()->json($response);
    }

    private function loanStatus($loan, $status){
        $to = Carbon::createFromFormat('Y-m-d', date('Y-m-01',strtotime($loan->start_date)));
        $from = Carbon::createFromFormat('Y-m-d', date('Y-m-01'));

        $months_passed = $to->diffInMonths($from);
        $status['Loan Title'] = $loan->loan_name;
        $status['Loan Type'] = $loan->loan_type;
        $status['Interest Rate'] = $loan->interest;
        $status['Amount'] = $loan->amount;
        $status['Tenure'] = $loan->tenure;
        $status['Monthly Installment Amount'] = round($loan->amount/$loan->tenure, 2);
        $status['Start Date'] = $loan->start_date;
        $status['End Date'] = $loan->end_date;
        $status['Installments Paid'] = $months_passed;
        $status['Installments Remaining'] = $loan->tenure - $months_passed;
        $status['Paid Amount'] = $months_passed * $status['Monthly Installment Amount'];
        $status['Remaining Amount'] = $loan->amount - $status['Paid Amount'];


        return $status;
    }
}
