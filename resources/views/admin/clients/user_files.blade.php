@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <table class="table table-responsive-sm user-files-table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Audience Name</th>
                <th scope="col">Size</th>
                <th scope="col">Download</th>
                </tr>
            </thead>
            <tbody>
            @foreach($audience_files as $key=>$audience_file)
                <tr>
                <th scope="row">{{$key + 1}}</th>
                <td>{{$audience_file->audience_name}}</td>
                @if(env('APP_ENV') == 'production')
                    @if(\Storage::disk('s3')->exists('client/custom-audience/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv'))
                        <td>{{round(\Storage::disk('s3')->size('client/custom-audience/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv') / 1024 / 1024, 2)}} MB</td>
                        <td style="text-align: center;"><a href="{{\Storage::disk('s3')->temporaryUrl('client/custom-audience/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv', now()->addMinutes(5))}}"><i class="fas fa-file-download"></i></a></td>
                    @else
                        <td>N/A</td>
                        <td><i class="far fa-times-circle"></i> file not found</td>
                    @endif
                @else
                    @if(\Storage::disk('local')->exists('client/custom-audience/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv'))
                        <td>{{round(\Storage::disk('local')->size('client/custom-audience/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv') / 1024 / 1024, 2)}} MB</td>
                        <td><a href="{{\Storage::disk('local')->url('client/custom-audience/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv')}}"><i class="fas fa-file-download"></i></a></td>
                    @else
                        <td>N/A</td>
                        <td><i class="far fa-times-circle"></i> file not found</td>
                    @endif
                @endif
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>

@endsection