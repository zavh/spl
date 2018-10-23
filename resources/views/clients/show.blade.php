<div class="card shadow-lg">
    <div class="card-header text-white bg-primary">
                    {{ __('Client Details') }}
    </div>
    <div class="card-body">
    Client Contact: : <strong> {{$assignment->name}} </strong>
    </div>
    <div class="card-footer bg-transparent border-primary">Organization: : <strong> {{$assignment->organization}} </strong></div>
    <div class="card-footer bg-transparent border-primary">Address : <strong>{{$assignment->address}}</strong></div>
</div>