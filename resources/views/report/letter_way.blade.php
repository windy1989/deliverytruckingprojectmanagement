<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Laporan Surat Jalan</h5>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Order :</label>
                                        <select name="filter_order_id" id="filter_order_id" class="select2 form-control" style="width:100%;"></select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status :</label>
                                        <select name="filter_status" id="filter_status" class="form-control">
                                            <option value="">-- Pilih --</option>
                                            <option value="1">Pendataan</option>
                                            <option value="2">Surat Jalan Balik</option>
                                            <option value="3">Surat Jalan Legalisir</option>
                                            <option value="4">Surat Jalan Legalisir Balik</option>
                                            <option value="5">Surat Jalan TTBR</option>
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
                                    <th>Order</th>
                                    <th>Nomor</th>
                                    <th>Customer</th>
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
                    <h5 class="modal-title">Detail Surat Jalan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <h6 class="font-italic bg-info p-2 text-uppercase">SJ</h6>
                        <p>
                            <table cellpadding="10" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="20%" class="align-middle">Nomor</th>
                                        <td class="align-middle" id="number"></td>
                                    </tr>
                                    <tr>
                                        <th width="20%" class="align-middle">Tujuan</th>
                                        <td class="align-middle" id="destination_id"></td>
                                    </tr>
                                    <tr>
                                        <th width="20%" class="align-middle">Berat</th>
                                        <td class="align-middle" id="weight"></td>
                                    </tr>
                                    <tr>
                                        <th width="20%" class="align-middle">Qty</th>
                                        <td class="align-middle" id="qty"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </p>
                    </div>
                    <div class="form-group">
                        <h6 class="font-italic bg-info p-2 text-uppercase">SJ Balik</h6>
                        <p>
                            <table cellpadding="10" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="20%" class="align-middle">Lampiran</th>
                                        <td class="align-middle" id="send_back_attachment"></td>
                                    </tr>
                                    <tr>
                                        <th width="20%" class="align-middle">Pecah</th>
                                        <td class="align-middle" id="ttbr_qty"></td>
                                    </tr>
                                    <tr>
                                        <th width="20%" class="align-middle">Tanggal Terima</th>
                                        <td class="align-middle" id="received_date"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </p>
                    </div>
                    <div class="form-group">
                        <h6 class="font-italic bg-info p-2 text-uppercase">SJ Legalisir</h6>
                        <p>
                            <table cellpadding="10" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="20%" class="align-middle">Lampiran</th>
                                        <td class="align-middle" id="legalize_attachment"></td>
                                    </tr>
                                    <tr>
                                        <th width="20%" class="align-middle">Tanggal Terima</th>
                                        <td class="align-middle" id="legalize_received_date"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </p>
                    </div>
                    <div class="form-group">
                        <h6 class="font-italic bg-info p-2 text-uppercase">SJ Legalisir Balik</h6>
                        <p>
                            <table cellpadding="10" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="20%" class="align-middle">Lampiran</th>
                                        <td class="align-middle" id="legalize_send_back_attachment"></td>
                                    </tr>
                                    <tr>
                                        <th width="20%" class="align-middle">Tanggal Terima</th>
                                        <td class="align-middle" id="legalize_send_back_received_date"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </p>
                    </div>
                    <div class="form-group">
                        <h6 class="font-italic bg-info p-2 text-uppercase">SJ TTBR</h6>
                        <p>
                            <table cellpadding="10" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="20%" class="align-middle">Lampiran</th>
                                        <td class="align-middle" id="ttbr_attachment"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </p>
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

            select2ServerSide('#filter_order_id', '{{ url("select2_serverside/order") }}');
        });

        function reset() {
            $('#filter_start_date').val('');
            $('#filter_finish_date').val('');
            $('#filter_status').val('');
            $('#filter_order_id').val(null).trigger('change');
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
                    url: '{{ url("report/letter_way/datatable") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        start_date: $('#filter_start_date').val(),
                        finish_date: $('#filter_finish_date').val(),
                        order_id: $('#filter_order_id').val(),
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
                    { name: 'order_id', className: 'text-center align-middle' },
                    { name: 'number', className: 'text-center align-middle' },
                    { name: 'customer_id', orderable: false, className: 'text-center align-middle' },
                    { name: 'status', searchable: false, orderable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
            });
        }

        function show(id) {
            $('.tab-pane').removeClass('show active');
            $('#justify-letter-way').addClass('show active');
            $('.nav-link-tab').removeClass('active');
            $('#justify-letter-way-tab').addClass('active');
            $('#form_modal').modal('show');

            $.ajax({
                url: '{{ url("report/letter_way/show") }}',
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
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    $('#number').html(response.number);
                    $('#destination_id').html(response.destination_id);
                    $('#weight').html(response.weight);
                    $('#qty').html(response.qty);
                    $('#received_date').html(response.received_date);
                    $('#send_back_attachment').html(response.send_back_attachment);
                    $('#legalize_attachment').html(response.legalize_attachment);
                    $('#legalize_received_date').html(response.legalize_received_date);
                    $('#legalize_send_back_attachment').html(response.legalize_send_back_attachment);
                    $('#legalize_send_back_received_date').html(response.legalize_send_back_received_date);
                    $('#ttbr_qty').html(response.ttbr_qty);
                    $('#ttbr_attachment').html(response.ttbr_attachment);
                },
                error: function() {
                    $('#form_modal').modal('hide');
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function downloadExcel() {
            var param       = 'report_letter_way';
            var start_date  = $('#filter_start_date').val();
            var finish_date = $('#filter_finish_date').val();
            var order_id    = $('#filter_order_id').val() ? $('#filter_order_id').val() : '';
            var status      = $('#filter_status').val();
            var build_query = 'param=' + param + '&start_date=' + start_date + '&finish_date=' + finish_date + '&order_id=' + order_id + '&status=' + status;

            return window.location.href = '{{ url("download/excel") }}' + '?' + build_query;
        }
    </script>
