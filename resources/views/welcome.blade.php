@extends('layouts.app')

@section('content')
    <div class="row mx-auto">
        <div class="col-12">
            <div id="carouselExample" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <!-- <div class="carousel-item active">
                        <img class="d-block w-100" src="{{Storage::disk('s3')->url('meetpat/public/images/welcome-page/avatar-2155431_1920.png')}}">
                    </div> -->
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="{{Storage::disk('s3')->url('meetpat/public/images/welcome-page/avatar-2191918_1920.png')}}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{Storage::disk('s3')->url('meetpat/public/images/welcome-page/avatar-2191931.png')}}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{Storage::disk('s3')->url('meetpat/public/images/welcome-page/avatar-2191932_1920.png')}}">
                    </div>
                </div>
                <!-- <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a> -->
            </div>
        </div>
    </div>
    <br />
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="display-4"><span style="color: #3C3C3C">What is Meet</span><span style="color: #008DFF">PAT</span> ?</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut rhoncus sem nec metus auctor ornare in in ex. Nullam lacinia volutpat leo eget pharetra. Phasellus dictum, metus varius placerat consequat, metus libero dictum odio, ut suscipit tellus justo eget elit. Integer ultricies mauris in nulla dignissim, a varius sapien facilisis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer sed gravida lorem, egestas facilisis nibh. Nullam posuere ex sed tincidunt feugiat. Praesent eu arcu sed quam gravida iaculis. Quisque tincidunt eros nisl, sit amet commodo arcu consectetur eget. Proin vel magna eu orci iaculis finibus. 
                </p>
                <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut rhoncus sem nec metus auctor ornare in in ex. Nullam lacinia volutpat leo eget pharetra. Phasellus dictum, metus varius placerat consequat, metus libero dictum odio, ut suscipit tellus justo eget elit. Integer ultricies mauris in nulla dignissim, a varius sapien facilisis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer sed gravida lorem, egestas facilisis nibh. Nullam posuere ex sed tincidunt feugiat. Praesent eu arcu sed quam gravida iaculis. Quisque tincidunt eros nisl, sit amet commodo arcu consectetur eget. Proin vel magna eu orci iaculis finibus. 
                </p>
            </div>
        </div>
    </div>

@endsection