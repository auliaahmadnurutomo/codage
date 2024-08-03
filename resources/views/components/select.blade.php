<div class="form-group row {{$required ?? ''}}">
    @if(isset($label))
        <label class="col-md-{{$width}} control-label">{{$label}}</label>
    @endif
    <div class="col-md-{{(12-$width) > 0? 12-$width : 12}}">
        <select id="{{$id ?? ''}}" name="{{$name}}" class="selectpicker w-100  border rounded" data-live-search="{{$search ?? 'true'}}" {{$required ?? ''}}>
            {{ $slot }}
        </select>
        <span id="{{$name}}" class="text-danger" data-label="alert"></span>
    </div>
</div>
