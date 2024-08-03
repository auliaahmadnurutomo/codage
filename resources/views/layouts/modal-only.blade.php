<div id="layout-form" class="card animated fadeIn" style="margin-bottom: 0px;">
    <div id="overlay"><div class="loading-div"></div></div>
    <style>

    </style>
    <div class="card-header bg-light text-muted">
        <strong class="h5">{{isset($header_form) ? $header_form: 'Data'}}</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card-body">
        @yield('modal-content')
    </div>
</div>
@yield('form-js')
