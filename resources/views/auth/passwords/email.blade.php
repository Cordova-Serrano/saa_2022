@extends('layouts.app')

@section('content')
<div class="container">
    <div class="justify-content-center">
        <div class="card">
            <div class="overlay" id="overlay">
                <i class="fal fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="card-body login-card-body">
                <div class="login-box-msg">{{ __('Reestablecer contraseña') }}</div>
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible text-justify" id="error-alert">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
                    <h6><i class="icon fal fa-times-circle"></i>¡Error!</h6>
                    @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                    @endforeach
                </div>
                @endif

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" autocomplete="off">
                        @csrf

                        <!-- EMAIL -->
                        <div class="input-group mb-3">
                            <input id="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Correo electrónico" required autofocus type="email" value="{{ old('email') }}" autocomplete="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fal fa-user"></span>
                                </div>
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <!-- /EMAIL -->
                        <div class="row ">
                            <div class="">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enviar enlace de restablecimiento de contraseña') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection