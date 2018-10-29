
    <main role="main" class="container-fluid">
        <!--Edit and close-->
        <div class="bg-dark text-white text-left">
            <div class='row'> 
                <div class='col'>
                    Details of Role : <strong>"{{$assignment->role_name}}"</strong>
                </div>
                <div class="col col-lg-2" style='font-size:10px;vertical-align:middle'>
                    <a href="{{route('roles.edit', [$assignment->id])}}">Edit</a>
                </div>
                {{-- <div class="col col-lg-2" style='font-size:10px'>
                    Close
                </div>                             --}}
            </div>
        </div>
        
        <!-- Role Details Column Starts-->
        <div>
            <!-- Role Name Area Starts-->
            <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-primary rounded shadow-sm">
                <div class="lh-100">
                <h6 class="mb-0 text-white lh-100">Role Name: {{$assignment->role_name}}</h6>
                <small>Posted on: {{$assignment->created_at}}</small>
                </div>
            </div>
            <!-- Role Name Area Ends-->
            <div class="my-3 p-3 bg-white rounded shadow-sm">
                <h6 class="border-bottom border-gray pb-2 mb-0">Role Details</h6>
                <!-- Detail Area Starts-->
                <div class="media text-muted pt-3">
                    <img data-src="holder.js/32x32?theme=thumb&amp;bg=007bff&amp;fg=007bff&amp;size=1" alt="32x32" class="mr-2 rounded" style="width: 32px; height: 32px;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2232%22%20height%3D%2232%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_166b868647d%20text%20%7B%20fill%3A%23007bff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A2pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_166b868647d%22%3E%3Crect%20width%3D%2232%22%20height%3D%2232%22%20fill%3D%22%23007bff%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2212.166666746139526%22%20y%3D%2216.9%22%3E32x32%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark">@Name: {{$assignment->role_name}} </strong>
                    </p>                      
                </div>
                <div class="media text-muted pt-3">
                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                        <span class="text-dark">&#9993; Description: {{$assignment->role_description}}</span>
                    </p>
                </div>  
                <!-- Client Area Ends-->
            </div>
        </div>
    </main>