<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Laporan Customer</h5>
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
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Telp</th>
                                    <th>Kota</th>
                                    <th>PIC</th>
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
                    <h5 class="modal-title">Detail Customer</h5>
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
                                    <a class="nav-link nav-link-tab" id="justify-bill-tab" data-toggle="tab" href="#justify-bill" role="tab" aria-controls="justify-bill" aria-selected="false">Rekening</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="justifyTabContent">
                                <div class="tab-pane fade show active" id="justify-data" role="tabpanel" aria-labelledby="justify-data-tab">
                                    <div class="form-group">
                                        <label>Kode :</label>
                                        <input type="text" name="code" id="code" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama :</label>
                                        <input type="text" name="name" id="name" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>PIC :</label>
                                        <input type="text" name="pic" id="pic" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Kota :</label>
                                        <input type="text" name="city" id="city" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>No Telp :</label>
                                        <input type="text" name="phone" id="phone" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Fax :</label>
                                        <input type="text" name="fax" id="fax" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat :</label>
                                        <textarea name="address" id="address" class="form-control" style="resize:none;" readonly></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Website :</label>
                                        <input type="url" name="website" id="website" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Status :</label>
                                        <input type="text" name="status" id="status" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="justify-bill" role="tabpanel" aria-labelledby="justify-bill-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="width:100%;">
                                            <thead>
                                                <tr class="text-center">
                                                    <td>Bank</td>
                                                    <td>No Rekening</td>
                                                </tr>
                                            </thead>
                                            <tbody id="data_bill"></tbody>
                                        </table>
                                    </div>
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
                order: [[0, 'asc']],
                iDisplayInLength: 10,
                scrollX: true,
                ajax: {
                    url: '{{ url("report/customer/datatable") }}',
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
                        notif('Server Error!', '#DC3545');
                        loadingClose('#datatable_serverside');
                    }
                },
                columns: [
                    { name: 'id', searchable: false, className: 'text-center align-middle' },
                    { name: 'code', className: 'text-center align-middle' },
                    { name: 'name', className: 'text-center align-middle' },
                    { name: 'phone', searchable: false, className: 'text-center align-middle' },
                    { name: 'city_id', className: 'text-center align-middle' },
                    { name: 'pic', className: 'text-center align-middle' },
                    { name: 'status', searchable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
            });
        }

        function show(id) {
            $('#form_modal').modal('show');
            $.ajax({
                url: '{{ url("report/customer/show") }}',
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
                    $('#data_bill').html('');
                },
                success: function(response) {
                    loadingClose('.modal-content');

                    $.each(response.bill, function(i, val) {
                        $('#data_bill').append(`
                            <tr class="text-center">
                                <td>` + val.bank + `</td>
                                <td>` + val.bill + `</td>
                            </tr>
                        `);
                    });

                    $('#city').val(response.city);
                    $('#code').val(response.code);
                    $('#name').val(response.name);
                    $('#phone').val(response.phone);
                    $('#fax').val(response.fax);
                    $('#website').val(response.website);
                    $('#address').val(response.address);
                    $('#pic').val(response.pic);
                    $('#status').val(response.status);
                },
                error: function() {
                    cancel();
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function downloadExcel() {
            var param       = 'report_customer';
            var build_query = 'param=' + param;

            return window.location.href = '{{ url("download/excel") }}' + '?' + build_query;
        }
    </script>
