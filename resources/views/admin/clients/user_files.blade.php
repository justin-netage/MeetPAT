@extends('layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/w/bs4/dt-1.10.18/r-2.2.2/datatables.min.css"/>
@endsection

@section('content')

<!-- User token -->
<form id="user_token_form">
    <input type="hidden" id="ApiToken" name="api_token" value="{{$user_api_token}}">
</form>
<!-- End -->

<div class="container">
    <div id="alertSection"></div>
    <div class="row">
        <div class="col-12"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <table id="user_files" class="display table table-bordered table-hover table-striped mt-4 mb-4">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Audience Name</th>
                        <th>Original Data Source</th>
                        <th>Size</th>
                        <th>Download</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($audience_files as $key=>$audience_file)
                    <tr id="fileRow__{{$audience_file->id}}">
                        <td class="text-center">{{$key + 1}}</td>
                        <td>{{explode(" - ", $audience_file->audience_name)[0]}}</td>
                        <td>{{ucwords(str_replace("_", " ", $audience_file->file_source_origin))}}</td>
                        @if(env('APP_ENV') == 'production')
                            @if(\Storage::disk('s3')->exists('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv'))
                                <td class="text-center">{{round(\Storage::disk('s3')->size('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv') / 1024 / 1024, 2)}} MB</td>
                                <td class="text-center"><a href="{{\Storage::disk('s3')->temporaryUrl('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv', now()->addMinutes(5))}}"><i class="fas fa-file-download"></i></a></td>
                                @else
                                <td class="text-center">N/A</td>
                                <td class="text-center"><i class="far fa-times-circle"></i> file not found</td>
                            @endif
                        @else
                            @if(\Storage::disk('local')->exists('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv'))
                                <td class="text-center">{{round(\Storage::disk('local')->size('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv') / 1024 / 1024, 2)}} MB</td>
                                <td class="text-center"><a href="{{\Storage::disk('local')->url('client/client-records/' . 'user_id_' . $user->id . '/' . $audience_file->file_unique_name . '.csv')}}"><i class="fas fa-file-download"></i></a></td>
                            @else
                                <td class="text-center">N/A</td>
                                <td class="text-center"><i class="far fa-times-circle"></i> file not found</td>
                            @endif
                        @endif
                        <td class="text-center" data-file-id="{{$audience_file->id}}" onclick="delete_audience_file(this)">
                            <div class="spinner-border d-none" style="width: 24px; height: 24px;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <i class="far fa-trash-alt action-link"></i>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/w/bs4/dt-1.10.18/r-2.2.2/datatables.min.js" defer></script>
<script type="text/javascript">
    var auth_token = $("#ApiToken").val();    

    // Change client active status.
    function delete_audience_file(audience_file) {
        var delete_file_confirm = confirm("Are you sure that you want to delete the selected file?");

        if (delete_file_confirm == true) {
            var file_id = audience_file.getAttribute("data-file-id");
            
            $("i", audience_file).hide();
            $(".spinner-border", audience_file).removeClass("d-none");

            $.ajax({
                url: '/api/meetpat-admin/delete-file',
                data: {file_id: file_id, api_token: auth_token},
                method: "POST",
                success: function(data) {
                    
                    $("#fileRow__" + file_id).hide();
                    
                },
                error: function(error) {
                    console.log(error);
                    
                    $(".spinner-border", audience_file).addClass("d-none");
                    $("i", audience_file).show();
                }
            }).done(function() {
                location.reload();
            });
        } 

    }

    $(document).ready(function() {
        $('#user_files').DataTable({
            responsive: true
        });
    });
    
</script>

@endsection