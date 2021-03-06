@extends('admin.layout.base')
@section('content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row ">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-primary text-white-all">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item"><a href="#"><i class=""></i> Quản lý sản phẩm</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fas"></i> Danh sách sản phẩm</li>
                    </ol>
                </nav>
                <div class="card">
                    <div class="card-header">
                        <div class="group_action float-right">
                            <a href="#add_modal" class="btn btn-info" data-toggle="modal"  target=""><i class="fa fa-plus"></i>&#32;Tạo mới</a>
                        </div>
                    </div>
                    <div class="card-body">             
                        <div class="table-responsive">
                            <table class="table table-striped" id="table_model">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            #
                                        </th>
                                        <th>Tên</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Tùy chọn</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- add form -->
<div class="modal fade in" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm chuyên mục</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="add_error"></div>
                <form action="" method="post" id="form_add" action="javascript:void(0)" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tên chuyên mục</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <div class="control-label">Trạng thái</div>
                        <label class="custom-switch mt-2">
                          <input type="checkbox" name="status" class="custom-switch-input" checked>
                          <span class="custom-switch-indicator"></span>
                        </label>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" id="btn_add_data">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- edit form -->
<div class="modal fade in" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm chuyên mục</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="edit_error"></div>
                <form action="" method="post" id="form_edit" action="javascript:void(0)" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Chỉnh sửa chuyên mục</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <div class="control-label">Trạng thái</div>
                        <label class="custom-switch mt-2">
                          <input type="checkbox" name="status" class="custom-switch-input">
                          <span class="custom-switch-indicator"></span>
                        </label>
                    </div>
                    <div class="btn-group">
                        <input type="hidden" class="form-control" name="id" id="id_edit">
                        <button type="button" class="btn btn-primary" id="btn_edit_data">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    //load data
    var tElement = $('#table_model');
    var table = tElement.dataTable({
        "processing": true,
        "language": {
            "processing": "Đang xử lý",
            "search": "Tìm kiếm ",
            "emptyTable": "Không tìm thấy bản ghi",
            "sLengthMenu":    "Hiển thị _MENU_ bản ghi trên 1 trang",
        },
        "serverSide": true,
        "ajax":{
            "url": "{{ url('admin/getDataCategory') }}",
            "dataType": "json",
            "type": "post",
            "data":{ _token: "{{csrf_token()}}" }
        },
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
            },
            {
                "targets": 2,
                "orderable": false,
            },
            {
                "targets": 4,
                "orderable": false,
            },
        ],
        "columns": [
            { "data": "index" },
            { "data": "name" },
            { "data": "status" },
            { "data": "created_at"},
            { "data": "options"},
        ],
        "autoWidth": false
    });
</script>
<script>
    //add
    $('#btn_add_data').on('click', function (e) {
        e.preventDefault();
        var table = $('#table_model').DataTable();
        var form = $(this).closest('form');
        var btnSubmit = $(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        btnSubmit.attr("disabled", true);
        btnSubmit.html('Đang xử lý...');
        
        $.ajax({
            url: '/admin/saveCategory' ,
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                if (response.status !== 1) {
                    btnSubmit.html('Save');
                    $('#add_error').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + response.message + '</div>');
                    $("#addModel").animate({ scrollTop: 0 }, "slow");
                    btnSubmit.attr("disabled", false);
                } else {
                    form.trigger("reset");
                    form.find('input').val('');
                    btnSubmit.html('Save');
                    $('#add_error').html('');
                    $('#add_modal').modal('hide');
                    iziToast.success({
                        message: 'Thêm thành công!',
                        position: 'topRight'
                    });
                    if(typeof table !== 'undefined' && table !== null) {
                        table.draw();
                    }
                    btnSubmit.attr("disabled", false);
                }
            }
        });
    });
</script>
<script>
    // get data when update
    $("body").on("click", ".editRecord", function () {
        var id_edit = $(this).data('id');
        var url_edit = $(this).data('url');
        var method_edit = $(this).data('method');
        var editForm = $('#form_edit');
        $.ajax({
            type: method_edit,
            url: url_edit,
            data: { id: id_edit },
            success: function (data) {
                editForm.find("input[name='id']").val(data.data.id);
                editForm.find("input[name='name']").val(data.data.name);
                data.data.status ? editForm.find('[name="status"]').prop("checked", true) : editForm.find('[name="status"]').prop("checked", false);
            }
        });
    });
</script>
<script>
    //update
    $('#btn_edit_data').on('click', function () {
        var table = $('#table_model').DataTable();
        var form = $(this).closest('form');
        var btnSubmit = $(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        btnSubmit.html('Đang xử lý..');
        var id = $('#id_edit').val();
        $.ajax({
            url: '/admin/updateCategory/' + id,
            type: "POST",
            data: form.serialize(),
            success: function (data) {
                if (data.status !== 1) {
                    btnSubmit.html('Save');
                    $('#edit_error').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.message + '</div>');
                } else {
                    btnSubmit.attr("disabled", true);
                    btnSubmit.attr("disabled", false);
                    btnSubmit.html('Save');
                    $('#edit_modal').modal('hide');
                    $('#edit_error').html('');
                    form.trigger("reset");
                    table.draw();
                    iziToast.success({
                        message: 'Cập nhật thành công!',
                        position: 'topRight'
                    });
                }
            }
        });
    });
</script>
@endsection