@extends('template')
@section('content')
<div class="container-fluid">
    <h5 class="mb-0">User </h5> <br>
    <div class="card">
        <div class="card-body">
            <!-- <h5 class="card-title fw-semibold mb-4">Sample Page</h5> -->
            <!-- <p class="mb-0">This is a sample page </p> -->
            <div class="row">
                <div class="pull-right mb-2">
                    <a class="btn btn-secondary m-1" onClick="add()" href="javascript:void(0)">Create User</a>
                </div>
            </div>
            <table class="table table-bordered" id="user">
                <thead>
                    <tr>
                        <th>No</th>
                        <!-- <th>Name</th> -->
                        <th>Email</th>
                        <th>role</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
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
                        <input type="hidden" name="id" id="id">
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
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="role" name="role" placeholder="Enter Role" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="password" name="password" placeholder="Enter Password" required="">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10"><br />
                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->

</div>


<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#user').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/user') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '5%',
                    className: 'text-center',
                },
                // { data: 'name', name: 'name' },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
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
                $('#id').val(res.id);
                $('#name').val(res.name);
                $('#email').val(res.email);
                $('#role').val(res.role);
                $('#password').val(res.password);
            }
        });
    }

    function hapusUser(id) {
        if (confirm("Delete Record?") == true) {
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
                    var oTable = $('#user').dataTable();
                    oTable.fnDraw(false);
                    Swal.fire(
                        'Terhapus!',
                        'Data berhasil dihapus.',
                        'success'
                    );
                }
            });
        }
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
                $("#btn-save").html('Submit');
                $("#btn-save").attr("disabled", false);
                Swal.fire(
                        'Success!',
                        'Data berhasil ditambahkan/diubah.',
                        'success'
                    );
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
</script>
@endsection
