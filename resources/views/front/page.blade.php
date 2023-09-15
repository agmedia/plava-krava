@extends('front.layouts.app')
@if (request()->routeIs(['index']))
    @section ( 'title', 'Plava Krava | Webshop |  Knjige na engleskom jeziku' )
    @section ( 'description', 'Istražite svijet književnih čuda u našoj online knjižari. Otkrijte bogatu kolekciju knjiga na engleskom jeziku, od klasičnih romana do suvremenih bestselera. Pronađite svoje sljedeće zanimljivo štivo danas!' )


    @push('meta_tags')

        <link rel="canonical" href="{{ env('APP_URL')}}" />
        <meta property="og:locale" content="hr_HR" />
        <meta property="og:type" content="product" />
        <meta property="og:title" content="Plava Krava | Webshop |  Knjige na engleskom jeziku" />
        <meta property="og:description" content="Istražite svijet književnih čuda u našoj online knjižari. Otkrijte bogatu kolekciju knjiga na engleskom jeziku, od klasičnih romana do suvremenih bestselera. Pronađite svoje sljedeće zanimljivo štivo danas!" />
        <meta property="og:url" content="{{ env('APP_URL')}}"  />
        <meta property="og:site_name" content="Plava Krava | Webshop |  Knjige na engleskom jeziku" />
        <meta property="og:image" content="{{ asset('media/plavakrava.jpg') }}" />
        <meta property="og:image:secure_url" content="{{ asset('media/plavakrava.jpg') }}" />
        <meta property="og:image:width" content="1920" />
        <meta property="og:image:height" content="720" />
        <meta property="og:image:type" content="image/jpeg" />
        <meta property="og:image:alt" content="Plava Krava | Webshop |  Knjige na engleskom jeziku" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Plava Krava | Webshop |  Knjige na engleskom jeziku" />
        <meta name="twitter:description" content="Istražite svijet književnih čuda u našoj online knjižari. Otkrijte bogatu kolekciju knjiga na engleskom jeziku, od klasičnih romana do suvremenih bestselera. Pronađite svoje sljedeće zanimljivo štivo danas!" />
        <meta name="twitter:image" content="{{ asset('media/plavakrava.jpg') }}" />

    @endpush

@else
    @section ( 'title', $page->title. ' - Plava Krava |  Knjige na engleskom jeziku' )
    @section ( 'description', $page->meta_description )
@endif

@section('content')

    @if (request()->routeIs(['index']))

      {{--@include('front.layouts.partials.hometemp') --}}

      <h1 style="visibility: hidden;height:1px "> Knjige na engleskom jeziku </h1>

        {!! $page->description !!}


    @else


        <nav class="mb-4" aria-label="breadcrumb">
            <ol class="breadcrumb flex-lg-nowrap">
                <li class="breadcrumb-item"><a class="text-nowrap" href="{{ route('index') }}"><i class="ci-home"></i>Naslovnica</a></li>
                <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $page->title }}</li>
            </ol>
        </nav>


        <section class="d-md-flex justify-content-between align-items-center mb-4 pb-2">
            <h1 class="h2 mb-3 mb-md-0 me-3">{{ $page->title }}</h1>

        </section>



            <div class="mt-5 mb-5 fs-md" style="max-width:1240px">
                {!! $page->description !!}
            </div>


    @endif

@endsection
