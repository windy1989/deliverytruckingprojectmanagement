<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Data Sopir</h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#form_modal" onclick="cancel()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Tambah Data</button>
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
                    <h5 class="modal-title">Form Sopir</h5>
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
                                    <a class="nav-link nav-link-tab" id="justify-sim-tab" data-toggle="tab" href="#justify-sim" role="tab" aria-controls="justify-sim" aria-selected="false">SIM</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-tab" id="justify-ktp-tab" data-toggle="tab" href="#justify-ktp" role="tab" aria-controls="justify-ktp" aria-selected="false">KTP</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="justifyTabContent">
                                <div class="tab-pane fade show active" id="justify-data" role="tabpanel" aria-labelledby="justify-data-tab">
                                    <p>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label>Foto :</label>
                                                    <input type="file" name="photo" id="photo" class="form-control" onchange="previewImage(this, '#preview_photo img', '#preview_photo')">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <a href="{{ asset("website/empty.png") }}" id="preview_photo" data-lightbox="Foto" data-title="Preview Foto"><img src="{{ asset("website/empty.png") }}" class="img-fluid" style="width:100px; height:75px;"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama :</label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Masukan nama lengkap">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Vendor :</label>
                                            <select name="vendor_id" id="vendor_id" class="form-control" style="width:100%;"></select>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Kota :</label>
                                            <select name="city_id" id="city_id" class="form-control" style="width:100%;"></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat :</label>
                                            <textarea name="address" id="address" class="form-control" placeholder="Masukan alamat lengkap" style="resize:none;"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Status :</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">-- Pilih --</option>
                                                <option value="1">Aktif</option>
                                                <option value="2">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="justify-sim" role="tabpanel" aria-labelledby="justify-sim-tab">
                                    <p>
                                        <div class="form-group">
                                            <label>Jenis :</label>
                                            <select name="type_driving_licence" id="type_driving_licence" class="form-control">
                                                <option value="">-- Pilih --</option>
                                                <option value="Sim A">Sim A</option>
                                                <option value="Sim A Umum">Sim A Umum</option>
                                                <option value="Sim B1">Sim B1</option>
                                                <option value="Sim B1 Umum">Sim B1 Umum</option>
                                                <option value="Sim B2">Sim B2</option>
                                                <option value="Sim B2 Umum">Sim B2 Umum</option>
                                                <option value="Sim C">Sim C</option>
                                                <option value="Sim C1">Sim C1</option>
                                                <option value="Sim C2">Sim C2</option>
                                                <option value="Sim D">Sim D</option>
                                                <option value="Sim D1">Sim D1</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>No :</label>
                                            <input type="text" name="no_driving_licence" id="no_driving_licence" class="form-control" placeholder="Masukan no SIM">
                                        </div>
                                        <div class="form-group">
                                            <label>Masa Berlaku :</label>
                                            <input type="date" name="valid_driving_licence" id="valid_driving_licence" class="form-control">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label>Foto :</label>
                                                    <input type="file" name="photo_driving_licence" id="photo_driving_licence" class="form-control" onchange="previewImage(this, '#preview_photo_driving_licence img', '#preview_photo_driving_licence')">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <a href="{{ asset("website/empty.png") }}" id="preview_photo_driving_licence" data-lightbox="Foto SIM" data-title="Preview Foto SIM"><img src="{{ asset("website/empty.png") }}" style="width:100px; height:75px;" class="img-fluid"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="justify-ktp" role="tabpanel" aria-labelledby="justify-ktp-tab">
                                    <p>
                                        <div class="form-group">
                                            <label>No :</label>
                                            <input type="text" name="no_identity_card" id="no_identity_card" class="form-control" placeholder="Masukan no KTP">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label>Foto :</label>
                                                    <input type="file" name="photo_identity_card" id="photo_identity_card" class="form-control" onchange="previewImage(this, '#preview_photo_identity_card img', '#preview_photo_identity_card')">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <a href="{{ asset("website/empty.png") }}" id="preview_photo_identity_card" data-lightbox="Foto KTP" data-title="Preview Foto KTP"><img src="{{ asset("website/empty.png") }}" style="width:100px; height:75px;" class="img-fluid"></a>
                                                </div>
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
            $('#no_identity_card').inputmask({mask: '9999999999999999'});
            $('#no_driving_licence').inputmask({mask: '9999999999999'});
            select2ServerSide('#vendor_id', '{{ url("select2_serverside/vendor") }}', '#form_modal');
            select2ServerSide('#city_id', '{{ url("select2_serverside/city") }}', '#form_modal');
            loadDataTable();
        });

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
            $('#city_id').val(null).trigger('change');
            $('#vendor_id').val(null).trigger('change');
            $('#validation_alert').hide();
            $('#validation_content').html('');
            $('#preview_photo').attr('href', '{{ asset("website/empty.png") }}');
            $('#preview_photo img').attr('src', '{{ asset("website/empty.png") }}');
            $('#preview_photo_driving_licence').attr('href', '{{ asset("website/empty.png") }}');
            $('#preview_photo_driving_licence img').attr('src', '{{ asset("website/empty.png") }}');
            $('#preview_photo_identity_card').attr('href', '{{ asset("website/empty.png") }}');
            $('#preview_photo_identity_card img').attr('src', '{{ asset("website/empty.png") }}');
            $('.tab-pane').removeClass('show active');
            $('#justify-data').addClass('show active');
            $('.nav-link-tab').removeClass('active');
            $('#justify-data-tab').addClass('active');
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
                order: [[0, 'asc']],
                iDisplayInLength: 10,
                scrollX: true,
                ajax: {
                    url: '{{ url("master_data/driver/datatable") }}',
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

        function create() {
            $.ajax({
                url: '{{ url("master_data/driver/create") }}',
                type: 'POST',
                dataType: 'JSON',
                data: new FormData($('#form_data')[0]),
                processData: false,
                contentType: false,
                cache: false,
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
                url: '{{ url("master_data/driver/show") }}',
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
                    $('#city_id').append(`<option value="` + response.city_id + `" selected>` + response.city_name + `</option>`);
                    $('#vendor_id').append(`<option value="` + response.vendor_id + `" selected>` + response.vendor_name + `</option>`);
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
                url: '{{ url("master_data/driver/update") }}' + '/' + id,
                type: 'POST',
                dataType: 'JSON',
                data: new FormData($('#form_data')[0]),
                processData: false,
                contentType: false,
                cache: false,
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
                        url: '{{ url("master_data/driver/destroy") }}',
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
