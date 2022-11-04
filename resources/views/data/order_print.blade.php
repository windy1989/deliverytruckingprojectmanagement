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

        .no-print {
            display: none;
        }
    }
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area">
                    <div class="col-md-12 no-print">
                        <div class="row mb-5">
                            <div class="col-md-12 text-right">
                                <button type="button" onclick="location.href='{{ url('data/order') }}'" class="btn btn-info" style="height:45px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>&nbsp;Kembali</button>
                                <button type="button" class="btn btn-info" onclick="window.print()" style="height:45px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>&nbsp;Cetak</button>
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
                                        <div class="row inv--detail-section mb-5">
                                            <div class="col-md-12 text-center">
                                                <h4 class="font-weight-bold">
                                                    <span style="border-bottom:3px solid #3B3F5C;"><b>ORDER MUAT</b></span>
                                                </h4>
                                                <span class="text-dark" style="font-size:16px;">
                                                    <b>{{ $order->code }}</b>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row inv--product-table-section">
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <h6 class="text-dark">
                                                    <b>Kepada Yth.</b><br>
                                                    @if($order->orderCustomerDetail->count() > 0)
                                                        @foreach($order->orderCustomerDetail as $key => $od)
                                                            @if($key + 1 == $order->orderCustomerDetail->count())
                                                                <b style="text-text-uppercase">{{ $od->customer->name }}</b>
                                                            @else
                                                                <b style="text-text-uppercase">{{ $od->customer->name }}</b>,
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <b style="text-text-uppercase">{{ $order->customer->name }}</b>
                                                    @endif
                                                    <br><br>
                                                    Dengan Hormat,
                                                </h6>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                <div class="row">
                                                    <div class="text-dark col-md-6 col-sm-6 col-xs-6"><b>Berlaku</b></div>
                                                    <div class="text-dark col-md-6 col-sm-6 col-xs-6"><b>Hari</b></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-4 mb-4">
                                                <div class="row mb-3">
                                                    <div class="text-dark col-md-3 col-sm-3 col-xs-3"><b>Mohon Truk Kami No.Pol</b></div>
                                                    <div class="text-dark col-md-9 col-sm-9 col-xs-9"> :
                                                        <b>
                                                            @if($order->orderTransport->count() > 0)
                                                                @foreach($order->orderTransport as $key => $ot)
                                                                    @if($key + 1 == $order->orderTransport->count())
                                                                        {{ $ot->transport->no_plate }} ({{ $ot->driver->name }})
                                                                    @else
                                                                        {{ $ot->transport->no_plate }} ({{ $ot->driver->name }}),
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                Tidak Ada
                                                            @endif
                                                        </b>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="text-dark col-md-3 col-sm-3 col-xs-3"><b>Dilayani Angkutan</b></div>
                                                    <div class="text-dark col-md-9 col-sm-9 col-xs-9"> :
                                                        <b>KERAMIK</b>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="text-dark col-md-3 col-sm-3 col-xs-3"><b>Seberat</b></div>
                                                    <div class="text-dark col-md-9 col-sm-9 col-xs-9"> :
                                                        <b>{{ $order->weight }} Kg</b>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="text-dark col-md-3 col-sm-3 col-xs-3"><b>Asal Muat</b></div>
                                                    <div class="text-dark col-md-9 col-sm-9 col-xs-9">
                                                                @if($order->orderTransport->count() > 0)
                                                                    @foreach($order->orderTransport as $key => $ot)
                                                                    <li>
                                                                        <b> {{ $ot->warehouseOrigin->name }} &rarr; {{ $order->orderDestination[$key]->destination->cityOrigin->name }}</b>
                                                                    </li>
                                                                    @endforeach
                                                                @else
                                                                    Tidak Ada
                                                                @endif

                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <div class="text-dark col-md-3 col-sm-3 col-xs-3"><b>Tujuan</b></div>
                                                    <div class="text-dark col-md-9 col-sm-9 col-xs-9">

                                                            @if($order->orderTransport->count() > 0)
                                                                @foreach($order->orderTransport as $key => $ot)
                                                                <li>
                                                                    <b> {{ $ot->warehouseDestination->name }} &rarr; {{ $order->orderDestination[$key]->destination->cityDestination->name }}</b>
                                                                </li>

                                                                @endforeach
                                                            @else
                                                                Tidak Ada
                                                            @endif

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="text-dark col-md-12"><b>Atas perhatian dan bantuan Sdr kami ucapkan terima kasih.</b></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-5">
                                                <div class="row">
                                                    <div class="text-dark col-md-12 text-right mb-4"><b>Surabaya, {{ $order->created_at->toDateString() }}</b></div>
                                                    <div class="col-md-6 col-xs-6 col-sm-6">
                                                        <div class="text-dark text-center">
                                                            <b>Yang Terima Sopir,</b><br><br><br><br><br>
                                                            <p class="text-uppercase" style="text-transform:underline;">
                                                                (........................................................)
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6 col-sm-6">
                                                        <div class="text-dark text-center">
                                                            <b>Disetujui Oleh,</b><br>
                                                            @if($order->user->signature)
                                                                <img src="{{ $order->user->signature() }}" style="width:147px; height:83px;" class="img-fluid">
                                                            @else
                                                                <br><br><br><br><br>
                                                            @endif
                                                            <p class="text-uppercase" style="text-dark text-transform:underline;">
                                                                <b>({{ $order->user->name }})</b>
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
