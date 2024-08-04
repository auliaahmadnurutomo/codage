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
    <x-select label="Parent" name="parent" search="true" width="12">
        <option value="all">All</option>
        <option value="0" {{@$results->id_parent == 0 ? 'selected':''}}>Top Level Menu</option>
        @foreach(@$parents as $parent)
            <option value="{{$parent->id}}" {{@$results->id_parent == $parent->id ? 'selected':''}}>{{$parent->name}}</option>
        @endforeach
    </x-select>
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
        @foreach ($table_header as $header)
            <x-th col="{{ @$header['col'] }}" title="{{ @$header['title'] }}" first="{{ @$header['first'] }}" mw="{{ isset($header['mw']) ? $header['mw'] : '200' }}px" static="{{ isset($header['static']) ? $header['static'] : false }}"></x-th>
        @endforeach
        <th colspan="2" class="text-center"># </th>
    </tr>
    @endif
@endsection

