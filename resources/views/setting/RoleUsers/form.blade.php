@extends('layouts.blank-frame')
@php
$modal_size = 'modal-md';
$section = 'Create Role ';
@endphp
@section('title',$section)

@section('blank-content')


<h4 class="mt-5 col-lg-8">Create</h4>
<hr>
<form id="formData" method="post" class="col-lg-8">
    @csrf
    <input class="id_reference" type="hidden" name="id_reference" value="{{ @$results->id}}">
      <x-input label="Name" width="2" name="name" placeholder="Access Name" required="required" value="{{@$results->name}}" />

      <div class="form-group row">
        <div id="theTreedMenu" class="col-md-8 offset-md-2">
          <h5>Menu &amp; Access Authorization</h5>
            <ul id="sitri" class="tree">
              <?php echo $acl?>
            </ul>
            <span name="setting_acl"></span>
            <span id="setting_acl" class="text-danger text-center" data-label="alert"></span>
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
    var showToastNotif = true;
    jQuery(document).ready(function(){
    $('#sitri, #tree2').treed({openedClass:'fa fa-minus', closedClass:'fa fa-plus'});
    $('#formData').validator();
    hideFullLoader()
  });

  $.fn.extend({
    treed: function (o) {

      var openedClass = '';
      var closedClass = '';

      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };

        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function(){
          let el=this;
          $(this).on('click', function () {
              $(this).closest('li').click();
          });
        });
        
        $('input[name="authorization[]"][type=checkbox]').on('change', function(e){
          if($(this).is(':checked')){
              if($(this).siblings('ul').children('li:hidden').length){
                 $(this).closest('li').click();
                 $(this).parents('ul').parent('li.branch').children('input').prop("checked",false);
               }
              $(this).parents('ul').parent('li.branch').children('input').prop("checked",true)
            }
            else{

             $(this).siblings('ul').find('input').prop("checked", false);// uncheck all children
            }
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
  });
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