
@foreach($enquiries as $index=>$enquiry)
@if($index > 0)
<div class="mt-1 p-2 bg-dark">
@else
<div class="p-2 bg-dark">
@endif
<div class='small'>
        @if($enquiry['details']->type == 'submerse')
            <div class='border-bottom border-white row'>
                <div class='col-4 text-white-50 d-flex justify-content-between'>
                    <span>Type</span> <span>:</span>
                </div>
                <div class='col-8 text-white'>Submersible</div>
            </div>
            <div class='border-bottom border-white row'>
                <div class='col-4 text-white-50 d-flex justify-content-between'>
                    <span>Category</span> <span>:</span>
                </div>
                <div class='col-8 text-white'>{{ $enquiry['details']->subtype }}</div>
            </div>
        @elseif ($enquiry['details']->type == 'surface')
            <div class='border-bottom border-white row'>
                <div class='col-4 text-white-50 d-flex justify-content-between'>
                    <span>Type</span> <span>:</span>
                </div>
                <div class='col-8 text-white'>Surface</div>
            </div>
            <div class='border-bottom border-white row'>
                <div class='col-4 text-white-50 d-flex justify-content-between'>
                    <span>Category</span> <span>:</span>
                </div>
                <div class='col-8 text-white'>{{ $enquiry['details']->surftype }}</div>
            </div>
        @endif
        <div class='border-bottom border-white row'>
            <div class='col-4 text-white-50 d-flex justify-content-between'>
                <span>Liquid</span> <span>:</span>
            </div>
            <div class='col-8 text-white'>{{ $enquiry['details']->liquid }}</div>
        </div>
        <div class='border-bottom border-white row'>
            <div class='col-4 text-white-50 d-flex justify-content-between'>
                <span>Temperature</span> <span>:</span>
            </div>
            <div class='col-8 text-white'>{{ $enquiry['details']->liqtemp }}</div>
        </div>
        <div class='border-bottom border-white row'>
            <div class='col-4 text-white-50 d-flex justify-content-between'>
                <span>Pump Capacity</span> <span>:</span>
            </div>
            <div class='col-8 text-white'>{{ $enquiry['details']->pumpcap }}</div>
        </div>
        <div class='border-bottom border-white row'>
            <div class='col-4 text-white-50 d-flex justify-content-between'>
                <span>Head Capacity</span> <span>:</span>
            </div>
            <div class='col-8 text-white'>{{ $enquiry['details']->pumphead }}</div>
        </div>
</div>
<div class="media text-muted">    
    <div class="media-body small lh-125">
        <span class='text-white-50'>Purpose:</span> 
        <span class="text-white">{{$enquiry['details']->description}}</span>
        <a href="javascript:void(0)" 
            onclick="ajaxFunction('editEnquiries',  {{$enquiry['id']}}  , 'enqdiv')" class='text-white-50'>
            [ Edit ]
        </a>
        <a href="javascript:void(0)" 
            onclick="deleteEnquiry('{{$enquiry['details']->description}}','{{$enquiry['id']}}')" class='text-white-50'>
            [ Delete ]
        </a>
        <form 
            id="enquiry-delete-form-{{$enquiry['id']}}"
            method="post"
            action="{{route('enquiries.destroy', $enquiry['id'])}}" 
            >
            <input type="hidden" name="_method" value="delete">
            {{csrf_field()}}
        </form>
    </div>
</div>
</div>
@endforeach