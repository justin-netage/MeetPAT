@if ( Session::has('success') )

<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> {{ Session::get('success') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

@endif


@if ( Session::has('error') )

<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Oops!</strong> {{ Session::get('success') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

@endif


@if ( Session::has('warning') )

<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning</strong> {{ Session::get('success') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

@endif


@if ( Session::has('info') )

<div class="alert alert-info alert-dismissible fade show" role="alert">
  <strong>FYI</strong> {{ Session::get('success') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

@endif


<!-- @if ($errors->any())

<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning</strong> You should check in on some of those fields below.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

@endif -->