<table>
    <thead>
        <tr>
            <th bgcolor="#9cc458">
                <center>No</center>
            </th>
            <th bgcolor="#9cc458">
                <center>No Order Muat</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Tgl Order Muat</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Tgl Deadline</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Sisa Hari</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Vendor</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Muat</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Tujuan</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Plat Nomer</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Sopir</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Gudang Tujuan</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Nomer Surat Jalan</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Tanggal Terima(Vendor)</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Total Hari Vendor</center>
            </th>
            <th bgcolor="#9cc458">
                <center>Berat</center>
            </th>
            <th bgcolor="#9cc458">
                <center>DOS</center>
            </th>
            <th bgcolor="#9cc458">
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
            <td>
                <center>
                    {{ $no }}
                </center>
            </td>
            <td>
                <center>
                    {{ $sj->order->code}}
                </center>
            </td>
            <td>
                <center>
                    {{date('d M Y',
                    strtotime($sj->order->date)); }}
                </center>
            </td>
            <td>
                <center>
                    {{ $deadlineDate}}
                </center>
            </td>

            <td>
                <center>
                    {{
                    $currentDate->diffInDays($deadlineDate,
                    false) <= 0 ? "0" :$currentDate->
                        diffInDays($deadlineDate,
                        false)}}
                </center>
            </td>
            <td>
                <center>
                    {{ $sj->order->vendor->name}}
                </center>
            </td>
            <td>
                <center>
                    {{
                    $sj->destination->cityOrigin->name}}
                </center>
            </td>
            <td>
                <center>
                    {{
                    $sj->destination->cityDestination->name}}
                </center>
            </td>
            <td>
                <center>
                    {{$sj->order->orderTransport->first()->transport->no_plate}}
                </center>
            </td>
            <td>
                <center>
                    {{$sj->order->orderTransport->first()->driver->name}}
                </center>
            </td>
            <td>
                <center>
                    {{$sj->order->orderTransport->first()->warehouseDestination->name}}
                </center>
            </td>
            <td>
                <center>
                        {{$sj->number}}
                </center>
            </td>
            <td>
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
                bgcolor="{{$totalHariVendor >=  $sj->order->orderCustomerDetail->first()->customer->warning_date_vendor && $totalHariVendor < $sj->order->orderCustomerDetail->first()->customer->danger_date_vendor ? "#fcba03" :($totalHariVendor > $sj->order->orderCustomerDetail->first()->customer->danger_date_vendor ? "#fa5555":"#2dba50")}}">
                <center>
                    <b style="color:black;">
                        {{$totalHariVendor}}</b>
                </center>
            </td>
            <td>
                <center>
                    {{$sj->weight." Kg"}}
                </center>
            </td>
            <td>
                <center>
                    {{$sj->qty. $sj->order->unit->name}}
                </center>
            </td>
            <td bgcolor="{{$sj->ttbr_received_date !== null ? '#2dba50':'#fa5555'}}">
                <center>
                    <b style="color:black;">
                    {{$sj->ttbr_qty.
                    $sj->order->unit->name}}</b>
                </center>
            </td>

        </tr>
        @php
        $no++;
        @endphp
        @endforeach
    </tbody>
</table>