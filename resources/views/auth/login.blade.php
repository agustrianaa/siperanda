<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPERANDA</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/polindra.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
@include('sweetalert::alert')
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="{{ url('/') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="../assets/images/logos/logo2.png" width="180" alt="">
                                </a>
                                <!-- <p class="text-center">Your Social Campaigns</p> -->
                                <!-- <h2  class="text-center d-block py-3 w-100"> SIPERANDA</h2> -->
                                <form method="POST" action="{{ route('login') }}">
                                @csrf
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Username</label>
                                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <!-- <div class="form-check">
                                            <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                                            <label class="form-check-label text-dark" for="flexCheckChecked">
                                                Remeber this Device
                                            </label>
                                        </div> -->
                                        <a class="text-primary fw-bold" href="./index.html">Forgot Password ?</a>
                                    </div>
                                    <button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" type="submit">Log In</button>
                                    <!-- <a href="./index.html" >Sign In</a> -->
                                    <div class="d-flex align-items-center justify-content-center">
                                        <!-- <p class="fs-4 mb-0 fw-bold">New to Modernize?</p> -->
                                        <!-- <a class="text-primary fw-bold ms-2" href="./authentication-register.html">Create an account</a> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
