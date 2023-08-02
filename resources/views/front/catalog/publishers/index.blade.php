@extends('front.layouts.app')

@if (isset($meta_tags))
    @push('meta_tags')
        @foreach ($meta_tags as $tag)
            <meta name={{ $tag['name'] }} content={{ $tag['content'] }}>
        @endforeach
    @endpush
@endif

@section('content')




    <section class="d-md-flex justify-content-between align-items-center mb-2 pb-2">
        <h1 class="h2 mb-3 mb-md-0 me-3">Lista izdavača</h1>
        <p class="pb-0 text-dark fs-sm text-center mb-0">Pretraživanje prema početnom slovu imena nakladnika</p>
    </section>

    <!-- Topics grid-->
    <section class=" py-3 mb-5">
        <div class="row align-items-center py-md-3">
            <div class="col-lg-12   py-2 text-center">
                <div class="scrolling-wrapper">

                @foreach ($letters as $item)
                        <a href="{{ route('catalog.route.publisher', ['publisher' => null, 'letter' => $item['value']]) }}"
                           class="btn btn-outline-primary btn-sm  mb-2 @if( ! $item['active']) disabled @endif @if($item['value'] == $letter) bg-primary  @endif">
                            <h3 class="h6  @if($item['value'] == $letter) text-white @else  @endif  py-0 mt-1 mb-0 px-1">{{ $item['value'] }}</h3></a>
                @endforeach
                </div>
            </div>
        </div>

        <div class="row py-md-3">
            <div class="col-lg-12 text-center mb-5">
                <h2>{{ $letter ?: 'Svi nakladnici' }}</h2>
                <hr>
            </div>

            @foreach ($publishers as $publisher)
                <div class="col-sm-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-0"> <a href="{{ url($publisher['url']) }}" class="text-dark">{{ $publisher['title'] }} <span class="badge rounded-pill bg-secondary float-end">{{ $publisher['products_count'] }}</span></a></h6>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="row py-md-3">
            <div class="col-lg-12">
                {{ $publishers->links() }}
            </div>
        </div>

    </section>

@endsection

@push('js_after')
    <style>
        @media only screen and (max-width: 1040px) {
            .scrolling-wrapper {
                overflow-x: scroll;
                overflow-y: hidden;
                white-space: nowrap;
                padding-bottom: 15px;
            }
        }
    </style>
@endpush
