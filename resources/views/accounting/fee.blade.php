<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Biaya - Biaya</h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#form_modal" onclick="reset()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Tambah Data</button>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="form-group">
                                <label>Filter Tanggal :</label>
                                <div class="input-group">
                                    <input type="date" name="filter_start_date" id="filter_start_date" max="{{ date('Y-m-d') }}" class="form-control">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">s/d</span>
                                    </div>
                                    <input type="date" name="filter_finish_date" id="filter_finish_date" max="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                            </div>
                            <div class="text-right form-group">
                                <button type="button" class="btn btn-secondary" onclick="reset()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg>&nbsp;Reset</button>
                                <button type="button" class="btn btn-success" onclick="loadDataTable()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>&nbsp;Filter</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable_serverside" class="table table-bordered table-hover nowrap" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Lampiran</th>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>User</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Keterangan</th>
                                    <th>Total</th>
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
                    <h5 class="modal-title">Form Biaya</h5>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Keterangan :</label>
                                        <textarea name="description" id="description" class="form-control" style="resize:none;" placeholder="Masukan keterangan"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal :</label>
                                        <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Nominal :</label>
                                        <input type="number" name="total" id="total" class="form-control" placeholder="Masukan nominal">
                                    </div>
                                    <div class="form-group">
                                        <label>Debit :</label>
                                        <select name="coa_debit" id="coa_debit" class="form-control">
                                            <option value="">-- Pilih --</option>
                                            @foreach($coa as $c)
                                                <optgroup label="{{ $c->code }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $c->name }}">
                                                    @foreach($c->sub->where('status', 1) as $s)
                                                        <option value="{{ $s->code }}">
                                                            {{ $s->code }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $s->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Kredit :</label>
                                        <select name="coa_credit" id="coa_credit" class="form-control">
                                            <option value="">-- Pilih --</option>
                                            @foreach($coa as $c)
                                                <optgroup label="{{ $c->code }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $c->name }}">
                                                    @foreach($c->sub->where('status', 1) as $s)
                                                        <option value="{{ $s->code }}">
                                                            {{ $s->code }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $s->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis :</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">-- Pilih --</option>
                                            <option value="1">BKK</option>
                                            <option value="2">BKM</option>
                                            <option value="3">BBM</option>
                                            <option value="4">BBK</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Lampiran :</label>
                                        <input type="file" name="attachment" id="attachment" class="form-control" onchange="previewImage(this, '#preview_attachment img', '#preview_attachment')">
                                    </div>
                                    <div class="form-group">
                                        <center>
                                            <a href="{{ asset("website/empty.png") }}" id="preview_attachment" data-lightbox="Lampiran" data-title="Lampiran"><img src="{{ asset("website/empty.png") }}" style="max-width:525px; max-height:285px;" class="img-thumbnail img-fluid"></a>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </p>
                </div>
                <div class="modal-footer md-button">
                    <button type="button" class="btn btn-info" id="btn_create" onclick="create()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Tambah</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            loadDataTable();
        });

        function reset() {
            $('#form_data').trigger('reset');
            $('#validation_alert').hide();
            $('#validation_content').html('');
            $('#preview_attachment').attr('href', '{{ asset("website/empty.png") }}');
            $('#preview_attachment img').attr('src', '{{ asset("website/empty.png") }}');
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
                    url: '{{ url("accounting/fee/datatable") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        start_date: $('#filter_start_date').val(),
                        finish_date: $('#filter_finish_date').val()
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
                    { name: 'attachment', searchable: false, className: 'text-center align-middle' },
                    { name: 'created_at', searchable: false, className: 'text-center align-middle' },
                    { name: 'code', className: 'text-center align-middle' },
                    { name: 'user_id', className: 'text-center align-middle' },
                    { name: 'coa_debit', className: 'text-center align-middle' },
                    { name: 'coa_credit', className: 'text-center align-middle' },
                    { name: 'description', className: 'text-center align-middle' },
                    { name: 'total', searchable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
            });
        }

        function create() {
            $.ajax({
                url: '{{ url("accounting/fee/create") }}',
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
                        url: '{{ url("accounting/fee/destroy") }}',
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
