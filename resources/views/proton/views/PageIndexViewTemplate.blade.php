@extends('layouts.page-index.index')
@php
    $modal_size = 'modal-md';
    $interval_reload = 'reload';
    $section = 'Section';
@endphp
@section('title',$section)

<!-- Filtering field-->
@section('filter-field')
    <div class="form-group row">
        <div class="col-md-12 mt-2">
            <x-select name="status" required="required" search="false" width="12">
                <option value="all">All</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </x-select>
        </div>
    </div>
@endsection

{{--    Additional Button--}}
@section('shortcut')
    <div class="col p-0 shortcut d-flex justify-content-end">
        {!! @$btn_create !!}
    </div>
@endsection


{{--    Table Header--}}
@section('table-header')
    
    @if(@$table_header)
    <tr class="border-bottom">
        <th>No</th>
        @foreach ($table_header as $key => $header)
            <x-th col="{{ @$header['col'] }}" title="{{ @$header['title'] }}" first="{{ @$header['first'] }}" mw="{{ isset($header['mw']) ? $header['mw'] : '200' }}px" static="{{ isset($header['static']) ? $header['static'] : false }}"></x-th>
            @if(isset($header['toggleable'] ) && $header['toggleable'] == true)
               @php
                $columnOptions[$key+1] = $header['title'];
                @endphp
            @endif
        @endforeach
    </tr>
    @endif
@endsection

@section('addJs')
<script src="{{ asset('theme/js/adjust-column-table.js') }}"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        // Initialize table column manager
        const columnManager = new TableColumnManager('dataTable', '{{$controller_path}}_columnStates');
    });
</script>
@endsection