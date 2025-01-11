<div id="layout-form" class="card animated fadeIn" style="margin-bottom: 0px;">
    <div id="overlay"><div class="loading-div"></div></div>
    <style>

    </style>
    <div class="card-header bg-light text-muted">
        <strong class="h5">{{isset($header_form) ? $header_form: 'Add New'}}</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card-body">
        <form id="formData" method="post" enctype="multipart/form-data">
            @yield('form-field')

            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>

            <div class="form-group row border-top mb-0">
                <div class="col-md-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary" data-loading={{isset($btn_submit) ? $btn_submit :  'Saving'}}>
                        {{isset($btn_submit) ? $btn_submit :  'Save'}}
                    </button>
                    <button type="button" class="btn btn-light border" data-dismiss="modal">
                        {{isset($btn_back) ? $btn_back : 'Cancel'}}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $('#formData').on('submit', function (e) {
        e.preventDefault(); // prevent pengiriman form bisasa
        ajaxModalSubmit();
    });
    $('#formData input').on('keypress', function (e) {
        if (e.which === 13) { // 13 adalah kode tombol "Enter"
            e.preventDefault(); // prevent pengiriman form bisasa
            ajaxModalSubmit();
        }
    });
    
</script>
@yield('form-js')
