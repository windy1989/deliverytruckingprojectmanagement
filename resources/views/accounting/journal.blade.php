<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5 no-print">
                        <div class="col-md-6">
                            <h5 class="mt-2">Jurnal</h5>
                        </div>
                        <div class="col-md-12 mt-5">
                            <form action="{{ url('accounting/journal') }}" method="GET">
                                @csrf
                                <div class="form-group">
                                    <label>Filter Tanggal :</label>
                                    <div class="input-group">
                                        <input type="date" name="start_date" id="start_date" max="{{ date('Y-m-d') }}" class="form-control" value="{{ $start_date ? $start_date : null }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">s/d</span>
                                        </div>
                                        <input type="date" name="finish_date" id="finish_date" max="{{ date('Y-m-d') }}" class="form-control" value="{{ $finish_date ? $finish_date : null }}">
                                    </div>
                                </div>
                                <div class="text-right form-group">
                                    <a href="{{ url('accounting/journal') }}" class="btn btn-secondary" onclick="reset()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg>&nbsp;Reset</a>
                                    <button type="submit" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>&nbsp;Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group"><hr class="bg-success"></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-secondary text-center p-3">
                            <h6 class="font-weight-bold text-uppercase text-white">PT. Digital Trans Indonesia</h6>
                            <h6 class="font-weight-bold text-uppercase text-white">Laporan Jurnal</h6>
                            <h6 class="font-weight-bold text-uppercase text-white">
                                @if($start_date && $finish_date) 
                                    {{ date('d M Y', strtotime($start_date)) }} - {{ date('d M Y', strtotime($finish_date)) }}
                                @elseif($start_date) 
                                    {{ date('d M Y', strtotime($start_date)) }}
                                @elseif($finish_date) 
                                    {{ date('d M Y', strtotime($finish_date)) }}
                                @else
                                    {{ date('F Y') }}
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered nowrap">
                                    <thead class="table-info no-gutters">
                                        <tr class="text-center align-midle">
                                            <th rowspan="2" class="align-midle">Tanggal</th>
                                            <th colspan="2" class="align-midle">Akun</th>
                                            <th rowspan="2" class="align-midle">Deskripsi</th>
                                            <th rowspan="2" class="align-midle">Debet</th>
                                            <th rowspan="2" class="align-midle">Kredit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0; @endphp
                                        @if($journal->count() > 0)
                                            @foreach($journal as $j)
                                                <tr class="text-center">
                                                    <td rowspan="{{ ($j->detail()->count() * 2) + 1 }}" class="align-middle">
                                                        {{ date('d M Y', strtotime($j->created_at)) }}
                                                    </td>
                                                </tr>
                                                @foreach($j->detail() as $d)
                                                    @php $total += $d->nominal; @endphp
                                                    <tr class="text-center">
                                                        <td colspan="2" class="align-middle text-left">
                                                            {{ $d->coaDebit->code }}&nbsp;&nbsp;{{ $d->coaDebit->name }}
                                                        </td>
                                                        <td rowspan="2" class="align-middle">
                                                            {{ $d->description }}
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="text-success font-weight-bold">
                                                                Rp {{ number_format($d->nominal, 2, ',', '.') }}
                                                            </span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="text-danger font-weight-bold">-</span>
                                                        </td>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <td colspan="2" class="align-middle text-right">
                                                            {{ $d->coaCredit->code }}&nbsp;&nbsp;{{ $d->coaCredit->name }}
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="text-success font-weight-bold">-</span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="text-danger font-weight-bold">
                                                                Rp {{ number_format($d->nominal, 2, ',', '.') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @else
                                            <tr class="text-center">
                                                <td colspan="6">Tidak ada data.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right align-middle font-italic">TOTAL :</th>
                                            <th class="text-center align-middle">
                                                <span class="font-weight-bold text-success font-italic">
                                                    Rp {{ number_format($total, 2, ',', '.') }}
                                                </span>
                                            </th>
                                            <th class="text-center align-middle">
                                                <span class="font-weight-bold text-danger font-italic">
                                                    Rp {{ number_format($total, 2, ',', '.') }}
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