<!-- {"title": "Simple Card Widget", "description": "Some description of a Simple Card Widget."} -->
<section class=" py-3 " >
    <div class="d-flex flex-wrap justify-content-between align-items-center pt-1  pb-3 mb-3">
        <h2 class="h3 mb-0 pt-3 font-title me-3"><img src="{{ asset('img/logo-plava-krava-glava.svg') }}" style="max-height:35px" alt="Akcije i popusti"/> DODATNO U PONUDI </h2>
    </div>

    <div class="row  mt-2 mt-lg-3 ">
        @foreach ($data as $widget)
            <div class="col-lg-12 col-xl-{{ $widget['width'] }} mb-grid-gutter">
                <div class="d-block d-sm-flex justify-content-between align-items-center bg-faded-info rounded-3">
                        <div class="pt-5 py-sm-4 px-4 ps-md-4 pe-md-0 text-center text-sm-start">
                                <h2 class="font-title">{{ $widget['title'] }}</h2>
                                <p class="text-muted pb-2">{{ $widget['subtitle'] }}</p><a class="btn btn-primary" href="{{ url($widget['url']) }}">Pogledajte ponudu <i class="ci-arrow-right ms-2 me-n1"></i></a>
                        </div>
                       <img class="d-block mx-auto mx-sm-0 rounded-end" src="{{ $widget['image'] }}" width="220" alt="{{ $widget['title'] }}">

                </div>
            </div>
        @endforeach
    </div>
</section>