@extends('template')
@section('content')
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"> -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Sample Page</h5>
            <!-- <p class="mb-0">This is a sample page </p> -->
            <div class="row">
                <div class="pull-right mb-2">
                    <a class="btn btn-success" onClick="add()" href="javascript:void(0)">Create User</a>
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
                    <h5 class="modal-title">Employee</h5>
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
                                <input type="text" class="form-control" id="password" name="password" placeholder="Enter role" required="">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10"><br />
                            <button type="submit" class="btn btn-primary" id="btn-save" >Save changes</button>
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
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            // { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false},
        ],
        order: [[0, 'desc']]
    });
    });

    function add() {
        $('#UserForm').trigger("reset");
        $('#UserModal').html("Add User");
        $('#user-modal').modal('show');
        $('#id').val('');
    }
    $('#UserForm').submit(function(e){
        e.preventDefault();
        var formData = new FormData();
        formData.append('name', $('#name').val()); // Mengambil nilai nama
        formData.append('email', $('#email').val()); // Mengambil nilai email
        formData.append('role', $('#role').val()); // Mengambil nilai role
        formData.append('password', $('#password').val()); // Mengambil nilai password
        $.ajax({
            type: 'POST',
            url: "{{ route('superadmin.tambah_user')}}",
            data: formData,
            // cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#user-modal").modal('hide');
                $("#btn-save").html('Submit');
            $("#btn-save"). attr("disabled", false);
            },
            error: function(data){
            console.log(data);
        }
        });
    });




</script>
@endsection
