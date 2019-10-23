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
    <div class="row">
        <div class="col-12">
            <div id="mainAlertSection"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-3 offset-md-9 mb-2">
            <a href="/meetpat-admin/clients/create" class="btn btn-block btn-secondary">
                <strong><i class="fas fa-user-plus"></i>&nbsp;&nbsp;Add Client</strong>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">

        <table id="clients_table" class="display table table-bordered table-hover table-striped mt-4 mb-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Active</th>
                    <th>Files</th>  
                    <th>Edit</th>
                    <th>Settings</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td class="text-center">{{$client->user_id}}</td>
                        <td>{{$client->name}}</td>
                        <td><a href="mailto:{{$client->email}}?Subject=MeetPAT" target="_top">{{$client->email}}</a></td>
                        <td class="text-center">
                            @if($client->active)
                                <i class="fas fa-toggle-on" data-user-id="{{$client->user_id}}" onclick="set_status(this)"></i>
                            @else
                                <i class="fas fa-toggle-off" data-user-id="{{$client->user_id}}" onclick="set_status(this)"></i>
                            @endif
                        </td>
                        <td class="text-center"><a href="/meetpat-admin/users/files/{{$client->user_id}}"><i class="fas fa-folder"></i></a></td>
                        <td class="text-center"><i class="fas fa-pen" data-user-id="{{$client->user_id}}" onclick="open_edit(this)"></i></td>
                        <td class="text-center"><i class="fas fa-sliders-h" data-user-id="{{$client->user_id}}" onclick="open_settings(this)"></i></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        </div>
    </div>
</div>

@endsection

@section('modals')
<div id="modalsContainer"></div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/w/bs4/dt-1.10.18/r-2.2.2/datatables.min.js" defer></script>
<script type="text/javascript" src="{{asset('js/meetpat_clients.min.js')}}"></script>

@endsection