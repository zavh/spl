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
            return redirect()->route('clients.index')->with('error', 'Client deleted successfully');
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
        $validator = Validator::make($request->all(), [
            'organization' => 'required',
            'address' => 'required',
            'background' => 'required|min:4'
        ]);

        if($validator->fails()){
            $response['status'] = 'failed';
            $response['messages'] = $validator->errors()->messages();
        }
        else {
            $client = Client::find($id);
            $client->organization = $request->input('organization');
            $client->address = $request->input('address');
            $client->background = $request->input('background');
    
            $client->save();
            
            $response['view'] = view('clients.clientdetails', [
                'organization'=>$client->organization, 
                'address'=>$client->address, 
                'client_id'=>$client->id,
                'background'=>$client->background])->render();
            $response['status'] = 'success';
            $response['client_id'] = $client->id;
            $response['organization'] = $client->organization;
        }
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
        return view('clients.clientdetails', [
            'organization'=>$findClient->organization, 
            'address'=>$findClient->address,
            'background'=>$findClient->background, 
            'client_id'=>$findClient->id]);
    }

    public function clientslisting(){
        $response = $this->makeClientList();
        $response['status'] = 'success';
        return response()->json(['response'=>$response]);
    }

    private function makeClientList($tempclient = null){
        $clients = CLient::all();
        $newid = null;
        if($tempclient !=null){
            $lastid = $clients[count($clients)-1]->id;
            $newid = $lastid + 1;
            $newclient = new CLient;
            $newclient->id = $newid;
            $newclient->organization = $tempclient['organization'];
            $newclient->address = $tempclient['address'];
            $newclient->background = $tempclient['background'];
            $clients->push($newclient);
            $response['client_id'] = $newid;
        }
        $response['view'] = view('clients.clientlisting',['clients'=>$clients, 'newid'=>$newid])->render();
        $response['clients'] = json_encode($clients);

        return $response;
    }
    public function validateonly(Request $request){
        $request_data = $request->All();
        $validator = $this->client_validation_rules($request_data);
        if($validator->fails()){
            $result['status'] = 'failed';
            $result['message'] = $validator->errors()->all();
            return response()->json(['result'=>$result]);
        }
        else {
            $tempclient['organization'] = $request_data['organization'];
            $tempclient['address'] = $request_data['address'];
            $tempclient['background'] = $request_data['background'];
            $result = $this->makeClientList($tempclient);
            
            $cleintcontact = new Clientcontact;
            $cleintcontact->id = 0;
            $cleintcontact->designation = $request_data['designation'];
            $cleintcontact->contact = $request_data['contact'];
            $cleintcontact->name = $request_data['name'];
            $cleintcontact->client_id = $result['client_id'];
            $contacts[0] = $cleintcontact;
            
            $result['contacts'] = json_encode($contacts);
            $result['contactview'] = view('clientcontacts.contactlisting',['contacts'=>$contacts])->render();
            $result['status'] = 'success';
            
            return response()->json(['result'=>$result]);
        }
    }

    private function client_validation_rules(array $data)
    {
        $messages = [
            'organization.required' => 'organization|Required',
            'organization.max' => 'organization|Max characters 191',
            'organization.min' => 'organization|Minumum length 4 characters',
            'address.required' => 'address|Required',
            'address.max' => 'address|Maximum length 191',
            'address.min' => 'address|Minimum length 4',
            'background.required' => 'background|Required',
            'background.max' => 'background|Max 300 characters',
            'background.min' => 'background|Minimum 20 characters',
            'name.required' => 'name|Required',
            'name.max' => 'name|Maximum length 50',
            'name.min' => 'name|Minimum length 4',
            'designation.required' => 'designation|Required',
            'designation.max' => 'designation|Maximum length 191',
            'designation.max' => 'designation|Minimum length 4',
            'contact.required' => 'contact|Required',
            'contact.integer' => 'contact|Only digits allowed',
            'contact.max' => 'contact|Cannot excced 17 digits',
            'contact.min' => 'contact|Cannot be less than 6 digits',
        ];

        $validator = Validator::make($data, [
            'organization' => 'required|max:191:min:4',
            'address' => 'required|max:191|min:4',
            'background' => 'required|max:300|min:20',
            'name' => 'required|max:50|min:4',
            'designation' => 'required|max:191|min:4',
            'contact' => 'required|integer|max:99999999999999999|min:100000',
        ], $messages);

        return $validator;
    }
}
