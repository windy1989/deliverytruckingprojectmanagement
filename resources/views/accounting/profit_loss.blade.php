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
    <div class="layout-px-spacing" style=" min-height: 0 !important;">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h3 class="card-title"></h3>
                            <div class="col-md-12 text-right">
                                <a href="{{ url('accounting/profit_loss') }}" class="btn btn-secondary"
                                    onclick="reset()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-refresh-ccw">
                                        <polyline points="1 4 1 10 7 10"></polyline>
                                        <polyline points="23 20 23 14 17 14"></polyline>
                                        <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15">
                                        </path>
                                    </svg>&nbsp;Reset</a>
                            </div>
                        </div>
                        <div class="card-body py-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="GET" id="form_filter_year">
                                        <center class="d-block">
                                            <div class="row justify-content-center">
                                                @csrf
                                                <div class="col-md-3 text-left">
                                                    <div class="form-group">
                                                        <label>Year</label>
                                                        <select name="year" id="year" class="select2 form-control"
                                                            onchange="submitFilterYear()">
                                                            @for($i=date('Y');$i > (date('Y')-5);$i--)
                                                            <option value="{{ $i }}" {{ $i==$year ? 'selected' : '' }}>
                                                                {{ $i
                                                                }}
                                                            </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </center>
                                    </form>
                                    <div id="chartdiv" style="height: 100vh !important;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row mb-5 no-print">
                        <div class="col-md-6">
                            <h5 class="mt-2">Laba Rugi</h5>
                        </div>
                        <div class="col-md-12 mt-5">
                            <form action="{{ url('accounting/profit_loss') }}" method="GET">
                                @csrf
                                <div class="form-group">
                                    <label>Filter Tanggal :</label>
                                    <div class="input-group">
                                        <input type="date" name="start_date" id="start_date" max="{{ date('Y-m-d') }}"
                                            class="form-control" value="{{ $start_date ? $start_date : null }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">s/d</span>
                                        </div>
                                        <input type="date" name="finish_date" id="finish_date" max="{{ date('Y-m-d') }}"
                                            class="form-control" value="{{ $finish_date ? $finish_date : null }}">
                                    </div>
                                </div>
                                <div class="text-right form-group">
                                    <button type="button" class="btn btn-info" onclick="window.print()"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-printer">
                                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                            <path
                                                d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                            </path>
                                            <rect x="6" y="14" width="12" height="8"></rect>
                                        </svg>&nbsp;Cetak</button>
                                    <a href="{{ url('accounting/profit_loss') }}" class="btn btn-secondary"
                                        onclick="reset()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-refresh-ccw">
                                            <polyline points="1 4 1 10 7 10"></polyline>
                                            <polyline points="23 20 23 14 17 14"></polyline>
                                            <path
                                                d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15">
                                            </path>
                                        </svg>&nbsp;Reset</a>
                                    <button type="submit" class="btn btn-success"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-filter">
                                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                        </svg>&nbsp;Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <hr class="bg-success">
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="invoice-header-section">
                            <div class="invoice-container">
                                <div id="ct">
                                    <div class="invoice-0001">
                                        <div class="content-section animated animatedFadeInUp fadeInUp">
                                            <div class="inv--product-table-section">
                                                <div class="card-header bg-secondary text-center p-3">
                                                    <h6 class="font-weight-bold text-uppercase text-white">PT. Digital
                                                        Trans Indonesia</h6>
                                                    <h6 class="font-weight-bold text-uppercase text-white">Laporan Laba
                                                        Rugi</h6>
                                                    <h6 class="font-weight-bold text-uppercase text-white">
                                                        @if($start_date && $finish_date)
                                                        {{ date('d M Y', strtotime($start_date)) }} - {{ date('d M Y',
                                                        strtotime($finish_date)) }}
                                                        @elseif($start_date)
                                                        {{ date('d M Y', strtotime($start_date)) }}
                                                        @elseif($finish_date)
                                                        {{ date('d M Y', strtotime($finish_date)) }}
                                                        @else
                                                        Semua Periode
                                                        @endif
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="card">
                                                        <a href="#accordionProfit" data-toggle="collapse" role="button"
                                                            aria-expanded="false" aria-controls="accordionProfit"
                                                            style="text-decoration:none;">
                                                            <div class="card-header">
                                                                <div class="p-3">
                                                                    <span class="font-weight-bold"
                                                                        style="color:black !important;">PENDAPATAN</span>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <div id="accordionProfit" class="collapse multi-collapse">
                                                            <div class="card-body">
                                                                <table cellpadding="10" cellspacing="10" width="100%">
                                                                    <tbody>
                                                                        @php $total_profit = 0; @endphp
                                                                        @if($coa_profit->count() > 0)
                                                                        @foreach($coa_profit as $cp)
                                                                        @php $total_profit += $cp->balance('debit',
                                                                        $cp->code); @endphp
                                                                        <tr>
                                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $cp->code
                                                                                }}&nbsp;&nbsp;{{ $cp->name }}</td>
                                                                            <td>Rp {{
                                                                                number_format($cp->balance('debit',
                                                                                $cp->code), 2, ',', '.') }}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                        @else
                                                                        <tr class="text-center">
                                                                            <td colspan="2">Tidak ada laba yang terjadi.
                                                                            </td>
                                                                        </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="card-footer text-right font-weight-bold text-uppercase">
                                                            Total Pendapatan : Rp {{ number_format($total_profit, 2,
                                                            ',', '.') }}
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <a href="#accordionLoss" data-toggle="collapse" role="button"
                                                            aria-expanded="false" aria-controls="accordionLoss"
                                                            style="text-decoration:none;">
                                                            <div class="card-header">
                                                                <div class="p-3">
                                                                    <span class="font-weight-bold"
                                                                        style="color:black !important;">BEBAN</span>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <div id="accordionLoss" class="collapse multi-collapse">
                                                            <div class="card-body">
                                                                <table cellpadding="10" cellspacing="10" width="100%">
                                                                    <tbody>
                                                                        @php $total_loss = 0; @endphp
                                                                        @if($coa_profit->count() > 0)
                                                                        @foreach($coa_loss as $cl)
                                                                        @php $total_loss += $cl->balance('credit',
                                                                        $cl->code); @endphp
                                                                        <tr>
                                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $cl->code
                                                                                }}&nbsp;&nbsp;{{ $cl->name }}</td>
                                                                            <td>Rp {{
                                                                                number_format($cl->balance('credit',
                                                                                $cl->code), 2, ',', '.') }}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                        @else
                                                                        <tr class="text-center">
                                                                            <td colspan="2">Tidak ada rugi yang terjadi.
                                                                            </td>
                                                                        </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="card-footer text-right font-weight-bold text-uppercase">
                                                            Total Beban : Rp {{ number_format($total_loss, 2, ',', '.')
                                                            }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    @php $profit_loss = $total_profit - $total_loss; @endphp
                                                    <div class="row">
                                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                                            <h6
                                                                class="text-right text-dark font-weight-bold text-uppercase">
                                                                Nett Profit :</h6>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                                            <h6
                                                                class="text-right text-dark font-weight-bold text-uppercase">
                                                                @if($profit_loss > 0)
                                                                Rp {{ number_format($profit_loss) }}
                                                                @else
                                                                Rp 0,00
                                                                @endif
                                                            </h6>
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

    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <script>
        function submitFilterYear() {
            loadingOpen('.content');
            $('#form_filter_year').submit();
        }
        
        am4core.ready(function() {

        am4core.useTheme(am4themes_animated);

        var chart = am4core.create("chartdiv", am4charts.XYChart);

        chart.legend = new am4charts.Legend();

        var data = [
			@php
				foreach($total_profit_loss as $row){
			@endphp
				{
				  "month": "{{ date('M',strtotime($row['month'])) }}",
				  "total_profit": {{ $row['total_profit'] }},
				  "total_loss": {{ $row['total_loss'] }},
				},
			@php
				}
			@endphp
			];
      



        /* Create axes */
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "month";
        categoryAxis.renderer.grid.template.disabled = true;
        categoryAxis.renderer.cellStartLocation = 0.1;
        categoryAxis.renderer.cellEndLocation = 0.9;
        categoryAxis.renderer.minGridDistance = 30;

        /* Create value axis */
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.grid.template.disabled = false;

        /* Create series */
        var columnSeries = chart.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "Profit ";
        columnSeries.dataFields.valueY = "total_profit";
        columnSeries.dataFields.categoryX = "month";

        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";
        columnSeries.fill = am4core.color("#6c32cc");
        columnSeries.columns.template.width = am4core.percent(100);

        var columnSeries1 = chart.series.push(new am4charts.ColumnSeries());
        columnSeries1.name = "Loss ";
        columnSeries1.dataFields.valueY = "total_loss";
        columnSeries1.dataFields.categoryX = "month";

        columnSeries1.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
        columnSeries1.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries1.columns.template.propertyFields.stroke = "stroke";
        columnSeries1.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries1.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries1.tooltip.label.textAlign = "middle";
        columnSeries1.fill = am4core.color("#c7344c");
        columnSeries1.columns.template.width = am4core.percent(100);

        


        chart.data = data;

        }); // end am4core.ready() // end am4core.ready()
    </script>