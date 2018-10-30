<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-primary rounded shadow-sm">
    <div class="lh-100">
    <h6 class="mb-0 text-white lh-100">Client Name: {{$assignment->name}}</h6>
    <small>Posted on: {{$assignment->created_at}}</small>
    </div>
</div>
<!-- Role Name Area Ends-->
<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0">Client Details</h6>
    <!-- Detail Area Starts-->
    <div class="media text-muted pt-3">
            <img data-src="holder.js/32x32?theme=thumb&amp;bg=007bff&amp;fg=007bff&amp;size=1" alt="32x32" class="mr-2 rounded" style="width: 32px; height: 32px;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2232%22%20height%3D%2232%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_166c38e1706%20text%20%7B%20fill%3A%23007bff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A2pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_166c38e1706%22%3E%3Crect%20width%3D%2232%22%20height%3D%2232%22%20fill%3D%22%23007bff%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2212.166666746139526%22%20y%3D%2216.9%22%3E32x32%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
            <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                <div class="d-flex justify-content-between align-items-center w-100">
                <strong class="text-gray-dark">@Contact Person: {{$assignment->name}}</strong>
                <a href="{{route('clients.edit', [$assignment->id])}}">Edit</a>
                </div>
                <span class="d-block">@username</span>
            </div>
            </div>
    <div class="media text-muted pt-3">
        <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
            <span class="text-dark">&#9993; Organization: {{$assignment->organization}}</span>
        </p>
    </div>
    <div class="media text-muted pt-3">
        <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
            <span class="text-dark">&#9993; Address: {{$assignment->address}}</span>
        </p>
    </div> 
    <div class="media text-muted pt-3">
        <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
            <span class="text-dark">&#9993; Contact: {{$assignment->contact}}</span>
        </p>
    </div> 
    <!-- Client Area Ends-->
</div>