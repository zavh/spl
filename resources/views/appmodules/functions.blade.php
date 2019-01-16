<div class='m-2'>
    <div class="media text-muted border border-dark">
        <div class="media-body mb-0">
        <!-- Header Starts -->
            <div class="d-flex justify-content-between align-items-center w-100">
                <span class="text-dark mx-2">
                    <strong>{{$name}}</strong>
                </span>
                <a 
                    href='javascript:void(0)' 
                    class='badge badge-pill btn border shadow-sm mx-2'
                    onclick="removeModule('{{$name}}')"
                    >x</a>
            </div>
        <!-- Header Ends -->
                <div class='w-100'>
                    <table class='table table-sm small text-center mb-0 pb-0'>
                    <tr class='bg-dark text-light'><th>Select</th><th>Function</th><th>Name</th><th>URL</th></tr>
                    @for( $i=0; $i < count($data['action']);$i++)
                    <tr>
                    <td><input type="checkbox" name="LoginController" value="LoginController" onclick="assignModule(this)"></td>
                    <td>{{$data['action'][$i]['func']}}</td>
                    <td>{{$data['action'][$i]['name']}}</td>
                    <td>{{$data['action'][$i]['url']}}</td>
                    </tr>
                   @endfor
                    </table>
                </div>
        </div>
    </div>
</div>
