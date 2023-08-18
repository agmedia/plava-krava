@extends('back.layouts.backend')

@push('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endpush

@section('content')

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">API Postavke</h1>
            </div>
        </div>
    </div>

    <div class="content content-full">
        @include('back.layouts.partials.session')

        <div class="row">
            <div class="col-12">
                <div class="block block-rounded">
                    <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#btabs-static-home">Api IN (Download)</a>
                        </li>
                        <li class="nav-item ml-auto">
                            <a class="nav-link" href="#btabs-static-settings">
                                <i class="si si-settings"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="block-content tab-content">
                        <div class="tab-pane active" id="btabs-static-home" role="tabpanel">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div id="accordion2" role="tablist" aria-multiselectable="true">
                                        <div class="block block-rounded mb-1">
                                            <div class="block-header block-header-default" role="tab" id="akademska_knjiga_tab">
                                                <a class="font-w600" data-toggle="collapse" data-parent="#accordion2" href="#akademska_knjiga" aria-expanded="true" aria-controls="akademska_knjiga">Akademska Knjiga .mk</a>
                                            </div>
                                            <div id="akademska_knjiga" class="collapse" role="tabpanel" aria-labelledby="akademska_knjiga_tab">
                                                <div class="block-content">
                                                    <div class="row items-push">
                                                        <div class="col-md-8">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-vcenter mb-0">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td style="width: 30%;">
                                                                            <button type="button" class="btn btn-sm btn-alt-info" onclick="event.preventDefault(); importTarget('akademska-knjiga-mk', 'products', '{{ route('api.api.import') }}');">Import Proizvoda</button>
                                                                        </td>
                                                                        <td>
                                                                            <code>Import novih proizvoda...</code>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <button type="button" class="btn btn-sm btn-alt-info" onclick="event.preventDefault(); importTarget('akademska-knjiga-mk', 'prices-quantities', '{{ route('api.api.update') }}');">Update Cijena i Količina</button>
                                                                        </td>
                                                                        <td>
                                                                            <code>Update cijena i količina...</code>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="block block-rounded block-bordered" id="my-block">
                                                                <div class="block-header block-header-default">
                                                                    <h3 class="block-title">Rezultat Api Poziva</h3>
                                                                </div>
                                                                <div class="block-content">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="btabs-static-settings" role="tabpanel">
                            <h4 class="font-w400">Settings Content</h4>
                            <p>...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('modals')

@endpush

@push('js_after')
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(() => {

        });

        function importTarget(target, method, route) {
            let item = {
                target: target,
                method: method
            };

            console.log(target, method, route)

            axios.post(route, {data: item})
            .then(response => {
                console.log(response.data)
                if (response.data.success) {
                    return successToast.fire(response.data.success);
                } else {
                    return errorToast.fire(response.data.message);
                }
            });
        }


        function updateTarget(target, method) {}

    </script>
@endpush