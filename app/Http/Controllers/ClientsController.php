<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Clientcontact;
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
			foreach($assignments as $index=>$assignment){
				$projects = Client::find($assignment->id)->projects;
				$assignments[$index]['projects'] = $projects;
			}
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
        
        if($validator->fails()){
            return redirect()->route('clients.index')->with('success', 'Client deleted successfully');
        }
        else {
            $client = new Client;
            $client->organization = $request->input('organization');
            $client->address = $request->input('address');
            $client->save();

            $contactCreate = Clientcontact::create([
                'name' => $request['name'],
                'contact' => $request['contact'],
                'designation' => $request['designation'],
                'client_id' => $client->id,
                ]);
        }
        return redirect('/clients')->with('success', 'Client Created');
        //return response()->json(['result'=>'success','message'=>'Added new records.']);
    }

    public function show($id)
    {
        if(Auth::Check()){ 
            $client = Client::find($id);
            $client['contacts'] = Client::find($id)->clientcontacts;
			$client['projects'] = Client::find($id)->projects;
            return view('clients.show')->with('client',$client);
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
            'organization' => 'required',
            'address' => 'required'
        ]);

        $client = Client::find($id);
        $client->organization = $request->input('organization');
        $client->address = $request->input('address');

        $client->save();
        //return view('clients.clientdetails', ['organization'=>$client->organization, 'address'=>$client->address, 'client_id'=>$client->id]);
        $response['view'] = view('clients.clientdetails', ['organization'=>$client->organization, 'address'=>$client->address, 'client_id'=>$client->id])->render();
        $response['status'] = 'success';
        $response['client_id'] = $client->id;
        $response['organization'] = $client->organization;
        return response()->json(['result'=>$response]);
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

    public function cancel($client_id)
    {
        $findClient = Client::find($client_id);
        return view('clients.clientdetails', ['organization'=>$findClient->organization, 'address'=>$findClient->address, 'client_id'=>$findClient->id]);
    }

}
