@php
    $header_form = @$type == 'create' ? 'New' : 'Edit';
    $btn_submit = @$type == 'create' ? 'Save' : 'Update';
@endphp

@section('form-field')
    <input class="id_reference" type="hidden" name="id_reference" value="{{ @$results->id}}">
    <x-input label="Name" width="3" name="name" placeholder="Nama" required="required" value="{{@$results->name}}"/>
    <x-input label="Code" width="3" name="code" placeholder="Kode" value="{{@$results->code}}"/>

    <!-- Lihat Komponen lengkap pada dokumentasi input -->
@endsection
@section('form-js')
    <script type="text/javascript">
        //pastikan tidak ada variable, id, class yang conflict dengan variable page-index
        //yang dipake js di page-index
        jQuery(document).ready(function(){
            // Load fungsi js untuk form modal lainnya

            dynamic_url = controller_path+"/{{@$type == 'create' ? 'store' : 'update'}}";
            $('#formData').validator();
            hideFullLoader();
        });
        // tambahan fungsi js untuk form modal special case, tapi ati2 dengan js yang ada di page-index.
    </script>
@endsection
@include('layouts.form.simple-modal')
