@extends('layouts.app')

@section('content')
<style>
    body {
        background: #0264d6;
        /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover, #0264d6 1%, #1c2b5a 100%);
        /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(1%, #0264d6), color-stop(100%, #1c2b5a));
        /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover, #0264d6 1%, #1c2b5a 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover, #0264d6 1%, #1c2b5a 100%);
        /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover, #0264d6 1%, #1c2b5a 100%);
        /* IE10+ */
        background: radial-gradient(ellipse at center, #0264d6 1%, #1c2b5a 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0264d6', endColorstr='#1c2b5a', GradientType=1);
        /* IE6-9 fallback on horizontal gradient */
    }

    .card,
    .card-header {
        background: transparent;
        border: none;
    }
    .input-group-text{
        width: 50px;
    }
</style>
<div class="login d-flex align-items-center justify-content-center" style="height:80vh">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header h3 text-center text-warning">{{ config('app.name', 'Login') }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label class="text-warning">Email</label>
                            <div class="input-group input-group-lg mb-3">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>

                            @error('email')
                            <span class="invalid-feedback d-block text-warning" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="text-warning">Password</label>
                        <div class="input-group input-group-lg mb-3">
                                <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password">
                            </div>
                            @error('password')
                            <span class="invalid-feedback d-block text-warning" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-0">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>

                            
                        </div>
                        <div class="text-right">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link text-white small" href="{{ route('password.request') }}">
                                <small>Forgot Your Password ?</small>
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection