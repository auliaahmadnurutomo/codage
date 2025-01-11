<style type="text/css">
    .input-suggest {
        position: relative;
    }

    .input-suggest-append {
        position: absolute;
        right: 10px;
        /* Sesuaikan dengan kebutuhan Anda */
        top: 50%;
        transform: translateY(-50%);
    }

    .input-suggest-text {
        background: transparent;
        border: none;
        font-size: 16px;
        /* Sesuaikan ukuran ikon */
    }

</style>
<div class="form-group row {{$required ?? ''}}">
    @if(isset($label))
    <label class="col-md-{{$width}} control-label small">{{$label}}</label>
    @endif

    <div class="col-md-{{(12-$width) > 0? 12-$width : 12}}">
        <div id="group_{{$id}}" class="input-suggest">
            <input id="{{$id ?? 'input-string'}}" class="form-control" placeholder="{{$placeholder ?? ''}}" type="{{ @$type ? @$type : 'text'}}" {{$required ?? ''}} value="{{$value ?? ''}}">
            <div class="input-suggest-append">
                {{-- <i class="fa fa-exclamation input-suggest-text text-warning"></i> --}}
            </div>
        </div>
        <input type="hidden" id="{{$id}}_select" name="{{$name}}" value="{{$value ?? ''}}">
        <div id="no-results-{{$id}}" class="p-2 alert-warning mt-2" style="display:none;">
            <small class="">
                Data Not Found
            </small>
            <button type="button" class="btn btn-link btn-sm text-success" id="group_{{$id}}_add_item">
                {{-- <i class="fa fa-plus"></i> Add Item Directly? --}}
                 <small>Add data = <span class="inputValue font-weight-bold"></span> ? </small> 
            </button>
        </div>
        <span id="{{$name}}" class="text-danger" data-label="alert"></span>
    </div>
</div>