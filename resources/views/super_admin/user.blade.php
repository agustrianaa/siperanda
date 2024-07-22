@extends('template')
@section('page-title')
<h4 class="fw-semibold">Users</h4>
@endsection
@section('content')
<style>
    .password-input-container {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .password-input-container input {
        width: 100%;
        padding-right: 40px;
        /* Adjust this value as needed */
    }

    .password-input-container .toggle-password {
        position: absolute;
        top: 70%;
        right: 10px;
        /* Adjust this value as needed */
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>


<div class="container-fluid">
    <div class="row">
        <div class="pull-right mb-2">
            <a class="btn btn-secondary m-1" onClick="add()" href="javascript:void(0)">Create User</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="user" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
</div>

<!-- modal -->
<div class="modal fade" id="user-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="UserForm" name="UserForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <!-- untuk id dalam user -->
                    <input type="hidden" name="id" id="user_id">
                    <!-- untuk user_id pada role -->
                    <input type="hidden" name="user_id" id="id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Role</label>
                        <select name="role" id="role" class="form-select">
                            <option value="#" disabled selected>- Pilih Role -</option>
                            <option value="super_admin" disabled> Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="direksi">Direksi</option>
                            <option value="unit">Unit</option>
                        </select>
                    </div>
                    <div class="form-group password-input-container" id="password_fields">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required="">
                            <i class="fa fa-eye toggle-password" id="togglePassword" onclick="togglePassword()"></i>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10"><br />
                        <button type="submit" class="btn btn-primary" id="btn-save-user">Save changes</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- end bootstrap model -->

<!-- Modal Reset Password -->
<div class="modal fade" id="password-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="passwordForm" name="passwordForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="reset_id">
                    <div class="form-group mb-3 password-input-container">
                        <label for="new_password" class="col-sm-4 control-label">New Password</label>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" id="new_password" name="password" placeholder="Masukkan Password" maxlength="50" required="">
                            <i class="fa fa-eye toggle-password" id="toggleNewPassword" onclick="togglePassword('new_password', 'toggleNewPassword')"></i>
                        </div>
                    </div>
                    <div class="form-group password-input-container">
                        <label for="confirm_password" class="col-sm-4 control-label">Confirm Password</label>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" id="confirm_password" name="password_confirmation" placeholder="Konfirmasi Password" maxlength="50" required="">
                            <i class="fa fa-eye toggle-password" id="toggleConfirmPassword" onclick="togglePassword('confirm_password', 'toggleConfirmPassword')"></i>
                        </div>
                    </div>
                    <div class="col-sm-8 offset-sm-8"><br />
                        <button type="button" class="btn btn-danger mr-2" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="btn-save-password">Simpan</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


</div>


<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#change_password_checkbox').on('change', function() {
            if ($(this).is(':checked')) {
                $('#password_fields').show();
                $('#password').attr('required', true);
            } else {
                $('#password_fields').hide();
                $('#password').val('');
                $('#password').removeAttr('required');
                $('#password_confirmation').val('');
            }
        });

        $('#user').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('superadmin.user') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '5%',
                    className: 'text-center',
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    orderable: false
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
    });

    function add() {
        $('#UserForm').trigger("reset");
        $('#UserModal').html("Add User");
        $('#user-modal').modal('show');
        $('#id').val('');
    }

    function editUser(id) {
        $.ajax({
            type: "POST",
            url: "{{ route('superadmin.edit_user')}}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                $('#UserModal').html("Edit User");
                $('#user-modal').modal('show');
                $('#user_id').val(res.id);
                $('#name').val(res.admin_name || res.unit_name || res.super_admin_name || res.direksi_name || res.name);
                $('#email').val(res.email);
                $('#role').val(res.role);
                $('#password').val('');
                $('#password_confirmation').val('');
                $('#change_password_checkbox').prop('checked', false);
                $('#password_fields').hide();
                $('#password').removeAttr('required');
            }
        });
    }

    function hapusUser(id) {
        if (confirm("Delete Data User?") == true) {
            var id = id;
            // ajax
            $.ajax({
                type: "POST",
                url: "{{ route('superadmin.hapus_user')}}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    var oTable = $('#user').DataTable();
                    oTable.ajax.reload();
                    Swal.fire(
                        'Terhapus!',
                        'Data user berhasil dihapus.',
                        'success'
                    );
                }
            });
        }
    }

    function ResetPass(id) {
        $('#reset_id').val(id);
        $('#password-modal').modal('show');
        $('#passwordForm').trigger("reset");
    }

    $('#UserForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "{{ route('superadmin.tambah_user')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#user-modal").modal('hide');
                var oTable = $('#user').DataTable();
                oTable.ajax.reload();
                $("#btn-save-user").html('Submit');
                $("#btn-save-user").attr("disabled", false);
                Swal.fire(
                    'Success!',
                    'Berhasil!!',
                    'success'
                );
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: "{{ route('reset-password') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response.message);
                $('#password-modal').modal('hide');
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = '';

                $.each(errors, function(key, value) {
                    errorMessage += value[0] + '\n';
                });

                alert(errorMessage);
            }
        });
    });

    function togglePassword(passwordFieldId, toggleIconId) {
        var passwordField = document.getElementById(passwordFieldId);
        var toggleIcon = document.getElementById(toggleIconId);
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
@endsection
