<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" type="image/png" href="{{ asset('/_app_assets/images/logos/x-icon.png') }}" />
        <title>Not Found</title>

        <link rel="stylesheet" href="{{ asset('/_app_assets/css/style.min.css') }}">
    </head>
    <body>
        <div class="preloader">
            <img src="{{ asset('/_app_assets/images/logos/x-icon.png') }}" alt="loader" class="lds-ripple img-fluid" />
        </div>

        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
            <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center justify-content-center w-100">
                    <div class="row justify-content-center w-100">
                        <div class="col-lg-4">
                            <div class="text-center">
                                <img src="{{ asset('_app_assets/images/backgrounds/errorimg.svg') }}" alt="404" class="img-fluid">
                                <h1 class="fw-semibold mb-7 fs-9">Opps!!!</h1>
                                <h4 class="fw-semibold mb-7">This page you are looking for could not be found.</h4>
                                <a class="btn btn-primary" href="{{ route('home') }}" role="button">Go Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('_app_assets/libs/jquery/dist/jquery.min.js') }}"></script>
        <script>
            $('.preloader').fadeOut();
        </script>
    </body>
</html>
