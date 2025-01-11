<div class="form-group row">
	@php
	$width = isset($width) ? $width : '12';
	@endphp
    @if(isset($label))
        <label class="col-md-{{$width}} control-label small">{{$label}}</label>
    @endif

    <div class="col-md-{{(12-$width) > 0? 12-$width : 12}}">
        <div class="p-2 bg-light form-control form-control-sm {{@$class}}">
        	{{$value ?? ''}}
        </div>
    </div>
</div>
