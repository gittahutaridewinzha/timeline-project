<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sunset Hill Admin</title>
    <!-- Tambahkan ini di bagian <head> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <!-- Script untuk DataTables -->
    <script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Script untuk Select Plugin -->
    <script src="{{ asset('js/dataTables.select.min.js') }}"></script>

    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- endinject -->

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Default layout */
        .main-container {
            display: flex;
            flex: 1;
            margin-top: 50px;
        }

        .sidebar {
            width: 230px;
        }

        .content {
            flex: 1;
            padding: 10px;
            margin-right: -230px;
            margin-top: -60px;
            justify-content: center;
            align-content: center;
            align-items: center;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .sidebar {
                position: absolute;
                top: 60px;
                /* atau tinggi navbar kamu */
                left: 0;
                width: 100%;
                background: white;
                z-index: 1000;
                display: none;
                /* disembunyikan default */
            }

            .sidebar.active {
                display: block;
            }

            .content {
                padding: 15px;
                margin: 0;
                width: 100%;
            }
        }

        .form-switch {
            display: flex;
            align-items: center;
            /* Pastikan align center */
        }

        .form-check-input[type="checkbox"] {
            width: 2em;
            height: 1.2em;
            cursor: pointer;
            margin-right: 10px;
        }


        .form-check-label {
            margin-bottom: 0;
            line-height: 1.2;
            font-size: 0.95rem;
            position: relative;
            top: 1px;
            /* atau coba 2px sesuai kebutuhan */
        }
         .modal-content img {
        display: inline-block;
        max-width: 100%;
        height: 100%;
    }
    </style>
</head>

<body>

    <div class="wrapper">

        <div class="main-container">
            <div class="sidebar sidebar-offcanvas">
                @include('back-end.layouts.sidebar')
            </div>

            <div class="content">
                @yield('content')
            </div>
        </div>

        {{-- @include('.layouts.footer') --}}
    </div>

    @yield('scripts')

    <!-- plugins:js -->
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/dataTables.select.min.js') }}"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/settings.js') }}"></script>
    <script src="{{ asset('js/todolist.js') }}"></script>

    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/Chart.roundedBarCharts.js') }}"></script>
    <!-- End custom js for this page -->
    {{-- <script>
    $(document).ready(function() {
        $('#example').DataTable(); // ← ini kemungkinan penyebabnya
    });
  </script> --}}

  @stack('scripts')
</body>

</html>
