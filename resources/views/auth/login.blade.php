<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store_information->name }} | Login</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <link href="{{ asset('assets/extensions/fontawesome-6/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extensions/fontawesome-6/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/extensions/fontawesome-6/css/solid.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/icon.png') }}" type="image/png">
</head>

@include('layouts.sweet-alert')
<body class="bg-primary">
    <div class="container col-lg-4">
        <div class="row" style="margin-top:170px;">
            <div class="col-lg-12 col-lg-8">
                <div class="card shadow-lg rounded">
                    <h1 class="text-center my-3">Login.</h1>
                    <form action="{{ route('login.post') }}" method="POST" class="form form-horizontal">
                        @csrf
                        <div class="row mx-2">
                            <div class="col-md-8 col-md-12">
                                <div class="form-group position-relative has-icon-left mb-4">
                                    <input type="text" name="emailOrUsername" class="form-control form-control-xl @error('emailOrUsername') is-invalid @enderror" placeholder="Username / Email" autocomplete="off" value="{{ old('emailOrUsername') }}">
                                    
                                    @error('emailOrUsername')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="form-control-icon">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="form-group position-relative has-icon-left">
                                    <input type="password" id="password-login" name="password" class="form-control form-control-xl" placeholder="Password">
                                    <div class="form-control-icon">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" id="check-login-pass" class="form-check-input form-check-primary">
                                    <label class="form-check-label">Lihat Password</label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block btn-xs shadow-lg mt-3">Log in</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mt-4 text-lg-12 text-lg-8 fs-6">
                        <p class="text-gray-600 small">Belum Punya Akun ? <a href="{{ route('register.index') }}" class="font-bold">Daftar Sekarang !</a></p>
                        <p class="small">Lupa Password ? <a class="font-bold" href="{{ route('forget-pass.index') }}">Reset Password</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/sweetalert2.js') }}"></script>
</html>
