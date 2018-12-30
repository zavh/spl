<div class="row m-0 mb-2">
    <div class="shadow-sm border rounded w-100 small">
        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
            <span class="pl-2">Group Name: {{$name}}</span>
            <input type="hidden" id="grp_name_{{$type}}_{{$level}}_{{$index}}" value='{{$name}}'>
            <span class="p-1">
                <a 
                    href="javascript:void(0)" 
                    class="badge badge-pill badge-light border"
                    data-level={{$level}}
                    data-type={{$type}}
                    data-index="{{$index}}"
                    data-grpindex={{isset($data)?count($data):0}}
                    onclick='addGroupElement(this)'>+</a>
                <a href="" class="badge badge-pill badge-light border">x</a>
            </span>
        </div>
        <div id="el_{{$type}}_{{$level}}_{{$index}}">
        @isset($data)
            @for($i = 0; $i < count($data); $i++)
            <div id="{{$type}}_{{$level}}_{{$index}}_$i" class='mx-1'>
                @include('products.elsnippet',['data'=>$data[$i]])
            </div>
            @endfor
            <div id="{{$type}}_{{$level}}_{{$index}}_{{count($data)}}" class='mx-1'></div>
        @else 
            <div id="{{$type}}_{{$level}}_{{$index}}_0" class='mx-1'></div>
        @endisset
        </div>
    </div>
</div>