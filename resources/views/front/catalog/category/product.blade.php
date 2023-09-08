<div class="article pb-1" >

    <div class="card product-card d-flex align-items-stretch  pb-1">
        @if ($product->main_price > $product->main_special)
            <span class="badge bg-primary badge-shadow">-{{ number_format(floatval(\App\Helpers\Helper::calculateDiscount($product->price, $product->special())), 0) }}%</span>
        @endif
        <a class="card-img-top d-block overflow-hidden" href="{{ url($product->url) }}">
            <img load="lazy" src="{{ str_replace('.webp','-thumb.webp', $product->image) }}" width="400" height="400" alt="{{ $product->name }}">
        </a>
        <div class="card-body pt-2" style="min-height: 120px;">

            <div class="d-flex flex-wrap justify-content-between align-items-start pb-1">
                <div class="text-muted fs-xs me-1">
                    <a class="product-meta fw-medium" href="{{ $product->author->url }}">{{ $product->author->title }}</a>
                </div>

            </div>

            <h3 class="product-title fs-sm text-truncate"><a href="{{ url($product->url) }}">{{ $product->name }}</a></h3>
            {!! $product->category_string !!}
            @if ($product->main_price > $product->main_special)
                <div class="product-price"><small><span class="text-muted">NC 30 dana: {{ $product->main_price_text }}  @if($product->secondary_price_text){{ $product->secondary_price_text }} @endif</span></small></div>
                <div class="product-price"><span class="text-dark fs-md">{{ $product->main_special_text }} @if($product->secondary_special_text) <small class="text-muted">{{ $product->secondary_special_text }}</small> @endif</span></div>
            @else
                <div class="product-price"><span class="text-dark fs-md">{{ $product->main_price_text }}  @if($product->secondary_price_text) <small class="fs-sm text-muted">{{ $product->secondary_price_text }} </small>@endif</span></div>
            @endif
        </div>
        <div class="product-floating-btn">
            <add-to-cart-btn-simple id="{{ $product->id }}" available="{{ $product->quantity }}"></add-to-cart-btn-simple>
        </div>
    </div>
</div>

