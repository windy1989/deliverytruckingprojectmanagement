<div id="content" class="main-content">
	<div class="layout-px-spacing">
		<div class="row layout-top-spacing" id="cancel-row">
			<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
				<div class="widget-content widget-content-area br-6">
					<div class="row mb-5">
						<div class="col-md-6">
							<h5 class="mt-2">Data Customer</h5>
						</div>
						<div class="col-md-6 text-right">
							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#form_modal"
								onclick="cancel()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
									<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
									<line x1="12" y1="8" x2="12" y2="16"></line>
									<line x1="8" y1="12" x2="16" y2="12"></line>
								</svg>&nbsp;Tambah Data</button>
						</div>
					</div>
					<div class="table-responsive">
						<table id="datatable_serverside" class="table table-bordered table-hover nowrap"
							style="width:100%">
							<thead>
								<tr class="text-center">
									<th>No</th>
									<th>Kode</th>
									<th>Nama</th>
									<th>Telp</th>
									<th>Kota</th>
									<th>PIC</th>
									<th>Status</th>
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
					<h5 class="modal-title">Form Customer</h5>
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
						<ul class="nav nav-tabs  mb-3 mt-3 nav-fill" id="justifyTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link nav-link-tab active" id="justify-data-tab" data-toggle="tab"
									href="#justify-data" role="tab" aria-controls="justify-data"
									aria-selected="true">Data</a>
							</li>
							<li class="nav-item">
								<a class="nav-link nav-link-tab" id="justify-bill-tab" data-toggle="tab"
									href="#justify-bill" role="tab" aria-controls="justify-bill"
									aria-selected="false">Rekening</a>
							</li>
						</ul>
						<div class="tab-content" id="justifyTabContent">
							<div class="tab-pane fade show active" id="justify-data" role="tabpanel"
								aria-labelledby="justify-data-tab">
								<div class="form-group">
									<label>Kode :</label>
									<input type="text" name="code" id="code" class="form-control"
										placeholder="Auto Generate" disabled>
								</div>
								<div class="form-group">
									<label>Nama :</label>
									<input type="text" name="name" id="name" class="form-control"
										placeholder="Masukan nama">
								</div>
								<div class="form-group">
									<label>PIC :</label>
									<input type="text" name="pic" id="pic" class="form-control"
										placeholder="Masukan PIC">
								</div>
								<div class="form-group mb-0">
									<label>Kota :</label>
									<select name="city_id" id="city_id" class="form-control"
										style="width:100%;"></select>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>No Telp :</label>
											<input type="text" name="phone" id="phone" class="form-control"
												placeholder="Masukan no telp">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Fax :</label>
											<input type="text" name="fax" id="fax" class="form-control"
												placeholder="Masukan fax">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Alamat :</label>
									<textarea name="address" id="address" class="form-control"
										placeholder="Masukan alamat" style="resize:none;"></textarea>
								</div>
								<div class="form-group">
									<label>Website :</label>
									<input type="url" name="website" id="website" class="form-control"
										placeholder="Masukan website">
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="badge bg-warning text-wrap mb-2">
												Warning (Hari) Vendor:
											</div>
											<input type="number" name="warning_date_vendor" id="warning_date_vendor"
												class="form-control" placeholder="Masukan hari warning">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="badge bg-danger text-wrap mb-2">
												Past Due (Hari) Vendor:
											</div>
											<input type="number" name="danger_date_vendor" id="danger_date_vendor"
												class="form-control" placeholder="Masukan hari past due">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="badge bg-warning text-wrap mb-2">
												Warning (Hari) TTBR :
											</div>
											<input type="number" name="warning_date_ttbr" id="warning_date_ttbr"
												class="form-control" placeholder="Masukan hari warning">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="badge bg-danger text-wrap mb-2">
												Past Due (Hari) TTBR :
											</div>
											<input type="number" name="danger_date_ttbr" id="danger_date_ttbr"
												class="form-control" placeholder="Masukan hari past due">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Status :</label>
									<select name="status" id="status" class="form-control">
										<option value="">-- Pilih --</option>
										<option value="1">Aktif</option>
										<option value="2">Tidak Aktif</option>
									</select>
								</div>
							</div>
							<div class="tab-pane fade" id="justify-bill" role="tabpanel"
								aria-labelledby="justify-bill-tab">
								<div class="row">
									<div class="col-md-5">
										<div class="form-group">
											<div class="form-group">
												<label>Bank :</label>
												<select name="temp_bank" id="temp_bank" class="form-control">
													<option value="">-- Pilih Bank --</option>
													<option value="Bank Mandiri">Bank Mandiri</option>
													<option value="Bank Bukopin">Bank Bukopin</option>
													<option value="Bank Danamon">Bank Danamon</option>
													<option value="Bank Mega">Bank Mega</option>
													<option value="Bank CIMB Niaga">Bank CIMB Niaga</option>
													<option value="Bank Permata">Bank Permata</option>
													<option value="Bank Sinarmas">Bank Sinarmas</option>
													<option value="Bank QNB">Bank QNB</option>
													<option value="Bank Lippo">Bank Lippo</option>
													<option value="Bank UOB">Bank UOB</option>
													<option value="Panin Bank">Panin Bank</option>
													<option value="Citibank">Citibank</option>
													<option value="Bank ANZ">Bank ANZ</option>
													<option value="Bank Commonwealth">Bank Commonwealth</option>
													<option value="Bank Maybank">Bank Maybank</option>
													<option value="Bank Maspion">Bank Maspion</option>
													<option value="Bank J Trust">Bank J Trust</option>
													<option value="Bank QNB">Bank QNB</option>
													<option value="Bank KEB Hana">Bank KEB Hana</option>
													<option value="Bank Artha Graha">Bank Artha Graha</option>
													<option value="Bank OCBC NISP">Bank OCBC NISP</option>
													<option value="Bank MNC">Bank MNC</option>
													<option value="Bank DBS">Bank DBS</option>
													<option value="BCA">BCA</option>
													<option value="BNI">BNI</option>
													<option value="BRI">BRI</option>
													<option value="BTN">BTN</option>
													<option value="Bank DKI">Bank DKI</option>
													<option value="Bank BJB">Bank BJB</option>
													<option value="Bank BPD DIY">Bank BPD DIY</option>
													<option value="Bank Jateng">Bank Jateng</option>
													<option value="Bank Jatim">Bank Jatim</option>
													<option value="Bank BPD Bali">Bank BPD Bali</option>
													<option value="Bank Sumut">Bank Sumut</option>
													<option value="Bank Nagari">Bank Nagari</option>
													<option value="Bank Riau Kepri">Bank Riau Kepri</option>
													<option value="Bank Sumsel Babel">Bank Sumsel Babel</option>
													<option value="Bank Lampung">Bank Lampung</option>
													<option value="Bank Jambi">Bank Jambi</option>
													<option value="Bank Kalbar">Bank Kalbar</option>
													<option value="Bank Kalteng">Bank Kalteng</option>
													<option value="Bank Kalsel">Bank Kalsel</option>
													<option value="Bank Kaltim">Bank Kaltim</option>
													<option value="Bank Sulsel">Bank Sulsel</option>
													<option value="Bank Sultra">Bank Sultra</option>
													<option value="Bank BPD Sulteng">Bank BPD Sulteng</option>
													<option value="Bank Sulut">Bank Sulut</option>
													<option value="Bank NTB">Bank NTB</option>
													<option value="Bank NTT">Bank NTT</option>
													<option value="Bank Maluku">Bank Maluku</option>
													<option value="Bank Papua">Bank Papua</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label>No Rekening :</label>
											<input type="text" name="temp_bill" id="temp_bill" class="form-control"
												placeholder="Masukan no rekening">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label class="text-white">.</label>
											<button type="button" class="btn btn-success col-12"
												onclick="addBill()"><svg xmlns="http://www.w3.org/2000/svg" width="24"
													height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
													class="feather feather-plus-square">
													<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
													<line x1="12" y1="8" x2="12" y2="16"></line>
													<line x1="8" y1="12" x2="16" y2="12"></line>
												</svg></button>
										</div>
									</div>
								</div>
								<div class="form-group">
									<hr>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered" style="width:100%;">
										<thead>
											<tr class="text-center">
												<td>Bank</td>
												<td>No Rekening</td>
												<td>Hapus</td>
											</tr>
										</thead>
										<tbody id="data_bill"></tbody>
									</table>
								</div>
							</div>
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
		$(function() {
			loadDataTable();
			select2ServerSide('#city_id', '{{ url("select2_serverside/city") }}', '#form_modal');

			$('#data_bill').on('click', '#delete_data_bill', function() {
				$(this).closest('tr').remove();
			});
		});

		function addBill() {
			var temp_bank = $('#temp_bank');
			var temp_bill = $('#temp_bill');

			if(temp_bank.val() && temp_bill.val()) {
				$('#data_bill').append(`
					<tr class="text-center">
						<input type="hidden" name="bank[]" value="` + temp_bank.val() + `">
						<input type="hidden" name="bill[]" value="` + temp_bill.val() + `">

						<td>` + temp_bank.val() + `</td>
						<td>` + temp_bill.val() + `</td>
						<td>
							<button type="button" class="btn btn-danger btn-sm" id="delete_data_bill">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
							</button>
						</td>
					</tr>
				`);

				temp_bank.val(null).trigger('change');
				temp_bill.val('');
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
			$('.tab-pane').removeClass('show active');
			$('#justify-data').addClass('show active');
			$('.nav-link-tab').removeClass('active');
			$('#justify-data-tab').addClass('active');
			$('#validation_alert').hide();
			$('#validation_content').html('');
			$('#city_id').val(null).trigger('change');
			$('#data_bill').html('');
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
					url: '{{ url("master_data/customer/datatable") }}',
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
					{ name: 'name', className: 'text-center align-middle' },
					{ name: 'phone', searchable: false, className: 'text-center align-middle' },
					{ name: 'city_id', className: 'text-center align-middle' },
					{ name: 'pic', className: 'text-center align-middle' },
					{ name: 'status', searchable: false, className: 'text-center align-middle' },
					{ name: 'action', orderable: false, searchable: false, className: 'text-center align-middle' },
				]
			});
		}

		function create() {
			$.ajax({
				url: '{{ url("master_data/customer/create") }}',
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
				url: '{{ url("master_data/customer/show") }}',
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
					$('#data_bill').html('');
				},
				success: function(response) {
					loadingClose('.modal-content');

					$.each(response.bill, function(i, val) {
						$('#data_bill').append(`
							<tr class="text-center">
								<input type="hidden" name="bank[]" value="` + val.bank + `">
								<input type="hidden" name="bill[]" value="` + val.bill + `">

								<td>` + val.bank + `</td>
								<td>` + val.bill + `</td>
								<td>
									<button type="button" class="btn btn-danger btn-sm" id="delete_data_bill">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
									</button>
								</td>
							</tr>
						`);
					});

					$('#city_id').append(`<option value="` + response.city_id + `" selected>` + response.city_name + `</option>`);
					$('#code').val(response.code);
					$('#name').val(response.name);
					$('#phone').val(response.phone);
					$('#fax').val(response.fax);
					$('#website').val(response.website);
					$('#address').val(response.address);
					$('#pic').val(response.pic);
					$('#warning_date_vendor').val(response.warning_date_vendor);
					$('#danger_date_vendor').val(response.danger_date_vendor);
					$('#warning_date_ttbr').val(response.warning_date_ttbr);
					$('#danger_date_ttbr').val(response.danger_date_ttbr);
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
				url: '{{ url("master_data/customer/update") }}' + '/' + id,
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
						url: '{{ url("master_data/customer/destroy") }}',
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