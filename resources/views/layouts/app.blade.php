<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        @if (config('app.is_demo'))
            <title itemprop="name">
                POWEREDUCATION
            </title>
            <meta name="description" content="Fullstack tool for building Laravel apps with hundreds of UI components
                and ready-made CRUDs">
            <meta name="keywords" content="creative tim, updivision, html dashboard, laravel, api, html css dashboard laravel,  Corporate UI Dashboard Laravel,  Corporate UI Laravel,  Corporate Dashboard Laravel, UI Dashboard Laravel, Laravel admin, laravel dashboard, Laravel dashboard, laravel admin, web dashboard, bootstrap 5 dashboard laravel, bootstrap 5, css3 dashboard, bootstrap 5 admin laravel, frontend, responsive bootstrap 5 dashboard, corporate dashboard laravel,  Corporate UI Dashboard Laravel">
            <meta property="og:app_id" content="655968634437471">
            <meta property="og:type" content="product">
            <meta property="og:title" content="Corporate UI Dashboard Laravel by Creative Tim & UPDIVISION">
        @endif
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png')}}">
        <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
        <title>
            POWEREDUCATION
        </title>
        <!-- css -->
        <link rel="stylesheet" href="{{ asset('css/add.css') }}" />
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-grid.css') }}"/>
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-grid.rtl.css') }}"/>
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-reboot.css') }}"/>
        <link rel="stylesheet" href="{{ asset('css/toast.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/custom-dropdown.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/dashboard-menu.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bell.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/ckeditor5.css') }}" />
        <!-- JQuery file -->
        <script src="{{ asset('js/plugins/jquery.js') }}"></script>
        <!--     Fonts and icons     -->
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700"
            rel="stylesheet" />
        <!-- Nucleo Icons -->
        <link href="{{ asset ('css/nucleo-icons.css') }}" rel="stylesheet" />
        <link href="{{ asset ('css/nucleo-svg.css') }}" rel="stylesheet" />
        <!-- some css for pluguins [select2, b-select] -->
        <!--link rel="stylesheet" href="{ { asset('css/bootstrap-select.min.css') }}"-->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        <!-- app.js
        @ vite(['resources/css/app.css', 'resources/js/app.js']) -->
        <!-- CKEDITOR -->
        <script type="importmap">
            {
                "imports" : {
                    "ckeditor5": "{{ asset('/vendor/ckeditor5.js') }}",
                    "ckeditor5/": "{{ asset('/vendor/') }}"
                }
            }
        </script>
        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/612ac88160.js" crossorigin="anonymous"></script>
        <link href="{{ asset ('css/nucleo-svg.css') }}" rel="stylesheet" />
        <!-- CSS Files -->
        <link id="pagestyle" href="{{ asset('css/corporate-ui-dashboard.css?v=1.0.0') }}" rel="stylesheet" />
    </head>

    <body class="g-sidenav-show  bg-gray-100">
        @php
            $topSidenavArray = ['wallet', 'profile'];
            $topSidenavTransparent = ['signin', 'signup'];
            $topSidenavRTL = ['RTL'];
        @endphp
        @if (in_array(request()->route()->getName(),
                $topSidenavArray))
            <x-sidenav-top />
        @elseif(in_array(request()->route()->getName(),
                $topSidenavTransparent))

        @elseif(in_array(request()->route()->getName(),
                $topSidenavRTL))
        @else
            <x-app.sidebar />
        @endif

        {{ $slot }}
        <!--   Core JS Files   -->
        <script src="{{ asset('js/core/popper.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.js')}}"></script>
        <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
        <script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>
        <script src="{{ asset('js/plugins/swiper-bundle.min.js') }}" type="text/javascript"></script>
        <script>
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                    damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
        </script>
        <!-- CKEDITOR configuration -->
        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Paragraph,
                Bold,
                Italic,
                Font,
                Image,
                //ImageToolbar,
                ImageCaption,
                ImageStyle,
                ImageResize,
                ImageUpload,
                Link,
                Table,
                List,
                BlockQuote,
                Heading,
                ImageToolbar,
                CKFinderUploadAdapter
            } from 'ckeditor5';
            if(document.querySelector( '#content' )) {
                ClassicEditor
                    .create( document.querySelector( '#content' ), {
                        plugins: [
                            ClassicEditor,
                            Essentials,
                            Paragraph,
                            Bold,
                            Italic,
                            Font,
                            Image,
                            CKFinderUploadAdapter,
                            ImageCaption,
                            ImageStyle,
                            ImageResize,
                            ImageUpload,
                            Link,
                            Table,
                            List,
                            BlockQuote,
                            Heading,
                            ImageToolbar
                        ],
                        toolbar: [
                            'undo', 'redo','|','heading','|', 'bold', 'italic','bulletedList','numberedList','blockquote','|','imageUpload','link',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor','|','insertTable'
                        ],
                        image: {
                            toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side'],
                            styles: ['full', 'side'],
                        },
                        ckfinder: {
                            uploadUrl: `{{ route('upload.image').'?_token='.csrf_token() }}`
                        }
                    } )
                    .then( editor => {
                        window.editor = editor;
                    } )
                    .catch( error => {
                        console.error( error );
                    } );
            }
        </script>
        <!-- some js for common plugins -->
        <!--script src="{ { asset('js/plugins/bootstrap-select.min.js') }}"></-script-->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="{{ asset('js/corporate-ui-dashboard.min.js?v=1.0.0') }}"></script>
        <script src="{{ asset('js/functions/pwd.js') }}"></script>
        <script src="{{ asset('js/functions/dashboard-menu.js') }}"></script>
        <script src="{{ asset('js/functions/success.js') }}"></script>
        <script src="{{ asset('js/functions/modules/utils.js') }}"></script>
        <script src="{{ asset('js/functions/spinner.js') }}"></script>
        <script src="{{ asset('js/functions/toggle-eye.js') }}"></script>
        <script src="{{ asset('js/functions/error-input.js') }}"></script>
        <script src="{{ asset('js/functions/toast.js') }}"></script>
        <script src="{{ asset('js/functions/custom-dropdown.js') }}"></script>
        <script src="{{ asset('js/functions/migrate.js') }}"></script>
        <script src="{{ asset('js/functions/edit.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
        <script src="{{ asset('js/functions/modules/chart.js') }}"></script>
        <!-- If using real-time notifications -->
        @if(config('broadcasting.default') === 'pusher')
            <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
            <script>
                window.Pusher = Pusher;
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: '{{ config('broadcasting.connections.pusher.key') }}',
                    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                    encrypted: true
                });
            </script>
        @endif
        <script src="{{ asset('js/functions/notificatons-bell.js') }}"></script>
        @yield('scripts')
    </body>
</html>
