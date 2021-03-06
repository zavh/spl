<?php

namespace App\Http\Controllers;

use App\Clientcontact;
use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientcontactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($client_id)
    {
        $clinetContacts = Clientcontact::where('client_id', $client_id)->get();
        return view('clientcontacts.index',['contacts'=>$clinetContacts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($client_id)
    {
        return view('clientcontacts.create')->with('client_id', $client_id);
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
            'contact_name.required' => 'contact_name|Please enter a valid name',
            'designation.required' => 'designation|Designation is required',
            'contact.required' => 'contact|Enter a valid phone number',
            'contact.min' => 'contact|Contact number should be at least 6 digits',
            'contact.max' => 'contact|Contact number should cannot exceed 17 digits',
            'contact.integer' => 'contact|Phone number should contain digits only',
        ];

        $validator = Validator::make($request->all(), [
            'contact_name' => 'required|max:100|min:3',
            'designation' => 'required|max:100:min:4',
            'contact' => 'required|integer|min:100000|max:99999999999999999'],
            $messages);
        
        if($validator->fails()){
            $result['status'] = 'failed';
            $result['message'] = $validator->errors()->all();
            return response()->json(['result'=>$result]);
        }
        $contactCreate = Clientcontact::create([
            'name' => $request['contact_name'],
            'designation' => $request['designation'],
            'contact' => $request['contact'],
            'client_id' => $request['cc_client_id'],
            ]);
        if($contactCreate){
            $client_id = $request['cc_client_id'];
            $contacts = Client::find($client_id)->clientcontacts;
            $result['view'] = view('clientcontacts.index',['contacts'=>$contacts])->render();
            $result['status'] = 'success';
            return response()->json(['result'=>$result]);}
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cc = Clientcontact::find($id);
        return view('clientcontacts.edit', ['cc'=>$cc]);
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
        // dd($request);   
        $messages = [
            'name.required' => 'name|Please enter a valid name',
            'designation.required' => 'designation|Designation is required',
            'contact.required' => 'contact|Enter a valid phone number',
            'contact.min' => 'contact|Contact number should be at least 6 digits',
            'contact.max' => 'contact|Contact number should cannot exceed 17 digits',
            'contact.integer' => 'contact|Phone number should contain digits only',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|min:3',
            'designation' => 'required|max:100:min:4',
            'contact' => 'required|integer|min:100000|max:99999999999999999'],
            $messages);
        
        if($validator->fails()){
            $result['status'] = 'failed';
            $result['message'] = $validator->errors()->all();
            return response()->json(['result'=>$result]);
            
        }
        // dd($request);
        $contact = Clientcontact::find($id);
        // dd('request',$request,'contact',$contact);
        $contact->name = $request->input('name');
        $contact->designation = $request->input('designation');
        $contact->contact = $request->input('contact');
        
        $contact->save();

        $contacts  = Clientcontact::where('client_id', $contact->client_id)->get(); 
        $result['status'] = 'success';
        $result['client_id'] = $contact->client_id;
        $result['view'] = view('clientcontacts.index',['contacts'=>$contacts])->render();
        return response()->json(['result'=>$result]);
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

    public function contactheader($client_id)
    {
        return view('clientcontacts.contactheader')->with('client_id', $client_id);
    }

    public function contactlist($client_id)
    {   
        if(Auth::Check()){
            $clinetContacts = Clientcontact::where('client_id', $client_id)->get();
            $response['status'] = 'success';
            $response['view'] = view('clientcontacts.contactlisting',['contacts'=>$clinetContacts])->render();
            $response['contacts'] = json_encode($clinetContacts);
            return response()->json(['data'=>$response]);
        }
        else {
            $response['status'] = 'failed';
            $response['message'] = 'session';
        }
    }
}
