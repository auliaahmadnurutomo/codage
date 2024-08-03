@php
    $header_form = @$type == 'create' ? 'New' : 'Edit';
    $btn_submit = @$type == 'create' ? 'Save' : 'Update';
@endphp

@section('form-field')
    <input class="id_reference" type="hidden" name="id_reference" value="{{ @$results->id}}">

    <x-select label="Source" name="option" required="required" search="true" width="3">
            <option value="0" {{@$results->option == 0 ? 'selected':''}}>Kosong</option>
            <option value="1" {{@$results->option == 1 ? 'selected':''}}>Satu</option>
    </x-select>

    <x-input label="Name" width="3" name="name" placeholder="Name" required="required" value="{{@$results->nama_jabatan}}"/>
    <x-input label="Code" width="3" name="code" placeholder="Code" required="required" value="{{@$results->kode_jabatan}}"/>
    <x-input id="MaxToday" label="Max Today" width="3" name="max_today" placeholder="Maximum today - 5Y" value="{{@$results->max_today}}"/>
    <x-input id="MinToday" label="Min Today Today" width="3" name="min_today" placeholder="Minimum today + 5Y" value="{{@$results->max_today}}"/>
    
    <h4>Maximum Current Date</h4>
    <x-input id="dateFromOld" label="Dari" name="dt_start" value="{{ date('01-m-Y') }}" width="3"></x-input>
    <x-input id="dateToOld" label="Sampai" name="dt_end" value="{{ date('d-m-Y') }}" width="3"></x-input>

    <h4>Minimum Current Date</h4>
    <x-input id="dateFromNew" label="Dari" name="dt_start" value="{{ date('01-m-Y') }}" width="3"></x-input>
    <x-input id="dateToNew" label="Sampai" name="dt_end" value="{{ date('d-m-Y') }}" width="3"></x-input>
@endsection
@section('form-js')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            $("select").selectpicker();
            dynamic_url = controller_path+"/{{@$type == 'create' ? 'store' : 'update'}}";
            $('#formData').validator();
            dateInit('#MaxToday','-5Y',0);
            dateInit('#MinToday',0,'5Y');
            initOldDateRange('#dateFromOld','#dateToOld',31);
            initNewDateRange('#dateFromNew','#dateToNew',31);
            hideFullLoader();
        });
    </script>
@endsection
@include('layouts.form.simple-modal')
