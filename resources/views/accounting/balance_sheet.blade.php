<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5 no-print">
                        <div class="col-md-6">
                            <h5 class="mt-2">Neraca</h5>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-secondary text-center p-3">
                            <h6 class="font-weight-bold text-uppercase text-white">PT. Digital Trans Indonesia</h6>
                            <h6 class="font-weight-bold text-uppercase text-white">Laporan Neraca</h6>
                            <h6 class="font-weight-bold text-uppercase text-white">Semua Periode</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered nowrap">
                                <thead class="table-info">
                                    <tr class="text-left">
                                        <th class="align-midle">Akun</th>
                                        <th colspan="2" class="align-midle">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="align-middle text-center font-weight-bold"
                                            style="font-size:18px;">ASSET</td>
                                    </tr>
                                    @php $total_asset = 0; @endphp
                                    @php $no = 0; @endphp
                                    @foreach($coa_asset as $ca)
                                    @php $balance = $ca->balance('debit', $ca->code); @endphp
                                    @php $total_asset += $balance; @endphp
                                    @php $get_child = $ca->getChild('debit', $ca->code); @endphp
                                    <tr>
                                        <td class="align-middle">
                                            <a data-toggle="collapse" href="#collapseDebit{{$no}}" role="button"
                                                aria-expanded="false" aria-controls="collapseDebit{{$no}}">
                                                {{ $ca->code }} &nbsp;&nbsp;&nbsp;&nbsp;{{ $ca->name }}
                                            </a>

                                            <div class="collapse m-3" id="collapseDebit{{$no}}">
                                                <div class="accordion-body">
                                                    @foreach ($get_child as $gc)
                                                    <div class="row mb-2">
                                                        <div class="col-8">
                                                            {{$gc->description }} &nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                        <div class="col">
                                                            Rp {{ number_format(abs($gc->nominal), 2, ',', '.') }} <br>
                                                        </div>
                                                    </div>

                                                    @endforeach

                                                </div>
                                            </div>
                                        </td>

                                        <td colspan="2" class="align-middle text-left">
                                            Rp {{ number_format(abs($balance), 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    @php
                                    $no++;
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="align-middle text-center font-weight-bold"
                                            style="font-size:18px;">LIABILITY</td>
                                    </tr>
                                    @php $total_liability = 0; @endphp
                                    @foreach($coa_liability as $cl)
                                    @php $balance = $cl->balance('credit', $cl->code); @endphp
                                    @php $total_liability += $balance; @endphp
                                    @php $get_child = $cl->getChild('credit', $cl->code); @endphp
                                    <tr>
                                        <td class="align-middle">
                                            <a data-toggle="collapse" href="#collapseCredit{{$no}}" role="button"
                                                aria-expanded="false" aria-controls="collapseCredit{{$no}}">
                                                {{ $cl->code }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $cl->name }}
                                            </a>
                                            <div class="collapse m-3" id="collapseCredit{{$no}}">
                                                <div class="accordion-body">
                                                    @foreach ($get_child as $gc)
                                                    <div class="row mb-2">
                                                        <div class="col-8">
                                                            {{$gc->description }} &nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                        <div class="col">
                                                            Rp {{ number_format(abs($gc->nominal), 2, ',', '.') }} <br>
                                                        </div>
                                                    </div>
                                                    @endforeach

                                                </div>
                                            </div>

                                        </td>
                                        <td colspan="2" class="align-middle text-left">
                                            Rp {{ number_format(abs($balance), 2, ',', '.') }}
                                        </td>

                                    </tr>
                                    @php
                                    $no++;
                                    @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-success">
                                        <td colspan="2" class="align-middle text-right font-weight-bold">TOTAL ASSET :
                                        </td>
                                        <td class="align-middle text-right font-weight-bold">Rp {{
                                            number_format(abs($total_asset), 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td colspan="2" class="align-middle text-right font-weight-bold">TOTAL LIABILITY
                                            :</td>
                                        <td class="align-middle text-right font-weight-bold">Rp {{
                                            number_format(abs($total_liability), 2, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>