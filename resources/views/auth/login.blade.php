<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPERANDA</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/polindra.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .password-input-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .password-input-container input {
            width: 100%;
            padding-right: 40px; /* Adjust this value as needed */
        }
        .password-input-container .toggle-password {
            position: absolute;
            top: 70%;
            right: 10px; /* Adjust this value as needed */
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
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
                                <form method="POST" action="{{ route('login') }}">
                                @csrf
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Username</label>
                                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-4 password-input-container">
                                        <label for="exampleInputPassword1" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <i class="fa fa-eye toggle-password" id="togglePassword" onclick="togglePassword()"></i>
                                    </div>
                                    <button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" type="submit">Log In</button>
                                    <div class="d-flex align-items-center justify-content-center">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.getElementById("togglePassword");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
     @if(session('success'))
    <script>
        Swal.fire({
            title: 'Success',
            text: "{{ session('success') }}",
            icon: 'success'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            title: 'Error',
            text: "{{ session('error') }}",
            icon: 'error'
        });
    </script>
    @endif
</body>

</html>
