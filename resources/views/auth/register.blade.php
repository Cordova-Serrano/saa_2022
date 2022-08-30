@extends('layouts.app')

@section('content')
<div class="container">
    <div class="justify-content-center">
        <div class="card">
            <div class="overlay" id="overlay">
                <i class="fal fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Regístrate para continuar</p>
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible text-justify" id="error-alert">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
                    <h6><i class="icon fal fa-times-circle"></i>¡Error!</h6>
                    @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}" autocomplete="off">
                    @csrf
                    <!-- NAME -->
                    <div class="input-group mb-3">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nombre">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fal fa-user"></span>
                            </div>
                        </div>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- /NAME -->
                    <!-- EMAIL -->
                    <div class="input-group mb-3">
                        <input id="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Correo electrónico" required type="email" value="{{ old('email') }}" autocomplete="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fal fa-envelope"></i>
                            </div>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- /EMAIL -->
                    <!-- PASSWORD -->
                    <div class="input-group mb-3">
                        <input id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Contraseña" required type="password" autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fal fa-lock-alt"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- /PASSWORD -->
                    <!-- CONFIRM PASSWORD -->
                    <div class="input-group mb-3">
                        <input id="password-confirm" class="form-control" name="password_confirmation" placeholder="Confirma contraseña" required type="password" autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fal fa-lock-alt"></span>
                            </div>
                        </div>
                    </div>
                    <!-- /CONFIRM PASSWORD -->
                    <!-- BUTTON -->
                    <div class="row mb-0">
                        <div class="col-12 text-center text-sm-right ">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fal fa-sign-in mr-2"></i>{{ __('Regístrate') }}
                            </button>
                            @guest
                            @if (Route::has('login'))
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a>
                            @endif
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </div>
                    </div>
                    <!-- /BUTTON -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection