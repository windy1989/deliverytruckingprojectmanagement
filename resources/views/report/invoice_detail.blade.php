<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area">
                    <div class="col-md-12" id="no-print">
                        <div class="row mb-5">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('report/invoice') }}" class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>&nbsp;Kembali</a>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-header-section">
                        <div class="invoice-container">
                            <div id="ct">
                                <div class="invoice-0001">
                                    <div class="content-section animated animatedFadeInUp fadeInUp">
                                        <div class="row inv--detail-section mb-4">
                                            <div class="col-md-6 col-xs-6 col-sm-6">
                                                Customer :
                                                <p>{{ $invoice->customer->name }}</p>
                                                <p>{{ $invoice->customer->city->name }}</p>
                                            </div>
                                            <div class="col-md-6 col-xs-6 col-sm-6 text-right">
                                                <p><b>No. Inv</b> : {{ $invoice->code }}</p>
                                                <p><b>Tanggal</b> : {{ date('d M Y', strtotime($invoice->created_at)) }}</p>
                                            </div>
                                        </div>
                                        <div class="row inv--product-table-section">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>No</th>
                                                            <th>Surat Jalan</th>
                                                            <th>Qty</th>
                                                            <th>Berat</th>
                                                            <th>Harga Per Kg</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($invoice->invoiceDetail as $key => $id)
                                                            @php
                                                                $price = $id->letterWay->destination->destinationPrice->last();
                                                            @endphp
                                                            <tr class="text-center">
                                                                <td class="align-middle">{{ $key + 1 }}</td>
                                                                <td class="align-middle">{{ $id->letterWay->number }}</td>
                                                                <td class="align-middle">{{ $id->letterWay->qty }} {{ $id->letterWay->order->unit->name }}</td>
                                                                <td class="align-middle">{{ $id->letterWay->weight }} Kg</td>
                                                                <td class="align-middle">
                                                                    Rp {{ number_format($price->price_customer, 2, ',', '.') }}
                                                                </td>
                                                                <td class="align-middle">Rp {{ number_format($id->total, 2, ',', '.') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5" class="text-right">SUBTOTAL :</th>
                                                            <td width="35%" class="text-right">
                                                                Rp {{ number_format($invoice->subtotal, 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-right">DISKON ({{ $invoice->discount }}%) :</th>
                                                            <td width="35%" class="text-right">
                                                                @php $total_discount = ($invoice->discount / 100) * $invoice->subtotal; @endphp
                                                                Rp {{ number_format($total_discount, 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-right">PAJAK ({{ $invoice->tax }}%) :</th>
                                                            <td width="35%" class="text-right">
                                                                @php $total_tax = ($invoice->tax / 100) * $invoice->subtotal; @endphp
                                                                Rp {{ number_format($total_tax, 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-right">DP :</th>
                                                            <td width="35%" class="text-right">
                                                                Rp {{ number_format($invoice->down_payment, 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-right">GRANDTOTAL :</th>
                                                            <td width="35%" class="text-right">
                                                                Rp {{ number_format($invoice->grandtotal, 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6" class="align-middle">
                                                                TERBILANG :
                                                                <div class="font-italic text-uppercase">{{ App\Models\Invoice::numberToWord($invoice->grandtotal) }}</div>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
