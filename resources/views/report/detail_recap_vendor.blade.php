<style>
    body {
        font-family: 'Lato', sans-serif;
        overflow: visible;
        width: 300%;
    }

    th {
        font-size: 12px;
    }

    table {
        width: 100%;
    }

    .div th:nth-child(2),
    .div th:nth-child(3) {
        border-top: 2px solid rgba(0, 0, 0, 0.1);
    }

    .div th:nth-child(2),
    .div td:nth-child(2) {
        position: sticky;
        left: 250px;
        background-color: #fff;
        width: 200px;

        border-bottom: 2px solid rgba(0, 0, 0, 0.1);

    }

    .div th:nth-child(3),
    .div td:nth-child(3) {
        position: sticky;
        left: 450px;
        background-color: #fff;
        width: 200px;

        border-bottom: 2px solid rgba(0, 0, 0, 0.1);
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
                                <a href="{{url('report/recap')}}" class="btn btn-info"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-arrow-left">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>&nbsp;Kembali</a>
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
                                                {{--
                                                @if ($vendor instanceof Illuminate\Database\Eloquent\Collection)
                                                Vendor :
                                                @foreach ($vendor as $v)
                                                <p> {{ $v->name. ","}} </p>

                                                @endforeach
                                                @else
                                                Vendor :
                                                <p> {{ $vendor->name}} </p>

                                                @endif --}}

                                            </div>
                                        </div>

                                        <div class="row inv--product-table-section">
                                            <div class="col-md-12">

                                                <table cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td style="text-align:start;" colspan="2">
                                                            <img src="{{ asset('website/logo.jpeg') }}"
                                                                style="max-width:300px;" alt="Logo">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="text-align: justify;" class="div">
                                                            <table border="1" cellpadding="12" cellspacing="0"
                                                                style="font-size:13px;" class="table table-bordered">
                                                                <thead>
                                                                    <tr style="text-align:center;margin-right: 100px;">
                                                                        <th>
                                                                            <center>No</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>No Order Muat</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Tgl Order Muat</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Tgl Deadline</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Sisa Hari</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Vendor</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Muat</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Tujuan</center>
                                                                        </th>
                                                                        {{-- <th>
                                                                            <center>Harga(PLT)</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Harga(Vendor)</center>
                                                                        </th> --}}
                                                                        <th>
                                                                            <center>Plat Nomer</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Sopir</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Gudang Tujuan</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Nomer Surat Jalan</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>Tanggal Terima(Vendor)</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>TOTAL HARI (VENDOR)</center>
                                                                        </th>
                                                                        {{-- <th>
                                                                            <center>Tanggal Terima(Legalisir)
                                                                            </center>
                                                                        </th> --}}
                                                                        <th>
                                                                            <center>Berat</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>DOS</center>
                                                                        </th>
                                                                        <th>
                                                                            <center>DOS PECAH</center>
                                                                        </th>

                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                    $no = 1;
                                                                    $currentDate = Carbon\Carbon::now();
                                                                    @endphp
                                                                    @foreach($data as $key => $sj)

                                                                    @php
                                                                    $deadlineDate = date('d M Y',
                                                                    strtotime($sj->order->date."+"
                                                                    .$sj->order->deadline.
                                                                    "days"));

                                                                    if($sj->order->orderCustomerDetail->first()->customer->invoice->first()->receiptDetail)
                                                                    {
                                                                    if($sj->order->orderCustomerDetail->first()->customer->invoice->first()->receiptDetail->receipt->paid_off
                                                                    >=
                                                                    $sj->order->orderCustomerDetail->first()->customer->invoice->first()->receiptDetail->receipt->total)
                                                                    {
                                                                    $paid = 'Lunas';
                                                                    } else {
                                                                    $paid = 'Belum Lunas';
                                                                    }
                                                                    } else {
                                                                    $paid = '-';
                                                                    }

                                                                    $orderDate = Carbon\Carbon::parse($sj->order->date);
                                                                    $vendorReceivedDate =
                                                                    Carbon\Carbon::parse($sj->received_date);
                                                                    $ttbrReceivedDate =
                                                                    Carbon\Carbon::parse($sj->ttbr_received_date);



                                                                    $totalHariVendor =
                                                                    $orderDate->diffInDays( $vendorReceivedDate,
                                                                    false);
                                                                    @endphp
                                                                    <tr>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{ $no }}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{ $sj->order->code}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{date('d M Y',
                                                                                strtotime($sj->order->date)); }}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{ $deadlineDate}}
                                                                            </center>
                                                                        </td>

                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{
                                                                                $currentDate->diffInDays($deadlineDate,
                                                                                false) <= 0 ? "0" :$currentDate->
                                                                                    diffInDays($deadlineDate,
                                                                                    false)}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{ $sj->order->vendor->name}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{
                                                                                $sj->destination->cityOrigin->name}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{
                                                                                $sj->destination->cityDestination->name}}
                                                                            </center>
                                                                        </td>
                                                                        {{-- <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{
                                                                                $sj->destination->destinationPrice->first()->price_customer}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{
                                                                                $sj->destination->destinationPrice->first()->price_vendor}}
                                                                            </center>
                                                                        </td> --}}
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{$sj->order->orderTransport->first()->transport->no_plate}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{$sj->order->orderTransport->first()->driver->name}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{$sj->order->orderTransport->first()->warehouseDestination->name}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{$sj->number}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                @if ($sj->received_date !== null)
                                                                                {{date('d M Y',
                                                                                strtotime($sj->received_date))
                                                                                }}
                                                                                @else
                                                                                {{
                                                                                $sj->received_date
                                                                                }}
                                                                                @endif
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;"
                                                                            class="{{$totalHariVendor >=  $sj->order->orderCustomerDetail->first()->customer->warning_date && $totalHariVendor < $sj->order->orderCustomerDetail->first()->customer->danger_date ? "
                                                                            bg-warning" : ($totalHariVendor>
                                                                            $sj->order->orderCustomerDetail->first()->customer->danger_date
                                                                            ? "bg-danger":"bg-success")}}">
                                                                            <center>
                                                                                <b style="color:black;">
                                                                                    {{$totalHariVendor}}</b>
                                                                            </center>
                                                                        </td>

                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{$sj->weight." Kg"}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;">
                                                                            <center>
                                                                                {{$sj->qty. $sj->order->unit->name}}
                                                                            </center>
                                                                        </td>
                                                                        <td style="vertical-align:center;"
                                                                            class="{{$sj->ttbr_received_date !== null ? 'bg-success':'bg-danger'}}">
                                                                            <center>
                                                                                <b style="color:black;">
                                                                                    {{$sj->ttbr_qty.
                                                                                    $sj->order->unit->name}}
                                                                                </b>
                                                                            </center>
                                                                        </td>

                                                                    </tr>
                                                                    @php
                                                                    $no++;
                                                                    @endphp
                                                                    @endforeach
                                                                </tbody>

                                                            </table><br><br>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: justify;">
                                                            &nbsp;
                                                        </td>

                                                    </tr>
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
</div>