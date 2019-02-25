{{-- <pre>@php print_r(session('menu')) @endphp</pre> --}}
@if(session('menu') != NULL)
<ul class="navbar-nav ml-auto small">
@foreach(session('menu') as $key=>$value)
    @if($value == NULL) @continue
    @endif
    <li class="nav-item">
        <a class="nav-link" href="/{{$value}}">{{$key}}</a>
    </li>
@endforeach
<li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }} <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            @if(Auth::user()->role_id == 1)
                <a class="dropdown-item" href="{{ url('/users') }}">
                    {{ __('User Management') }}
                </a>
                <a class="dropdown-item" href="{{ url('/roles') }}">
                    {{ __('Role Management') }}
                </a>
                <a class="dropdown-item" href="{{ url('/departments') }}">
                    {{ __('Department Management') }}
                </a>
                <a class="dropdown-item" href="{{ url('/designations') }}">
                    {{ __('Designation Management') }}
                </a>
            @endif                                    
            <a class="dropdown-item" href="/changepass">
                {{ __('Change Password') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>
</ul>
@else
<script type="text/javascript">
    window.location = "/home";
</script>
@endif