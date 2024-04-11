<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPERANDA</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/polindra.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    @include('sweetalert::alert')
        <!-- SIDEBAR -->
        @include('layouts.components.sidebar')

        <!--  Main wrapper -->
        <div class="body-wrapper">

            <!-- HEADER/NAVBAR -->
            @include('layouts.components.navbar')

            <div class="container-fluid">
                <!-- CONTENT -->
                @yield('content')


            </div>
        </div>
    </div>
    <!-- <script src="../assets/libs/jquery/dist/jquery.min.js"></script> -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>

</html>
