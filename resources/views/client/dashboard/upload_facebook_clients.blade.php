@extends('app.layouts')

@section('content')

@extends('layouts.app')

@section('content')
<div id="loader"></div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1>{{ __('Create User') }}</h1></div>

                <div class="card-body">
                    <form method="POST" id="new-user-form" action="{{ route('create-user-save') }}" onsubmit="displayLoader();">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{ __('First Name') }}</label>

                                <input id="audience_name" type="text" class="form-control{{ $errors->has('audience_name') ? ' is-invalid' : '' }}" name="audience_name" value="{{ old('audience_name') }}" autofocus>

                                @if ($errors->has('firstname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                        </div>
                        
                        <div class="form-group mb-0">
                            <button type="submit" id="create_user" class="btn btn-primary">
                                {{ __('Create New User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>

    function displayLoader() {
        $("#loader").css("display", "block");
    }


</script>

@endsection

@endsection