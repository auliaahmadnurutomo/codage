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
    <input class="id_reference" type="hidden" name="id_reference" value="{{ @$results->uuid}}">

    <x-input label="Name" width="3" name="name" placeholder="Max 200" required value="{{@$results->name}}"/>
    <x-input label="Code" width="3" name="code" placeholder="Max 200" required value="{{@$results->code}}"/>

    <x-select label="Source" name="option" search width="3" required>
        <option value="0" {{@$results->option == 0 ? 'selected':''}}>Kosong</option>
        <option value="1" {{@$results->option == 1 ? 'selected':''}}>Satu</option>
    </x-select>

    <div class="col">
        <div class="form-check row col-md-9 offset-md-3">
            <input class="form-check-input" type="checkbox" value="">
            <label class="form-check-label font-weight-bold text-primary">
                Checkbox
            </label>
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

    //feel free nulis js

</script>
@endsection