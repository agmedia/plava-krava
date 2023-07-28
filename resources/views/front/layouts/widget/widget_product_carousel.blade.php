<!-- {"title": "Product Carousel", "description": "Some description of a Product Carousel."} -->
<section class="pt-2">

    <div class="d-flex flex-wrap justify-content-between align-items-center pt-1  pb-2 mb-2">
        <h2 class="h3 mb-0 pt-3 font-title me-3"><img src="{{ asset('img/logo-plava-krava-glava.svg') }}" style="max-height:35px" alt="{{ $data['title'] }}"/>{{ $data['title'] }}</h2>
            @if($data['subtitle'])  <p class="text-muted text-center mb-5">{{ $data['subtitle'] }}</p> @endif
        @if($data['url'] !='/')
            <p class=" text-center">  <a class="btn btn-primary btn-shadow " href="{{ url($data['url']) }}">Pogledajte ponudu <i class="ci-arrow-right "></i></a></p>
        @endif

    </div>
    <div class="tns-carousel tns-controls-static tns-controls-outside tns-nav-enabled pt-2">
        <div class="tns-carousel-inner" data-carousel-options="{&quot;items&quot;: 1, &quot;gutter&quot;: 16, &quot;controls&quot;: true, &quot;autoHeight&quot;: true, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1}, &quot;480&quot;:{&quot;items&quot;:2}, &quot;720&quot;:{&quot;items&quot;:3}, &quot;991&quot;:{&quot;items&quot;:2}, &quot;1140&quot;:{&quot;items&quot;:3}, &quot;1300&quot;:{&quot;items&quot;:4}, &quot;1500&quot;:{&quot;items&quot;:5}}}">
            @foreach ($data['items'] as $product)
                <!-- Product-->
                    <div>
                        @include('front.catalog.category.product')
                    </div>
                @endforeach
            </div>
        </div>


</section>
