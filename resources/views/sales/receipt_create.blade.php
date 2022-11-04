<div id="content" class="main-content">
	<div class="layout-px-spacing">
		<div class="row layout-top-spacing" id="cancel-row">
			<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
				@if($errors->any())
					<div class="alert alert-warning" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
						<ul>
							@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@elseif(session('success'))
					<div class="alert bg-success" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
						{{ session('success') }}
					</div>
				@elseif(session('failed'))
					<div class="alert bg-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
						{{ session('failed') }}
					</div>
				@endif
				<div class="widget-content widget-content-area br-6">
					<div class="row mb-5">
						<div class="col-md-6">
							<h5 class="mt-2">Buat Kwitansi</h5>
						</div>
					</div>
					<form action="{{ url('sales/receipt/create') }}" method="POST" id="form_data">
						@csrf
						<div class="form-group">
							<label>Customer :</label>
								<select name="customer_id" id="customer_id" class="select2 form-control" style="width:100%;" onchange="getInvoice()" required>
								<option value="">-- Pilih --</option>
								@foreach($customer as $c)
									<option value="{{ $c->id }}" {{ $c->id == old('customer_id') ? 'selected' : '' }}>{{ $c->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group"><hr class="bg-success"></div>
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<thead class="table-primary">
									<tr class="text-center font-weight-bold">
										<td>#</td>
										<td>Invoice</td>
										<td>Tanggal</td>
										<td>Total</td>
									</tr>
								</thead>
								<tbody id="data_receipt">
									@if(old('receipt_detail'))
										@php
											$invoice = App\Models\Invoice::where('customer_id', old('customer_id'))
												->whereNotExists(function($query) {
													$query->select(DB::raw(1))
														->from('receipt_details')
														->whereColumn('receipt_details.invoice_id', 'invoices.id');
												})
												->get();
										@endphp
										@foreach($invoice as $key => $i)
											<tr class="text-center">
												<td class="align-middle">
													@php $find_id = in_array($i->id, old('receipt_detail')); @endphp
													<input type="checkbox" name="receipt_detail[]" value="{{ $i->id }}" onclick="grandtotal()" {{ $find_id ? 'checked' : '' }}>
												</td>
												<td class="align-middle">{{ $i->code }}</td>
												<td class="align-middle">{{ date('d M Y', strtotime($i->created_at)) }}</td>
												<td class="align-middle">Rp {{ number_format($i->grandtotal) }}</td>
											</tr>
										@endforeach
									@else
										<tr class="text-center">
											<td class="align-middle" colspan="4">Tidak ada data.</td>
										</tr>
									@endif
								</tbody>
								<tfoot>
									<tr>
										<th colspan="3" class="text-right align-middle">JATUH TEMPO</th>
										<th width="30%">
											<div class="form-group">
												<input type="date" class="form-control" name="due_date" id="due_date">
											</div>
										</th>
									</tr>
                                    <tr>
										<th colspan="3" class="text-right align-middle">BIAYA LAIN</th>
										<th width="30%">
											<div class="form-group input-group">
												<div class="input-group-append">
													<span class="input-group-text">Rp</span>
												</div>
												<input type="number" class="form-control" name="other" id="other" placeholder="0" value="0" oninput="grandtotal()" step="any">
											</div>
										</th>
									</tr>
                                    <tr>
										<th colspan="3" class="text-right align-middle">GRANDTOTAL</th>
										<th width="30%">
											<div class="form-group input-group">
												<div class="input-group-append">
													<span class="input-group-text">Rp</span>
												</div>
												<input type="hidden" class="form-control" name="total" id="total">
												<input type="text" class="form-control" name="total1" id="total1" placeholder="0" readonly>
											</div>
										</th>
									</tr>
									<tr>
										<th colspan="3" class="text-right align-middle">TERBAYAR</th>
										<th width="30%">
											<div class="form-group input-group">
												<div class="input-group-append">
													<span class="input-group-text">Rp</span>
												</div>
												<input type="number" class="form-control" name="paid_off" id="paid_off" placeholder="0" oninput="grandtotal()" value="0" step="any">
											</div>
											<small id="message_paid_off"></small>
										</th>
									</tr>
                                    <tr>
										<th colspan="3" class="text-right align-middle">TAGIHAN</th>
										<th width="30%">
											<div class="form-group input-group">
												<div class="input-group-append">
													<span class="input-group-text">Rp</span>
												</div>
												<input type="hidden" class="form-control" name="bill" id="bill">
												<input type="text" class="form-control" name="bill1" id="bill1" placeholder="0" readonly>
											</div>
										</th>
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="form-group"><hr class="bg-success"></div>
						<div class="form-group text-right">
							<a href="{{ url('sales/receipt') }}" class="btn btn-secondary">Kembali</a>
							<button type="submit" class="btn btn-primary" id="btn_create">Buat</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

<script>
	$(function() {
		$('.select2').select2({
			placeholder: '-- Pilih --'
		});

		grandtotal();
	});

	function grandtotal() {
		$.ajax({
			url: '{{ url("sales/receipt/total_receipt") }}',
			type: 'GET',
			dataType: 'JSON',
			data: $('#form_data').serialize(),
			success: function(response) {
				$('#total').val(response.total);
				$('#total1').val(response.total1);
                $('#bill').val(response.bill);
				$('#bill1').val(response.bill1);
			}
		});
	}

	function getInvoice() {
		$.ajax({
			url: '{{ url("sales/receipt/get_invoice") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				customer_id: $('#customer_id').val()
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				loadingOpen('.widget-content');
				$('#data_receipt').html('');
			},
			success: function(response) {
				loadingClose('.widget-content');
				if(response.length > 0) {
					$.each(response, function(i, val) {
						$('#data_receipt').append(`
							<tr class="text-center">
								<td class="align-middle">
									<input type="checkbox" name="receipt_detail[]" value="` + val.id + `" onclick="grandtotal()" checked>
								</td>
								<td class="align-middle">` + val.code + `</td>
								<td class="align-middle">` + val.date + `</td>
								<td class="align-middle">` + val.total + `</td>
							</tr>
						`);
					});
				} else {
					$('#data_receipt').html(`
						<tr class="text-center">
							<td class="align-middle" colspan="4">Tidak ada data.</td>
						</tr>
					`);
				}

				grandtotal();
			},
			error: function() {
				loadingClose('.widget-content');
				notif('Server Error!', '#DC3545');
			}
		});
	}
</script>
