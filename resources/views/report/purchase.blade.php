<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Laporan Pembelian</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-primary" onclick="downloadExcel()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>&nbsp;Download Excel</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-12">
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
                            <div class="form-group">
                                <label>Vendor :</label>
                                <select name="filter_vendor_id" id="filter_vendor_id" class="select2 form-control" style="width:100%;">
                                    <option value="">-- Pilih --</option>
                                    @foreach($vendor as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <div class="row no-gutters">
                                        <div class="col-md-4">
                                            <div class="form-group text-center">
                                                <div class="form-check">
                                                    <input type="radio" name="filter_radio" id="filter_paid" class="form-check-input" value="paid">
                                                    <label class="form-check-label" for="filter_paid">Lunas</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group text-center">
                                                <div class="form-check">
                                                    <input type="radio" name="filter_radio" id="filter_no_paid" class="form-check-input" value="no_paid">
                                                    <label class="form-check-label" for="filter_no_paid">Belum Dibayar</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group text-center">
                                                <div class="form-check">
                                                    <input type="radio" name="filter_radio" id="filter_all" class="form-check-input" value="" checked>
                                                    <label class="form-check-label" for="filter_all">Semua</label>
                                                </div>
                                            </div>
                                        </div>
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
                                    <th>Vendor</th>
                                    <th>Total</th>
                                    <th>Terbayar</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            loadDataTable();
        });

        function reset() {
            $('#filter_start_date').val('');
            $('#filter_finish_date').val('');
            $('#filter_vendor_id').val(null).trigger('change');
            $('input[name="filter_radio"][value=""]').prop('checked', true);
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
                    url: '{{ url("report/purchase/datatable") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        start_date: $('#filter_start_date').val(),
                        finish_date: $('#filter_finish_date').val(),
                        vendor_id: $('#filter_vendor_id').val(),
                        radio: $('input[name="filter_radio"]:checked').val()
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
                    { name: 'vendor_id', className: 'text-center align-middle' },
                    { name: 'total', searchable: false, className: 'text-center align-middle' },
                    { name: 'paid_off', searchable: false, className: 'text-center align-middle' },
                    { name: 'created_at', searchable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
            });
        }

        function downloadExcel() {
            var param       = 'report_purchase';
            var start_date  = $('#filter_start_date').val();
            var finish_date = $('#filter_finish_date').val();
            var vendor_id   = $('#filter_vendor_id').val();
            var radio       = $('input[name="filter_radio"]:checked').val();
            var build_query = 'param=' + param + '&start_date=' + start_date + '&finish_date=' + finish_date + '&vendor_id=' + vendor_id + '&radio=' + radio;

            return window.location.href = '{{ url("download/excel") }}' + '?' + build_query;
        }
    </script>
