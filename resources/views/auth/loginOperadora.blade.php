@extends('layouts.login')

@section('content')
<div class="">
    <div class="row justify-content-end">
        <div class="col-md-3 card-login">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('login.operadora') }}">
                        @csrf

                        <div class="d-flex justify-content-center">
                            <img src="img/logo_cliente.png" alt="">
                        </div>
                        <div class="form-group form-group-input row">
                            <!-- <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Login') }}</label> -->

                            <div class="col-md-12">
                                <input id="email" type="email" placeholder="E-mail" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group form-group-input row">
                            <!-- <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('Password') }}</label> -->

                            <div class="col-md-12">
                                <input id="password" type="password"  placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

    
                        <div class="form-group form-group-button row">
                            <div class="col-md-12">
                                <div class="float-right">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
