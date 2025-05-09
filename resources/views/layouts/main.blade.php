<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <!-- Fonts -->
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/jquery-confirm/jquery-confirm.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800|Roboto:400,500,700" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/jquery-ui/jquery-ui.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('theme/bootstrap-select/bootstrap-select.min.css')}}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/css/loaderfio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/css/table-sortable.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/css/vmenuModule.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/css/page-index.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/select2/select2-bootstrap4.min.css') }}">
    <script type="text/javascript" src="{{ asset('theme/js/jquery-3.4.1.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('theme/js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('theme/bootstrap/bootstrap.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('theme/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('theme/js/validator.js') }}" defer></script>
    <script src="{{ asset('theme/js/vmenuModule.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('theme/select2/js/select2.min.js') }}"></script>


</head>
<!-- global style -->
<style type="text/css">
    .sidebar-sticky {
        scrollbar-width: none;
        scrollbar-color: blue white;
    }

    .sidebar-sticky::-webkit-scrollbar {
        width: 0px;
    }

    .bg-card {
        background-size: contain;
        background-position: right;
        /*border-top-right-radius: .375rem;*/
        /*border-bottom-right-radius: .375rem;*/
    }

    .bg-holder {
        position: absolute;
        width: 100%;
        min-height: 100%;
        top: 0;
        left: 0;
        /*background-size: cover;*/
        /*background-position: center;*/
        overflow: hidden;
        will-change: transform, opacity, filter;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        background-repeat: no-repeat;
        z-index: 0;
    }

    * {
        letter-spacing: .05em;

    }

    #dropdownColumnOptions .dropdown-menu {
        min-width: 8rem;
        padding: 0.5rem 0;
    }

    #dropdownColumnOptions .dropdown-item {
        display: flex;
        align-items: center;
        padding: 0.25rem 1.5rem;
    }

    #dropdownColumnOptions .dropdown-item input[type="checkbox"] {
        margin-right: 0.5rem;
    }
</style>
<script type="text/javascript">
    var modal_form = true;
    var startReload = true;
</script>

<body class="bg-body bg-white">
    <div class="preload full-loader">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <!-- CONTENT GOES HERE  -->

    <div id="target-top" class="container-fluid">
        <div class="row">
            <!-- SIDE-NAV -->
            @include('layouts.sidebar')
            <!-- END SIDE-NAV -->

            <!-- TOP-NAV -->
            <nav id="top-nav" class="fixed-top bg-transparent">
                <div class="navbar py-2 bg-white border-bottom">

                    <div class="d-flex">
                        <button class="btn btn-sm btn-menu border rounded text-primary mr-3">
                            <i class="fa fa-bars"></i>
                        </button>

                        @if (empty(session('office_access')))
                            <h5 class="mt-1 mb-0 text-muted"><strong>{{ session('office') }}</strong></h5>
                        @else
                            <select id="officeChange" class="form-control form-control-sm rounded border" data-size="5"
                                data-live-search="true" data-width="250px">
                                @foreach (session('office_access') as $office)
                                    <option value="{{ $office->id }}" data-subtext="{{ $office->code }}"
                                        {{ session('office_id') == $office->id ? 'selected' : '' }}>{{ $office->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="nav">
                        <div class="nav-item dropdown mr-0 mx-md-3">
                            <button data-toggle="dropdown" class="btn btn-link border rounded-circle">
                                <i class="text-primary fa fa-user fa-sm text-white"></i></button>
                            <div>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ url('usersProfile') }}" class="dropdown-item"><i class="fa fa-cog"></i>
                                        {{ Auth::user()?->name }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ url('logout') }}" class="dropdown-item"><i
                                            class="fa fa-power-off text-danger"></i> &nbspLogout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style type="text/css">
                        .nav-item .indicator {
                            background: #d9534f;
                            box-shadow: 0 .1rem .2rem rgba(0, 0, 0, .05);
                            border-radius: 50%;
                            display: block;
                            height: 18px;
                            width: 18px;
                            padding: 1px;
                            position: absolute;
                            top: 0;
                            right: -8px;
                            text-align: center;
                            transition: top .1s ease-out;
                            font-size: .675rem;
                            color: #fff;
                        }

                        .navbar .nav-item .nav-link::after {
                            display: none;
                        }
                    </style>
                </div>
                <!-- <div aria-live="polite" aria-atomic="true"> -->
                <!-- Position it -->
                <!-- <div> -->
                <!-- Then put toasts within -->
                <div id="liveToast" class="toast hide" role="alert" data-autohide="false" data-animation="true"
                    aria-atomic="true" style="position: absolute; top: 10; right: 0;">
                    <div class="toast-header">
                        <strong class="mr-auto header">Bootstrap</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="errorMsg" class="toast-body">
                        <!-- Message goes here -->
                        <ul></ul>
                    </div>
                </div>
                <!-- </div> -->
                <!-- </div> -->
            </nav>

            <!-- END TOP-NAV -->




            <main role="main" class="main my-0  bg-white">
                <div class="app-content px-3 px-md-4 my-0">
                    @yield('content')
                </div>
            </main>
        </div>
    </div> <!-- /.container-fluid -->




    <!-- Modal Form -->
    <div class="modal fade" id="modalForm" role="dialog" aria-labelledby="modalForm" aria-hidden="true">
        <div class="modal-dialog {{ isset($modal_size) ? $modal_size : 'modal-md' }} modal-dialog-centered"
            role="document">

            <div class="modal-content">
                <!-- Ajax content load here -->
            </div>
        </div>
    </div>
    <!-- End Modal Form -->

    <!-- Modal Response Click -->
    <div class="modal fade" id="response-click" data-keyboard="false" data-backdrop="static" tabindex="-1"
        role="dialog" aria-labelledby="response-click" aria-hidden="true">
        <div class="modal-dialog {{ isset($modal_size) ? $modal_size : 'modal-md' }} modal-dialog-centered"
            role="document">
            <div class="modal-content">
                <!-- Ajax content load here -->
            </div>
        </div>
    </div>
    <!-- End Modal Response Click -->

</body>

<!-- JS SCRIPT  -->
{{-- <script type="text/javascript" src="{{asset('theme/bootstrap-select/bootstrap-select.min.js')}}"></script> --}}
<script type="text/javascript" src="{{ asset('theme/jquery-ui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('theme/js/script.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".u-vmenu").vmenuModule({
            Speed: 200,
            autostart: true,
            autohide: true,
            linkActive:'{{@$activeMenu ?? @$controller_path}}'
        });

        // $('.toast').toast('show');
        $(window).scroll(function() {
            var y = $(window).scrollTop();
            if (y > 0) {
                $(".navbar").addClass('shadow-sm');
            } else {
                $(".navbar").removeClass('shadow-sm');
            }
        });
        $('#officeChange').on('change', function() {
            off_Change($(this).val());
            // $("#officeChange").select2('refresh');
        });

        function off_Change(officeId) {
            var url = "{{ url('officeAccess/changeOffice') }}";
            showFullLoader();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "POST",
                url: url,
                data: {
                    'id_office': officeId,
                    'controller_path': '{{ @$controller_path }}'
                },
                success: function(data) {
                    hideFullLoader();

                    if (data.status == 401) {
                        van_modal();
                    } else {
                        $("#response-click .modal-content").html(data);
                        $("#response-click").modal({
                            'keyboard': false,
                            'backdrop': 'static',
                        });
                    }
                },
                error: function(data) {
                    $("#officeChange").val('').select2('refresh');
                    if (data.status == 419 || data.status == 401) {
                        van_modal();
                    }
                }
            });
        }
        $('select:not(.selectpicker, .exclude-predefine-select2)').select2({
            placeholder: function() {
                return $(this).data('placeholder');
            },
            theme: 'bootstrap4',
        });
    });
</script>
<!-- END JS SCRIPT  -->

</html>
