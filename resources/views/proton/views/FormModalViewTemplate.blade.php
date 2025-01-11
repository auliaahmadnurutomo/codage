@php
    $header_form = @$type == 'create' ? 'New' : 'Edit';
    $btn_submit = @$type == 'create' ? 'Save' : 'Update';
@endphp

@section('form-field')
    <input class="id_reference" type="hidden" name="id_reference" value="{{ @$results->uuid}}">
    <x-select label="Source" name="option" search width="3">
            <option value="0" {{@$results->option == 0 ? 'selected':''}}>Kosong</option>
            <option value="1" {{@$results->option == 1 ? 'selected':''}}>Satu</option>
    </x-select>
    
    <x-input label="Name" width="3" name="name" placeholder="Max 200" required value="{{@$results->name}}"/>
    <x-input label="Code" width="3" name="code" placeholder="Max 200" required value="{{@$results->code}}"/>

    <!-- Lihat Komponen lengkap pada dokumentasi input -->

    @if(@$results) //opsional
    <hr>
    <div class="form-group row">
        <label class="col-md-3 control-label">Insert</label>
        <div class="col-md-9 bg-light">
            <small>{{@$results->user_insert}} <br>{{date('d-m-Y H:i',strtotime(@$results->dt_insert))}}</small>
        </div>
    </div>
    @endif

@endsection
@section('form-js')
    <script type="text/javascript">
        //pastikan tidak ada variable, id, class yang conflict dengan variable page-index
        //yang dipake js di page-index
        jQuery(document).ready(function(){
            $("select").select2({
                theme: 'bootstrap4',
            });
            // Load fungsi js untuk form modal lainnya

            dynamic_url = controller_path+"/{{@$type == 'create' ? 'store' : 'update'}}";
            $('#formData').validator();
            hideFullLoader();
        });
        // tambahan fungsi js untuk form modal special case, tapi ati2 dengan js yang ada di page-index.
    </script>
@endsection
@include('layouts.form.simple-modal')
