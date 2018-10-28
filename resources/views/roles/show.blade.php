<div class="card shadow-lg">
    <div class="card-header text-white bg-primary">
                    {{ __('Role Details') }}
    </div>
    <div class="card-body">
    Role : <strong> {{$role->role_name}} </strong>
    </div>
    <div class="card-footer bg-transparent border-primary">Organization: : <strong> {{$role->role_description}} </strong></div>
</div>