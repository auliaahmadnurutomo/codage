@extends('layouts.main')
@section('content')

{{--    index content--}}
    <div>
        @yield('blank-content')
    </div>

    <script type="text/javascript">
        let X_CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let controller_path = '{{url($controller_path)}}';
    </script>
    @yield('extend-js')
@endsection
