<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Digitrans Invoice</title>
    <style>
        @page {
            margin: 200px 50px;
        }

        .header {
            position: fixed;
            left: 0px;
            top: -150px;
            right: 0px;
            text-align: center;
            display: block;
        }

        .kasihtau {
            position: relative;
            left: 0px;
            top: 250px;
            right: 0px;
            text-align: center;
            display: block;
        }

        .pagenum:before {
            content: counter(page);
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table, th, td {
            font-size: x-small;
            border-spacing: -1px;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }

        td, th {
            padding: 10px;
        }

        thead tr {
            box-shadow: 0 1px 10px #000000;
        }

        th {
            background-color: #ccc;
        }

        tr.even td {
            background-color: yellow;
        }

        tr.odd td {
            background-color: orange;
        }

        tbody:before {
            line-height: 1em;
            content: ".";
            color: white;
            display: block;
        }
    </style>
</head>
<body>
	<div class="header">
		<table width="100%">
			<tr>
				<td valign="top">
                    <img src="data:image/png;base64, {{base64_encode(file_get_contents(asset('website/logo.jpeg')))}}" width="500">
                </td>
                <td>
                    <p class="text-dark"><b>No. Inv</b> : <b>{{ $invoice->code }}</b></p>
                    <p class="text-dark"><b>Tanggal</b> : <b>{{ date('d M Y', strtotime($invoice->created_at)) }}</b></p>
                    <p class="text-dark"><b>Halaman</b> : <span class="pagenum"></span></p>
                </td>
			</tr>
			<tr>
				<pre>
					<b>Customer :</b>
					<b>{{ $invoice->customer->name }}</b>
					<b>{{ $invoice->customer->city->name }}</b>
				</pre>
			</tr>
		</table>
	</div>
	<div class="kasihtau">
		<table width="100%" style="border:1px solid black;">
			<thead style="background-color: lightgray; border:1px solid black;">
				<tr style="border:1px solid black;">
					<th style="border:1px solid black;">No</th>
					<th style="border:1px solid black;">Order</th>
					<th style="border:1px solid black;">Surat Jalan</th>
					<th style="border:1px solid black;">Qty</th>
					<th style="border:1px solid black;">Berat</th>
					<th style="border:1px solid black;">Harga Per Kg</th>
					<th style="border:1px solid black;">Total</th>
				</tr>
			</thead>
			<tbody style="border:1px solid black;">
				@foreach($invoice->invoiceDetail as $key => $id)
                    @php
                        $price = $id->letterWay->destination->destinationPrice->last();
                    @endphp
                    <tr style="border:1px solid black;" class="text-center">
                        <td style="border:1px solid black;" class="text-dark align-middle"><b>{{ $key + 1 }}</b></td>
                        <td style="border:1px solid black;" class="text-dark align-middle">
                            <b>{{ $id->letterWay->order->reference ? $id->letterWay->order->reference : $id->letterWay->order->code }}</b>
                        </td>
                        <td style="border:1px solid black;" class="text-dark align-middle"><b>{{ $id->letterWay->number }}</b></td>
                        <td style="border:1px solid black;" class="text-dark align-middle"><b>{{ $id->letterWay->qty }} {{ $id->letterWay->order->unit->name }}</b></td>
                        <td style="border:1px solid black;" class="text-dark align-middle"><b>{{ $id->letterWay->weight }} Kg</b></td>
                        <td style="border:1px solid black;" class="text-dark align-middle">
                            <b>Rp {{ number_format($price->price_customer, 2, ',', '.') }}</b>
                        </td>
                        <td style="border:1px solid black;" class="text-dark align-middle"><b>Rp {{ number_format($id->total, 2, ',', '.') }}</b></td>
                    </tr>
				@endforeach
		    </tbody>
			<tfoot>
                <tr>
                    <td colspan="5"></td>
                    <td style="border:1px solid black;" align="right">Subtotal Rp.</td>
                    <td style="border:1px solid black;" align="right"><b>Rp {{ number_format($invoice->subtotal, 2, ',', '.') }}</b></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td style="border:1px solid black;" align="right">Diskon Rp.</td>
                    <td style="border:1px solid black;" align="right">
                        @php $total_discount = ($invoice->discount / 100) * $invoice->subtotal; @endphp
                        <b>Rp {{ number_format($total_discount, 2, ',', '.') }}</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td style="border:1px solid black;" align="right">Pajak Rp.</td>
                    <td style="border:1px solid black;" align="right">
                        @php $total_tax = ($invoice->tax / 100) * $invoice->subtotal; @endphp
                        <b>Rp {{ number_format($total_tax, 2, ',', '.') }}</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td style="border:1px solid black;" align="right">DP Rp.</td>
                    <td style="border:1px solid black;" align="right"><b>Rp {{ number_format($invoice->down_payment, 2, ',', '.') }}</b></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td style="border:1px solid black;" align="right">Grandtotal Rp.</td>
                    <td style="border:1px solid black;" align="right" class="gray"><b>Rp {{ number_format($invoice->grandtotal, 2, ',', '.') }}</b></td>
                </tr>
                <tr>
                    <td style="border:1px solid black;" colspan="5">
                        Transfer Via :<br>
						{{ $invoice->journal->coaCredit->name }} - IDR<br>
						Acc Name. : {{ $invoice->journal->coaCredit->description }}
                    </td>
                    <td style="border:1px solid black;" colspan="2" class="text-dark align-middle">
                        <b>TERBILANG :</b>
                        <div class="text-dark font-italic text-uppercase"><b>{{ App\Models\Invoice::numberToWord($invoice->grandtotal) }}</b></div>
                    </td>
				</tr>
			</tfoot>
		</table>
        <table>
            <tr>
                <td>
                    <p align="center"> Dibuat Oleh,</p><br>
                    @if($invoice->user->signature)
                        <center>
                            <img src="{{ $invoice->user->signature() }}" style="width:147px; height:83px;" class="img-fluid">
                        </center>
                    @else
                        <br><br><br><br><br>
                    @endif
                    <p align="center" style="text-transform:underline;">
                        <b> ({{ $invoice->user->name }})</b>
                    </p>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <p align="center"> Disetujui Oleh,</p><br><br><br><br>
                    <p class="text-dark text-uppercase" style="text-transform:underline;">
                        (.......................................................)
                    </p>
                </td>
            </tr>
            <tr>
                <td><p align="center"><small><b>Finance Officer</b></small></p></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><p align="center"><small><b>CFO</b></small></p></td>
            </tr>
        </table>
	</div>
</body>
</html>
