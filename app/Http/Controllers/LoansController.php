<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
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
        $salary = Salary::find($request->salary_id);
        $join_date = json_decode($salary->salaryinfo)->join_date;
        $validator = Validator::make($request->all(), [
            'salary_id' => 'required|integer|min:1',
            'amount'=>'required|numeric',
            'loan_name'=>'required|max:32',
            'start_date'=>'required|date|after:'.$join_date,
            'tenure'=>'required|integer',
            'interest'=>'required|numeric|min:0',
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
            $schedule = array();

            $currentmonth = date('n');
            if($currentmonth > 6){
                $payyearstarts = date("Y-07-01");
            }
            else {
                $payyearstarts = date("Y-m-d",mktime(0,0,0,7,1,(date("Y")-1)));
            }
            $mapping = array();
            $pCarbon = Carbon::parse($payyearstarts);
            for($i=0;$i<12;$i++){
                $mapping[$pCarbon->format('Y-m')] = $i;
                $pCarbon->addMonth();
            }
            
            $start_date = date("Y-m-01", strtotime($request->start_date));
            $t = $tenure - 1;
            $end_date = date("Y-m-t", strtotime("+$t months",strtotime($start_date)));

            $month = Carbon::parse($start_date);
            $addloan = array();
            for($i=0;$i<$tenure;$i++){
                $schedule[$month->format('Y-m')] = $request->amount/$tenure;
                if(isset($mapping[$month->format('Y-m')]))
                    $addloan[$mapping[$month->format('Y-m')]] = $schedule[$month->format('Y-m')];
                $month->addMonth();
            }
            $loanCreate = Loan::create([
                'salary_id' => $request->salary_id,
                'loan_name' => $request->loan_name,
                'amount' => $request->amount,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'loan_type' => $request->loan_type,
                'interest' => $request->interest,
                'tenure' => $tenure,
                'schedule' => json_encode($schedule),
                ]);

            $response['status'] = 'success';
            $response['loan']['name'] = $loanCreate->salary->user->fname." ".$loanCreate->salary->user->sname;
            $response['loan']['employee_id'] = $loanCreate->salary->user->name;
            $response['loan']['id'] = $loanCreate->id;
            $response['loan']['amount'] = $loanCreate->amount;
            $response['loan']['start_date'] = $loanCreate->start_date;
            $response['loan']['end_date'] = $loanCreate->end_date;
            $response['loan']['tenure'] = $loanCreate->tenure;
            $response['loan']['schedule'] = $schedule;
            if(count($addloan) > 0){
                $user_id = $salary->user->id;
                $db_table_name = 'yearly_income_'.date('Y',strtotime($payyearstarts)).'_'.(date('Y',strtotime($payyearstarts))+1);
                if(Schema::hasTable($db_table_name)){
                    $ys = DB::table($db_table_name)->select('salary')->where('user_id', $user_id)->first();
                    $salary = json_decode($ys->salary);
                    foreach($addloan as $index=>$loan){
                        $salary[$index]->loan += $loan;
                    }
                    DB::table($db_table_name)->where('user_id', $user_id)->update(['salary'=> json_encode($salary)]);
                }
            }
        }
        return response()->json($response);
    }

    private function getCurrentSalaryParams(){
        $currentmonth = date('n');

        if($currentmonth > 6){
            $payyearstarts = date("Y-07-01");
        }
        else {
            $payyearstarts = date("Y-m-d",mktime(0,0,0,7,1,(date("Y")-1)));
        }
        $mapping = array();
        $pCarbon = Carbon::parse($payyearstarts);
        for($i=0;$i<12;$i++){
            $mapping[$pCarbon->format('Y-m')] = $i;
            $pCarbon->addMonth();
        }
        $db_table_name = 'yearly_income_'.date('Y',strtotime($payyearstarts)).'_'.(date('Y',strtotime($payyearstarts))+1);
        return ['db_table_name'=>$db_table_name, 'mapping'=>$mapping];
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
            $response['name'] = $loan->salary->user->name." - ".$loan->salary->user->fname." ".$loan->salary->user->sname;;
            $response['id'] = $loan->id;
            $response['params'] = array();
            $response['params'] = $this->loanStatus($loan, $response['params']);
        }
        return response()->json($response);
    }

    public function updateSchedule(Request $request, $id){
        $loan = Loan::find($id);
        $oldschedule = json_decode($loan->schedule);
        $newschdule = json_decode($request->schedule);
        $salaryParams = $this->getCurrentSalaryParams();
        $db_table_name = $salaryParams['db_table_name'];
        $mapping = $salaryParams['mapping'];
        if(Schema::hasTable($db_table_name)){
            $user_id = $loan->salary->user->id;
            $ys = json_decode(DB::table($db_table_name)->select('salary')->where('user_id', $user_id)->first()->salary);
            
            foreach($mapping as $map=>$index){
                if(isset($oldschedule->$map))
                    $ys[$index]->loan -= $oldschedule->$map;
                if(isset($oldschedule->$map))
                    $ys[$index]->loan += $newschdule->$map;
            }
        }
        DB::table($db_table_name)->where('user_id', $user_id)->update(['salary'=>json_encode($ys)]);
        // $response['ys'] = $ys;
        $response['status'] = 'testing';
        $response['old'] = $oldschedule;
        $response['new'] = $newschdule;
        $response['salary_params'] = $salaryParams;
        $loan->schedule = $request->schedule;
        $loan->save();
        return response()->json($response);
    }

    public function destroy($id)
    {
        $loan = Loan::find($id);
        if($loan->delete())
        {
            return redirect('/loans')->with('success', 'Loan Deleted');
        }
        return back()->withInput()->with('error', 'Loan could not be deleted');
    }

    public function active(){
        // $loans = Loan::where('end_date', ">", date("Y-m-d"))->orderBy('id', 'desc')->get();
        $loans = Loan::where('active', 1)->orderBy('id', 'desc')->get();
        $response = [];
        $activecount = 0;
        for($i=0;$i<count($loans);$i++){
            if($loans[$i]->end_date < date("Y-m-d")){
                $loans[$i]->active = 0;
                $loans[$i]->save();
                continue;
            }
            $response[$activecount]['name'] = $loans[$i]->salary->user->fname." ".$loans[$i]->salary->user->sname;
            $response[$activecount]['employee_id'] = $loans[$i]->salary->user->name;
            $response[$activecount]['id'] = $loans[$i]->id;
            $response[$activecount]['tenure'] = $loans[$i]->tenure;
            $response[$activecount]['amount'] = $loans[$i]->amount;
            $response[$activecount]['start_date'] = $loans[$i]->start_date;
            $response[$activecount]['end_date'] = $loans[$i]->end_date;
            // $response[$activecount]['params'] = array();
            // $response[$activecount]['params'] = $this->loanStatus($loans[$i], $response[$i]['params']);
            $activecount++;
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
