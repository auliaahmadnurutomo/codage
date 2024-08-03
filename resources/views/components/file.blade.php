<div class="form-group row {{$required ?? ''}}">
    @if(isset($label))
        <label class="col-md-{{$width}} control-label">{{$label}}</label>
    @endif

    <div class="col-md-{{(12-$width) > 0? 12-$width : 12}}">
        <input id="{{$id ?? 'input-string'}}" name="{{$name}}" class="form-control" placeholder="{{$placeholder ?? ''}}" accept="{{ @$accept ? @$accept : '*'}}"
               {{$required ?? ''}} value="{{$value ?? ''}}" type="file">
        <span id="{{$name}}" class="text-danger" data-label="alert"></span>
    </div>
</div>
