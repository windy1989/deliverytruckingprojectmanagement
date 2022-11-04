<style>
	#nav-1 {
		display: block !important;
	}

	#nav-2 {
		display: block !important;
	}

	#nav-3 {
		display: block !important;
	}

	#nav-4 {
		display: block !important;
	}

	#nav-5 {
		display: block !important;
	}
</style>
<div id="content" class="main-content">
	<div class="layout-px-spacing">
		<div class="row invoice layout-top-spacing">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="app-hamburger-container">
					<div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
							viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
							stroke-linecap="round" stroke-linejoin="round"
							class="feather feather-menu chat-menu d-xl-none">
							<line x1="3" y1="12" x2="21" y2="12"></line>
							<line x1="3" y1="6" x2="21" y2="6"></line>
							<line x1="3" y1="18" x2="21" y2="18"></line>
						</svg></div>
				</div>
				<div class="doc-container">
					<div class="tab-title">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-12">
								<div class="search">
									<input type="text" class="form-control" placeholder="Search">
								</div>
								<ul class="nav nav-pills inv-list-container d-block" id="pills-tab" role="tablist">
									@foreach($order as $o)
									<li class="nav-item" onclick="listLetterWay({{ $o->id }})">
										<div class="nav-link list-actions" id="print-{{ $o->id }}"
											data-invoice-id="{{ $o->code }}">
											<div class="f-m-body">
												<div class="f-head">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
														viewBox="0 0 24 24" fill="none" stroke="currentColor"
														stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
														class="feather feather-truck">
														<rect x="1" y="3" width="15" height="13"></rect>
														<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
														<circle cx="5.5" cy="18.5" r="2.5"></circle>
														<circle cx="18.5" cy="18.5" r="2.5"></circle>
													</svg>
												</div>
												<div class="f-body">
													<p class="invoice-number">{{ $o->code }}</p>
													@foreach($o->orderCustomerDetail as $char)
													<p class="invoice-customer-name">{{ $char->customer->name }}</p>
													@endforeach
													<p class="invoice-generated-date">
														Tanggal: {{ date('d M Y', strtotime($o->date)) }}
													</p>
												</div>
											</div>
										</div>
									</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
					<div class="invoice-container">
						<div class="invoice-inbox">
							<div class="inv-not-selected">
								<p>Pilih Order Dari List</p>
							</div>
							<div class="invoice-header-section">
								<h4 class="inv-number"></h4>
							</div>
							<div id="ct">
								@foreach($order as $o)
								<div class="print-{{ $o->id }}" id="content_{{ $o->id }}">
									<div class="content-section animated animatedFadeInUp fadeInUp">
										<div class="inv--detail-section mb-0 mt-0">
											<div class="text-center mb-5">
												<h4 style="text-decoration:underline;"><b>{{ $o->code }}</b></h4>
												<div>Tanggal : {!! $o->date !!}</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="text-dark"><b>User :</b></label>
														<div class="font-italic text-muted">{{ $o->user->name }}</div>
													</div>
													<div class="form-group">
														<label class="text-dark"><b>Customer :</b></label>
														@foreach($o->orderCustomerDetail as $char)
														<div class="font-italic text-muted">{{ $char->customer->name }}
														</div>
														@endforeach
													</div>
													<div class="form-group">
														<label class="text-dark"><b>Vendor :</b></label>
														<div class="font-italic text-muted">{{ $o->vendor->name }}</div>
													</div>
													<div class="form-group">
														<label class="text-dark"><b>Satuan :</b></label>
														<div class="font-italic text-muted">{{ $o->unit->name }}</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="text-dark"><b>Berat Awal :</b></label>
														<div class="font-italic text-muted">{{ $o->weight }} Kg</div>
													</div>
													<div class="form-group">
														<label class="text-dark"><b>Qty Awal :</b></label>
														<div class="font-italic text-muted">{{ $o->qty }}</div>
													</div>
													<div class="form-group">
														<label class="text-dark"><b>Status :</b></label>
														<div class="font-italic text-muted">{!! $o->status() !!}</div>
													</div>
													<div class="form-group">
														<label class="text-dark"><b>Detail :</b></label>
														<div class="font-italic text-muted">
															<a href="javascript:void(0);" class="text-primary"
																data-toggle="modal"
																data-target="#modal_detail{{ $o->id }}">Lihat</a>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<hr class="bg-success">
											</div>
											<div class="form-group">
												<div class="text-right">
													<button type="button" onclick="showModal({{ $o->id }})"
														class="btn btn-primary">Tambah Surat Jalan</button>
												</div>
											</div>
											<div class="form-group">
												<div class="table-responsive">
													<table class="table table-bordered">
														<thead>
															<tr class="text-center">
																<th>No</th>
																<th>Berat</th>
																<th>Status</th>
																<th>#</th>
															</tr>
														</thead>
														<tbody id="list_letter_way_{{ $o->id }}"></tbody>
													</table>
												</div>
											</div>
											<div style="display:none;" id="btn_finish_{{ $o->id }}">
												<div class="form-group">
													<hr class="bg-success">
												</div>
												<div class="form-group text-center">
													<button type="button" class="btn btn-success btn-lg"
														onclick="finish({{ $o->id }}, '{{ $o->code }}')">Finish</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal fade" id="modal_detail{{ $o->id }}" tabindex="-1" role="dialog"
									aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Detail</h5>
												<button type="button" class="close" data-dismiss="modal"
													aria-label="Close">
													<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
														width="24" height="24" viewBox="0 0 24 24" fill="none"
														stroke="currentColor" stroke-width="2" stroke-linecap="round"
														stroke-linejoin="round" class="feather feather-x">
														<line x1="18" y1="6" x2="6" y2="18"></line>
														<line x1="6" y1="6" x2="18" y2="18"></line>
													</svg>
												</button>
											</div>
											<div class="modal-body">
												<p class="modal-text">
												<ul class="nav nav-tabs mb-3 mt-3 nav-fill" id="justifyTab"
													role="tablist">
													<li class="nav-item">
														<a class="nav-link nav-link-tab active"
															id="justify-transport-tab{{ $o->id }}" data-toggle="tab"
															href="#justify-transport{{ $o->id }}" role="tab"
															aria-controls="justify-transport{{ $o->id }}"
															aria-selected="true">Transportasi</a>
													</li>
													<li class="nav-item">
														<a class="nav-link nav-link-tab"
															id="justify-destination-tab{{ $o->id }}" data-toggle="tab"
															href="#justify-destination{{ $o->id }}" role="tab"
															aria-controls="justify-destination{{ $o->id }}"
															aria-selected="false">Tujuan</a>
													</li>
												</ul>
												<div class="tab-content" id="justifyTabContent">
													<div class="tab-pane fade show active"
														id="justify-transport{{ $o->id }}" role="tabpanel"
														aria-labelledby="justify-transport-tab{{ $o->id }}">
														<p class="mt-5">
														<div class="form-group">
															<div class="table-responsive">
																<table class="table table-bordered" style="width:100%;">
																	<thead>
																		<tr class="text-center">
																			<th>Kendaraan</th>
																			<th>Plat</th>
																			<th>Sopir</th>
																			<th>Gudang Asal</th>
																			<th>Gudang Tujuan</th>
																		</tr>
																	</thead>
																	<tbody>
																		@foreach($o->orderTransport as $ot)
																		<tr class="text-center">
																			<td class="align-middle">{{
																				$ot->transport->brand }}</td>
																			<td class="align-middle">{{
																				$ot->transport->no_plate }}</td>
																			<td class="align-middle">{{
																				$ot->driver->name }}</td>
																			<td class="align-middle">{{
																				$ot->warehouseOrigin->name }}</td>
																			<td class="align-middle">{{
																				$ot->warehouseDestination->name }}</td>
																		</tr>
																		@endforeach
																	</tbody>
																</table>
															</div>
														</div>
														</p>
													</div>
													<div class="tab-pane fade" id="justify-destination{{ $o->id }}"
														role="tabpanel"
														aria-labelledby="justify-destination-tab{{ $o->id }}">
														<p class="mt-5">
														<div class="form-group">
															<div class="table-responsive">
																<table class="table table-bordered" style="width:100%;">
																	<thead>
																		<tr class="text-center">
																			<th>Kota Asal</th>
																			<th>Kota Tujuan</th>
																		</tr>
																	</thead>
																	<tbody>
																		@foreach($o->orderDestination as $od)
																		<tr class="text-center">
																			<td class="align-middle">
																				{{ $od->destination->cityOrigin->name }}
																			</td>
																			<td class="align-middle">
																				{{
																				$od->destination->cityDestination->name
																				}}
																			</td>
																		</tr>
																		@endforeach
																	</tbody>
																</table>
															</div>
														</div>
														</p>
													</div>
												</div>
												</p>
											</div>
											<div class="modal-footer">
												<button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
											</div>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="form_modal" class="modal animated fadeInUp" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Form Surat Jalan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
							viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
							stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</button>
				</div>
				<div class="modal-body">
					<p class="modal-text">
					<div class="alert alert-danger" id="validation_alert" role="alert" style="display:none;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg
								aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
								viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
								stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
								<line x1="18" y1="6" x2="6" y2="18"></line>
								<line x1="6" y1="6" x2="18" y2="18"></line>
							</svg></button>
						<ul id="validation_content"></ul>
					</div>
					<form id="form_data">
						<input type="hidden" name="order_id" id="order_id">
						<ul class="nav nav-tabs  mb-3 mt-3 nav-fill" id="justifyTab" role="tablist">
							<li class="nav-item" id="nav-1">
								<a class="nav-link nav-link-tab active" id="justify-letter-way-tab" data-toggle="tab"
									href="#justify-letter-way" role="tab" aria-controls="justify-letter-way"
									aria-selected="true">SJ</a>
							</li>
							<li class="nav-item" id="nav-2">
								<a class="nav-link nav-link-tab" id="justify-letter-way-send-back-tab" data-toggle="tab"
									href="#justify-letter-way-send-back" role="tab"
									aria-controls="justify-letter-way-send-back" aria-selected="false">SJ Balik</a>
							</li>
							<li class="nav-item" id="nav-3">
								<a class="nav-link nav-link-tab" id="justify-letter-way-legalize-tab" data-toggle="tab"
									href="#justify-letter-way-legalize" role="tab"
									aria-controls="justify-letter-way-legalize" aria-selected="false">SJ Legalisir</a>
							</li>
							<li class="nav-item" id="nav-4">
								<a class="nav-link nav-link-tab" id="justify-letter-way-legalize-send-back-tab"
									data-toggle="tab" href="#justify-letter-way-legalize-send-back" role="tab"
									aria-controls="justify-letter-way-legalize-send-back" aria-selected="false">SJ
									Legalisir Balik</a>
							</li>
							<li class="nav-item" id="nav-5">
								<a class="nav-link nav-link-tab" id="justify-letter-way-ttbr-tab" data-toggle="tab"
									href="#justify-letter-way-ttbr" role="tab" aria-controls="justify-letter-way-ttbr"
									aria-selected="false">SJ TTBR</a>
							</li>
						</ul>
						<div class="tab-content" id="justifyTabContent">
							<div class="tab-pane fade show active" id="justify-letter-way" role="tabpanel"
								aria-labelledby="justify-letter-way-tab">
								<p>
								<div class="form-group">
									<label>Nomor :</label>
									<input type="text" name="number" id="number" class="form-control"
										placeholder="Masukan nomor surat jalan">
								</div>
								<div class="form-group">
									<label>Tujuan :</label>
									<select name="destination_id" id="destination_id" class="form-control"></select>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Berat :</label>
											<div class="input-group">
												<input type="number" name="weight" id="weight" class="form-control"
													placeholder="Masukan berat" aria-label="Berat">
												<div class="input-group-prepend">
													<span class="input-group-text" id="basic-addon5">Kg</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Qty :</label>
											<input type="number" name="qty" id="qty" class="form-control"
												placeholder="Masukan qty" aria-label="Qty">
										</div>
									</div>
								</div>
								</p>
							</div>
							<div class="tab-pane fade" id="justify-letter-way-send-back" role="tabpanel"
								aria-labelledby="justify-letter-way-send-back-tab">
								<p>
								<div class="row">
									<div class="col-md-10">
										<div class="form-group">
											<label>Lampiran :</label>
											<input type="file" name="send_back_attachment" id="send_back_attachment"
												class="form-control"
												onchange="previewImage(this, '#preview_send_back_attachment img', '#preview_send_back_attachment')">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<a href="{{ asset(" website/empty.png") }}"
												id="preview_send_back_attachment" data-lightbox="Lampiran"
												data-title="Lampiran Balik"><img src="{{ asset(" website/empty.png") }}"
													style="width:100px; height:75px;"></a>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Qty Pecah :</label>
									<input type="number" name="ttbr_qty" id="ttbr_qty" class="form-control"
										placeholder="Masukan qty pecah">
								</div>
								<div class="form-group">
									<label>Tanggal Diterima :</label>
									<input type="date" name="received_date" id="received_date" class="form-control">
								</div>
								</p>
							</div>
							<div class="tab-pane fade" id="justify-letter-way-legalize" role="tabpanel"
								aria-labelledby="justify-letter-way-legalize-tab">
								<p>
								<div class="row">
									<div class="col-md-10">
										<div class="form-group">
											<label>Lampiran :</label>
											<input type="file" name="legalize_attachment" id="legalize_attachment"
												class="form-control"
												onchange="previewImage(this, '#preview_legalize_attachment img', '#preview_legalize_attachment')">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<a href="{{ asset(" website/empty.png") }}" id="preview_legalize_attachment"
												data-lightbox="Legalisir" data-title="Lampiran Legalisir"><img
													src="{{ asset(" website/empty.png") }}"
													style="width:100px; height:75px;"></a>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Tanggal Diterima :</label>
									<input type="date" name="legalize_received_date" id="legalize_received_date"
										class="form-control">
								</div>
								</p>
							</div>
							<div class="tab-pane fade" id="justify-letter-way-legalize-send-back" role="tabpanel"
								aria-labelledby="justify-letter-way-legalize-send-back-tab">
								<p>
								<div class="row">
									<div class="col-md-10">
										<div class="form-group">
											<label>Lampiran :</label>
											<input type="file" name="legalize_send_back_attachment"
												id="legalize_send_back_attachment" class="form-control"
												onchange="previewImage(this, '#preview_legalize_send_back_attachment img', '#preview_legalize_send_back_attachment')">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<a href="{{ asset(" website/empty.png") }}"
												id="preview_legalize_send_back_attachment"
												data-lightbox="Legalisir Balik"
												data-title="Lampiran Legalisir Balik"><img src="{{ asset("
													website/empty.png") }}" style="width:100px; height:75px;"></a>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Tanggal Diterima :</label>
									<input type="date" name="legalize_send_back_received_date"
										id="legalize_send_back_received_date" class="form-control">
								</div>
								</p>
							</div>
							<div class="tab-pane fade" id="justify-letter-way-ttbr" role="tabpanel"
								aria-labelledby="justify-letter-way-ttbr-tab">
								<p>
								<div class="row">
									<div class="col-md-10">
										<div class="form-group">
											<label>Lampiran :</label>
											<input type="file" name="ttbr_attachment" id="ttbr_attachment"
												class="form-control"
												onchange="previewImage(this, '#preview_ttbr_attachment img', '#preview_ttbr_attachment')">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<a href="{{ asset(" website/empty.png") }}" id="preview_ttbr_attachment"
												data-lightbox="TTBR" data-title="Lampiran TTBR"><img src="{{ asset("
													website/empty.png") }}" style="width:100px; height:75px;"></a>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label>Tanggal Diterima :</label>
									<input type="date" name="ttbr_received_date" id="ttbr_received_date"
										class="form-control">
								</div>
								</p>
							</div>
						</div>
						<div class="form-group">
							<hr class="bg-success">
						</div>
						<div class="form-group">
							<select name="status" id="status" class="form-control">
								<option value="1">Proses</option>
								<option value="2">Finish</option>
							</select>
						</div>
					</form>
					</p>
				</div>
				<div class="modal-footer md-button">
					<button type="button" class="btn btn-danger" id="btn_cancel" onclick="cancel()"
						style="display:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
							viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
							stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>&nbsp;Batal</button>
					<button type="button" class="btn btn-warning" id="btn_update" onclick="update()"
						style="display:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
							viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
							stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
							<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
							<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
						</svg>&nbsp;Simpan</button>
					<button type="button" class="btn btn-info" id="btn_create" onclick="create()"><svg
							xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
							class="feather feather-plus-square">
							<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
							<line x1="12" y1="8" x2="12" y2="16"></line>
							<line x1="8" y1="12" x2="16" y2="12"></line>
						</svg>&nbsp;Tambah</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		function checkFinishOrder(order_id) {
		$.ajax({
			url: '{{ url("data/letter_way/check_finish_order") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				order_id: order_id
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				$('#btn_finish').hide();
				loadingOpen('.content_' + order_id);
			},
			success: function(response) {
				loadingClose('.content_' + order_id);
				if(response.data < 1) {
					$('#btn_finish_' + order_id).show();
				} else {
					$('#btn_finish_' + order_id).hide();
				}
			},
			error: function() {
				loadingClose('.content_' + order_id);
				swal('Server Error!', '', 'error');
			}
		});
	}

	function getDestination(order_id) {
		$.ajax({
			url: '{{ url("data/letter_way/get_destination") }}',
			type: 'POST',
			data: {
				order_id: order_id
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				loadingOpen('.content_' + order_id);
				$('#destination_id').html('<option value="">-- Pilih --</option>')
			},
			success: function(response) {
				loadingClose('.content_' + order_id);
				$.each(response, function(i, val) {
					$('#destination_id').append(`
						<option value="` + val.id + `">` + val.destination + `</option>
					`);
				});
			},
			error: function() {
				loadingClose('.content_' + order_id);
				swal('Server Error!', '', 'error');
			}
		});
	}

	function showModal(order_id) {
		loadingOpen('.content_' + order_id);
		$('#form_modal').modal('show');
		$('#order_id').val(order_id);
		$('#btn_create').show();
		$('#btn_update').hide();
		$('#btn_cancel').hide();
		$('#btn_create').attr('onclick', 'create(' + order_id + ')');
		reset();
		loadingClose('.content_' + order_id);
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
		$('#preview_send_back_attachment').attr('href', '{{ asset("website/empty.png") }}');
		$('#preview_send_back_attachment img').attr('src', '{{ asset("website/empty.png") }}');
		$('#preview_legalize_attachment').attr('href', '{{ asset("website/empty.png") }}');
		$('#preview_legalize_attachment img').attr('src', '{{ asset("website/empty.png") }}');
		$('#preview_legalize_send_back_attachment').attr('href', '{{ asset("website/empty.png") }}');
		$('#preview_legalize_send_back_attachment img').attr('src', '{{ asset("website/empty.png") }}');
		$('#preview_ttbr_attachment').attr('href', '{{ asset("website/empty.png") }}');
		$('#preview_ttbr_attachment img').attr('src', '{{ asset("website/empty.png") }}');
		$('.tab-pane').removeClass('show active');
		$('#justify-letter-way').addClass('show active');
		$('.nav-link-tab').removeClass('active');
		$('#justify-letter-way-tab').addClass('active');
	}

	function success() {
		reset();
		$('#form_modal').modal('hide');
	}

	function listLetterWay(order_id) {
		checkFinishOrder(order_id);
		$.ajax({
			url: '{{ url("data/letter_way/load_data") }}',
			type: 'POST',
			data: {
				order_id: order_id
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				$('#list_letter_way_' + order_id).html('');
				loadingOpen('.content_' + order_id);
			},
			success: function(response) {
				loadingClose('.content_' + order_id);
				getDestination(order_id);
				$.each(response, function(i, val) {
					if(val.status=="Diproses")
					{
						$('#list_letter_way_' + order_id).append(`
						<tr class="text-center">
							<td class="align-middle">` + val.number + `</td>
							<td class="align-middle">` + val.weight + `</td>
							<td class="align-middle">` + val.status + `</td>
							<td class="align-middle">
							<button type="button" class="btn btn-warning btn-sm" onclick="show(` + val.id + `)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit</button>
							<button type="button" class="btn btn-danger btn-sm" onclick="destroy(` + val.id + `,`+ order_id + `)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus</button>
							</td>

						</tr>
						`);
					}
					else
					{
						$('#list_letter_way_' + order_id).append(`
						<tr class="text-center">
							<td class="align-middle">` + val.number + `</td>
							<td class="align-middle">` + val.weight + `</td>
							<td class="align-middle">` + val.status + `</td>
							<td class="align-middle"><button type="button" class="btn btn-warning btn-sm" onclick="show(` + val.id + `)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit</button></td>
						</tr>
						`);
					}
				});
			},
			error: function() {
				loadingClose('.content_' + order_id);
				swal('Server Error!', '', 'error');
			}
		});
	}

	function create(order_id) {
		$.ajax({
			url: '{{ url("data/letter_way/create") }}' + '/' + order_id,
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
					listLetterWay(order_id);
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
			url: '{{ url("data/letter_way/show") }}',
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
				$('#preview_send_back_attachment').attr('href', response.send_back_attachment);
				$('#preview_send_back_attachment img').attr('src', response.send_back_attachment);
				$('#preview_legalize_attachment').attr('href', response.legalize_attachment);
				$('#preview_legalize_attachment img').attr('src', response.legalize_attachment);
				$('#preview_legalize_send_back_attachment').attr('href', response.legalize_send_back_attachment);
				$('#preview_legalize_send_back_attachment img').attr('src', response.legalize_send_back_attachment);
				$('#preview_ttbr_attachment').attr('href', response.ttbr_attachment);
				$('#preview_ttbr_attachment img').attr('src', response.ttbr_attachment);
				$('#order_id').val(response.order_id);
				$('#destination_id').val(response.destination_id);
				$('#number').val(response.number);
				$('#weight').val(response.weight);
				$('#qty').val(response.qty);
				$('#received_date').val(response.received_date);
				$('#ttbr_qty').val(response.ttbr_qty);
				$('#legalize_received_date').val(response.legalize_received_date);
				$('#legalize_send_back_received_date').val(response.legalize_send_back_received_date);
				$('#ttbr_received_date').val(response.ttbr_received_date);
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
			url: '{{ url("data/letter_way/update") }}' + '/' + id,
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
					listLetterWay($('#order_id').val());
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

	function destroy(id,order_id) {
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
						url: '{{ url("data/letter_way/destroy") }}',
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
								listLetterWay(order_id);
								success();
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

	function finish(order_id, code) {
		swal({
			title: code,
			text: 'Anda yakin ingin menyelesaikannya?',
			type: 'info',
			showCancelButton: true,
			confirmButtonText: 'Ya, selesaikan!',
			cancelButtonText: 'Batal!',
			reverseButtons: true,
			padding: '2em'
		}).then(function(result) {
			if(result.value) {
				$.ajax({
					url: '{{ url("data/letter_way/finish") }}',
					type: 'POST',
					dataType: 'JSON',
					data: {
						order_id: order_id
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('.doc-container');
					},
					success: function(response) {
						if(response.status == 200) {
							location.reload(true);
						} else {
							loadingClose('.doc-container');
							swal('Ooopsss!', 'Server bermasalah.', 'error');
						}
					},
					error: function() {
						loadingClose('.doc-container');
						notif('Server Error!', '#DC3545');
					}
				});
			}
		});
	}
	</script>