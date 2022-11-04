<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Laporan Harga Per Tujuan</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-primary" onclick="downloadExcel()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>&nbsp;Download Excel</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable_serverside" class="table table-bordered table-hover nowrap" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Vendor</th>
                                    <th>Label</th>
                                    <th>Kota Asal</th>
                                    <th>Kota Tujuan</th>
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
                    <h5 class="modal-title">Detail Harga Per Tujuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-text">
                        <form id="form_data">
                            <ul class="nav nav-tabs  mb-3 mt-3 nav-fill" id="justifyTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link nav-link-tab active" id="justify-data-tab" data-toggle="tab" href="#justify-data" role="tab" aria-controls="justify-data" aria-selected="true">Data</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-tab" id="justify-price-tab" data-toggle="tab" href="#justify-price" role="tab" aria-controls="justify-price" aria-selected="false">Harga</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="justifyTabContent">
                                <div class="tab-pane fade show active" id="justify-data" role="tabpanel" aria-labelledby="justify-data-tab">
                                    <p>
                                        <div class="form-group">
                                            <label>Vendor :</label>
                                            <input type="text" name="vendor" id="vendor" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Label :</label>
                                            <input type="text" name="label" id="label" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Kota Asal :</label>
                                            <input type="text" name="city_origin" id="city_origin" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Kota Tujuan :</label>
                                            <input type="text" name="city_destination" id="city_destination" class="form-control" readonly>
                                        </div>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="justify-price" role="tabpanel" aria-labelledby="justify-price-tab">
                                    <p class="mt-5">
                                        <div class="form-group">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="width:100%;">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Tanggal</th>
                                                            <th>Satuan</th>
                                                            <th>Harga Vendor</th>
                                                            <th>Harga Customer</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data_price"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            loadDataTable();
        });

        function loadDataTable() {
            $('#datatable_serverside').DataTable({
                serverSide: true,
                deferRender: true,
                destroy: true,
                order: [[0, 'desc']],
                iDisplayInLength: 10,
                scrollX: true,
                ajax: {
                    url: '{{ url("report/destination/datatable") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        status: $('#status').val()
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
                    { name: 'vendor_id', className: 'text-center align-middle' },
                    { name: 'label', className: 'text-center align-middle' },
                    { name: 'city_origin', searchable: false, className: 'text-center align-middle' },
                    { name: 'city_destination', searchable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
            });
        }

        function show(id) {
            $('#form_modal').modal('show');
            $.ajax({
                url: '{{ url("report/destination/show") }}',
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
                    $('#data_price').html('');
                },
                success: function(response) {
                    loadingClose('.modal-content');

                    $.each(response.destination_price, function(i, val) {
                        $('#data_price').append(`
                            <tr class="text-center">
                                <td>` + val.date + `</td>
                                <td>` + val.unit + `</td>
                                <td>` + val.price_vendor + `</td>
                                <td>` + val.price_customer + `</td>
                            </tr>
                        `);
                    });

                    $('#vendor').val(response.vendor);
                    $('#label').val(response.label);
                    $('#city_origin').val(response.city_origin);
                    $('#city_destination').val(response.city_destination);
                },
                error: function() {
                    cancel();
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function downloadExcel() {
            var param       = 'report_destination';
            var build_query = 'param=' + param;

            return window.location.href = '{{ url("download/excel") }}' + '?' + build_query;
        }
    </script>
