    <form method='post' action="{{route('users.update', [$user->id])}}" style='width:100%'>
        {{csrf_field()}}
        <input type="hidden" name="_method" value="put">
        <table class="table table-hover table-sm">
            <tbody>
                <tr class="bg-dark text-white text-center">
                    <td colspan=3>
                        <div class='row'> 
                            <div class='col'>
                                Details of Username : <strong>"{{$user->name}}"</strong>
                            </div>
                            <div class="col col-lg-1" style='font-size:10px;vertical-align:middle'>
                                <a href="{{route('users.edit', [$user->id])}}">Edit</a>
                            </div>
                            <div class="col col-lg-1" style='font-size:10px'>
                                Close
                            </div>                            
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">User Name</th>
                    <td> <input type='text' name='name' value="{{$user->name}}"> </td>
                    <td><span class='text-left'>&#9998;</span></td>
                </tr>
                <tr>
                    <th scope="col">Email</th>
                    <td> <input type='text' name='email' value="{{$user->email}}"> </td>
                    <td><span class='text-left'>&#9998;</span></td>
                </tr>
                <tr>
                    <th scope="col">First Name</th>
                    <td> <input type='text' name='fname' value="{{$user->fname}}"> </td>
                    <td><span class='text-left'>&#9998;</span></td>
                </tr>
                <tr>
                    <th scope="col">Surname Name</th>
                    <td> <input type='text' name='sname' value="{{$user->sname}}"> </td>
                    <td><span class='text-left'>&#9998;</span></td>
                </tr>                
                <tr>
                    <th scope="col">Active From</th>
                    <td> {{$user->created_at}} </td>
                    <td><span class='text-left'>&#9998;</span></td>
                </tr>
                <tr>
                    <th scope="col">Last Activity</th>
                    <td> {{$user->updated_at}} </td>
                    <td><span class='text-left'>&#9998;</span></td>
                </tr>
                <tr>
                    <th scope="col">Phone</th>
                    <td> <input type='text' name='phone' value="{{$user->phone}}"> </td>
                    <td><span class='text-left'>&#9998;</span></td>
                </tr>
                <tr>
                    <th scope="col">Address</th>
                    <td> <input type='text' name='address' value="{{$user->address}}"> </td>
                    <td><span class='text-left'>&#9998;</span></td>
                </tr>
                <tr>
                    <th scope="col">Role</th>
                    <td> {{$user->role_name}} </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" class="btn btn-primary btn-lg btn-block" value="Submit Changes">
        </form>
