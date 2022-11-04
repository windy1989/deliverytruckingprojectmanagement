<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area">
                    <div class="col-md-12" id="no-print">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('report/receipt') }}" class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>&nbsp;Kembali</a>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-header-section">
                        <div class="invoice-container">     
                            <div id="ct">
                                <div class="invoice-0001">
                                    <div class="content-section animated animatedFadeInUp fadeInUp">
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
                                                </table>
                                            </div>
                                            <div class="col-md-12 mt-4">
                                                <div class="text-center">
                                                    <h5 class="font-weight-bold">
                                                        <span style="text-transform:underline !important;">
                                                            Jumlah Rp. {{ number_format($receipt->total, 2, ',', '.') }}
                                                        </span>
                                                    </h5>
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