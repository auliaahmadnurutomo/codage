<div class="form-group row {{$required ?? ''}}">
    @if(isset($label))
        <label class="col-md-{{$width}} control-label small">{{$label}}</label>
    @endif
    <div class="col-md-{{(12-$width) > 0? 12-$width : 12}}">
        <select id="{{$id ?? ''}}" data-placeholder="{{@$placeholder ?? 'Select Option'}}"  name="{{$name}}" class="form-control form-control-sm mw-100 border rounded show-tick" data-size="10" data-live-search="{{$search ?? 'true'}}" {{$required ?? ''}} 
        {{@$multiple ?? ''}}>
            {{ $slot }}
        </select>
        <span id="{{$name}}" class="text-danger small" data-label="alert"></span>
    </div>
</div>
