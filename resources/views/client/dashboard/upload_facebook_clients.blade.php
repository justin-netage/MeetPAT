@extends('layouts.app')

@section('content')
<div id="loader"></div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1>{{ __('Upload New Audience') }}</h1></div>

                <div class="card-body">
                    <form id="upload-custom-audience" onsubmit="displayLoader(); submit_form(); return false;" novalidate>
                        @csrf
                        <div class="form-group">
                            <label for="email">{{ __('Audience Name') }}</label>

                                <input id="audience_name" type="text" class="form-control{{ $errors->has('audience_name') ? ' is-invalid' : '' }}" name="audience_name" value="{{ old('audience_name') }}" autofocus>

                                @if ($errors->has('audience_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('audience_name') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <a href="{{Storage::disk('s3')->url('meetpat/public/sample/example_audience_file.csv')}}">download sample file</a>
                        <div class="upload-box mb-2 text-center">
                            <input type="file" name="custom_audience" class="file-input-box" id="exampleFormControlFile1">
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" id="create_user" class="btn btn-primary">
                                {{ __('Submit Audience') }}
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

<script type="text/javascript">
    // var displayLoader = function () {
    //     $("#loader").css("display", "block");
    // };

    // var submit_form = function () {
    //     var formData = new FormData();

    //     formData.append("audience_name", $("#exampleFormControlFile1").files[0]);

    //     var request = new XMLHttpRequest();
    //     request.open("POST", "/meetpat-client/upload-facebook-custom-audience");

    //     console.log(request.send(formData));
    //     $("#loader").css("display", "none");

    // };

    $(document).ready(function(e) {
            e.preventDefault();
    });        
    

</script>

@endsection