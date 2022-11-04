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

<head>
    <script>
        function openWin() {
            myWindow.print();
        }
      </script>
</head>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area">
                    <div class="col-md-12" id="no-print">
                        <div class="row mb-5">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('sales/receipt') }}" class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>&nbsp;Kembali</a>
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
                                            <img src="{{ asset('website/logo.jpeg') }}" style="max-width:300px;" alt="Logo">
                                        </div>
                                        <div class="row inv--product-table-section">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="20%" class="text-dark align-middle">Sudah Terima Dari</th>
                                                            <th colspan="2" class="text-dark align-middle">{{ $receipt->customer->name }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th width="20%" class="text-dark align-middle">Banyaknya Uang</th>
                                                            <th colspan="2" class="text-dark align-middle">
                                                                {{ App\Models\Receipt::numberToWord($receipt->total) }}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th width="20%" class="text-dark align-middle">Untuk Pembayaran</th>
                                                            <th colspan="2" class="text-dark align-middle">
                                                                @foreach($receipt->receiptDetail as $key => $rd)
                                                                    @if($key + 1 == $receipt->receiptDetail->count())
                                                                        {{ $rd->invoice->code }}
                                                                    @else
                                                                        {{ $rd->invoice->code }},
                                                                    @endif
                                                                @endforeach
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td width="20%" class="text-dark font-weight-bold align-middle">CEK / GIRO NO</td>
                                                            <td class="text-dark font-weight-bold align-middle">
																@foreach($receipt->receiptDetail as $key => $rd)
																	Transfer Via :<br>
																	{{ $rd->invoice->journal->coaCredit->name }} - IDR<br>
																	Acc Name. : {{ $rd->invoice->journal->coaCredit->description }}<br>
																@endforeach
                                                            </td>
                                                            <td class="text-dark font-weight-bold align-middle text-center">
                                                                {{ $receipt->code }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12 mt-5">
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6 col-sm-6">
                                                        <div class="text-center">
                                                            <h5 class="text-dark font-weight-bold" style="line-height:200px;">
                                                                <span style="text-transform:underline !important;">
                                                                    Jumlah Rp. {{ number_format($receipt->total, 2, ',', '.') }}
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6 col-sm-6">
                                                        <div class="text-dark text-center">
                                                            Surabaya, {{ date('d F Y') }}<br><br><br><br><br>
                                                            <p class="text-dark text-uppercase" style="text-transform:underline;">
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
