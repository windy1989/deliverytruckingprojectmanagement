<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Laporan Sopir</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-primary" onclick="downloadExcel()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>&nbsp;Download Excel</button>
                            </div>
                        </div>
                    </div>
                    <table id="datatable_serverside" class="table table-bordered table-hover display nowrap" style="width:100%">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Kota</th>
                                <th>Vendor</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="form_modal" class="modal animated  fadeInUp" data-backdrop="static" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Sopir</h5>
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
                                    <a class="nav-link nav-link-tab" id="justify-sim-tab" data-toggle="tab" href="#justify-sim" role="tab" aria-controls="justify-sim" aria-selected="false">SIM</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-tab" id="justify-ktp-tab" data-toggle="tab" href="#justify-ktp" role="tab" aria-controls="justify-ktp" aria-selected="false">KTP</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="justifyTabContent">
                                <div class="tab-pane fade show active" id="justify-data" role="tabpanel" aria-labelledby="justify-data-tab">
                                    <p>
                                        <div class="form-group">
                                            <label>Foto :</label><br>
                                            <a href="{{ asset("website/empty.png") }}" id="preview_photo" data-lightbox="Foto" data-title="Preview Foto"><img src="{{ asset("website/empty.png") }}" class="img-fluid" style="width:100px; height:75px;"></a>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama :</label>
                                            <input type="text" name="name" id="name" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Vendor :</label>
                                            <input type="text" name="vendor" id="vendor" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Kota :</label>
                                            <input type="text" name="city" id="city" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat :</label>
                                            <textarea name="address" id="address" class="form-control" style="resize:none;" readonly></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Status :</label>
                                            <input type="text" name="status" id="status" class="form-control" readonly>
                                        </div>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="justify-sim" role="tabpanel" aria-labelledby="justify-sim-tab">
                                    <p>
                                        <div class="form-group">
                                            <label>Jenis :</label>
                                            <input type="text" name="type_driving_licence" id="type_driving_licence" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>No :</label>
                                            <input type="text" name="no_driving_licence" id="no_driving_licence" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Masa Berlaku :</label>
                                            <input type="text" name="valid_driving_licence" id="valid_driving_licence" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Foto :</label><br>
                                            <a href="{{ asset("website/empty.png") }}" id="preview_photo_driving_licence" data-lightbox="Foto SIM" data-title="Preview Foto SIM"><img src="{{ asset("website/empty.png") }}" style="width:100px; height:75px;" class="img-fluid"></a>
                                        </div>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="justify-ktp" role="tabpanel" aria-labelledby="justify-ktp-tab">
                                    <p>
                                        <div class="form-group">
                                            <label>No :</label>
                                            <input type="text" name="no_identity_card" id="no_identity_card" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Foto :</label><br>
                                            <a href="{{ asset("website/empty.png") }}" id="preview_photo_identity_card" data-lightbox="Foto KTP" data-title="Preview Foto KTP"><img src="{{ asset("website/empty.png") }}" style="width:100px; height:75px;" class="img-fluid"></a>
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
                order: [[0, 'asc']],
                iDisplayInLength: 10,
                scrollX: true,
                ajax: {
                    url: '{{ url("report/driver/datatable") }}',
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
                    { name: 'photo', searchable: false, className: 'text-center align-middle' },
                    { name: 'name', className: 'text-center align-middle' },
                    { name: 'city_id', className: 'text-center align-middle' },
                    { name: 'vendor_id', className: 'text-center align-middle' },
                    { name: 'status', searchable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
            });
        }

        function show(id) {
            $('#form_modal').modal('show');
            $.ajax({
                url: '{{ url("report/driver/show") }}',
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
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    $('#city').val(response.city);
                    $('#vendor').val(response.vendor);
                    $('#preview_photo').attr('href', response.photo);
                    $('#preview_photo img').attr('src', response.photo);
                    $('#name').val(response.name);
                    $('#preview_photo_identity_card').attr('href', response.photo_identity_card);
                    $('#preview_photo_identity_card img').attr('src', response.photo_identity_card);
                    $('#no_identity_card').val(response.no_identity_card);
                    $('#preview_photo_driving_licence').attr('href', response.photo_driving_licence);
                    $('#preview_photo_driving_licence img').attr('src', response.photo_driving_licence);
                    $('#no_driving_licence').val(response.no_driving_licence);
                    $('#type_driving_licence').val(response.type_driving_licence);
                    $('#valid_driving_licence').val(response.valid_driving_licence);
                    $('#address').val(response.address);
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
            var param       = 'report_driver';
            var build_query = 'param=' + param;

            return window.location.href = '{{ url("download/excel") }}' + '?' + build_query;
        }
    </script>
