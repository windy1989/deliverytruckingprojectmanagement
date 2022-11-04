<style>
    @page {
        size: auto;
        margin: 10mm 10mm 10mm 10mm;
    }

    body {
        margin: 0px;
    }

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
                                <div class="row justify-content-end">
                                    <div class="col-md-8 text-left">
                                        <h4 class="mt-2">Cetak Klaim Penjualan</h4>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select name="choose" id="choose" class="custom-select" onchange="chooseable()">
                                                <option value="1">Pecah</option>
                                                <option value="2">Terlambat</option>
                                                <option value="3">Tidak Bermasalah</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-info col-12" onclick="window.print()" style="height:40px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>&nbsp;Cetak</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-header-section">
                        <div class="invoice-container">
                            <div id="ct">
                                <div class="invoice-0001">
                                    <div class="content-section animated animatedFadeInUp fadeInUp">
                                        <div class="row inv--product-table-section">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th class="align-middle" colspan="6">
                                                            <h5 class="font-weight-bold">PT.DIGITAL TRANS INDONESIA</h5>
                                                        </th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th class="align-middle text-dark" colspan="2">
                                                            Keterangan : {{ $claim->description }}
                                                        </th>
                                                        <th class="align-middle text-dark" colspan="2">
                                                            NO. {{ $claim->claimable->code }}
                                                        </th>
                                                        <th class="align-middle text-dark" colspan="2">
                                                            Tanggal : {{ date('F d, Y', strtotime($claim->date)) }}
                                                        </th>
                                                    </tr>
                                                    <tr class="text-center claim_head_destroy">
                                                        <th colspan="6">KLAIM PECAH</th>
                                                    </tr>
                                                    <tr class="text-center claim_head_destroy">
                                                        <th class="text-dark" colspan="2">Surat Jalan</th>
                                                        <th class="text-dark">Status</th>
                                                        <th class="text-dark">Nominal</th>
                                                        <th class="text-dark">Toleransi</th>
                                                        <th class="text-dark">Total</th>
                                                    </tr>
                                                    <tr class="text-center claim_head_late">
                                                        <th colspan="6">KLAIM TERLAMBAT</th>
                                                    </tr>
                                                    <tr class="text-center claim_head_late">
                                                        <th class="text-dark" colspan="3">Surat Jalan</th>
                                                        <th class="text-dark">Status</th>
                                                        <th class="text-dark">Persentase</th>
                                                        <th class="text-dark">Total</th>
                                                    </tr>
                                                    <tr class="text-center claim_head_clean">
                                                        <th colspan="6">KLAIM TIDAK BERMASALAH</th>
                                                    </tr>
                                                    <tr class="claim_head_clean">
                                                        <th class="text-dark" colspan="6">Surat Jalan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $claim_total_destroy = 0;
                                                        $claim_total_late    = 0;
                                                    @endphp
                                                    @foreach($claim->claimable->receiptDetail as $rd)
                                                        @foreach($rd->invoice->invoiceDetail as $id)
                                                            @php
                                                                $deadline        = $id->letterWay->order->deadline;
                                                                $order_date      = date('Y-m-d', strtotime("+$deadline day", strtotime($id->letterWay->order->date)));
                                                                $letter_way_date = date('Y-m-d', strtotime($id->letterWay->updated_at));
                                                                $ttbr_qty        = $id->letterWay->ttbr_qty;
                                                                $difference_late = strtotime($letter_way_date) - strtotime($order_date);
                                                                $total_late      = $difference_late / 60 / 60 / 24;
                                                                $claim_detail    = App\Models\ClaimDetail::where('claim_id', $claim->id)
                                                                    ->where('letter_way_id', $id->letterWay->id)
                                                                    ->first();
                                                            @endphp
                                                            @if($ttbr_qty > 0)
                                                                <tr class="claim_body_destroy text-center">
                                                                    @php $total = $claim_detail->nominal - $claim_detail->tolerance; @endphp
                                                                    <td class="align-middle" colspan="2">{{ $id->letterWay->number }}</td>
                                                                    <td class="align-middle">Pecah <b>{{ $ttbr_qty }}</b></td>
                                                                    <td class="align-middle">
                                                                        Rp {{ number_format($claim_detail->nominal, 2, ',', '.') }}
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        Rp {{ number_format($claim_detail->tolerance, 2, ',', '.') }}
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        Rp {{ number_format($total, 2, ',', '.') }}
                                                                    </td>
                                                                    @php $claim_total_destroy += $total; @endphp
                                                                </tr>
                                                            @elseif($letter_way_date > $order_date)
                                                                <tr class="claim_body_late text-center">
                                                                    @php
                                                                        if($claim->claimable->flag == 1) {
                                                                            $calculate_by = $id->letterWay->weight;
                                                                        } else {
                                                                            $calculate_by = $id->letterWay->qty;
                                                                        }

                                                                        if($id->letterWay->destination->destinationPrice->last()) {
                                                                            $price          = $id->letterWay->destination->destinationPrice->last();
                                                                            $price_customer = $price->price_customer;
                                                                        } else {
                                                                            $price_customer = 0;
                                                                        }

                                                                        $percentage = $claim_detail ? $claim_detail->percentage : 0;
                                                                        $total      = $total_late * ($calculate_by * $price_customer) * ($percentage / 100);
                                                                    @endphp
                                                                    <td class="align-middle" colspan="3">{{ $id->letterWay->number }}</td>
                                                                    <td class="align-middle">Terlambat <b>{{ $total_late }}</b> Hari</td>
                                                                    <td class="align-middle">{{ $percentage }}%</td>
                                                                    <td class="align-middle">Rp {{ number_format($total, 2, ',', '.') }}</td>
                                                                    @php $claim_total_late += $total; @endphp
                                                                </tr>
                                                            @else
                                                                <tr class="claim_body_clean">
                                                                    <td class="align-middle text-left" colspan="6">{{ $id->letterWay->number }}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="claim_total">
                                                    <tr>
                                                        <th class="text-right" colspan="4">TOTAL</th>
                                                        <th colspan="2">
                                                            <span class="claim_total_destroy">
                                                                Rp {{ number_format($claim_total_destroy, 2, ',', '.') }}
                                                            </span>
                                                            <span class="claim_total_late">
                                                                Rp {{ number_format($claim_total_late, 2, ',', '.') }}
                                                            </span>
                                                        </th>
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

<script>
    $(function() {
        chooseable();
    });

    function chooseable() {
        var value = $('#choose').val();
        if(value == 1) {
            $('.claim_total').show();
            $('.claim_head_destroy').show();
            $('.claim_body_destroy').show();
            $('.claim_total_destroy').show();
            $('.claim_head_late').hide();
            $('.claim_body_late').hide();
            $('.claim_total_late').hide();
            $('.claim_head_clean').hide();
            $('.claim_body_clean').hide();
        } else if(value == 2) {
            $('.claim_total').show();
            $('.claim_head_destroy').hide();
            $('.claim_body_destroy').hide();
            $('.claim_total_destroy').hide();
            $('.claim_head_late').show();
            $('.claim_body_late').show();
            $('.claim_total_late').show();
            $('.claim_head_clean').hide();
            $('.claim_body_clean').hide();
        } else {
            $('.claim_total').hide();
            $('.claim_head_destroy').hide();
            $('.claim_body_destroy').hide();
            $('.claim_head_late').hide();
            $('.claim_body_late').hide();
            $('.claim_head_clean').show();
            $('.claim_body_clean').show();
        }
    }
</script>
