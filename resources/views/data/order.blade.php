<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Data Order</h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#form_modal" onclick="cancel()" style="height:40px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Tambah Data</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable_serverside" class="table table-bordered table-hover nowrap" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>No PO</th>
                                    <th>User</th>
                                    <th>Jumlah Customer</th>
                                    <th>Vendor</th>
                                    <th>Qty</th>
                                    <th>Berat</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="form_modal" class="modal animated  fadeInUp" data-backdrop="static" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-text">
                        <div class="alert alert-danger" id="validation_alert" role="alert" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                            <ul id="validation_content"></ul>
                        </div>
                        <form id="form_data">
                            <ul class="nav nav-tabs  mb-3 mt-3 nav-fill" id="justifyTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link nav-link-tab active" id="justify-data-tab" data-toggle="tab" href="#justify-data" role="tab" aria-controls="justify-data" aria-selected="true">Data</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-tab" id="justify-transport-tab" data-toggle="tab" href="#justify-transport" role="tab" aria-controls="justify-transport" aria-selected="false">Transportasi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-tab" id="justify-destination-tab" data-toggle="tab" href="#justify-destination" role="tab" aria-controls="justify-destination" aria-selected="false">Tujuan</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="justifyTabContent">
                                <div class="tab-pane fade show active" id="justify-data" role="tabpanel" aria-labelledby="justify-data-tab">
                                    <p>
                                        <div class="form-group">
                                            <label>Kode :</label>
                                            <input type="text" name="code" id="code" class="form-control" placeholder="Jika kosong auto generate">
                                        </div>
                                        <div class="form-group">
                                            <label>No PO :</label>
                                            <input type="text" name="reference" id="reference" class="form-control" placeholder="Masukan no PO">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Customer :</label>
                                            <select name="customer_id[]" id="customer_id" class="select2 form-control" style="width:100%;" multiple>
                                                @foreach($customer as $c)
                                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Vendor :</label>
                                            <select name="vendor_id" id="vendor_id" class="select2 form-control" style="width:100%;" onchange="getDriverDestination()">
                                                @foreach($vendor as $v)
                                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Berat :</label>
                                                    <div class="input-group">
                                                        <input type="number" name="weight" id="weight" class="form-control" placeholder="Masukan berat">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Qty :</label>
                                                    <div class="input-group">
                                                        <input type="number" name="qty" id="qty" class="form-control" placeholder="Masukan qty">
                                                        <div class="input-group-prepend">
                                                            <select name="unit_id" id="unit_id" class="form-control" style="border-radius:0;">
                                                                @foreach($unit as $u)
                                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Toleransi :</label>
                                                    <div class="input-group">
                                                        <input type="number" name="tolerance" id="tolerance" class="form-control" value="0.5" placeholder="Masukan toleransi">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal :</label>
                                                    <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Batas Waktu :</label>
                                                    <div class="input-group">
                                                        <input type="number" name="deadline" id="deadline" class="form-control" placeholder="Masukan batas waktu">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Hari</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group text-right">
                                            <input type="checkbox" id="status" name="status" value="4">
                                            <label for="status"> Cancel Order Ini</label><br>
                                        </div>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="justify-transport" role="tabpanel" aria-labelledby="justify-transport-tab">
                                    <p class="mt-5">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Kendaraan :</label>
                                                    <select name="temp_transport_id" id="temp_transport_id"
                                                    class="select2 form-control" style="width:100%;">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach($transport as $t)
                                                            <option value="{{ $t->no_plate }} ({{ $t->brand }});{{ $t->id }}">{{ $t->no_plate }} ({{ $t->brand }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sopir :</label>
                                                    <select name="temp_driver_id" id="temp_driver_id" class="select2 form-control" style="width:100%;"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Gudang Asal :</label>
                                                    <select name="temp_warehouse_origin" id="temp_warehouse_origin" class="select2 form-control" style="width:100%;">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach($warehouse as $w)
                                                            <option value="({{ $w->code }}) {{ $w->name }};{{ $w->id }}">({{ $w->code }}) {{ $w->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Gudang Tujuan :</label>
                                                    <select name="temp_warehouse_destination" id="temp_warehouse_destination" class="select2 form-control" style="width:100%;">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach($warehouse as $w)
                                                            <option value="({{ $w->code }}) {{ $w->name }};{{ $w->id }}">({{ $w->code }}) {{ $w->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group text-right">
                                                    <label class="text-white">.</label>
                                                    <button type="button" class="btn btn-success col-12" style="height:45px;" onclick="addTransport()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="width:100%;">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Kendaraan</th>
                                                            <th>Sopir</th>
                                                            <th>Gudang Asal</th>
                                                            <th>Gudang Tujuan</th>
                                                            <th>Hapus</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data_transport"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="justify-destination" role="tabpanel" aria-labelledby="justify-destination-tab">
                                    <p class="mt-5">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label>Tujuan :</label>
                                                    <select name="temp_destination_id" id="temp_destination_id" class="select2 form-control" style="width:100%;"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="text-white">.</label>
                                                    <button type="button" class="btn btn-success col-12" style="height:45px;" onclick="addDestination()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="width:100%;">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Kota Asal</th>
                                                            <th>Kota Tujuan</th>
                                                            <th>Hapus</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data_destination"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </p>
                </div>
                <div class="modal-footer md-button">
                    <button type="button" class="btn btn-danger" id="btn_cancel" onclick="cancel()" style="display:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>&nbsp;Batal</button>
                    <button type="button" class="btn btn-warning" id="btn_update" onclick="update()" style="display:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>&nbsp;Simpan</button>
                    <button type="button" class="btn btn-info" id="btn_create" onclick="create()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Tambah</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            loadDataTable();

            $('#data_transport').on('click', '#delete_data_transport', function() {
                $(this).closest('tr').remove();
            });

            $('#data_destination').on('click', '#delete_data_destination', function() {
                $(this).closest('tr').remove();
            });

            $(function() {
                $('.sidebarCollapse').trigger('click');
            });

            $('.select2').select2({
                placeholder: '-- Pilih --',
                dropdownParent: $('#form_modal')
            });
        });

        function getDriverDestination() {
            getDriver();
            getDestination();
        }

        function getDestination() {
            $.ajax({
                url: '{{ url("data/order/get_destination") }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    vendor_id: $('#vendor_id').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    loadingOpen('.modal-content');
                    $('#temp_destination_id').html('');
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    $.each(response, function(i, val) {
                        $('#temp_destination_id').append(`<option value="` + val.id + `;` + val.city_origin + `;` + val.city_destination + `">` + val.destination + `</option>`)
                    });

                    $('#temp_destination_id').val(null).trigger('change');
                },
                error: function() {
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function getDriver() {
            $.ajax({
                url: '{{ url("data/order/get_driver") }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    vendor_id: $('#vendor_id').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    loadingOpen('.modal-content');
                    $('#temp_driver_id').html('');
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    $.each(response, function(i, val) {
                        $('#temp_driver_id').append(`<option value="` + val.name + `;` + val.id + `">` + val.name + `</option>`)
                    });
                    $('#temp_driver_id').val(null).trigger('change');
                },
                error: function() {
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function addTransport() {
            let temp_transport_id          = $('#temp_transport_id');
            let temp_driver_id             = $('#temp_driver_id');
            let temp_warehouse_origin      = $('#temp_warehouse_origin');
            let temp_warehouse_destination = $('#temp_warehouse_destination');

            if(temp_transport_id.val() && temp_driver_id.val() && temp_warehouse_origin.val() && temp_warehouse_destination.val()) {
                let arr_transport_id          = temp_transport_id.val().split(';');
                let arr_driver_id             = temp_driver_id.val().split(';');
                let arr_warehouse_origin      = temp_warehouse_origin.val().split(';');
                let arr_warehouse_destination = temp_warehouse_destination.val().split(';');

                $('#data_transport').append(`
                    <tr class="text-center">
                        <input type="hidden" name="transport_id[]" value="` + arr_transport_id[1] + `">
                        <input type="hidden" name="driver_id[]" value="` + arr_driver_id[1] + `">
                        <input type="hidden" name="warehouse_origin[]" value="` + arr_warehouse_origin[1] + `">
                        <input type="hidden" name="warehouse_destination[]" value="` + arr_warehouse_destination[1] + `">

                        <td>` + arr_transport_id[0] + `</td>
                        <td>` + arr_driver_id[0] + `</td>
                        <td>` + arr_warehouse_origin[0] + `</td>
                        <td>` + arr_warehouse_destination[0] + `</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" id="delete_data_transport">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </td>
                    </tr>
                `);

                temp_transport_id.val(null).trigger('change');
                temp_driver_id.val(null).trigger('change');
                temp_warehouse_origin.val(null).trigger('change');
                temp_warehouse_destination.val(null).trigger('change');
            } else {
                swal('Harap mengisi semua field!', '', 'info');
            }
        }

        function addDestination() {
            let temp_destination_id = $('#temp_destination_id');

            if(temp_destination_id.val()) {
                let arr_destination_id = temp_destination_id.val().split(';');

                $('#data_destination').append(`
                    <tr class="text-center">
                        <input type="hidden" name="destination_id[]" value="` + arr_destination_id[0] + `">

                        <td>` + arr_destination_id[1] + `</td>
                        <td>` + arr_destination_id[2] + `</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" id="delete_data_destination">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </td>
                    </tr>
                `);

                temp_destination_id.val(null).trigger('change');
            } else {
                swal('Harap memilih tujuan!', '', 'info');
            }
        }

        function cancel() {
            reset();
            $('#form_modal').modal('hide');
            $('#btn_create').show();
            $('#btn_update').hide();
            $('#btn_cancel').hide();
        }

        function toShow() {
            $('#form_modal').modal('show');
            $('#validation_alert').hide();
            $('#validation_content').html('');
            $('#btn_create').hide();
            $('#btn_update').show();
            $('#btn_cancel').show();
        }

        function reset() {
            $('#form_data').trigger('reset');
            $('#validation_alert').hide();
            $('#validation_content').html('');
            $('#customer_id').val(null).trigger('change');
            $('#vendor_id').val(null).trigger('change');
            $('#unit_id').val(1);
            $('.tab-pane').removeClass('show active');
            $('#justify-data').addClass('show active');
            $('.nav-link-tab').removeClass('active');
            $('#justify-data-tab').addClass('active');
            $('#temp_transport_id').val(null).trigger('change');
            $('#temp_driver_id').val(null).trigger('change');
            $('#temp_warehouse_origin').val(null).trigger('change');
            $('#temp_warehouse_destination').val(null).trigger('change');
            $('#temp_destination_id').val(null).trigger('change');
            $('#data_transport').html('');
            $('#data_destination').html('');
        }

        function success() {
            reset();
            $('#form_modal').modal('hide');
            $('#datatable_serverside').DataTable().ajax.reload(null, false);
        }

        function loadDataTable() {
            $('#datatable_serverside').DataTable({
                serverSide: true,
                deferRender: true,
                destroy: true,
                order: [[0, 'desc']],
                iDisplayInLength: 10,
                scrollX: true,
                ajax: {
                    url: '{{ url("data/order/datatable") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        loadingOpen('#datatable_serverside');
                    },
                    complete: function() {
                        loadingClose('#datatable_serverside');
                    },
                    error: function() {
                        loadingClose('#datatable_serverside');
                        notif('Server Error!', '#DC3545');
                    }
                },
                columns: [
                    { name: 'id', searchable: false, className: 'text-center align-middle' },
                    { name: 'code', className: 'text-center align-middle' },
                    { name: 'reference', className: 'text-center align-middle' },
                    { name: 'user_id', className: 'text-center align-middle' },
                    { name: 'customer_id', className: 'text-center align-middle' },
                    { name: 'vendor_id', className: 'text-center align-middle' },
                    { name: 'qty', searchable: false, className: 'text-center align-middle' },
                    { name: 'weight', searchable: false, className: 'text-center align-middle' },
                    { name: 'date', searchable: false, className: 'text-center align-middle' },
                    { name: 'status', searchable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
            });
        }

        function create() {
            $.ajax({
                url: '{{ url("data/order/create") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $('#form_data').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#validation_alert').hide();
                    $('#validation_content').html('');
                    loadingOpen('.modal-content');
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    if(response.status == 200) {
                        success();
                        notif(response.message, '#198754');
                    } else if(response.status == 422) {
                        notif('Validasi!', '#FFC107');
                        $('#validation_alert').show();
                        $('.modal-body').scrollTop(0);

                        $.each(response.error, function(i, val) {
                            $('#validation_content').append(`
                                <li>` + val + `</li>
                            `);
                        })
                    } else {
                        notif(response.message, '#DC3545');
                    }
                },
                error: function() {
                    $('.modal-body').scrollTop(0);
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function show(id) {
            toShow();
            $.ajax({
                url: '{{ url("data/order/show") }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    loadingOpen('.modal-content');
                    $('#data_transport').html('');
                    $('#data_destination').html('');
                },
                success: function(response) {
                    loadingClose('.modal-content');

                    $.each(response.order_transport, function(i, val) {
                        $('#data_transport').append(`
                            <tr class="text-center">
                                <input type="hidden" name="transport_id[]" value="` + val.transport_id + `">
                                <input type="hidden" name="driver_id[]" value="` + val.driver_id + `">
                                <input type="hidden" name="warehouse_origin[]" value="` + val.warehouse_origin + `">
                                <input type="hidden" name="warehouse_destination[]" value="` + val.warehouse_destination + `">

                                <td>` + val.transport_no_plate + ` (` + val.transport_brand + `)</td>
                                <td>` + val.driver_name + `</td>
                                <td>` + val.warehouse_origin_name + `</td>
                                <td>` + val.warehouse_destination_name + `</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" id="delete_data_transport">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    $.each(response.order_destination, function(i, val) {
                        $('#data_destination').append(`
                            <tr class="text-center">
                                <input type="hidden" name="destination_id[]" value="` + val.destination_id + `">

                                <td>` + val.city_origin + `</td>
                                <td>` + val.city_destination + `</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" id="delete_data_destination">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    $('#vendor_id').val(response.vendor_id).trigger('change');
                    $('#customer_id').val(response.customer_id).trigger('change');
                    $('#unit_id').val(response.unit_id);
                    $('#code').val(response.code);
                    $('#reference').val(response.reference);
                    $('#weight').val(response.weight);
                    $('#qty').val(response.qty);
                    $('#date').val(response.date);
                    $('#deadline').val(response.deadline);
                    $('#tolerance').val(response.tolerance);
                    $('#btn_update').attr('onclick', 'update(' + id + ')');
                },
                error: function() {
                    cancel();
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function update(id) {
            $.ajax({
                url: '{{ url("data/order/update") }}' + '/' + id,
                type: 'POST',
                dataType: 'JSON',
                data: $('#form_data').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#validation_alert').hide();
                    $('#validation_content').html('');
                    loadingOpen('.modal-content');
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    if(response.status == 200) {
                        success();
                        notif(response.message, '#198754');
                    } else if(response.status == 422) {
                        notif('Validasi!', '#FFC107');
                        $('#validation_alert').show();
                        $('.modal-body').scrollTop(0);

                        $.each(response.error, function(i, val) {
                            $('#validation_content').append(`
                                <li>` + val + `</li>
                            `);
                        })
                    } else {
                        notif(response.message, '#DC3545');
                    }
                },
                error: function() {
                    $('.modal-body').scrollTop(0);
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function destroy(id) {
            swal({
                title: 'Anda yakin ingin menghapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan lagi.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal!',
                reverseButtons: true,
                padding: '2em'
            }).then(function(result) {
                if(result.value) {
                    $.ajax({
                        url: '{{ url("data/order/destroy") }}',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if(response.status == 200) {
                                $('#datatable_serverside').DataTable().ajax.reload(null, false);
                                notif(response.message, '#198754');
                            } else {
                                notif(response.message, '#DC3545');
                            }
                        },
                        error: function() {
                            notif('Server Error!', '#DC3545');
                        }
                    });
                }
            });
        }
    </script>
