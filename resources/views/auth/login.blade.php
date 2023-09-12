@extends('layouts.login')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="auth-title text-center">Intranet Corporativa BH - PL</h3>
            <p class="auth-subtitle mb-5 text-center">Inicie sesión con los datos que se le proporcionaron.</p>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="email" class="form-control form-control-xl  @error('email') is-invalid @enderror text-center"
                        name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email"
                        autofocus>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror text-center"
                        placeholder="Password" name="password" required autocomplete="current-password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }} id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        {{ __('Remember Me') }}
                    </label>
                </div>
                <button type="submit" class="boton btn-block btn-lg shadow-lg mt-5"> {{ __('Login') }}</button>
                {{-- @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif --}}
            </form>
        </div>
    </div>
@endsection
