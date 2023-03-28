<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=61238cdd4b43b00013899bbf&product=inline-share-buttons" async="async"></script>


</head>



<body>

    <!-- CSS only -->

    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"

        integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous"> -->

    



    <header>

        <div class="container">

            <div class="d-flex align-items-center header-top">

                <a href="/" class="d-inline-block brand">

                    <img src="{{ asset('asset/images/logo.jpg') }}" alt="NRN POST Logo">

                </a>

                <div>

                    <p class="mb-1">{{nrndate(date("y-m-d"))}}</p>

                 

                </div>

                <div class="dropdown flex-grow-1 text-right regional-dd">

                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton"

                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        संस्करण छानुहोस

                    </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                    @foreach($editions as $edition)

                    <li class="nav-item">

                        <a class="dropdown-item" href="{{ route('website.edition', ['slug' => $edition->slug]) }}">{{$edition->name}} </a>

                    </li>

                    @endforeach

    

                    </div>

                </div>

            </div>

        </div>

        <!-- BREAKING NEWS-->

        <div class="breaking-news">

            <div class="container">

                <div class="d-flex align-items-center">

                    <div class="badge badge-pill badge-white text-uppercase text-regular mr-3">Breaking News</div>

                    <marquee scrolldelay="150" onmouseover="this.stop();" onmouseout="this.start();">

                        <ul class="list-inline mb-0">

                        @foreach($posts as $post)

                            <li class="list-inline-item"><a href="{{ route('website.post', ['edition'=>$post->edition->name,'country'=>$post->country->name,'id' => $post->id]) }}">{{$post->title}} </a>

                            </li>

                            @endforeach

                        </ul>

                    </marquee>

                </div>

            </div>

        </div><!-- End breaking news-->

    </header>

    <!-- NAVIGATION --> 

    <nav class="navbar navbar-expand-lg  py-3 mb-3" style="padding-top:10px !important;">

        <div class="container">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"

                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

                <span class="navbar-toggler-icon"></span>

                <span class="navbar-toggler-icon"></span>

            </button>



            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    @php

                    $current = Request::url();

                    $current = basename($current);

                    $root = request()->getHttpHost();

                    @endphp



                <ul class="navbar-nav">

                @if($root==$current)

                    <li class="nav-item active">

                        <a class="nav-link" href="/">गृहपृष्ठ <span class="sr-only">(current)</span></a>

                    </li>

                @else

                <li class="nav-item ">

                        <a class="nav-link" href="/">गृहपृष्ठ <span class="sr-only">(current)</span></a>

                    </li>

                @endif
                <li class="nav-item ">

                <a class="nav-link" href="{{ route('website.country', ['slug' => 'nepal']) }}">देश</span></a>

                </li>

                    

                    @foreach($categories as $category)
@if($category)
                    @if($current==$category->slug)

                    <li class="nav-item active">

                        <a class="nav-link" href="{{ route('website.category', ['slug' => $category->slug]) }}">{{$category->name}} </a>

                    </li>

                    @else

                    <li class="nav-item">

                        <a class="nav-link" href="{{ route('website.category', ['slug' => $category->slug]) }}">{{$category->name}} </a>

                    </li>

                    @endif
                    @endif
                    @endforeach



                    </li>

              <li class="nav-item ">

                        <a class="nav-link" href="{{ route('website.bichar') }}">विचार <span class="sr-only">(current)</span></a>

                    </li>

                   

                   

                   

                </ul>

            </div>



         

        </div>

        

    </nav>

    <div class="container container--custom">

    <!-- <div class="container container--custom">

    

        <div class="region-buttons">

        <div class="btn-group btn-group-toggle">

                

                <label class="btn btn-outline-dark btn__opaque active">

                    <input type="radio" name="options" id="option1" checked> संस्करण

                </label>

                @foreach($editions as $edition)

            <label class="btn btn-outline-dark btn__opaque aling-it">

                 <input type="radio" name="options" id="option2"><a href="{{ route('website.edition', ['slug' => $edition->slug]) }}">{{$edition->name}}</a>

             </label>

             @endforeach

            </div>

        </div>  -->

       

    



