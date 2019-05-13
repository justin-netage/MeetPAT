@extends('layouts.app')

@section('content')

<div class="container">
    <div id="alertSection"></div>
    <div class="row">
        <div class="col-12">
            <table class="table table-responsive-sm table-hover user-files-table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Audience Name</th>
                <th scope="col">Original Data Source</th>
                <th scope="col">Size</th>
                <th scope="col">Download</th>
                <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
            @foreach($audience_files as $key=>$audience_file)
                <tr id="fileRow__{{$audience_file->id}}">
                <th scope="row">{{$key + 1}}</th>
                <td>{{$audience_file->audience_name}}</td>
                <td>{{ucwords(str_replace("_", " ", $audience_file->file_source_origin))}}</td>
                @if(env('APP_ENV') == 'production')
                    @if(\Storage::disk('s3')->exists('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv'))
                        <td>{{round(\Storage::disk('s3')->size('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv') / 1024 / 1024, 2)}} MB</td>
                        <td style="text-align: center;"><a href="{{\Storage::disk('s3')->temporaryUrl('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv', now()->addMinutes(5))}}"><i class="fas fa-file-download"></i></a></td>
                        @else
                        <td>N/A</td>
                        <td><i class="far fa-times-circle"></i> file not found</td>
                    @endif
                @else
                    @if(\Storage::disk('local')->exists('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv'))
                        <td>{{round(\Storage::disk('local')->size('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv') / 1024 / 1024, 2)}} MB</td>
                        <td><a href="{{\Storage::disk('local')->url('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv')}}"><i class="fas fa-file-download"></i></a></td>
                    @else
                        <td>N/A</td>
                        <td><i class="far fa-times-circle"></i> file not found</td>
                    @endif
                @endif
                    <td>
                        <button class="delete-tooltip table_button" data-toggle="modal" data-target="#DeleteFile__{{$audience_file->id}}" data-toggle="tooltip" data-html="true" title="<em>delete</em>">
                            <i class="far fa-trash-alt action-link"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>
<!--  -->
@endsection

@section('modals')

@foreach($audience_files as $key=>$audience_file)

<div class="modal" id="DeleteFile__{{$audience_file->id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Selected File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
            Are you sure that you want to permanetly remove this file?
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="delete_file('{{$audience_file->user_id}}', '{{$audience_file->id}}');" >Yes</button>
      </div>
    </div>
  </div>
</div>

@endforeach

@endsection

@section('scripts')

<script type="text/javascript">
    var delete_file = function(user_id, file_id) {
        $(this).prop('disabled', true);
        $.post('/api/meetpat-admin/delete-file', {user_id: user_id, file_id: file_id}, function(data) {

        }).fail(function(error) {
            $(this).prop('disabled', false);
            console.log(error);
            $("#DeleteFile__" + file_id).modal('hide');
            $("#alertSection").html(
                `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error</strong> &mdash; File could not be removed. Either it has already been removed or an error has occured.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`
            );
        }).done(function(data) {
            $(this).prop('disabled', false);
            console.log(data);
            $("#DeleteFile__" + file_id).modal('hide');
            $("#fileRow__" + file_id).remove();
            if(data.message == 'success') {
                $("#alertSection").html(
                `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success</strong> &mdash; The selected file has been removed.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`
            );
            } else {
                $("#alertSection").html(
                `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error</strong> &mdash; File could not be removed. Either it has already been removed or an error has occured.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`
            );
            }
        });
    }
    $(document).ready(function() {

    });
</script>

@endsection