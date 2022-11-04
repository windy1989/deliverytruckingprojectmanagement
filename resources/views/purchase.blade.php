<div id="content" class="main-content">
	<div class="layout-px-spacing">
		<div class="row layout-top-spacing" id="cancel-row">
			<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
				<div class="widget-content widget-content-area br-6">
					<div class="row mb-5">
						<div class="col-md-6">
							<h5 class="mt-2">Data Pembelian</h5>
						</div>
						<div class="col-md-6 text-right">
							<a href="{{ url('purchase/create') }}" class="btn btn-primary"><svg
									xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
									fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
									stroke-linejoin="round" class="feather feather-plus-square">
									<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
									<line x1="12" y1="8" x2="12" y2="16"></line>
									<line x1="8" y1="12" x2="16" y2="12"></line>
								</svg>&nbsp;Buat Pembelian</a>
						</div>
					</div>
					<div class="row mb-5">
						<div class="col-md-12">
							<div class="form-group">
								<label>Tanggal :</label>
								<div class="input-group">
									<input type="date" name="filter_start_date" id="filter_start_date"
										max="{{ date('Y-m-d') }}" class="form-control">
									<div class="input-group-prepend">
										<span class="input-group-text">s/d</span>
									</div>
									<input type="date" name="filter_finish_date" id="filter_finish_date"
										max="{{ date('Y-m-d') }}" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label>Vendor :</label>
								<select name="filter_vendor_id" id="filter_vendor_id" class="select2 form-control"
									style="width:100%;">
									<option value="">-- Pilih --</option>
									@foreach($vendor as $v)
									<option value="{{ $v->id }}">{{ $v->name }}</option>
									@endforeach
								</select>
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
						</div>
					</div>
					<div class="form-group mb-5">
						<hr class="bg-success">
					</div>
					<div class="text-center mt-0">
						<span class="badge badge-success">TOTAL : <b id="grandtotal">Rp 0</b></span>
					</div>
					<div class="table-responsive">
						<table id="datatable_serverside" class="table table-bordered table-hover nowrap"
							style="width:100%">
							<thead>
								<tr class="text-center">
									<th>No</th>
									<th>Kode</th>
									<th>Vendor</th>
									<th>Pembayaran</th>
									<th>Status</th>
									<th>Jatuh Tempo</th>
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

	<div id="paid_modal" class="modal animated  fadeInUp" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<p class="modal-text">
					<h4 class="text-center">PEMBAYARAN UNTUK TAGIHAN :</h4><br>
					<p class="text-center" id="show_code"></p>
					<div class="form-group">
						<hr>
					</div>
					<div class="form-group">
						<label class="text-center">Total Tagihan :</label>
						<input type="number" name="total" id="total" class="form-control" readonly>
					</div>
					<div class="form-group">
						<label class="text-center">Sisa Tagihan :</label>
						<input type="number" name="rest" id="rest" class="form-control" readonly>
					</div>
					<div class="form-group">
						<label class="text-center">Tagihan Terbayar :</label>
						<input type="number" name="paid" id="paid" class="form-control" readonly>
					</div>
					<div class="form-group">
						<label>Debit :</label>
						<select name="coa_debit" id="coa_debit" class="form-control">
							<option value="">-- Pilih --</option>
							@foreach($coa->where('code', 10000) as $c)
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
							@foreach($coa->where('code', 2000) as $c)
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
						<label class="text-center">Jumlah Yang Ingin Dibayar :</label>
						<input type="number" name="paid_off" id="paid_off" class="form-control"
							placeholder="Masukan jumlah bayar">
						<small id="message_paid_off"></small>
					</div>
				</div>
				</p>
				<div class="text-right mb-2">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
					<button type="button" onclick="pay()" class="btn btn-success" id="btn_paid" disabled>Bayar</button>
				</div>
				<div class="modal-footer">
					<div class="text-center text-danger">
						<small class="font-italic font-weight-bold">
							Jika pembayaran = total tagihan (lunas), pelunasan di anggap sudah terkonfirmasi lunas dan
							tidak dapat diganti kembali jumlah pembayarannya
						</small>
					</div>
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

			$('#paid_off').keyup(function() {
				var rest     = parseInt($('#rest').val());
				var paid_off = parseInt($('#paid_off').val());

				if(paid_off && rest > 0) {
					if(paid_off > rest) {
						$('#paid_off').addClass('is-invalid');
						$('#message_paid_off').addClass('badge badge-danger');
						$('#message_paid_off').text('Pembayaran melampaui jumlah sisa tagihan');
						$('#btn_paid').attr('disabled', true);
					} else {
						$('#paid_off').removeClass('is-invalid');
						$('#message_paid_off').removeClass('badge badge-danger');
						$('#message_paid_off').text('');
						$('#btn_paid').attr('disabled', false);
					}
				} else {
					$('#paid_off').removeClass('is-invalid');
					$('#message_paid_off').removeClass('badge badge-danger');
					$('#message_paid_off').text('');
					$('#btn_paid').attr('disabled', true);
				}
			});
		});

        function reset() {
            $('#filter_vendor_id').val(null).change();
            $('#filter_start_date').val(null);
            $('#filter_finish_date').val(null);
            loadDataTable();
        }

        function grandtotal() {
            $.ajax({
                url: '{{ url("purchase/grandtotal") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    vendor_id: $('#filter_vendor_id').val(),
                    start_date: $('#filter_start_date').val(),
                    finish_date: $('#filter_finish_date').val()
                },
                beforeSend: function() {
                    loadingOpen('.main-content');
                },
                success: function(response) {
                    loadingClose('.main-content');
                    $('#grandtotal').html(response);
                },
                error: function() {
                    loadingClose('.main-content');
                    notif('Server Error!', '#DC3545');
                }
            });
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
					url: '{{ url("purchase/datatable") }}',
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
                    data: {
                        vendor_id: $('#filter_vendor_id').val(),
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
					{ name: 'code', className: 'text-center align-middle' },
					{ name: 'vendor_id', className: 'text-center align-middle' },
					{ name: 'paidable', orderable: false, searchable: false, className: 'text-center align-middle' },
                    { name: 'status', orderable: false, searchable: false, className: 'text-center align-middle' },
                    { name: 'due_date', searchable: false, className: 'text-center align-middle' },
					{ name: 'total', searchable: false, className: 'text-center align-middle' },
					{ name: 'paid_off', searchable: false, className: 'text-center align-middle' },
					{ name: 'created_at', searchable: false, className: 'text-center align-middle' },
					{ name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
				]
			});

            grandtotal();
		}

		function paid(id, paid, code, total, rest) {
			$('#paid_modal').modal('show');
			$('#show_code').text(code);
			$('#total').val(total);
			$('#rest').val(rest);
			$('#paid').val(paid);
			$('#paid_off').val(null);
			$('#btn_paid').attr('disabled', true);
			$('#btn_paid').attr('onclick', 'pay(' + id + ')');
		}

		function pay(id) {
			$.ajax({
				url: '{{ url("purchase/paid") }}',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id: id,
					paid_off: $('#paid_off').val(),
					coa_debit: $('#coa_debit').val(),
					coa_credit: $('#coa_credit').val()
				},
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
						$('#datatable_serverside').DataTable().ajax.reload(null, false);
						$('#paid_modal').modal('hide');
						notif(response.message, '#198754');
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
</script>