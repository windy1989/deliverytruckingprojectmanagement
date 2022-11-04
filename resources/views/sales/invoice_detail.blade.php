<style>
    @media print {
        .main-content {
            margin-left: 0 !important;
            margin-top: 0 !important;
            padding-bottom: 0 !important;
            margin-bottom: 500px !important;
        }

        .layout-px-spacing {
            padding: 0 !important;
            min-height: 0 !important;
        }

        .widget-content-area {
            padding: 0 !important;
        }

        .main-container {
            min-height: 0 !important;
        }

        #no-print {
            display: none;
        }
    }
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area">
                    <div class="col-md-12" id="no-print">
                        <div class="row mb-5">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('sales/invoice') }}" class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>&nbsp;Kembali</a>
                                <a href="{{ url('generatepdf').'/'.$invoice->id }}" target="_blank" class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>&nbsp;Cetak</a>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-header-section">
                        <div class="invoice-container">
                            <div id="ct">
                                <div class="invoice-0001">
                                    <div class="content-section animated animatedFadeInUp fadeInUp">
                                        <div class="inv--head-section mb-5">
                                            <img src="{{ asset('website/logo.jpeg') }}" style="max-width:300px;" alt="Logo">
                                        </div>
                                        <div class="row inv--detail-section mb-4">
                                            <div class="text-dark col-md-6 col-xs-6 col-sm-6">
                                                <b>Customer :</b>
                                                <p class="text-dark"><b>{{ $invoice->customer->name }}</b></p>
                                                <p class="text-dark"><b>{{ $invoice->customer->city->name }}</b></p>
                                            </div>
                                            <div class="col-md-6 col-xs-6 col-sm-6 text-right">
                                                <p class="text-dark"><b>No. Inv</b> : <b>{{ $invoice->code }}</b></p>
                                                <p class="text-dark"><b>Tanggal</b> : <b>{{ date('d M Y', strtotime($invoice->created_at)) }}</b></p>
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
                                                                <td class="text-dark align-middle"><b>{{ $key + 1 }}</b></td>
                                                                <td class="text-dark align-middle"><b>{{ $id->letterWay->number }}</b></td>
                                                                <td class="text-dark align-middle"><b>{{ $id->letterWay->qty }} {{ $id->letterWay->order->unit->name }}</b></td>
                                                                <td class="text-dark align-middle"><b>{{ $id->letterWay->weight }} Kg</b></td>
                                                                <td class="text-dark align-middle">
                                                                    <b>Rp {{ number_format($price->price_customer, 2, ',', '.') }}</b>
                                                                </td>
                                                                <td class="text-dark align-middle"><b>Rp {{ number_format($id->total, 2, ',', '.') }}</b></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5" class="text-dark text-right"><b>SUBTOTAL :</b></th>
                                                            <td width="35%" class="text-dark text-right">
                                                                <b>Rp {{ number_format($invoice->subtotal, 2, ',', '.') }}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-dark text-right">DISKON ({{ $invoice->discount }}%) :</th>
                                                            <td width="35%" class="text-dark text-right">
                                                                @php $total_discount = ($invoice->discount / 100) * $invoice->subtotal; @endphp
                                                                <b>Rp {{ number_format($total_discount, 2, ',', '.') }}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-dark text-right">PAJAK ({{ $invoice->tax }}%) :</th>
                                                            <td width="35%" class="text-dark text-right">
                                                                @php $total_tax = ($invoice->tax / 100) * $invoice->subtotal; @endphp
                                                                <b>Rp {{ number_format($total_tax, 2, ',', '.') }}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-dark text-right">DP :</th>
                                                            <td width="35%" class="text-dark text-right">
                                                                <b>Rp {{ number_format($invoice->down_payment, 2, ',', '.') }}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-dark text-right">GRANDTOTAL :</th>
                                                            <td width="35%" class="text-dark text-right">
                                                                <b>Rp {{ number_format($invoice->grandtotal, 2, ',', '.') }}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" class="text-dark align-middle font-weight-bold">
                                                                Transfer Via :<br>
                                                                {{ $invoice->journal->coaCredit->name }} - IDR<br>
                                                                Acc Name. : {{ $invoice->journal->coaCredit->description }}
                                                            </td>
                                                            <td colspan="2" class="text-dark align-middle">
                                                                <b>TERBILANG :</b>
                                                                <div class="text-dark font-italic text-uppercase"><b>{{ App\Models\Invoice::numberToWord($invoice->grandtotal) }}</b></div>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="col-md-12 mt-5">
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6 col-sm-6">
                                                        <div class="text-dark text-center">
                                                            Dibuat Oleh,<br>
                                                            @if($invoice->user->signature)
                                                                <img src="{{ $invoice->user->signature() }}" style="width:147px; height:83px;" class="img-fluid">
                                                            @else
                                                                <br><br><br><br><br>
                                                            @endif
                                                            <p class="text-dark text-uppercase" style="text-transform:underline;">
                                                                <b> ({{ $invoice->user->name }})</b>
                                                            </p>
                                                            <p text-dark><small><b>Finance Officer</b></small></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6 col-sm-6">
                                                        <div class="text-dark text-center">
                                                            Disetujui Oleh,<br><br><br><br><br>
                                                            <p class="text-dark text-uppercase" style="text-transform:underline;">
                                                                (.......................................................)
                                                            </p>
                                                            <p><small><b>CFO</b></small></p>
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
            </div>
        </div>
    </div>
