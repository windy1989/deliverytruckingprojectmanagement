<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Daftar Data Menu</h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#form_modal" onclick="cancel()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Tambah Data</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable_serverside" class="table table-bordered table-hover nowrap" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Url</th>
                                    <th>Icon</th>
                                    <th>Urutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="form_modal" class="modal animated fadeInUp" data-backdrop="static" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Menu</h5>
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
                                        <label>Nama :</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukan nama">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Icon :</label>
                                        <input type="text" name="icon" id="icon" class="form-control" placeholder="Masukan icon">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Url :</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ url('/') }}/</span>
                                    </div>
                                    <input type="text" name="url" id="url" class="form-control" placeholder="Masukan url">
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label>Parent :</label>
                                <select name="parent_id" id="parent_id" class="select2 form-control"></select>
                            </div>
                            <div class="form-group">
                                <label>Urutan :</label>
                                <input type="number" name="order" id="order" class="form-control" placeholder="Masukan urutan">
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
            $('.select2').select2({
                dropdownParent: $('#form_modal')
            });

            var table = $('#datatable_serverside').DataTable({
                serverSide: true,
                deferRender: true,
                destroy: true,
                order: [[4, 'asc']],
                iDisplayInLength: 10,
                scrollX: true,
                ajax: {
                    url: '{{ url("setting/menu/datatable") }}',
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
                    { name: null, orderable: false, searchable: false, className: 'text-center align-middle details-control' },
                    { name: 'id', searchable: false, className: 'text-center align-middle' },
                    { name: 'name', className: 'text-center align-middle' },
                    { name: 'url', className: 'text-center align-middle' },
                    { name: 'icon', searchable: false, className: 'text-center align-middle' },
                    { name: 'order', orderable: false, searchable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
            });

            $('#datatable_serverside tbody').on('click', 'td.details-control', function() {
                var tr    = $(this).closest('tr');
                var badge = tr.find('span.badge');
                var icon  = tr.find('i.fas');
                var row   = table.row(tr);

                if(row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    badge.first().removeClass('badge-danger');
                    badge.first().addClass('badge-success');
                    icon.first().removeClass('fa-minus');
                    icon.first().addClass('fa-plus');
                } else {
                    row.child(submenu(row.data())).show();
                    tr.addClass('shown');
                    badge.first().removeClass('badge-success');
                    badge.first().addClass('badge-danger');
                    icon.first().removeClass('fa-plus');
                    icon.first().addClass('fa-minus');
                }
            });
        });

        function submenu(data) {
            var tbody = '';

            $.ajax({
                url: '{{ url("setting/menu/get_sub") }}',
                type: 'POST',
                async: false,
                data: {
                    name: data[2]
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.length > 0) {
                        $.each(response, function(i, val) {
                            tbody += `
                                <tr class="text-center">
                                    <td class="align-middle">` + val.name + `</td>
                                    <td class="align-middle">` + val.url + `</td>
                                    <td class="align-middle">` + val.order + `</td>
                                    <td class="align-middle">` + val.action + `</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody += `
                            <tr class="text-center">
                                <td class="align-middle" colspan="4">Tidak ada sub</td>
                            </tr>
                        `;
                    }
                },
                error: function() {
                    notif('Server Error!', '#DC3545');
                }
            });

            return `
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <tr class="text-center">
                                <td>Nama</td>
                                <td>Url</td>
                                <td>Urutan</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody>` + tbody + `</tbody>
                    </table>
                </div>
            `;
        }

        function getParent() {
            $.ajax({
                url: '{{ url("setting/menu/get_parent") }}',
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    loadingOpen('.modal-content');
                    $('#parent_id').html('<option value="0">Parent</option>');
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    $.each(response, function(i, val) {
                        $('#parent_id').append(`
                            <option value="` + val.id + `">` + val.name + `</option>
                        `);
                    });
                },
                error: function() {
                    cancel();
                    loadingClose('.modal-content');
                    notif('Server Error!', '#DC3545');
                }
            });
        }

        function cancel() {
            reset();
            $('#form_modal').modal('hide');
            $('#btn_create').show();
            $('#btn_update').hide();
            $('#btn_cancel').hide();
        }

        function toShow() {
            reset();
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
            getParent();
        }

        function success() {
            reset();
            $('#form_modal').modal('hide');
            $('#datatable_serverside').DataTable().ajax.reload(null, false);
        }

        function create() {
            $.ajax({
                url: '{{ url("setting/menu/create") }}',
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
                url: '{{ url("setting/menu/show") }}',
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
                    $('#name').val(response.name);
                    $('#url').val(response.url);
                    $('#icon').val(response.icon);
                    $('#parent_id').val(response.parent_id).trigger('change');
                    $('#order').val(response.order);
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
                url: '{{ url("setting/menu/update") }}' + '/' + id,
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
                        url: '{{ url("setting/menu/destroy") }}',
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
                                getParent();
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
