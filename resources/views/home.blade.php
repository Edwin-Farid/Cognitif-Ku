@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <a href="{{ route('cognifit.games') }}">Games</a>

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('user_token') && session('client_id') && session('client_secret'))
        <script>
            console.log('berhasil simpan');

            // Simpan token ke localStorage
            localStorage.setItem('AUTH_TOKEN', JSON.stringify({
                user_token: '{{ session('user_token') }}',
                client_id: '{{ session('client_id') }}',
                client_secret: '{{ session('client_secret') }}',
            }));
            // localStorage.setItem('api_token', '{{ session('api_token') }}');
            // localStorage.setItem('api_refresh_token', '{{ session('api_refresh_token') }}');

            // const userToken = localStorage.getItem("AUTH_TOKEN");

            // console.log(JSON.parse(userToken).access_token);
        </script>
    @endif
@endsection
