<ul class="navbar-nav ml-auto small">
                        <!-- Authentication Links -->
    @php
        $path = explode('/',Request::path());
        $thispath = $path[0];
    @endphp
    <li class="nav-item">
    
    @if($thispath=='home')
        <a class="nav-link active-nav active" href="/home">Dashboard</a>
    @else
        <a class="nav-link" href="/home">Dashboard</a> 
    @endif
    </li>
    <li class="nav-item">
    @if($thispath=='reports')
        <a class="nav-link active-nav active" href="/reports">Reports</a>
    @else
        <a class="nav-link" href="/reports">Reports</a>
    @endif
    </li>
    <li class="nav-item">
    @if($thispath=='products')
        <a class="nav-link active-nav active" href="/products">Products</a>
    @else
        <a class="nav-link" href="/products">Products</a>
    @endif
    </li>
    <li class="nav-item">
    @if($thispath=='projects' || $thispath=='enquiries')
        <a class="nav-link active-nav active" href="/projects">Projects</a>
    @else
        <a class="nav-link" href="/projects">Projects</a>
    @endif
    </li>
    <li class="nav-item">
    @if($thispath=='clients')
        <a class="nav-link active-nav active" href="/clients">Clients</a>
    @else 
        <a class="nav-link" href="/clients">Clients</a>
    @endif
    </li>
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