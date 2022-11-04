<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Laporan Order</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-primary" onclick="downloadExcel()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>&nbsp;Download Excel</button>
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="form-group">
                                <label>Tanggal :</label>
                                <div class="input-group">
                                    <input type="date" name="filter_start_date" id="filter_start_date" max="{{ date('Y-m-d') }}" class="form-control">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">s/d</span>
                                    </div>
                                    <input type="date" name="filter_finish_date" id="filter_finish_date" max="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label>Customer :</label>
                                        <select name="filter_customer_id" id="filter_customer_id" class="select2 form-control" style="width:100%;">
                                            <option value="">-- Pilih --</option>
                                            @foreach($customer as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label>Vendor :</label>
                                        <select name="filter_vendor_id" id="filter_vendor_id" class="select2 form-control" style="width:100%;">
                                            <option value="">-- Pilih --</option>
                                            @foreach($vendor as $v)
                                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label>Status :</label>
                                        <select name="filter_status" id="filter_status" class="form-control">
                                            <option value="">Semua</option>
                                            <option value="1">Ready</option>
                                            <option value="2">Running</option>
                                            <option value="3">Finish</option>
                                            <option value="4">Cancel</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"><hr class="bg-success"></div>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-secondary" onclick="reset()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg>&nbsp;Reset</button>
                            <button type="button" class="btn btn-success" onclick="loadDataTable()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>&nbsp;Filter</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable_serverside" class="table table-bordered table-hover nowrap" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>No PO</th>
                                    <th>Customer</th>
                                    <th>Vendor</th>
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
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
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
                                <table cellpadding="10" cellspacing="0" width="100%">
                                    <tbody>
                                        <tr>
                                            <th width="20%" class="align-middle">Kode</th>
                                            <td class="align-middle" id="code"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">No PO</th>
                                            <td class="align-middle" id="reference"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">Customer</th>
                                            <td class="align-middle" id="customer_id"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">Vendor</th>
                                            <td class="align-middle" id="vendor_id"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">Berat</th>
                                            <td class="align-middle" id="weight"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">Qty</th>
                                            <td class="align-middle" id="qty"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">Tanggal Order</th>
                                            <td class="align-middle" id="date"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">Batas Waktu</th>
                                            <td class="align-middle" id="deadline"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">Toleransi</th>
                                            <td class="align-middle" id="tolerance"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">Tanggal Dibuat</th>
                                            <td class="align-middle" id="created_at"></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" class="align-middle">User</th>
                                            <td class="align-middle" id="user_id"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </p>
                        </div>
                        <div class="tab-pane fade" id="justify-transport" role="tabpanel" aria-labelledby="justify-transport-tab">
                            <p class="mt-5">
                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="width:100%;">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Kendaraan</th>
                                                    <th>Sopir</th>
                                                    <th>Gudang Asal</th>
                                                    <th>Gudang Tujuan</th>
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
                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="width:100%;">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Kota Asal</th>
                                                    <th>Kota Tujuan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data_destination"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer md-button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            loadDataTable();

            $('.select2').select2({
                placeholder: '-- Pilih --'
            });
        });

        function reset() {
            $('#filter_start_date').val('');
            $('#filter_finish_date').val('');
            $('#filter_customer_id').val(null).trigger('change');
            $('#filter_vendor_id').val(null).trigger('change');
            $('#filter_status').val('');
            $('.tab-pane').removeClass('show active');
            $('#justify-data').addClass('show active');
            $('.nav-link-tab').removeClass('active');
            $('#justify-data-tab').addClass('active');
            loadDataTable();
        }

        function loadDataTable() {
            $('#datatable_serverside').DataTable({
                serverSide: true,
                deferRender: true,
                destroy: true,
                order: [[0, 'asc']],
                iDisplayInLength: 10,
                scrollX: true,
                ajax: {
                    url: '{{ url("report/order/datatable") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        start_date: $('#filter_start_date').val(),
                        finish_date: $('#filter_finish_date').val(),
                        customer_id: $('#filter_customer_id').val(),
                        vendor_id: $('#filter_vendor_id').val(),
                        status: $('#filter_status').val()
                    },
                    beforeSend: function() {
                        loadingOpen('#datatable_serverside');
                    },
                    complete: function() {
                        loadingClose('#datatable_serverside');
                    },
                    error: function() {
                        notif('Server Error!', '#DC3545');
                        loadingClose('#datatable_serverside');
                    }
                },
                columns: [
                    { name: 'id', searchable: false, className: 'text-center align-middle' },
                    { name: 'code', className: 'text-center align-middle' },
                    { name: 'reference', className: 'text-center align-middle' },
                    { name: 'customer_id', className: 'text-center align-middle' },
                    { name: 'vendor_id', className: 'text-center align-middle' },
                    { name: 'date', searchable: false, className: 'text-center align-middle' },
                    { name: 'status', searchable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
            });
        }

        function show(id) {
            $('.tab-pane').removeClass('show active');
            $('#justify-data').addClass('show active');
            $('.nav-link-tab').removeClass('active');
            $('#justify-data-tab').addClass('active');
            $('#form_modal').modal('show');

            $.ajax({
                url: '{{ url("report/order/show") }}',
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
                                <td>` + val.transport_no_plate + ` (` + val.transport_brand + `)</td>
                                <td>` + val.driver_name + `</td>
                                <td>` + val.warehouse_origin_name + `</td>
                                <td>` + val.warehouse_destination_name + `</td>
                            </tr>
                        `);
                    });

                    $.each(response.order_destination, function(i, val) {
                        $('#data_destination').append(`
                            <tr class="text-center">
                                <td>` + val.city_origin + `</td>
                                <td>` + val.city_destination + `</td>
                            </tr>
                        `);
                    });

                    $('#customer_id').html(response.customer_id);
                    $('#vendor_id').html(response.vendor_id);
                    $('#user_id').html(response.user_id);
                    $('#code').html(response.code);
                    $('#reference').html(response.reference);
                    $('#weight').html(response.weight);
                    $('#qty').html(response.qty);
                    $('#date').html(response.date);
                    $('#deadline').html(response.deadline);
                    $('#tolerance').html(response.tolerance);
                    $('#created_at').html(response.created_at);
                },
                error: function() {
                    $('#form_modal').modal('hide');
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function restore(id) {
            swal({
                title: 'Anda yakin ingin restore?',
                text: 'Data akan di restore',
                type: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ya, restore!',
                cancelButtonText: 'Batal!',
                reverseButtons: true,
                padding: '2em'
            }).then(function(result) {
                if(result.value) {
                    $.ajax({
                        url: '{{ url("report/order/restore") }}',
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

        function downloadExcel() {
            var param       = 'report_order';
            var start_date  = $('#filter_start_date').val();
            var finish_date = $('#filter_finish_date').val();
            var customer_id = $('#filter_customer_id').val();
            var vendor_id   = $('#filter_vendor_id').val();
            var status      = $('#filter_status').val();
            var build_query = 'param=' + param + '&start_date=' + start_date + '&finish_date=' + finish_date + '&customer_id=' + customer_id + '&vendor_id=' + vendor_id + '&status=' + status;

            return window.location.href = '{{ url("download/excel") }}' + '?' + build_query;
        }
    </script>
