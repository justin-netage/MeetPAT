@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron jumbotron-main">
                <h1 class="display-4">Sync Your Platform</h1>
                <h4 class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</h4>
                <hr class="my-4">
                <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
                <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a> 
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="jumbotron">
                <h1 class="display-4">Start Now With Google and Facebook</h1>
                <h4 class="lead">It uses utility classes for typography and spacing to space content out within the larger container.</h4>
                <hr class="my-4">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-dark btn-lg btn-block google-button disabled">
                        <i class="fab fa-google"></i><span class="google-o-red">o</span><span class="google-o-yellow">o</span><span class="google-g-blue">g</span ><span class="google-l-green">l</span><span class="google-e-orange">e</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-light btn-lg btn-block facebook-button">
                        <i class="fab fa-facebook-f"></i>acebook
                    </button>
                </div>
            </div>                
            </div>
        </div>
    </div>
</div>

@endsection