@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between d-md-none py-4">
        <div class="row col">
            <h5 class="text-center text-md-left mb-0 font-weight-bold">{{ $section }}</h5>
        </div>

        <div class="row col align-item-center justify-content-end">
            <button class="bg-light rounded-circle border border-lg text-muted ml-2" type="button" data-toggle="collapse"
                data-target="#collapse-filter" aria-expanded="false" aria-controls="collapse-filter">
                <i class="fa fa-ellipsis-h"></i>
            </button>
        </div>
    </div>
    @yield('tabNav')
    <div id="page-header" class="justify-content-between d-none d-md-flex align-items-center p-3">
        <div>
            <h5 class="text-center text-md-left mb-0 font-weight-bold text-muted">{{ $section }}</h5>
        </div>
        @yield('shortcut')
    </div>
    <div class="col pg-idx rounded-lg bg-white">

        <div class="collapse dont-collapse-sm" id="collapse-filter">
            <div class="card border-0">
                @include('layouts.page-index.filter_new')
            </div>
        </div>
        @include('layouts.page-index.table')

        <div class="col p-0 p-lg-auto my-3">
            <div class="row d-flex justify-content-center justify-content-md-between">
                <div class="col-sm-2 col-md-3 my-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text " id="basic-addon1"
                                style="width: 80px;"><small>Records</small></span>
                        </div>
                        <div class="border  form-control bolder font-weight-bold bg-light ">
                            <strong id="total_data">{{ $total_data }}</strong>
                        </div>
                    </div>
                </div>

                <nav id="paging-navigation" aria-label="navigation"
                    class="my-2 col-md-3 text-primary d-flex justify-content-center justify-content-md-end">
                    {!! @$data_pagination !!}
                </nav>
            </div>
        </div>
    </div>
    <!-- End Content -->

    <script type="text/javascript">
        let X_CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let controller_path = '{{ url($controller_path) }}';
        let data_fields = @json($fields);
        let dynamic_url = controller_path;
        var list_reload = '{{ isset($interval_reload) ? $interval_reload : false }}';
        jQuery(document).ready(function() {
            var list_data = @json($list_data);
            _append_tbody(list_data)
            if (list_reload == 'reload' && startReload) {
                console.log(list_reload);
                interval_reload();
            }
            changeSearchPlaceholder()
        });
    </script>
    @yield('addJs')
@endsection
