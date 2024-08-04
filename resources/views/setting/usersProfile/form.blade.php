@extends('layouts.blank-frame')
@php
$modal_size = 'modal-md';
$section = 'User Profile ';
@endphp
@section('title',$section)

@section('blank-content')

<h4 class="mt-5 col-lg-8">User Profile</h4>
<hr>
<form id="formData" method="post" class="col-lg-12" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="align-items-center text-center">
                <img id="preview-image" src="{{ auth()->user()->img_path != null ? asset(auth()->user()->img_path) : 'https://bootdey.com/img/Content/avatar/avatar7.png' }}" alt="User" class="rounded-circle" style="max-width: 30%; height: 30%; overflow:hidden;">
                <input type="file" name="img_path" id="img_path" accept="image/*" style="display: none;" onchange="previewImage(this)">
                <div class="mt-3">
                  <h4>{{ auth()->user()->name }}</h4>
                </div>
              </div>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Update Password</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  <input type="password" class="form-control" name="password" placeholder="Kosongkan Jika Password Tidak Diubah">
                </div>
              </div>
            </div>
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
                <button type="submit" class="btn btn-primary">{{@$type == 'create' ? 'Simpan Data' : 'Update Data' }}</button>
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

    // preview image
    function previewImage(input) {
        var preview = document.getElementById('preview-image');
        var file = input.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }

    document.getElementById('preview-image').addEventListener('click', function () {
        document.getElementById('img_path').click();
    });

</script>
@endsection