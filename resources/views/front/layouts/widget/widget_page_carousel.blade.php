<!-- {"title": "Page Carousel", "description": "Some description of a Page Carousel widget template."} -->
<section class="container-fluid py-3 " >
    <div class="d-flex flex-wrap justify-content-between align-items-center pt-1  pb-3 mb-3">
        <h2 class="h3 mb-0 pt-3 font-title me-3"><img src="{{ asset('img/logo-plava-krava-glava.svg') }}" style="max-height:35px" alt="{{ $data['title'] }}"/> {{ $data['title'] }}</h2>
    </div>

    @if ($data['tablename'] == 'category')
            <div class="tns-carousel">
                <div class="tns-carousel-inner" data-carousel-options='{"items": 1, "controls": true, "autoHeight": false, "responsive": {"0":{"items":1, "gutter": 20},"480":{"items":2, "gutter": 20},"800":{"items":3, "gutter": 20}, "1100":{"items":4, "gutter": 30}, "1500":{"items":5, "gutter": 30}}}'>
                @foreach ($data['items'] as $item)
                    <!-- Product-->
                        <div class="article mb-grid-gutter">
                            <a class="card border-0 shadow" href="{{ $item['group'] }}/{{ $item['slug'] }}">
                                <span class="blog-entry-meta-label fs-sm"><i class="ci-book text-primary me-0"></i></span>
                                <img class="card-img-top" loading="lazy" width="400" height="300" src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                                <div class="card-body py-3 text-center">
                                    <h3 class="h4 mt-1 font-title text-primary">{{ $item['title'] }}</h3>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

    @elseif ($data['tablename'] == 'publisher')
        <div class="row pb-2 pb-sm-0 pb-md-3">
            @foreach ($data['items'] as $item)
            <div class="col-md-3 col-sm-4 col-6"><a class="d-block bg-white shadow-sm rounded-3 py-3 py-sm-4 mb-grid-gutter" href="{{ $item['url'] }}"><img loading="lazy" class="d-block mx-auto" src="{{ $item['image'] }}" style="width: 150px;" alt="{{ $item['title'] }}"></a></div>
            @endforeach
        </div>

    @else


    @endif



</section>
