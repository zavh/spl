<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class ClientsController extends Controller
{
/*    public function __construct()
    {
        $this->middleware('auth');
    }*/
    public function index()
    {
        if(Auth::Check()){ 
            $assignments = Client::all();
            return view('clients.index')->with('assignments', $assignments);
        }
        else {
            return redirect('/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'name|Please enter the contact person\'s name',
            'organization.required' => 'organization|Client Organization must be of minimum 4 characters',
            'address.required' => 'address|Address is required',
            'contact' => 'contact|Client contact information is required',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|min:4',
            'organization' => 'required|max:191:min:4',
            'address' => 'required|max:191',
            'contact' => 'required|integer|min:100000|max:99999999999999999'],
            $messages);
        
        if($validator->fails())
            return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            //return response()->json(['error'=>$messages]);
        else {
            $client = new Client;
            $client->name = $request->input('name');
            $client->organization = $request->input('organization');
            $client->address = $request->input('address');
            $client->contact = $request->input('contact');
            $newClient = $client->save();
        }
        //return redirect('/clients')->with('success', 'Client Created');
        return response()->json(['result'=>'success','message'=>'Added new records.']);
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
            $assignment = Client::find($id);
            return view('clients.show')->with('assignment',$assignment);
        }
        else {
            //return response()->json(['result'=>'error','message'=>'Session Expired']);
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
        $assignment = Client::find($id);
        return view('clients.edit')->with('assignment', $assignment);
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
        $this->validate($request, [
            'name' => 'required',
            'organization' => 'required',
            'address' => 'required'
        ]);

        // Create Post
        $client = Client::find($id);
        $client->name = $request->input('name');
        $client->organization = $request->input('organization');
        $client->address = $request->input('address');

        
        $client->save();

        return redirect('/clients')->with('success', 'Client Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $findClient = Client::find($client->id);

        if($findClient->delete()){
            return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully');
        }

        return back()->withInput()->with('error', 'Client could not be deleted');
    }
}
