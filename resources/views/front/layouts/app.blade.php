<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <title> @yield('title') </title>
    <!-- SEO Meta Tags-->
    <meta name="description" content="@yield('description')">
    <meta name="author" content="Plava Krava">
    @stack('meta_tags')
    <!-- Viewport-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Favicon and Touch Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ config('settings.images_domain') . 'favicon-32x32.png' }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ config('settings.images_domain') . 'favicon-32x32.png' }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ config('settings.images_domain') . 'favicon-16x16.png' }}">

    <link rel="mask-icon" href="{{ config('settings.images_domain') . 'safari-pinned-tab.svg' }}" color="#18326d">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" media="screen" href="{{ asset('vendor/simplebar/dist/simplebar.min.css') }}"/>
    <link rel="stylesheet" media="screen" href="{{ asset(config('settings.images_domain') . 'css/theme.css?v=1.7') }}">

    @if (config('app.env') == 'production')
        @yield('google_data_layer')
        <!-- Google Tag Manager -->
          <!--  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                                                                  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','GTM-xxxxxxx');
            </script> -->

        <!-- Global site tag (gtag.js) - Google Analytics -->
     <!--   <script async src="https://www.googletagmanager.com/gtag/js?id=xxxxxxx"></script>-->
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', ' G-Q2GNBMK18T');
        </script>
    @endif

    @stack('css_after')

    @if (config('app.env') == 'production')
        <!-- Facebook Pixel Code -->
    <!--    <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', 'xxxxxxxxxxx');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id=xxxxxx&ev=PageView&noscript=1"
            /></noscript> -->
    @endif


    <style>
        [v-cloak] { display:none !important; }
    </style>
</head>
<!-- Body-->
<body class="bg-secondary">
<!-- Sign in / sign up modal-->
<div class="modal fade" id="signin-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link fw-medium active" href="#signin-tab" data-bs-toggle="tab" role="tab" aria-selected="true"><i class="ci-unlocked me-2 mt-n1"></i>Prijava</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#signup-tab" data-bs-toggle="tab" role="tab" aria-selected="false"><i class="ci-user me-2 mt-n1"></i>Registracija</a></li>
                </ul>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body tab-content py-4">
                <form method="POST" class="needs-validation tab-pane fade show active" action="{{ route('login') }}" autocomplete="off" novalidate id="signin-tab">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="si-email">Email adresa</label>
                        <input class="form-control" type="email" id="si-email" name="email" placeholder="" required>
                        <div class="invalid-feedback">Molimo unesite ispravnu email adresu.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="si-password">Zaporka</label>
                        <div class="password-toggle">
                            <input class="form-control" type="password" name="password" id="si-password" required>
                            <label class="password-toggle-btn" aria-label="Show/hide password">
                                <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 d-flex flex-wrap justify-content-between">
                        <div class="form-check mb-2 ps-0">
                            <x-jet-checkbox id="remember_me" name="remember" />
                            <label class="form-check-label" for="si-remember">Zapamti me</label>
                        </div><a class="fs-sm" href="#">Zaboravljena lozinka</a>
                    </div>
                    <button class="btn btn-primary btn-shadow d-block w-100" type="submit">Prijavi se</button>
                </form>
                <form class="needs-validation tab-pane fade" method="POST" action="{{ route('register') }}" autocomplete="off" novalidate id="signup-tab">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="su-name">Korisničko ime</label>
                        <input class="form-control" type="text" name="name" id="su-name" placeholder="" required>
                        <div class="invalid-feedback">Molimo unesite korisničko ime.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="si-email">Email adresa</label>
                        <input class="form-control" type="email" name="email"  id="su-email" placeholder="" required>
                        <div class="invalid-feedback">Molimo unesite ispravnu email adresu.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="su-password">Zaporka</label>
                        <div class="password-toggle">
                            <input class="form-control" type="password" name="password" id="su-password" required>
                            <label class="password-toggle-btn" aria-label="Show/hide password">
                                <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="su-password-confirm">Potvrdite zaporku</label>
                        <div class="password-toggle">
                            <input class="form-control" type="password" name="password_confirmation"  id="su-password-confirm" required>
                            <label class="password-toggle-btn" aria-label="Show/hide password">
                                <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                            </label>
                        </div>
                    </div>
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="form-group mb-3" >
                            <x-jet-label for="terms">
                                <div class="flex items-center">
                                    <x-jet-checkbox name="terms" id="terms"/>
                                    <label class="form-label">
                                        {!! __('Slažem se sa :terms_of_service', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('catalog.route.page',['page' => 'opci-uvjeti-kupnje']).'" class="link-fx">'.__('Uvjetima kupovine').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="link-fx">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </label>
                                </div>
                            </x-jet-label>
                        </div>
                    @endif
                    <button class="btn btn-primary btn-shadow d-block w-100" type="submit">Registriraj se</button>
                    <input type="hidden" name="recaptcha" id="recaptcha">
                </form>
            </div>
        </div>
    </div>
</div>

@if (config('app.env') == 'production')
    <!-- Google Tag Manager (noscript) -->
 <!--   <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-xxxxxxx" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript> -->
@endif




<div id="agapp">

    @include('front.layouts.partials.header')
    <main class="offcanvas-enabled" >

        <section class="ps-lg-4 pe-lg-3 pt-4">
            <div class="px-3 pt-2">

               @yield('content')

            </div>


        </section>

            @include('front.layouts.partials.footer')
        @include('front.layouts.partials.handheld')

    </main>
</div>


<!-- Back To Top Button--><a class="btn-scroll-top" href="#top" data-scroll data-fixed-element><span class="btn-scroll-top-tooltip text-muted fs-sm me-2">Top</span><i class="btn-scroll-top-icon ci-arrow-up">   </i></a>
<!-- Vendor Styles including: Font Icons, Plugins, etc.-->
<link rel="stylesheet" media="screen" href="{{ asset(config('settings.images_domain') . 'css/tiny-slider.css?v=1.2') }}"/>
<!-- Vendor scrits: js libraries and plugins-->
<script src="{{ asset('js/jquery/jquery-2.1.1.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/simplebar/dist/simplebar.min.js') }}"></script>
<script src="{{ asset('vendor/tiny-slider/dist/min/tiny-slider.js') }}"></script>
<script src="{{ asset('vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script>






<script src="{{ asset('js/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('js/shufflejs/dist/shuffle.min.js') }}"></script>
<!-- Main theme script-->

<script src="{{ asset('js/cart.js') }}"></script>

<script src="{{ asset('js/theme.min.js') }}"></script>

<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'register'}).then(function(token) {
            if (token) {
                document.getElementById('recaptcha').value = token;
            }
        });
    });
</script>

<script>
    $(() => {
        $('#search-input').on('keyup', (e) => {
            if (e.keyCode == 13) {
                e.preventDefault();
                $('search-form').submit();
            }
        })
    });
</script>

<!-- Messenger Chat Plugin Code
<div id="fb-root"></div>
-->
<!-- Your Chat Plugin code
<div id="fb-customer-chat" class="fb-customerchat">
</div>

<script>
    var chatbox = document.getElementById('fb-customer-chat');
    chatbox.setAttribute("page_id", "2149604518703728");
    chatbox.setAttribute("attribution", "biz_inbox");
</script>


<script>
    window.fbAsyncInit = function() {
        FB.init({
            xfbml            : true,
            version          : 'v17.0'
        });
    };

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
-->
@stack('js_after')


</body>
</html>
