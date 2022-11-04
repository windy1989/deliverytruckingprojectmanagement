<div id="content" class="main-content">
	<div class="layout-px-spacing">
		<div class="row layout-top-spacing" id="cancel-row">
			<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
				@if($errors->any())
				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true"
							xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
							class="feather feather-x">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg></button>
					<ul>
						@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@elseif(session('success'))
				<div class="alert bg-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true"
							xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
							class="feather feather-x">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg></button>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="feather feather-check">
						<polyline points="20 6 9 17 4 12"></polyline>
					</svg>
					{{ session('success') }}
				</div>
				@elseif(session('failed'))
				<div class="alert bg-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true"
							xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
							class="feather feather-x">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg></button>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						class="feather feather-x">
						<line x1="18" y1="6" x2="6" y2="18"></line>
						<line x1="6" y1="6" x2="18" y2="18"></line>
					</svg>
					{{ session('failed') }}
				</div>
				@endif
				<div class="widget-content widget-content-area br-6">
					<div class="row mb-5">
						<div class="col-md-6">
							<h5 class="mt-2">Buat Pembelian</h5>
						</div>
					</div>
					<form action="{{ url('purchase/create') }}" method="POST" id="form_data">
						@csrf
						<div class="form-group">
							<label>Vendor :</label>
							<select name="vendor_id" id="vendor_id" class="select2 form-control" style="width:100%;"
								onchange="getLetterWay()" required>
								<option value="">-- Pilih --</option>
								@foreach($vendor as $v)
								<option value="{{ $v->id }}" {{ $v->id == old('vendor_id') ? 'selected' : '' }}>{{
									$v->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<hr class="bg-success">
						</div>
						<div class="table-responsive">
							<table id="datatable" class="table table-bordered table-striped">
								<thead class="table-primary">
									<tr class="text-center font-weight-bold">
										<td>#</td>
										<td>Order</td>
										<td>No SJ</td>
										<td>Qty</td>
										<td>Berat</td>
										<td>Harga Per Kg</td>
										<td>Total</td>
										<td>Pecah</td>
										<td>Telat</td>
									</tr>
								</thead>
								<tbody>
									@if(old('repayment_detail'))
									@php
									$letter_way = App\Models\LetterWay::whereHas('order', function($query) {
									$query->where('vendor_id', old('vendor_id'));
									})
									->whereNotExists(function($query) {
									$query->select(DB::raw(1))
									->from('repayment_details')
									->whereColumn('repayment_details.letter_way_id', 'letter_ways.id');
									})
									->where('status', 2)
									->get();
									@endphp
									@foreach($letter_way as $key => $lw)
									@php
									$price = $lw->destination->destinationPrice->last();
									$find_id = in_array($lw->id, old('repayment_detail'));
									$statsTelat = "";
									$pecah = "";
									if($lw->legalize_received_date > $lw->order->date) {
									$datetime1 = new DateTime($lw->legalize_received_date);
									$datetime2 = new DateTime($lw->order->date);
									$interval = $datetime1->diff($datetime2);
									$statsTelat = 'Telat: ' . $interval->format('%a') . "Hari";
									} else {
									$statsTelat = '-';
									}
									@endphp

									<tr class="text-center">
										<td class="align-middle">
											<input type="checkbox" name="repayment_detail[]" value="{{ $lw->id }}"
												onclick="grandtotal()" {{ $find_id ? 'checked' : '' }}>
										</td>
										<td class="align-middle">{{ $lw->order->code }}</td>
										<td class="align-middle">{{ $lw->number }}</td>
										<td class="align-middle">{{ $lw->qty }} {{ $lw->order->unit->name }}</td>
										<td class="align-middle">{{ $lw->weight }} Kg</td>
										<td class="align-middle">Rp {{ number_format($price->price_vendor) }}</td>
										<td class="align-middle">
											Rp {{ number_format($price->price_vendor * ($lw->weight * $lw->qty)) }}
										</td>
										<td class="align-middle">{{ $lw->ttbr_qty }} {{ $lw->order->unit->name }}</td>
										<td class="align-middle">{{ $statsTelat }}</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
						</div>
						<div class="mt-5">
							<div class="row">
								<div class="col-md-3">
									<div class="text-center form-group">
										<label>Grandtotal</label>
										<div class="input-group">
											<div class="input-group-append">
												<span class="input-group-text">Rp</span>
											</div>
											<input type="hidden" class="form-control" name="total" id="total">
											<input type="text" class="form-control" name="total1" id="total1"
												placeholder="0" readonly>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="text-center form-group">
										<label>Pajak</label>
										<div class="form-group input-group">
											<input type="number" class="form-control" name="tax" id="tax"
												value="{{ old('tax') ? old('tax') : 0 }}" placeholder="0"
												onkeyup="grandtotal()">
											<div class="input-group-append">
												<span class="input-group-text">%</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="text-center form-group">
										<label>Jatuh Tempo</label>
										<input type="date" class="form-control" name="due_date" id="due_date">
									</div>
								</div>
								<div class="col-md-3">
									<div class="text-center form-group">
										<label>Nomor Refrensi</label>
										<input type="text" class="form-control" name="reference" id="reference"
											placeholder="Masukan nomor refrensi">
									</div>
								</div>
								<div class="col-md-3">
									<div class="text-center form-group">
										<label>Terbayar</label>
										<div class="input-group">
											<div class="input-group-append">
												<span class="input-group-text">Rp</span>
											</div>
											<input type="text" class="form-control" name="paid_off" id="paid_off"
												placeholder="0" required>
										</div>
										<small id="message_paid_off"></small>
									</div>
								</div>

							</div>
						</div>
						<div class="form-group">
							<hr class="bg-success">
						</div>
						<div class="form-group text-right">
							<a href="{{ url('purchase') }}" class="btn btn-secondary">Kembali</a>
							<button type="submit" class="btn btn-primary">Buat</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(function() {
		$('#datatable').DataTable();
		$('.select2').select2({
			placeholder: '-- Pilih --'
		});

		$('#paid_off').keyup(function() {
			var total    = parseInt($('#total').val());
			var paid_off = parseInt($('#paid_off').val());

			if(paid_off != '') {
				if(paid_off > total) {
					$('#paid_off').addClass('is-invalid');
					$('#message_paid_off').addClass('badge badge-danger');
					$('#message_paid_off').text('Pembayaran melampaui jumlah tagihan');
					$('#btn_create').attr('disabled', true);
				} else {
					$('#paid_off').removeClass('is-invalid');
					$('#message_paid_off').removeClass('badge badge-danger');
					$('#message_paid_off').text('');
					$('#btn_create').attr('disabled', false);
				}
			} else {
				$('#paid_off').removeClass('is-invalid');
				$('#message_paid_off').removeClass('badge badge-danger');
				$('#message_paid_off').text('');
				$('#btn_create').attr('disabled', true);
			}
		});

		grandtotal();
	});


	function grandtotal() {
		$.ajax({
			url: '{{ url("purchase/total_repayment") }}',
			type: 'GET',
			dataType: 'JSON',
			data: $('#form_data').serialize(),
			success: function(response) {
				$('#total').val(response.total);
				$('#total1').val(response.newtotal);
			}
		});
	}

	function getLetterWay() {
		$.ajax({
			url: '{{ url("purchase/get_letter_way") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				vendor_id: $('#vendor_id').val()
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				loadingOpen('.widget-content');
				$('#datatable').DataTable().clear().draw();
			},
			success: function(response) {
				loadingClose('.widget-content');
				$.each(response, function(i, val) {
					$('#datatable').DataTable().row.add([
						`<input type="checkbox" id="` + val.id + `" name="repayment_detail[]" value="` + val.id + `" onclick="grandtotal()">`,
						val.order_code,
						val.number,
						val.qty,
						val.weight,
						val.price,
						val.total,
						val.pecah,
						val.status_telat,
					]).draw().node();
				});
				grandtotal();
			},
			error: function() {
				loadingClose('.widget-content');
				notif('Server Error!', '#DC3545');
			}
		});
	}
	</script>