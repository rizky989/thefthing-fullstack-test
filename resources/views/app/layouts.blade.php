<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>THE F THING - TASK RECRUITMENT</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link href="{{asset('css/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
</head>

<body>
    <div class="App">
        @yield('content')
    </div>

    <div id="form-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Customer</h4>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-4">Name : </label>
                            <div class="col-md-8">
                                <input type="text" name="name" id="name" class="form-control"  required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Email : </label>
                            <div class="col-md-8">
                                <input type="email" name="email" id="email" class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Password </label>
                            <div class="col-md-8">
                                <input type="password" name="password" id="password" class="form-control"/>
                                <p style="margin:5px 0px -5px 0px;color:red;display:none" id="password-edit">*Bila diisi maka password akan diganti</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Gender : </label>
                            <div class="col-md-8">
                                <select name="gender" style="width:100%" class="form-control" required>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Marital Status : </label>
                            <div class="col-md-8">
                                <select name="is_married" style="width:100%" class="form-control" required>
                                    <option value="0">Belum Menikah</option>
                                    <option value="1">Sudah Menikah</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Address : </label>
                            <div class="col-md-8">
                                <input type="text" name="alamat" id="alamat" class="form-control" required/>
                            </div>
                        </div>
                        <br />
                        <div class="form-group" align="right" style="margin-right:0px">
                            <input type="hidden" name="action" id="action" />
                            <input type="hidden" name="hidden_id" id="hidden_id" />
                            <input type="submit" name="action_button" id="action_button" class="btn btn-warning"
                                value="Add" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="confirm-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title-delete">Confirmation Delete</h4>
                </div>
                <div class="modal-body">
                    <form method="get" id="delete_form" class="form-horizontal">
                        @csrf {{method_field('DELETE')}}
                        <h4 align="center" style="margin:0;">Are you sure you want to remove this data?
                        </h4>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="ok_button" id="ok_button" class="btn btn-danger">
                        <i class="fa fa-trash" style="margin-right: 10px"></i>Delete</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">
                        Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var globalToken = $('meta[name="csrf-token"]').attr("content");
        
            // utils
            function fillFormEdit(result) {
                const { name, email, gender, is_married, alamat, id } = result;
                $(".modal-title").text("Edit Record");
                $("#name").val(name);
                $("#email").val(email);
                $("#password").val("");
                $("#gender").val(gender);
                $("#is_married").val(is_married);
                $("#alamat").val(alamat);
                $("#hidden_id").val(id);
                $("#action").val("Edit");
                $("#password-edit").show();
            }
        
            function bootboxAlert(message) {
                bootbox.alert({
                    message: message,
                    size: "small",
                });
            }
        
            function resetModal(modal, form) {
                $(`#${modal}`).modal("hide");
                $(`#${form}`)["0"].reset();
            }
        
            $("#customer-table").DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ url('customer-data')}}",
                    dataSrc: "result.original.data", //nested JSON
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        className: "text-center",
                    },
                    {
                        data: "email",
                        name: "email",
                    },
                    {
                        data: "name",
                        name: "name",
                    },
                    {
                        data: "gender",
                        name: "gender",
                    },
                    {
                        data: "is_married",
                        name: "is_married",
                        render: function (data) {
                            return data == 1 ? "Sudah Menikah" : "Belum Menikah";
                        },
                    },
                    {
                        data: "alamat",
                        name: "alamat",
                        render: function (data) {
                            return data.length > 40 ? data.substr(0, 40) + "..." : data;
                        },
                    },
                    {
                        data: "action",
                        name: "action",
                        className: "text-center",
                    },
                ],
            });
        
            $("#add-customer").click(function () {
                $(".modal-title").text("Add New Record");
                $("#action_button").val("Add");
                $("#action").val("Add");
                $("#password-edit").hide();
                $("#sample_form")[0].reset();
                $("#form-modal").modal("show");
            });
        
            $("#sample_form").on("submit", function (event) {
                event.preventDefault();
                if ($("#action").val() == "Add") {
                    $.ajax({
                        url: "{{ route('customer.store') }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function (data) {
                            if (data.status.code != 201) {
                                bootboxAlert(data.status.message);
                            } else {
                                bootboxAlert("Data Added!");
                                resetModal("form-modal", "sample_form");
                                $("#customer-table").DataTable().ajax.reload();
                            }
                        },
                    });
                }
        
                if ($("#action").val() == "Edit") {
                    $.ajax({
                        url:
                            "{{url('/customer')}}" +
                            "/" +
                            $("#hidden_id").val() +
                            "/update",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function (data) {
                            var html = "";
                            if (data.status.code != 200) {
                                bootboxAlert(data.status.message);
                            } else {
                                bootboxAlert("Data Updated!");
                                resetModal("form-modal", "sample_form");
                                $("#customer-table").DataTable().ajax.reload();
                            }
                        },
                        error: function (data) {
                            console.log("Error:", data);
                        },
                    });
                }
            });
        
            $(document).on("click", ".edit", function () {
                var id = $(this).attr("id");
                $("#form_result").html("");
                $.ajax({
                    url: "/customer/" + id,
                    dataType: "json",
                    success: function (html) {
                        fillFormEdit(html.result);
        
                        $("#form-modal").modal("show");
                    },
                });
            });
        
            $(document).on("click", ".delete", function () {
                id = $(this).attr("id");
                $("#confirm-modal").modal("show");
            });
        
            $("#delete_form").on("submit", function (event) {
                event.preventDefault();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": globalToken,
                    },
                    type: "DELETE",
                    url: "{{url('/customer')}}" + "/" + id,
                    beforeSend: function () {
                        $("#ok_button").text("Deleting...");
                    },
                    success: function (data) {
                        setTimeout(function () {
                            $("#confirm-modal").modal("hide");
                            $("#table-index").DataTable().ajax.reload();
                        }, 150);
                        if (data.status.code != 204) {
                            bootboxAlert(data.status.message);
                        } else {
                            bootboxAlert("Data Deleted");
        
                            resetModal("confirm-modal", "delete_form");
        
                            $("#customer-table").DataTable().ajax.reload();
                        }
                    },
                });
            });
        });
        </script>
</body>

</html>
