<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mt-2">Recap</h5>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-primary" onclick="detail()" id="btn_detail"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-info">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg> &nbsp;Recap</button>

                                </svg>&nbsp;</button>
                                <button type="button" class="btn btn-primary" onclick="detailVendor()"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-info">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg> &nbsp;Recap (Vendor)</button>

                                </svg>&nbsp;</button>

                            </div>
                        </div>

                        <div class="col-md-12 mt-5">

                            <div id="errorAlert">

                            </div>


                            <div class="form-group">
                                <label>Tanggal Order:</label>
                                <div class="input-group">
                                    {{-- <input type="date" name="order_date" id="order_date" max="{{ date('Y-m-d') }}"
                                        class="form-control"> --}}
                                    <input type="date" name="start_date" id="start_date" max="{{ date('Y-m-d') }}"
                                        class="form-control">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">s/d</span>
                                    </div>
                                    <input type="date" name="finish_date" id="finish_date" max="{{ date('Y-m-d') }}"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label>Vendor :</label>
                                            <select name="filter_vendor_id" id="filter_vendor_id"
                                                class="select2 form-control" style="width:100%;">
                                                <option value="">-- Pilih --</option>
                                                @foreach($vendor as $v)
                                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Customer :</label>
                                            <select name="filter_customer_id" id="filter_customer_id"
                                                class="select2 form-control" style="width:100%;">
                                                <option value="">-- Pilih --</option>
                                                @foreach($customer as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Invoice :</label>
                                            <select name="filter_invoice" id="filter_invoice"
                                                class="select2 form-control" style="width:100%;">
                                                <option value="">-- Pilih --</option>
                                                <option value="yes">Ada</option>
                                                <option value="no">Tidak</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <hr class="bg-success">
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-secondary" onclick="reset()"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-refresh-ccw">
                                    <polyline points="1 4 1 10 7 10"></polyline>
                                    <polyline points="23 20 23 14 17 14"></polyline>
                                    <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15">
                                    </path>
                                </svg>&nbsp;Reset</button>
                            <button type="button" class="btn btn-success" onclick="loadDataTable()"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-filter">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>&nbsp;Filter</button>
                            </svg>&nbsp;</button>

                            <button type="button" class="btn btn-success" onclick="print()"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-printer">
                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                    <path
                                        d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                    </path>
                                    <rect x="6" y="14" width="12" height="8"></rect>
                                </svg>&nbsp;</button>
                            <button type="button" class="btn btn-success" onclick="printVendor()"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-printer">
                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                    <path
                                        d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                    </path>
                                    <rect x="6" y="14" width="12" height="8"></rect>
                                </svg>&nbsp;Vendor</button>

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable_serverside" class="table table-bordered table-hover nowrap"
                            style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Order</th>
                                    <th>Tanggal</th>
                                    <th>Nomor</th>
                                    <th>Customer</th>
                                    <th>Vendor</th>

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

            $('.select2').select2({
                placeholder: '-- Pilih --'
            });
         
            select2ServerSide('#filter_order_id', '{{ url("select2_serverside/order") }}');
        }); 
      

        function reset() {
            $('#start_date').val('');
            $('#finish_date').val('');
            $('#order_date').val('');
            $('#filter_vendor_id').val(null).trigger('change');
            $('#filter_customer_id').val(null).trigger('change');
            $('#filter_invoice').val(null).trigger('change');
            loadDataTable();
        }

        function loadDataTable() {
            $('#datatable_serverside').DataTable({
                serverSide: true,
                deferRender: true,
                destroy: true,
                order: [[1, 'asc']],
                iDisplayInLength: 10,
                scrollX: true,
                ajax: {
                    url: '{{ url("report/recap/datatable") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        start_date: $('#start_date').val(),
                        finish_date: $('#finish_date').val(),
                        vendor_id: $('#filter_vendor_id').val(),
                        customer_id: $('#filter_customer_id').val(),
                        hasInvoice: $('#filter_invoice').val()
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
                    { name: 'order_id', searchable: false, className: 'text-center align-middle'},
                    { name: 'number', className: 'text-center align-middle' },
                    { name: 'customer_id', orderable: false, className: 'text-center align-middle' },
                    { name: 'vendor_id', orderable: false, className: 'text-center align-middle' },
                  
                ]
            });
        }

        function detail() {
            if(!$('#start_date').val()){
                $('#errorAlert').append(
                    ` <div class="alert alert-danger alert-dismissible fade show" role="alert"
                        id="warning-alert">
                        <strong><i class="bi bi-info-circle"></i> Warning!</strong> tanggal order harus diisi
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                     </div>`
                );
            }else{
            var start_date = $("#start_date").val();
            var finish_date = $("#finish_date").val();
            var vendor_id = $("#filter_vendor_id").val();
            var customer_id = $("#filter_customer_id").val();
            var hasInvoice = $("#filter_invoice").val();
            var build_query = '&start_date=' + start_date + '&finish_date=' + finish_date + '&vendor_id=' + vendor_id + '&customer_id=' + customer_id + '&hasInvoice=' + hasInvoice;
            return window.location.href = '{{ url("report/recap/detail") }}' + '?' + build_query;
            
        }
        }

        function detailVendor() {
            if(!$('#start_date').val()){
                $('#errorAlert').append(
                    ` <div class="alert alert-danger alert-dismissible fade show" role="alert"
                        id="warning-alert">
                            <strong><i class="bi bi-info-circle"></i> Warning!</strong> tanggal order harus diisi
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                     </div>`
                );
            }else{
            var start_date = $("#start_date").val();
            var finish_date = $("#finish_date").val();
            var vendor_id = $("#filter_vendor_id").val();
            var customer_id = $("#filter_customer_id").val();
            var hasInvoice = $("#filter_invoice").val();
            var build_query = '&start_date=' + start_date + '&finish_date=' + finish_date + '&vendor_id=' + vendor_id + '&customer_id=' + customer_id + '&hasInvoice=' + hasInvoice;
            return window.location.href = '{{ url("report/recap/detail_vendor") }}' + '?' + build_query;
            }
        }


        function print() {
            if(!$('#start_date').val()){
                $('#errorAlert').append(
                    ` <div class="alert alert-danger alert-dismissible fade show" role="alert"
                        id="warning-alert">
                            <strong><i class="bi bi-info-circle"></i> Warning!</strong> tanggal order harus diisi
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                     </div>`
                );
            }else{
            var start_date = $("#start_date").val();
            var finish_date = $("#finish_date").val();
            var vendor_id = $("#filter_vendor_id").val();
            var customer_id = $("#filter_customer_id").val();
            var hasInvoice = $("#filter_invoice").val();
            var build_query = '&start_date=' + start_date + '&finish_date=' + finish_date + '&vendor_id=' + vendor_id + '&customer_id=' + customer_id + '&hasInvoice=' + hasInvoice;
            return window.location.href = '{{ url("report/recap/print") }}' + '?' + build_query;
            }
        }

        function printVendor() {
            if(!$('#start_date').val()){
                $('#errorAlert').append(
                    ` <div class="alert alert-danger alert-dismissible fade show" role="alert"
                        id="warning-alert">
                            <strong><i class="bi bi-info-circle"></i> Warning!</strong> tanggal order harus diisi
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                     </div>`
                );
            }else{
            var start_date = $("#start_date").val();
            var finish_date = $("#finish_date").val();
            var vendor_id = $("#filter_vendor_id").val();
            var customer_id = $("#filter_customer_id").val();
            var hasInvoice = $("#filter_invoice").val();
            var build_query = '&start_date=' + start_date + '&finish_date=' + finish_date + '&vendor_id=' + vendor_id + '&customer_id=' + customer_id + '&hasInvoice=' + hasInvoice;
            return window.location.href = '{{ url("report/recap/print_vendor") }}' + '?' + build_query;
            }
        }

    </script>