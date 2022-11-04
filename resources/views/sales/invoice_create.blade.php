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
                            <h5 class="mt-2">Generate Invoice</h5>
                        </div>
                    </div>
                    <form action="{{ url('sales/invoice/create') }}" method="POST" id="form_data">
                        @csrf
                        <div class="form-group">
                            <label>Customer :</label>
                                <select name="customer_id" id="customer_id" class="select2 form-control" style="width:100%;" onchange="getLetterWay()" required>
                                <option value="">-- Pilih --</option>
                                @foreach($customer as $c)
                                    <option value="{{ $c->id }}" {{ $c->id == old('customer_id') ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><hr class="bg-success"></div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kode :</label>
                                    <input type="text" class="form-control" name="codeOrder" id="codeOrder" placeholder="Masukan kode surat jalan" value="{{ old('codeOrder') }}">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Tanggal :</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="start_date" name="start_date" max="{{ date('Y-m-d') }}" value="{{ $start_date }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">s/d</span>
                                        </div>
                                        <input type="date" class="form-control" id="finish_date" name="finish_date" max="{{ date('Y-m-d') }}" value="{{ $finish_date }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label class="text-white">.</label>
                                    <button type="button" class="btn btn-success btn-sm col-12" style="border-radius:0; height:46px;" onclick="getLetterWay()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-primary">
                                    <tr class="text-center font-weight-bold">
                                        <td>#</td>
                                        <td>Order</td>
                                        <td>No SJ</td>
                                        <td>Qty</td>
                                        <td>Berat</td>
                                        <td>Harga Per Kg</td>
                                        <td>Total</td>
                                    </tr>
                                </thead>
                                <tbody id="data_letter_way">
                                    @if(old('invoice_detail'))
                                        @php
                                            $letter_way = App\Models\LetterWay::whereHas('order', function($query) {
                                                    $query->where('customer_id', old('customer_id'));
                                                })
                                                ->whereNotExists(function($query) {
                                                    $query->select(DB::raw(1))
                                                        ->from('invoice_details')
                                                        ->whereColumn('invoice_details.letter_way_id', 'letter_ways.id');
                                                })
                                                ->where('status', 2)
                                                ->get();
                                        @endphp
                                        @foreach($letter_way as $key => $lw)
                                            @php
                                                $price   = $lw->destination->destinationPrice->last();
                                                $find_id = in_array($lw->id, old('invoice_detail'));
                                            @endphp

                                            <tr class="text-center">
                                                <td class="align-middle">
                                                    <input type="checkbox" name="invoice_detail[]" value="{{ $lw->id }}" onclick="total()" {{ $find_id ? 'checked' : '' }}>
                                                </td>
                                                <td class="align-middle">{{ $lw->order->code }}</td>
                                                <td class="align-middle">{{ $lw->number }}</td>
                                                <td class="align-middle">{{ $lw->qty }} {{ $lw->order->unit->name }}</td>
                                                <td class="align-middle">{{ $lw->weight }} Kg</td>
                                                <td class="align-middle">Rp {{ number_format($price->price_customer) }}</td>
                                                <td class="align-middle">
                                                    Rp {{ number_format($price->price_customer * ($lw->weight)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td class="align-middle" colspan="7">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right align-middle">DP</th>
                                        <th width="30%">
                                            <div class="form-group input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" name="down_payment" id="down_payment" value="{{ old('down_payment') ? old('down_payment') : 0 }}" placeholder="0" onkeyup="total()">
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-right align-middle">PAJAK</th>
                                        <th width="30%">
                                            <div class="form-group input-group">
                                                <input type="number" class="form-control" name="tax" id="tax" value="{{ old('tax') ? old('tax') : 0 }}" placeholder="0" onkeyup="total()">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-right align-middle">DISKON</th>
                                        <th width="30%">
                                            <div class="form-group input-group">
                                                <input type="number" class="form-control" name="discount" id="discount" value="{{ old('discount') ? old('discount') : 0 }}" placeholder="0" onkeyup="total()">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-right align-middle">SUBTOTAL</th>
                                        <th width="30%">
                                            <div class="form-group input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control" name="subtotal" id="subtotal" placeholder="0" readonly>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-right align-middle">GRANDTOTAL</th>
                                        <th width="30%">
                                            <div class="form-group input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control" name="grandtotal" id="grandtotal" placeholder="0" readonly>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="form-group"><hr class="bg-success"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Akun :</label>
                                    <select name="coa_debit" id="coa_debit" class="form-control select2" style="width:100%;">
                                        @foreach($coa_debit as $cb)
                                            <optgroup label="{{ $cb->code }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $cb->name }}">
                                                @foreach($cb->sub->where('status', 1) as $s)
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
                                    <label>Pembalik :</label>
                                    <select name="coa_credit" id="coa_credit" class="form-control select2" style="width:100%;">
                                        @foreach($coa_credit as $cc)
                                            <optgroup label="{{ $cc->code }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $cc->name }}">
                                                @foreach($cc->sub->where('status', 1) as $s)
                                                    <option value="{{ $s->code }}">
                                                        {{ $s->code }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $s->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group"><hr class="bg-success"></div>
                        <div class="form-group text-right">
                            <a href="{{ url('sales/invoice') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Generate</button>
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

        total();
    });

    function total() {
        $.ajax({
            url: '{{ url("sales/invoice/total_invoice") }}',
            type: 'GET',
            dataType: 'JSON',
            data: $('#form_data').serialize(),
            success: function(response) {
                $('#subtotal').val(response.subtotal);
                $('#grandtotal').val(response.grandtotal);
            }
        });
    }

    function getLetterWay() {
        $.ajax({
            url: '{{ url("sales/invoice/get_letter_way") }}',
            type: 'POST',
            dataType: 'JSON',
            data: {
                customer_id: $('#customer_id').val(),
                start_date: $('#start_date').val(),
                finish_date: $('#finish_date').val(),
                code_order: $('#codeOrder').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                loadingOpen('.widget-content');
                $('#data_letter_way').html('');
            },
            success: function(response) {
                loadingClose('.widget-content');
                if(response.data.length > 0 || (response.start_date && response.finish_date)) {
                    $.each(response.data, function(i, val) {
                        $('#data_letter_way').append(`
                            <tr>
                                <td class="align-middle text-center">
                                    <input type="checkbox" name="invoice_detail[]" value="` + val.id + `" onclick="total()" checked>
                                </td>
                                <td class="align-middle">` + val.order_code + `</td>
                                <td class="align-middle">` + val.number + `</td>
                                <td class="align-middle">` + val.qty + `</td>
                                <td class="align-middle">` + val.weight + `</td>
                                <td class="align-middle">` + val.price + `</td>
                                <td class="align-middle">` + val.total + `</td>
                            </tr>
                        `);
                    });
                } else {
                    $('#data_letter_way').html(`
                        <tr class="text-center">
                            <td class="align-middle" colspan="7">Tidak ada data.</td>
                        </tr>
                    `);
                }

                total();
            },
            error: function() {
                loadingClose('.widget-content');
                notif('Server Error!', '#DC3545');
            }
        });
    }
</script>
