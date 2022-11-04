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
                                <a href="{{ url('report/invoice') }}" class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>&nbsp;Kembali</a>
                                <button type="button" class="btn btn-info" onclick="window.print()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>&nbsp;Cetak</button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-header-section">
                        <div class="invoice-container">
                            <div id="ct">
                                <div class="invoice-0001">
                                    <div class="content-section animated animatedFadeInUp fadeInUp">
                                        <div class="inv--head-section mb-5">
                                            <center>
                                                <img src="{{ asset('website/logo.jpeg') }}" style="max-width:300px;" class="mb-4">
                                                <h4 class="font-weight-bold">
                                                    <span style="border-bottom:3px solid #3B3F5C;">TANDA TERIMA</span>
                                                </h4>
                                            </center>
                                        </div>
                                        <div class="row inv--detail-section mb-4">
                                            <div class="col-md-6 col-xs-6 col-sm-6">
                                                Customer :
                                                <p>{{ $receipt->customer->name }}</p>
                                                <p>{{ $receipt->customer->city->name }}</p>
                                            </div>
                                            <div class="col-md-6 col-xs-6 col-sm-6 text-right">
                                                <p><b>No. Kwitansi</b> : {{ $receipt->code }}</p>
                                                <p><b>Tanggal</b> : {{ date('d M Y', strtotime($receipt->created_at)) }}</p>
                                            </div>
                                        </div>
                                        <div class="row inv--product-table-section">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>No</th>
                                                            <th>Invoice</th>
                                                            <th>Total Tagihan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $total = 0; @endphp
                                                        @foreach($receipt->receiptDetail as $key => $rd)
                                                            @php $total += $rd->invoice->grandtotal; @endphp
                                                            <tr class="text-center">
                                                                <td class="align-middle">{{ $key + 1 }}</td>
                                                                <td class="align-middle">{{ $rd->invoice->code }}</td>
                                                                <td class="align-middle">Rp {{ number_format($rd->invoice->grandtotal, 2, ',', '.') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="2" class="text-right">BIAYA LAIN :</th>
                                                            <td width="35%" class="text-right"><b>Rp {{ number_format($receipt->other, 2, ',', '.') }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="2" class="text-right">TOTAL KESELURUHAN :</th>
                                                            <td width="35%" class="text-right"><b>Rp {{ number_format($total, 2, ',', '.') }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="align-middle">
                                                                TERBILANG :
                                                                <div class="font-italic text-uppercase">{{ App\Models\Receipt::numberToWord($total) }}</div>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="col-md-12 mt-5">
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6 col-sm-6">
                                                        <div class="text-center">
                                                            Dibuat Oleh,<br>
                                                            @if($receipt->user->signature)
                                                                <img src="{{ $receipt->user->signature() }}" style="width:147px; height:83px;" class="img-fluid">
                                                            @else
                                                                <br><br><br><br><br>
                                                            @endif
                                                            <p class="text-uppercase" style="text-transform:underline;">
                                                                ({{ $receipt->user->name }})
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6 col-sm-6">
                                                        <div class="text-center">
                                                            Disetujui Oleh,<br><br><br><br><br>
                                                            <p class="text-uppercase" style="text-transform:underline;">
                                                                (.......................................................)
                                                            </p>
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
