@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Profile Area Starts-->
        <div class="col-md-4">
            <div class="shadow-sm border" id="user-profile">
                <div class=" mb-0 bg-white rounded">
                    <div class="media text-muted">
                        <div class="media-body small">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <strong class="text-dark pl-1 pt-1">Available App Modules</strong>
                            </div>
                            @foreach($modules as $name=>$module)
                            <div id='{{$name}}'>
                                <div class="row m-0 bg-light w-100 border-top">
                                    <div class="col-md-12 text-primary pl-1 text-secondary">
                                        <input class='avmodules' type="checkbox" name='av-{{$name}}' id='av-{{$name}}' value='{{$name}}' onclick='assignModule(this)' disabled>
                                        <strong>{{$name}}</strong>
                                    </div>
                                </div>
                            </div>
                            <script>
                            actions = [];
                            @for( $i=0; $i < count($module);$i++)
                            @if($name == 'Closure') @continue @endif
                                action = new Action("{{$module[$i]['function']}}", "{{$module[$i]['name']}}", "{{$module[$i]['uri']}}");
                                actions[actions.length] = action;
                            @endfor
                            appmodule = new AppModule("{{$name}}",actions);
                            root['{{$name}}'] = appmodule;
                            </script>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Area Starts-->
        <!-- Task Section Starts -->
        <div class="col-md-8">
            <div class="shadow-sm border" id="user-tasks">
                <div class="mb-0 bg-white rounded">
                    <div class="media text-muted">
                        <div class="media-body small">
                            <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                                <div class="input-group input-group-sm m-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="inputGroupSelect">Departments</label>
                                    </div>
                                    <select class="form-control" id="inputGroupSelect" onchange="showAppConfig(this)">
                                        <option value="" disabled selected>Select One</option>
                                        <option value="0">Default (Common for all Departments)</option>
                                        @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id='configmsg' class='text-center text-danger'></div>
                            <div id='appConfigContainer' class='w-100'>
                            </div>
                            <div><button onclick='changeConfig()'>Submit</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Task Section Ends -->
    </div>
</div>
@endsection
<script src="{{ asset('js/appmodule.js') }}"></script>