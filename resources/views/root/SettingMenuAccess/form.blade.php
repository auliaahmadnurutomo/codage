@php
    $header_form = @$type == 'create' ? 'New' : 'Edit';
    $btn_submit = @$type == 'create' ? 'Save' : 'Update';
@endphp

@section('form-field')
    <input class="id_reference" type="hidden" name="id_reference" value="{{ @$results->id}}">
    <x-select label="Parent" name="parent" required="required" search="true" width="3">
        <option value="0" {{@$results->id_parent == 0 ? 'selected':''}}>Top Level Menu</option>
        @foreach(@$parents as $parent)
            <option value="{{$parent->id}}" {{@$results->id_parent == $parent->id ? 'selected':''}}>{{$parent->name}}</option>
        @endforeach
    </x-select>
    
    <x-select label="Level" name="level" required="required" search="true" width="3">
        <option value="0" {{@$results->access == 0 ? 'selected':''}}>Root Access</option>
        <option value="1" {{@$results->access == 1 ? 'selected':''}}>User Access</option>
    </x-select>

    <x-select label="Type" name="type" required="required" search="true" width="3">
        <option value="0" {{@$results->type == 0 ? 'selected':''}}>Access</option>
        <option value="1" {{@$results->type == 1 ? 'selected':''}}>Menu</option>
    </x-select>

    <x-input label="Name" width="3" name="name" placeholder="menu name" required="required" value="{!! @$results->name !!}"/>

    <x-input label="Session" width="3" name="sess_name" required="required" placeholder="Session Name" value="{{@$results->sess_name}}"/>

    <x-input label="URL" width="3" name="url"  placeholder="Url address" value="{{@$results->url}}"/>

    <x-input label="Font Icon" width="3" name="icon" placeholder="fa fa-" value="{{@$results->icon}}"/>

    <x-input label="Order" width="3" name="order" placeholder="Menu Order Placement" value="{{@$results->menu_order}}"/>

@endsection
@section('form-js')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            $("select").selectpicker();
            dynamic_url = controller_path+"/{{@$type == 'create' ? 'store' : 'update'}}";
            $('#formData').validator();
            hideFullLoader();
        });
    </script>
@endsection
@include('layouts.form.simple-modal')
