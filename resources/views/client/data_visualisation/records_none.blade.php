@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Records</div>

                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        No Records Found
                    </div>
                    You have not uploaded any records with MeetPAT yet. Go <a href="/meetpat-client/upload-client-file-data">here<a/> to start uploading your client data.
                </div>
        </div>
    </div>
</div>

@endsection