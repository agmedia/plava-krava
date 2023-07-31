@extends('front.layouts.app')

@if (isset($group) && $group)
    @if ($group && ! $cat && ! $subcat)
        @section ( 'title',  \Illuminate\Support\Str::ucfirst($group). ' - Plava Krava' )
    @endif
    @if ($cat && ! $subcat)
        @section ( 'title',  $cat->title . ' - Plava Krava' )
        @section ( 'description', $cat->meta_description )
    @elseif ($cat && $subcat)
        @section ( 'title', $subcat->title . ' - Plava Krava' )
        @section ( 'description', $cat->meta_description )
    @endif
@endif

@if (isset($author) && $author)
    @section ('title',  $seo['title'])
    @section ('description', $seo['description'])
@endif

@if (isset($publisher) && $publisher)
    @section ('title',  $seo['title'])
    @section ('description', $seo['description'])
@endif

@if (isset($meta_tags))
    @push('meta_tags')
        @foreach ($meta_tags as $tag)
            <meta name={{ $tag['name'] }} content={{ $tag['content'] }}>
        @endforeach
    @endpush
@endif


@section('content')

    @if (Route::currentRouteName() == 'pretrazi')
        <section class="d-md-flex justify-content-between align-items-center mb-4 pb-2">
            <h1 class="h2 mb-3 mb-md-0 me-3"><span class="small fw-light me-2">Rezultati za:</span> {{ request()->input('pojam') }}</h1>
        </section>
    @endif

    @if (isset($author) && $author)
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center ">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="{{ route('index') }}"><i class="ci-home"></i>Naslovnica</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route.author') }}">Autori</a></li>
                    @if ( ! $cat && ! $subcat)
                        <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $author->title }}</li>
                    @endif
                    @if ($cat && ! $subcat)
                        <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route.author', ['author' => $author]) }}">{{ $author->title }}</a></li>
                        <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $cat->title }}</li>
                    @elseif ($cat && $subcat)
                        <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route.author', ['author' => $author]) }}">{{ $author->title }}</a></li>
                        <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route.author', ['author' => $author, 'cat' => $cat]) }}">{{ $cat->title }}</a></li>
                        <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $subcat->title }}</li>
                    @endif
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">{{ $author->title }}</h1>
        </div>
    @endif

    @if (isset($publisher) && $publisher)
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="{{ route('index') }}"><i class="ci-home"></i>Naslovnica</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route.publisher') }}">Nakladnici</a></li>
                    @if ( ! $cat && ! $subcat)
                        <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $publisher->title }}</li>
                    @endif
                    @if ($cat && ! $subcat)
                        <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route.publisher', ['publisher' => $publisher]) }}">{{ $publisher->title }}</a></li>
                        <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $cat->title }}</li>
                    @elseif ($cat && $subcat)
                        <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route.publisher', ['publisher' => $publisher]) }}">{{ $publisher->title }}</a></li>
                        <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route.publisher', ['publisher' => $publisher, 'cat' => $cat]) }}">{{ $cat->title }}</a></li>
                        <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $subcat->title }}</li>
                    @endif
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">{{ $publisher->title }}</h1>
        </div>
    @endif

            @if (isset($group) && $group)

                <nav class="mb-4" aria-label="breadcrumb">
                        <ol class="breadcrumb flex-lg-nowrap">
                            <li class="breadcrumb-item"><a class="text-nowrap" href="{{ route('index') }}"><i class="ci-home"></i>Naslovnica</a></li>
                            @if ($group && ! $cat && ! $subcat)
                                <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ \Illuminate\Support\Str::ucfirst($group) }}</li>
                            @elseif ($group && $cat)
                                <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route', ['group' => $group]) }}">{{ \Illuminate\Support\Str::ucfirst($group) }}</a></li>
                            @endif
                            @if ($cat && ! $subcat)
                                <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $cat->title }}</li>
                            @elseif ($cat && $subcat)
                                <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route', ['group' => $group, 'cat' => $cat]) }}">{{ $cat->title }}</a></li>
                                <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $subcat->title }}</li>
                            @endif
                        </ol>
                </nav>


                <section class="d-md-flex justify-content-between align-items-center mb-4 pb-2">

                    @if ($group && ! $cat && ! $subcat)
                        <h1 class="h2 mb-3 mb-md-0 me-3">{{ \Illuminate\Support\Str::ucfirst($group) }}</h1>
                    @endif
                    @if ($cat && ! $subcat)
                            <h1 class="h2 mb-3 mb-md-0 me-3">{{ $cat->title }}</h1>
                    @elseif ($cat && $subcat)
                            <h1 class="h2 mb-3 mb-md-0 me-3">{{ $subcat->title }}</h1>
                    @endif


                </section>



            @endif






            <products-view ids="{{ isset($ids) ? $ids : null }}"
                           group="{{ isset($group) ? $group : null }}"
                           cat="{{ isset($cat) ? $cat['id'] : null }}"
                           subcat="{{ isset($subcat) ? $subcat['id'] : null }}"
                           author="{{ isset($author) ? $author['slug'] : null }}"
                           publisher="{{ isset($publisher) ? $publisher['slug'] : null }}">
            </products-view>



    @if (isset($author) && $author && ! empty($author->description))
        <div class="container pb-4 mb-2 mb-md-4" >
            {!! $author->description !!}
        </div>
    @endif

    <div class="container pb-4 mb-2 mb-md-4" >
        @if ($cat && ! $subcat)
            {!! $cat->description !!}
        @elseif ($subcat && ! $subcat)
            {!! $cat->description !!}
        @endif
    </div>

@endsection

@push('js_after')
    <script type="application/ld+json">
        {!! collect($crumbs)->toJson() !!}
    </script>
@endpush