@extends('front.layouts.app')
@section ('title', $seo['title'])
@section ('description', $seo['description'])
@push('meta_tags')

    <link rel="canonical" href="{{ env('APP_URL')}}/{{ $prod->url }}" />
    <meta property="og:locale" content="hr_HR" />
    <meta property="og:type" content="product" />
    <meta property="og:title" content="{{ $seo['title'] }}" />
    <meta property="og:description" content="{{ $seo['description']  }}" />
    <meta property="og:url" content="{{ env('APP_URL')}}/{{ $prod->url }}"  />
    <meta property="og:site_name" content="ZuZi Shop" />
    <meta property="og:updated_time" content="{{ $prod->updated_at  }}" />
    <meta property="og:image" content="{{ asset($prod->image) }}" />
    <meta property="og:image:secure_url" content="{{ asset($prod->image) }}" />
    <meta property="og:image:width" content="640" />
    <meta property="og:image:height" content="480" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:alt" content="{{ $prod->image_alt }}" />
    <meta property="product:price:amount" content="{{ number_format($prod->price, 2) }}" />
    <meta property="product:price:currency" content="EUR" />
    <meta property="product:availability" content="instock" />
    <meta property="product:retailer_item_id" content="{{ $prod->sku }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $seo['title'] }}" />
    <meta name="twitter:description" content="{{ $seo['description'] }}" />
    <meta name="twitter:image" content="{{ asset($prod->image) }}" />

@endpush

@if (isset($gdl))
    @section('google_data_layer')
        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({ ecommerce: null });
            window.dataLayer.push({
                'event': 'view_item',
                'ecommerce': {
                    'items': [<?php echo json_encode($gdl); ?>]
                } });
        </script>
    @endsection
@endif

@section('content')

    @if($prod->totalreviews() > 1)
        @php
            $recenzija = 'Recenzije';
        @endphp
    @else
        @php
            $recenzija = 'Recenzija';
        @endphp
    @endif


    <!-- Page title + breadcrumb-->
    <nav class="mb-4" aria-label="breadcrumb">
        <ol class="breadcrumb flex-lg-nowrap">
            <li class="breadcrumb-item"><a class="text-nowrap" href="{{ route('index') }}"><i class="ci-home"></i>Naslovnica</a></li>
            @if ($group)
                @if ($group && ! $cat && ! $subcat)
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ \Illuminate\Support\Str::ucfirst($group) }}</li>
                @elseif ($group && $cat)
                    <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route', ['group' => $group]) }}">{{ \Illuminate\Support\Str::ucfirst($group) }}</a></li>
                @endif

                @if ($cat && ! $subcat)
                    @if ($prod)
                        <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route', ['group' => $group, 'cat' => $cat]) }}">{{ $cat->title }}</a></li>
                    @else
                        <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $cat->title }}</li>
                    @endif
                @elseif ($cat && $subcat)
                    <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route', ['group' => $group, 'cat' => $cat]) }}">{{ $cat->title }}</a></li>
                    @if ($prod)
                        @if ($cat && ! $subcat)
                            <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route', ['group' => $group, 'cat' => $cat]) }}">{{ \Illuminate\Support\Str::limit($prod->name, 50) }}</a></li>
                        @else
                            <li class="breadcrumb-item text-nowrap active" aria-current="page"><a class="text-nowrap" href="{{ route('catalog.route', ['group' => $group, 'cat' => $cat, 'subcat' => $subcat]) }}">{{ $subcat->title }}</a></li>
                        @endif
                    @endif
                @endif
            @endif

        </ol>
    </nav>
    <!-- Content-->
    <!-- Product Gallery + description-->
    <section class="row g-0 mx-n2 ">
        <div class="col-xl-6 px-2 mb-3">
            <div class="h-100 bg-light rounded-3 p-4">
                <div class="product-gallery">
                    <div class="product-gallery-preview order-sm-2">
                            @if ( ! empty($prod->image))
                                <div class="product-gallery-preview-item active" id="first"><img  src="{{ asset($prod->image) }}"  alt="{{ $prod->name }}" height="800"></div>
                            @endif
                            @if ($prod->images->count())
                                @foreach ($prod->images as $key => $image)
                                    <div class="product-gallery-preview-item" id="key{{ $key + 1 }}"><img  src="{{ asset($image->image) }}" alt="{{ $image->alt }}"  height="800"></div>
                                @endforeach
                            @endif
                    </div>
                    <div class="product-gallery-thumblist order-sm-1">
                        @if ($prod->images->count())
                            @if ( ! empty($prod->thumb))
                                <a class="product-gallery-thumblist-item active" href="#first"><img src="{{ asset($prod->thumb) }}" alt="{{ $prod->name }}"></a>
                            @endif
                            @foreach ($prod->images as $key => $image)
                                <a class="product-gallery-thumblist-item" href="#key{{ $key + 1 }}"><img src="{{ url('cache/thumb?size=100x100&src=' . $image->thumb) }}" width="100" height="100" alt="{{ $image->alt }}"></a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 px-2 mb-3">
            <div class="h-100 bg-light rounded-3 py-5 px-4 px-sm-5">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <a href="#reviews" data-scroll>
                        <div class="star-rating">

                            @for ($i = 0; $i < 5; $i++)
                                @if (floor($prod->avgreviews()) - $i >= 1)
                                    {{--Full Start--}}
                                    <i class="star-rating-icon ci-star-filled active"></i>
                                @elseif ($prod->avgreviews() - $i > 0)
                                    {{--Half Start--}}
                                    <i class="star-rating-icon ci-star"></i>
                                @else
                                    {{--Empty Start--}}
                                    <i class="star-rating-icon ci-star"></i>
                                @endif
                            @endfor


                        </div>
                        <span class="d-inline-block fs-sm text-body align-middle mt-1 ms-1">{{ $prod->totalreviews() }} {{ $recenzija }}</span>
                    </a>
                </div>

                <h1 class="h2">{{ $prod->name }}</h1>
                    <div class="mb-1">
                        @if ($prod->main_price > $prod->main_special)
                            <span class="h3 fw-normal text-accent me-1">{{ $prod->main_special_text }}</span>
                            <span class="text-muted fs-lg me-3">*{{ $prod->main_price_text }}</span>

                        @else
                            <span class="h3 fw-normal text-accent me-1">{{ $prod->main_price_text }}</span>
                        @endif

                    </div>

                @if($prod->secondary_price_text)
                    <div class="mb-1 mt-1 text-center text-lg-start">
                        @if ($prod->main_price > $prod->main_special)
                            <span class=" fs-sm text-muted me-1"> {{ $prod->secondary_special_text }}</span>
                            <span class="text-muted fs-sm me-3">*{{ $prod->secondary_price_text }}</span>
                        @else
                            <span class="fs-sm text-muted  me-1">{{ $prod->secondary_price_text }}</span>
                        @endif
                    </div>
                @endif
                @if ($prod->main_price > $prod->main_special)

                    <div class="mb-3 mt-1 text-center text-lg-start">
                        <span class=" fs-sm text-muted me-1"> *Najniža cijena u zadnjih 30 dana.</span>
                    </div>

                @endif


                <add-to-cart-btn id="{{ $prod->id }}" available="{{ $prod->quantity }}"></add-to-cart-btn>

                <!-- Product panels-->
                <div class="accordion mb-4" id="productPanels">
                    <div class="accordion-item">
                        <h3 class="accordion-header"><a class="accordion-button" href="#productInfo" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="productInfo"><i class="ci-announcement text-muted fs-lg align-middle mt-n1 me-2"></i>Osnovne informacije</a></h3>
                        <div class="accordion-collapse collapse show" id="productInfo" data-bs-parent="#productPanels">
                            <div class="accordion-body">

                                <ul class="fs-sm ps-4 mb-0">
                                    @if ($prod->author)
                                        <li><strong>Autor:</strong> <a href="{{ route('catalog.route.author', ['author' => $prod->author]) }}">{{ $prod->author->title }} </a></li>
                                    @endif
                                    @if ($prod->publisher)
                                        <li><strong>Nakladnik:</strong> <a href="{{ route('catalog.route.publisher', ['publisher' => $prod->publisher]) }}">{{ $prod->publisher->title }}</a> </li>
                                    @endif
                                    @if ($prod->isbn)
                                    <li><strong>ISBN:</strong> {{ $prod->isbn }} </li>
                                    @endif
                                        @if ($prod->quantity)
                                            @if ($prod->decrease)
                                                <li><strong>Dostupnost:</strong> {{ $prod->quantity }} </li>
                                            @else
                                                <li><strong>Dostupnost:</strong> Dostupno</span></li>
                                            @endif
                                        @else
                                            <li><strong>Dostupnost:</strong> Rasprodano</li>
                                        @endif


                                    <li><strong>Dostupnost:</strong> Dostupno </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header"><a class="accordion-button collapsed" href="#shippingOptions" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="shippingOptions"><i class="ci-delivery text-muted lead align-middle mt-n1 me-2"></i>Opcije dostave</a></h3>
                        <div class="accordion-collapse collapse" id="shippingOptions" data-bs-parent="#productPanels">
                            <div class="accordion-body fs-sm">
                                <div class="d-flex justify-content-between ">
                                    <div>
                                        <div class="fw-semibold text-dark">GLS dostava</div>
                                        <div class="fs-sm text-muted">2 - 4 dana</div>

                                        <div class="fs-sm text-muted"> Besplatna dostava za narudžbe iznad 30€</div>
                                    </div>
                                    <div>3.31€ <small>24.94 kn</small></div>
                                </div>

                            </div>
                            <small class="mt-2"></small>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header"><a class="accordion-button collapsed" href="#localStore" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="localStore"><i class="ci-card text-muted fs-lg align-middle mt-n1 me-2"></i>Načini plaćanja</a></h3>
                        <div class="accordion-collapse collapse" id="localStore" data-bs-parent="#productPanels">
                            <div class="accordion-body fs-sm">
                                <div class="d-flex justify-content-between border-bottom pb-2">
                                    <div>
                                        <div class="fw-semibold text-dark">CorvusPay</div>
                                        <div class="fs-sm text-muted">kreditnom karticom jednokratno ili na rate</div>
                                    </div>

                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <div>
                                        <div class="fw-semibold text-dark">Bankovna transakcija</div>
                                        <div class="fs-sm text-muted">virmanom / općom uplatnicom / internet bankarstvom</div>
                                    </div>

                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <div>
                                        <div class="fw-semibold text-dark">Pouzećem</div>
                                        <div class="fs-sm text-muted">gotovinom prilikom preuzimanja</div>
                                    </div>

                                </div>


                            </div>


                        </div>
                    </div>
                </div>
                <!-- Sharing-->
                <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->
            </div>
        </div>
    </section>
    <!-- Related products-->

    <section class="mx-n2 pb-4 px-2 mb-xl-3">
        <div class="bg-light px-2 mb-3 shadow-lg rounded-3">
            <!-- Tabs-->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link py-4 px-sm-4 active" href="#specs" data-bs-toggle="tab" role="tab"><span>Opis</span> </a></li>
                <li class="nav-item"><a class="nav-link py-4 px-sm-4" href="#reviews" data-bs-toggle="tab" role="tab">Recenzije <span class="fs-sm opacity-60">({{ $prod->totalreviews() }})</span></a></li>
            </ul>
            <div class="px-4 pt-lg-3 pb-3 mb-5">
                <div class="tab-content px-lg-3">
                    <!-- Tech specs tab-->
                    <div class="tab-pane fade show active" id="specs" role="tabpanel">
                        <!-- Specs table-->
                        <div class="row pt-2">
                            <div class="col-lg-7 col-sm-7">
                                <h3 class="h6">Sažetak</h3>
                                <div class=" fs-md pb-2">
                                    {!! $prod->description !!}
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-5 ">
                                <h3 class="h6">Dodatne informacije</h3>
                                <ul class="list-unstyled fs-sm pb-2">

                                    @if ($prod->author)
                                        <li class="d-flex justify-content-between pb-2 border-bottom"><span class="text-muted">Autor:</span><span>{{ $prod->author->title }}</span></li>
                                    @endif
                                    @if ($prod->publisher)
                                        <li class="d-flex justify-content-between pb-2 border-bottom"><span class="text-muted">Nakladnik:</span><span>{{ $prod->publisher->title }}</span></li>
                                    @endif


                                    <li class="d-flex justify-content-between pb-2 border-bottom"><span class="text-muted">Uvez:</span><span>{{ $prod->binding ?: '...' }}</span></li>
                                    <li class="d-flex justify-content-between pb-2 border-bottom"><span class="text-muted">Jezik:</span><span>{{ $prod->origin ?: '...' }}</span></li>
                                    <li class="d-flex justify-content-between pb-2 border-bottom"><span class="text-muted">Godina izdanja:</span><span>{{ $prod->year ?: '...' }}</span></li>
                                    <li class="d-flex justify-content-between pb-2 border-bottom"><span class="text-muted">Broj stranica:</span><span>{{ $prod->pages ?: '...' }}</span></li>
                                    <li class="d-flex justify-content-between pb-2 border-bottom"><span class="text-muted">Dimenzije:</span><span>{{ $prod->dimensions.' cm' ?: '...' }}</span></li>
                                    @if ($prod->isbn)
                                      <li class="d-flex justify-content-between pb-2 border-bottom"><span class="text-muted">ISBN:</span><span>{{ $prod->isbn }}</span></li>
                                    @endif
                                </ul>

                            </div>
                        </div>
                    </div>
                    <!-- Reviews tab-->
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <!-- Reviews-->
                        <div class="row pt-2 pb-3">
                            <div class="col-lg-4 col-md-5">
                                <h2 class="h3 mb-4"> {{ $prod->totalreviews() }} {{ $recenzija }}  </h2>
                                <div class="star-rating me-2">



                                    @for ($i = 0; $i < 5; $i++)
                                        @if (floor($prod->avgreviews()) - $i >= 1)
                                            {{--Full Start--}}
                                            <i class="ci-star-filled fs-sm text-accent me-1"></i>
                                        @elseif ($prod->avgreviews() - $i > 0)
                                            {{--Half Start--}}
                                            <i class="ci-star fs-sm text-muted me-1"></i>
                                        @else
                                            {{--Empty Start--}}
                                            <i class="ci-star fs-sm text-muted me-1"></i>
                                        @endif
                                    @endfor




                                </div><span class="d-inline-block align-middle">{{ $prod->avgreviews() }} Ukupno</span>

                            </div>
                            <div class="col-lg-8 col-md-7">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">5</span><i class="ci-star-filled fs-xs ms-1"></i></div>
                                    <div class="w-100">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $prod->percentreviews( $prod->reviews()->where('stars', 5)->count())}}%;" aria-valuenow="{{ $prod->percentreviews( $prod->reviews()->where('stars', 5)->count())}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div><span class="text-muted ms-3">{{ $prod->reviews()->where('stars', 5)->count() }}   </span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">4</span><i class="ci-star-filled fs-xs ms-1"></i></div>
                                    <div class="w-100">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar" role="progressbar"  style="width: {{ $prod->percentreviews( $prod->reviews()->where('stars', 4)->count())}}%;" aria-valuenow="{{ $prod->percentreviews( $prod->reviews()->where('stars', 4)->count())}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div><span class="text-muted ms-3">{{ $prod->reviews()->where('stars', 4)->count() }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">3</span><i class="ci-star-filled fs-xs ms-1"></i></div>
                                    <div class="w-100">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar" role="progressbar"  style="width: {{ $prod->percentreviews( $prod->reviews()->where('stars', 3)->count())}}%;" aria-valuenow="{{ $prod->percentreviews( $prod->reviews()->where('stars', 3)->count())}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div><span class="text-muted ms-3">{{ $prod->reviews()->where('stars', 3)->count() }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">2</span><i class="ci-star-filled fs-xs ms-1"></i></div>
                                    <div class="w-100">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar" role="progressbar"  style="width: {{ $prod->percentreviews( $prod->reviews()->where('stars', 2)->count())}}%;" aria-valuenow="{{ $prod->percentreviews( $prod->reviews()->where('stars', 2)->count())}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div><span class="text-muted ms-3">{{ $prod->reviews()->where('stars', 2)->count() }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">1</span><i class="ci-star-filled fs-xs ms-1"></i></div>
                                    <div class="w-100">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $prod->percentreviews( $prod->reviews()->where('stars', 1)->count())}}%;" aria-valuenow="{{ $prod->percentreviews( $prod->reviews()->where('stars', 1)->count())}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div><span class="text-muted ms-3">{{ $prod->reviews()->where('stars', 1)->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-4 mb-3">
                        <div class="row py-4">
                            <!-- Reviews list-->
                            <div class="col-md-7">



                                @if($prod->totalreviews())

                                  @foreach($prod->reviews()->get() as $review)
                                    <!-- Review-->
                                    <div class="product-review pb-4 mb-4 border-bottom">
                                        <div class="d-flex mb-3">
                                            <div class="d-flex align-items-center me-4 pe-2">
                                                <div>
                                                    <h6 class="fs-sm mb-0">{{ $review->fname }} {{ $review->lname }}</h6><span class="fs-ms text-muted">{{ \Carbon\Carbon::make($review->created_at)->locale('hr')->format('d.m.Y.') }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="star-rating">


                                                @for ($i = 0; $i < 5; $i++)
                                                    @if (floor($review->stars) - $i >= 1)
                                                        {{--Full Start--}}
                                                    <i class="star-rating-icon ci-star-filled active"></i>
                                                    @elseif ($review->stars - $i > 0)
                                                        {{--Half Start--}}
                                                            <i class="star-rating-icon ci-star"></i>
                                                    @else
                                                        {{--Empty Start--}}
                                                            <i class="star-rating-icon ci-star"></i>
                                                    @endif
                                                @endfor
                                                </div>

                                            </div>
                                        </div>
                                        <p class="fs-md mb-2">{{ strip_tags($review->message) }}</p>


                                    </div>

                                   @endforeach
                                @else
                                  <p>Trenutno nema ocjena i komentara za ovaj artikl!</p>
                                @endif

                            </div>
                            <!-- Leave review form-->
                            <div class="col-md-5 mt-2 pt-4 mt-md-0 pt-md-0">
                                <div class="bg-secondary py-grid-gutter px-grid-gutter rounded-3">
                                    <h3 class="h4 pb-2">Napišite recenziju</h3>
                                    <form class="needs-validation" method="post" novalidate>
                                        <div class="mb-3">
                                            <label class="form-label" for="review-name">Vaše Ime<span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" required id="review-name">
                                            <div class="invalid-feedback">Molimo unesite vaše ime!</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="review-email">Vaš email<span class="text-danger">*</span></label>
                                            <input class="form-control" type="email" required id="review-email">
                                            <div class="invalid-feedback">Molimo upišite ispravnu email adresu!</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="review-rating">Ocjena<span class="text-danger">*</span></label>
                                            <select class="form-select" required id="review-rating">
                                                <option value="">Odaberite ocjenu</option>
                                                <option value="5">5 stars</option>
                                                <option value="4">4 stars</option>
                                                <option value="3">3 stars</option>
                                                <option value="2">2 stars</option>
                                                <option value="1">1 star</option>
                                            </select>
                                            <div class="invalid-feedback">Molimo odaberite ocjenu!</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="review-text">Rocenzija<span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="6" required id="review-text"></textarea>
                                            <div class="invalid-feedback">Molimo napišite recenziju!</div>
                                        </div>

                                        <button class="btn btn-primary btn-shadow d-block w-100" type="submit">Pošalji</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product description-->
    <section class="pb-5 mb-2 mb-xl-4">
        <div class=" flex-wrap justify-content-between align-items-center  text-center">
            <h2 class="h3 mb-3 pt-1 font-title me-3 text-center"> POVEZANI PROIZVODI</h2>

        </div>
        <div class="tns-carousel tns-controls-static tns-controls-outside tns-nav-enabled pt-2">
            <div class="tns-carousel-inner" data-carousel-options="{&quot;items&quot;: 2, &quot;gutter&quot;: 16, &quot;controls&quot;: true, &quot;autoHeight&quot;: true, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:2}, &quot;480&quot;:{&quot;items&quot;:2}, &quot;720&quot;:{&quot;items&quot;:3}, &quot;991&quot;:{&quot;items&quot;:2}, &quot;1140&quot;:{&quot;items&quot;:3}, &quot;1300&quot;:{&quot;items&quot;:4}, &quot;1500&quot;:{&quot;items&quot;:5}}}">
                @foreach ($cat->products()->get()->take(10) as $cat_product)
                    @if ($cat_product->id  != $prod->id)
                        <div>
                            @include('front.catalog.category.product', ['product' => $cat_product])
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>


@endsection

@push('js_after')
    <script type="application/ld+json">
        {!! collect($crumbs)->toJson() !!}
    </script>
    <script type="application/ld+json">
        {!! collect($bookscheme)->toJson() !!}
    </script>
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=6134a372eae16400120a5035&product=sop' async='async'></script>
@endpush
