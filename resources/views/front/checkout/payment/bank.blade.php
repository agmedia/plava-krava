<form name="pay" class="w-100" action="{{ route('checkout') }}" method="GET">
    <input type="hidden" name="provjera" value="{{ $data['order_id'] }}">

    <div class="d-flex mt-3">
    <div class="w-50 pe-3">
        <a class="btn btn-secondary d-block w-100" href="{{ route('naplata') }}"><i class="ci-arrow-left  me-1"></i><span class="d-none d-sm-inline">Povratak na plaćanje</span><span class="d-inline d-sm-none">Povratak</span></a>
    </div>
    <div class="w-50 ps-2">
        <button style="z-index:10000" class="btn btn-primary w-100" type="submit"><span>Završi kupnju</span><i class="ci-arrow-right  ms-1"></i></button>
    </div>

    </div>

</form>
