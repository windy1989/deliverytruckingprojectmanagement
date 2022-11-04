<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Data Harga Per Tujuan</h5>
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
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Harga Per Tujuan</h5>
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
                                    <a class="nav-link nav-link-tab" id="justify-price-tab" data-toggle="tab" href="#justify-price" role="tab" aria-controls="justify-price" aria-selected="false">Harga</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="justifyTabContent">
                                <div class="tab-pane fade show active" id="justify-data" role="tabpanel" aria-labelledby="justify-data-tab">
                                    <p>
                                        <div class="form-group mb-0">
                                            <label>Vendor :</label>
                                            <select name="vendor_id" id="vendor_id" class="select2 form-control" style="width:100%;">
                                                @foreach($vendor as $v)
                                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Label :</label>
                                            <input type="text" name="label" id="label" class="form-control" placeholder="Masukan label">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Kota Asal :</label>
                                            <select name="city_origin" id="city_origin" class="select2 form-control" style="width:100%;">
                                                @foreach($city as $c)
                                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Kota Tujuan :</label>
                                            <select name="city_destination" id="city_destination" class="select2 form-control" style="width:100%;">
                                                @foreach($city as $c)
                                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="justify-price" role="tabpanel" aria-labelledby="justify-price-tab">
                                    <p class="mt-5">
                                        <div class="card bg-light mb-4">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Tanggal :</label>
                                                            <input type="date" name="temp_date" min="{{ date('Y-m-d') }}" id="temp_date" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Satuan :</label>
                                                            <select name="temp_unit_id" id="temp_unit_id" class="form-control form-control-sm">
                                                                <option value="">-- Pilih --</option>
                                                                @foreach($unit as $u)
                                                                    <option value="{{ $u->id }};{{ $u->name }}">{{ $u->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Harga Vendor :</label>
                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Rp</span>
                                                                </div>
                                                                <input type="number" name="temp_price_vendor" id="temp_price_vendor" class="form-control form-control-sm" placeholder="Harga vendor">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Harga Customer :</label>
                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Rp</span>
                                                                </div>
                                                                <input type="number" name="temp_price_customer" id="temp_price_customer" class="form-control form-control-sm" placeholder="Harga customer">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group text-right mb-0">
                                                            <button type="button" class="btn btn-success btn-sm" onclick="addPrice()">Add</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="width:100%;">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Tanggal</th>
                                                            <th>Satuan</th>
                                                            <th>Harga Vendor</th>
                                                            <th>Harga Customer</th>
                                                            <th>Hapus</th>
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

            $('#data_price').on('click', '#delete_data_price', function() {
                $(this).closest('tr').remove();
            });

            $('.select2').select2({
                placeholder: '-- Pilih --',
                dropdownParent: $('#form_modal')
            });
        });

        function addPrice() {
            var temp_date           = $('#temp_date');
            var temp_unit_id        = $('#temp_unit_id');
            var temp_price_vendor   = $('#temp_price_vendor');
            var temp_price_customer = $('#temp_price_customer');

            if(temp_date.val() && temp_price_vendor.val() && temp_price_customer.val()) {
                var	temp_price_vendor_rp = temp_price_vendor.val().toString();
                var splitRP	             = temp_price_vendor_rp.split('.');
                var gt                   = splitRP[0].toString().split('').reverse().join('');

                ribuan = gt.match(/\d{1,3}/g);
                ribuan = ribuan.join('.').split('').reverse().join('');

                if(splitRP[1] == undefined) {
                    rupiah = ribuan;
                } else {
                    rupiah = ribuan + ',' + splitRP[1];
                }


                var	temp_price_customer_rp = temp_price_customer.val().toString();
                var splitRP1               = temp_price_customer_rp.split('.');
                var gt1                    = splitRP1[0].toString().split('').reverse().join('');

                ribuan1 = gt1.match(/\d{1,3}/g);
                ribuan1 = ribuan1.join('.').split('').reverse().join('');

                if(splitRP1[1]==undefined) {
                    rupiah1 = ribuan1;
                } else {
                    rupiah1 = ribuan1 + ',' + splitRP1[1];
                }

                var unit      = temp_unit_id.val().split(';');
                var unit_id   = unit[0];
                var unit_name = unit[1];

                $('#data_price').append(`
                    <tr class="text-center">
                        <input type="hidden" name="date[]" value="` + temp_date.val() + `">
                        <input type="hidden" name="unit_id[]" value="` + unit_id + `">
                        <input type="hidden" name="price_vendor[]" value="` + temp_price_vendor.val() + `">
                        <input type="hidden" name="price_customer[]" value="` + temp_price_customer.val() + `">

                        <td>` + temp_date.val() + `</td>
                        <td>` + unit_name + `</td>
                        <td> Rp.` + rupiah + `</td>
                        <td> Rp.` + rupiah1 + `</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" id="delete_data_price">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </td>
                    </tr>
                `);

                temp_date.val(null);
                temp_unit_id.val(null);
                temp_price_vendor.val(null);
                temp_price_customer.val(null);
            } else {
                swal('Harap mengisi semua field!', '', 'info');
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
            $('#vendor_id').val(null).trigger('change');
            $('#city_origin').val(null).trigger('change');
            $('#city_destination').val(null).trigger('change');
            $('.tab-pane').removeClass('show active');
            $('#justify-data').addClass('show active');
            $('.nav-link-tab').removeClass('active');
            $('#justify-data-tab').addClass('active');
            $('#temp_date').val(null);
            $('#temp_unit_id').val(null);
            $('#temp_price_vendor').val(null);
            $('#temp_price_customer').val(null);
            $('#data_price').html('');
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
                    url: '{{ url("master_data/destination/datatable") }}',
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

        function create() {
            $.ajax({
                url: '{{ url("master_data/destination/create") }}',
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
                url: '{{ url("master_data/destination/show") }}',
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
                                <input type="hidden" name="date[]" value="` + val.date + `">
                                <input type="hidden" name="unit_id[]" value="` + val.unit_id + `">
                                <input type="hidden" name="price_vendor[]" value="` + val.price_vendor + `">
                                <input type="hidden" name="price_customer[]" value="` + val.price_customer + `">

                                <td>` + val.date + `</td>
                                <td>` + val.unit_name + `</td>
                                <td>` + val.price_vendor + `</td>
                                <td>` + val.price_customer + `</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" id="delete_data_price">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    $('#vendor_id').val(response.vendor_id).trigger('change');
                    $('#label').val(response.label);
                    $('#city_origin').val(response.city_origin).trigger('change');
                    $('#city_destination').val(response.city_destination).trigger('change');
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
                url: '{{ url("master_data/destination/update") }}' + '/' + id,
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
                        url: '{{ url("master_data/destination/destroy") }}',
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
