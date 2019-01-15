@extends('layouts.app')

@section('content')
<div id="loader"></div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1>{{ __('Upload New Audience') }}</h1></div>

                <div class="card-body">
                    <form method="POST" action="/api/meetpat-client/upload-facebook-custom-audience" id="upload-custom-audience" enctype="multipart/form-data" onsubmit="displayLoader(); return false; this.preventDefault();" novalidate>
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
                            <input type="hidden" name="user_id" value="{{\Auth::user()->id}}">
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
<script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
<script type="text/javascript">
//     var displayLoader = function () {
//         $("#loader").css("display", "block");
//     };

//     $("form#upload-custom-audience").submit(function(e) {
//     e.preventDefault();    
//     var formData = new FormData(this);

//     $.ajax({
//         url: '/api/meetpat-client/upload-facebook-custom-audience',
//         type: 'POST',
//         data: formData,
//         success: function (data) {
//             alert(data)
//         },
//         cache: false,
//         contentType: false,
//         processData: false
//     });

//     $("#loader").css("display", "none");

// });

   
    

</script>

@endsection