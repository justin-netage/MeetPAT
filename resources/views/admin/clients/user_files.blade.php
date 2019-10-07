@extends('layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('bower_components/jquery.bootgrid/dist/jquery.bootgrid.min.css')}}"/>
@endsection

@section('content')

<!-- User token -->
<form id="user_token_form">
    <input type="hidden" id="ApiToken" name="api_token" value="{{$user_api_token}}">
    <input type="hidden" id="UserId" name="user_id" value="{{$user->id}}">
</form>
<!-- End -->

<div class="container">
    <div id="alertSection"></div>
    <div class="row">
        <div class="col-12"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive" id="user_files">
                <table id="grid-data-api" class="table table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-column-id="id" data-identifier="true">ID</th>
                            <th data-column-id="created_at" data-type="numeric">Date</th>
                            <th data-column-id="audience_name">Audience Name</th>
                            <th data-column-id="file_source_origin">Original Data Source</th>
                            <th data-column-id="size">Size</th>
                            <th data-column-id="download" data-formatter="download" data-sortable="false">Download</th>
                            <th data-column-id="delete" data-formatter="delete" data-sortable="false">Delete</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('bower_components/jquery.bootgrid/dist/jquery.bootgrid.js')}}" defer></script>
<script type="text/javascript">
    var auth_token = $("#ApiToken").val();    
    var user_id = $("#UserId").val();

    $(document).ready(function() {
        var grid = $("#grid-data-api").bootgrid({
            columns: {
                align: 'center'
            },
            ajax: true,
            url: "/api/meetpat-admin/clients/files",            
            post: function() {
                $("#grid-data-api-footer").hide();
                $(".actions button[title='Refresh']").html("<i class=\"fas fa-sync-alt\"></i>");
                $(".actions .dropdown:last-child .dropdown-text").html("<i class=\"fas fa-th-list\"></i>");
                if(!$(".input-group-prepend").length) {
                    $(".search .input-group").prepend("<div class=\"input-group-prepend\">" +
                                                    "<span class=\"input-group-text\" id=\"basic-addon1\"><i class=\"fas fa-search\"></i></span>" +
                                                    "</div>");
                }
                
                $(".btn-group button").addClass("btn-secondary");
                
                return { api_token: auth_token, user_id: user_id}
            },
            formatters: {
                "download": function(column, row) {
                    return "<a href=\"" + row.download + "\"" + " ><i class=\"fas fa-file-download\"></i></a>";
                },
                "delete": function(column, row) {
                    return "<i class=\"far fa-trash-alt action-link command-delete\" data-file-id=\"" + row.id + "\"></i>";
                }
            }
            
        }).on("loaded.rs.jquery.bootgrid", function (e)
        {
            $(".pagination li").addClass("page-item");
            $(".pagination li a").addClass("page-link");
            $("#grid-data-api-footer").show();

            grid.find(".command-delete").on("click", function(e) {
                
                var delete_file_confirm = confirm("Are you sure that you want to delete the selected file?");                
                
                if (delete_file_confirm == true) {
                    
                    var file_id = $(this).data("file-id");
                    
                    grid.remove(file_id);
                    $.ajax({
                        url: '/api/meetpat-admin/delete-file',
                        data: {file_id: file_id, api_token: auth_token},
                        method: "POST",
                        success: function(data) {
                            $("#grid-data-api").bootgrid('reload');
                        },
                        error: function(error) {
                            console.log(error);
                            
                            $(".spinner-border", audience_file).addClass("d-none");
                            $("i", audience_file).show();
                        }
                    }).done(function() {
                        
                    });

                }
            });
                       
        });        

    });
    
</script>

@endsection