
<!-- Header -->
<div class="card-header bg-white px-0 border-0">
  <div class="row justify-content-between align-items-center flex-grow-1">
    <div class="col-md-8">
      <form id="pageIndexSearch" class="form-row align-items-center">
          @csrf
          @if(!empty($column_search) && isset($column_search))
          <div class="col-sm-4 my-1">
              <select id="getColumn" class="selectpicker w-100 border rounded" data-placeholder="Kolom yang dicari" name="getColumn" required>
                  @foreach(@$column_search as $cs => $valCs)
                    <option value= "{{ $cs }}" {{ $cs = @$getColumn ? "selected" : "" }} > {{ $valCs['label'] }}</option>
                  @endforeach
              </select>
          </div>
          <div class="col-sm-8 my-1">
              <div class="input-group">
                <input type="text" class="form-control" id="stringToSearch" placeholder="String To Search..." value="{{ @$stringToSearch }}">
                <div class="input-group-append">
                  <span class="input-group-text btn" id="btn-string-filter"><i class="fa fa-search"></i></span>
                </div>
              </div>
          </div>
          @endif
          
      </form>
    </div>

    <div class="col-md-4 mt-1 mt-md-0">
      <div class="d-flex justify-content-between justify-content-md-end">
       

        <a href="{{url($controller_path)}}" class="btn btn-light border mr-2"><i class="fa fa-sync-alt fa-xs mr-1 text-primary"></i></a>
        @if ($__env->hasSection('filter-field'))
        <button class="btn btn-light border" data-toggle="modal" data-target="#side-filter"><i class="fa fa-filter fa-xs mr-1 text-primary"></i></button>
        @endif
        @if(isset($columnOptions))
        <div id="dropdownColumnOptions" class="dropdown ml-2">
          <button class="btn btn-light border dropdown-toggle" type="button" id="dropdownColumns" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-list"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownColumns">
            @foreach($columnOptions as $key => $val)
            <label class="dropdown-item">
              <input type="checkbox" data-column="{{ $key }}" checked> {{ $val }}
            </label>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
  <!-- End Row -->
</div>

<div class="modal fade" id="side-filter" tabindex="-1" role="dialog" aria-labelledby="side-filter" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-slideout" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Filter {{$section}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formFilter" action="javascript:filter(this);" method="post">
              @yield('filter-field')
              <div class="text-center text-xl-left">
                  <button id="btn-search" class="btn alert-primary " data-loading="Filtering ...">&nbspApply Filter</button>
              </div>
            
          </form>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div> -->
      </div>
    </div>
  </div>
<!-- End Header -->
<style type="text/css">
.modal-dialog-slideout {min-height: 100%; margin: 0 0 0 auto;background: #fff;}
.modal.fade .modal-dialog.modal-dialog-slideout {-webkit-transform: translate(100%,0)scale(1);transform: translate(100%,0)scale(1);}
.modal.fade.show .modal-dialog.modal-dialog-slideout {-webkit-transform: translate(0,0);transform: translate(0,0);display: flex;align-items: stretch;-webkit-box-align: stretch;height: 100%;}
.modal.fade.show .modal-dialog.modal-dialog-slideout .modal-body{overflow-y: auto;overflow-x: hidden;}
.modal-dialog-slideout .modal-content{border: 0;}
.modal-dialog-slideout .modal-header, .modal-dialog-slideout .modal-footer {height: 69px; display: block;} 
.modal-dialog-slideout .modal-header h5 {float:left;}
.modal {
padding-right: 0px !important;
}
</style>
