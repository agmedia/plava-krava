<div class="wave-container"></div>
<footer class="footer bg-light pt-5">

    <div class="container-fluid pt-lg-1 px-lg-5 pt-2 pb-1">
        <div class="row pt-lg-2 text-left px-3">
            <div class="col-lg-3 col-sm-6 col-12 mb-grid-gutter">

                <div class="d-inline-flex align-items-center text-start"><i class="ci-truck text-primary" style="font-size: 3rem;"></i> <div class="ps-3"><p class="text-dark fw-bold fs-base mb-1">Brza dostava</p> <p class="text-dark fs-ms opacity-70 mb-0">Unutar 3 radna dana</p></div></div></div>

            <div class="col-lg-3 col-sm-6 col-12 mb-grid-gutter"><div class="d-inline-flex align-items-center text-start"><i class="ci-security-check text-primary" style="font-size: 3rem;"></i> <div class="ps-3"><p class="text-dark fw-bold fs-base mb-1">Sigurna kupovina</p> <p class="text-dark fs-ms opacity-70 mb-0">SSL certifitikat i CorvusPay</p></div></div></div>

            <div class="col-lg-3 col-sm-6 col-12 mb-grid-gutter"><div class="d-inline-flex align-items-center text-start"><i class="ci-bag text-primary" style="font-size: 3rem;"></i> <div class="ps-3"><p class="text-dark fw-bold fs-base mb-1">Besplatna dostava</p> <p class="text-dark fs-ms opacity-70 mb-0">Za narudžbe iznad 30 €</p></div></div></div>

            <div class="col-lg-3 col-sm-6 col-12 mb-grid-gutter"><div class="d-inline-flex align-items-center text-start"><i class="ci-locked text-primary" style="font-size: 3rem;"></i> <div class="ps-3"><p class="text-dark fw-bold fs-base mb-1">Zaštita kupca</p> <p class="text-dark fs-ms opacity-70 mb-0">Od narudžbe pa sve do dostave</p></div></div></div>

        </div>
    </div>
    <div class="px-lg-5 pt-2 pb-4">
        <div class="mx-auto px-3" >
            <div class="row py-lg-4">
                <div class="col-lg-4 mb-lg-0 mb-4">
                    <div class="widget pb-3 mb-lg-4">
                        <h3 class="widget-title text-dark pb-1">Ne propusti akciju</h3>
                        <form class="subscription-form validate" action="#" method="post" name="mc-embedded-subscribe-form" target="_blank" novalidate>
                            <div class="input-group flex-nowrap"><i class="ci-mail position-absolute top-50 translate-middle-y text-muted fs-base ms-3"></i>
                                <input class="form-control rounded-start" type="email" name="EMAIL" placeholder="Vaša email adresa" required>
                                <button class="btn btn-primary" type="submit" name="subscribe">Prijavi se*</button>
                            </div>
                            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                            <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                <input class="subscription-form-antispam" type="text" name="b_c7103e2c981361a6639545bd5_29ca296126" tabindex="-1">
                            </div>
                            <div class="form-text mt-3 fs-md text-dark opacity-50">*Prijavi se na naš Newsletter i budi u toku sa najnovijim akcijama i novostima!</div>
                            <div class="subscription-status"></div>
                        </form>
                    </div>
                    <div><a class="btn-social bs-dark bs-twitter me-2 mb-2" href="#"><i class="ci-twitter"></i></a><a class="btn-social bs-dark bs-facebook me-2 mb-2" href="#"><i class="ci-facebook"></i></a><a class="btn-social bs-dark bs-instagram me-2 mb-2" href="#"><i class="ci-instagram"></i></a><a class="btn-social bs-dark bs-youtube me-2 mb-2" href="#"><i class="ci-youtube"></i></a></div>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <div class="d-flex flex-sm-row flex-column justify-content-sm-between mt-n4 mx-lg-n3">

                        <div class="widget widget-links widget-dark mt-4 px-lg-3 px-sm-n2">
                            <h3 class="widget-title text-dark">Uvjeti kupnje</h3>
                            <ul class="widget-list">
                                @foreach ($uvjeti_kupnje->sortBy('title') as $page)
                                    <li class="widget-list-item"><a class="widget-list-link" href="{{ route('catalog.route.page', ['page' => $page]) }}">{{ $page->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="widget widget-links widget-dark mt-4 px-lg-3 px-sm-n2">
                            <h3 class="widget-title text-dark">Načini plaćanja</h3>
                            <ul class="widget-list">
                                <li class="widget-list-item"><a href="#" class="widget-list-link"> kreditnom karticom jednokratno ili na rate</a></li>
                                <li class="widget-list-item"><a href="#" class="widget-list-link"> virmanom / općom uplatnicom / internet bankarstvom</a></li> <li class="widget-list-item"><a href="#" class="widget-list-link">gotovinom prilikom pouzeća</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wave-container-blue"></div>
    <div class="bg-darker px-lg-5 py-3">
        <div class="d-sm-flex justify-content-between align-items-center mx-auto px-3" >
            <div class="fs-sm text-light opacity-50 text-center text-sm-start py-3">Plava krava d.o.o. © Sva prava pridržana. Web by <a class="text-light" href="https://www.agmedia.hr" target="_blank" rel="noopener">AG media</a></div>
            <div class="widget widget-links widget-light pb-4 text-center text-md-end"><img src="https://zuzi.selectpo.lin48.host25.com/media/cards/visa.svg" alt="Visa" class="d-inline-block" style="width: 55px; margin-right: 3px;" width="55" height="35"> <img src="https://zuzi.selectpo.lin48.host25.com/media/cards/maestro.svg" alt="Maestro" class="d-inline-block" style="width: 55px; margin-right: 3px;" width="55" height="35"> <img src="https://zuzi.selectpo.lin48.host25.com/media/cards/mastercard.svg" alt="MasterCard" class="d-inline-block" style="width: 55px; margin-right: 3px;" width="55" height="35"> <img src="https://zuzi.selectpo.lin48.host25.com/media/cards/diners.svg" alt="Diners" class="d-inline-block" style="width: 55px; margin-right: 3px;" width="55" height="35"></div>
        </div>
    </div>
</footer>
