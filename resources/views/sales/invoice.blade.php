<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Data Invoice</h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ url('sales/invoice/create') }}" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Generate Invoice Baru</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable_serverside" class="table table-bordered table-hover nowrap" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>DP</th>
                                    <th>Grandtotal</th>
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

        function loadDataTable() {
            $('#datatable_serverside').DataTable({
                serverSide: true,
                deferRender: true,
                destroy: true,
                order: [[0, 'asc']],
                iDisplayInLength: 10,
                scrollX: true,
                ajax: {
                    url: '{{ url("sales/invoice/datatable") }}',
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
                    { name: 'customer_id', className: 'text-center align-middle' },
                    { name: 'down_payment', searchable: false, className: 'text-center align-middle' },
                    { name: 'grandtotal', searchable: false, className: 'text-center align-middle' },
                    { name: 'created_at', searchable: false, className: 'text-center align-middle' },
                    { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
                ]
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
                        url: '{{ url("sales/invoice/destroy") }}',
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
