@extends('layouts.blank-frame')
@php
$modal_size = 'modal-md';
$section = 'Form Create ';
@endphp
@section('title',$section)

@section('blank-content')


<h4 class="mt-5 col-lg-8">Create</h4>
<hr>
<form id="formData" method="post" class="col-lg-8">
    @csrf
    <input class="id_reference" type="hidden" name="id_reference" value="{{ @$results->id}}">

    <x-input label="Name" width="3" name="name" placeholder="Name" required="required" value="{{@$results->name}}" />
    <x-input label="Email" width="3" name="email" placeholder="Email" required="required"
        value="{{@$results->email}}" />

    <x-select label="Roles Access Authority" name="role" search="true" width="3" required="required">
        @foreach(@$roles as $role)
        <option value="{{$role->id}}" {{@$results->id_access_template == $role->id ? 'selected':''}}>{{$role->name}}</option>
        @endforeach
    </x-select>

    <!-- manual html view -->
    <div class="form-group row required">
        <label class="col-md-3 control-label">Password</label>
        <div class="col-md-9">
            <input id="input-string" name="password" class="form-control" placeholder="Min 8 character " type="text" />
            <span id="password" class="text-danger" data-label="alert"></span>
        </div>
    </div>

    <x-select label="Office" name="id_office" search="true" width="3" required="required">
        @foreach(@$offices as $item)
        <option value="{{$item->id}}" {{@$results->id_office == $item->id ? 'selected':''}}>{{$item->name}}</option>
        @endforeach
    </x-select>

    
    <x-select label="Department" name="id_department" search="true" width="3" required="required">
        @foreach(@$department as $dpt)
        <option value="{{$dpt->id}}" {{@$results->id_department == $dpt->id ? 'selected':''}}>{{$dpt->name}}</option>
        @endforeach
    </x-select>
    <x-select label="Staff Position" name="id_staff_position" search="true" width="3" required="required">
        @foreach(@$staff_position as $position)
        <option value="{{$position->id}}" {{@$results->id_staff_position == $position->id ?
            'selected':''}}>{{$position->name}}</option>
        @endforeach
    </x-select>

    <x-file label="Avatar (png/jpg)" width="3" name="file" placeholder="Please Input File" id="avatar" accept="image/*"/>

    <div class="form-group row mt-4">
        <div class="col-md-9 offset-md-3">
            <div class="d-flex">
                <img src="{{ url($results->img_path ?? '') }}" id="avatarPreview" class="rounded" style="max-width: 300px">
            </div>
        </div>
    </div>
    
    <hr>

    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul></ul>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-9 offset-md-3">
            <div class="d-flex">
                <button type="submit" class="btn btn-primary">{{@$type == 'create' ? 'Simpan Data' : 'Update Data'
                    }}</button>
                @if(@$backlink !== null)
                <a href="{{url($backlink)}}" class="btn ml-2 btn-outline-secondary">Kembali</a>
                @else
                <a href="{{url($controller_path)}}" class="btn ml-2 btn-outline-secondary">Cancel</a>
                @endif
            </div>
        </div>
    </div>
</form>


@endsection


@section('extend-js')
<script type="text/javascript">
    let dynamic_url = controller_path + '/{{@$type == "create" ? "store" : "update"}}';
    var modal_form = false;
    jQuery(document).ready(function () {
        $('#formData').on('submit', function (e) {
            e.preventDefault(); // prevent pengiriman form bisasa
            ajaxFormSubmit();
        });
        $('#formData input').on('keypress', function (e) {
            if (e.which === 13) { // 13 adalah kode tombol "Enter"
                e.preventDefault(); // prevent pengiriman form bisasa
                // ajaxFormSubmit();
            }
        });

        $('#formData').validator();
        hideFullLoader()

    });


    $('#avatar').change(function(){
    var elm = $(this);
    // console.log(elm.attr('id'));
    var elmID = elm.attr('id')
    const file = this.files[0];
    if (file){
      let reader = new FileReader();
      reader.onload = function(event){
        // console.log(event.target.result);
        $('#'+elmID+'Preview').attr('src', event.target.result);
      }
      reader.readAsDataURL(file);
    }
  });
    //feel free nulis js

</script>
@endsection