<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="layout-top-spacing row">
            <div class="col-md-4 mb-4">
                <div class="card component-card_7 bg-warning">
                    <div class="card-body">
                        <h1 class="rating-count">{{ App\Models\Order::count() }}</h1>
                        <div class="rating-stars">
                            <small class="r-rating-num text-uppercase font-weight-bold">Total Order</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card component-card_7 bg-dark">
                    <div class="card-body">
                        <h1 class="rating-count">{{ App\Models\LetterWay::count() }}</h1>
                        <div class="rating-stars">
                            <small class="r-rating-num text-uppercase font-weight-bold">Total Surat Jalan</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card component-card_7 bg-success">
                    <div class="card-body">
                        <h1 class="rating-count">{{ App\Models\User::count() }}</h1>
                        <div class="rating-stars">
                            <small class="r-rating-num text-uppercase font-weight-bold">Total User</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4 mb-4">
            <div class="card-body">
                <h5 class="text-center mb-3">Tahun {{ date('Y') }}</h5>
                <canvas id="chart_order"></canvas>
            </div>
        </div>
    </div>

<script>
    $(function() {
        chartOrder();
    });

    function chartOrder() {
        var source = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        var config = {
            type: 'bar',
            data: {
                labels: source,
                datasets: [{
                    label: '',
                    fill: true,
                    backgroundColor: [
                        '#DC3545',
                        '#FFC107',
                        '#198754',
                        '#0D6EFD',
                        '#DC3545',
                        '#FFC107',
                        '#198754',
                        '#0D6EFD',
                        '#DC3545',
                        '#FFC107',
                        '#198754',
                        '#0D6EFD'
                    ],
                    data: [
                        '{{ $order["jan"] }}',
                        '{{ $order["feb"] }}',
                        '{{ $order["mar"] }}',
                        '{{ $order["apr"] }}',
                        '{{ $order["mei"] }}',
                        '{{ $order["jun"] }}',
                        '{{ $order["jul"] }}',
                        '{{ $order["agu"] }}',
                        '{{ $order["sep"] }}',
                        '{{ $order["okt"] }}',
                        '{{ $order["nov"] }}',
                        '{{ $order["des"] }}'
                    ]
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Statistik Order Ditahun ' + '{{ date("Y") }}'
                },
                tooltips: {
                    mode: 'index',
                    intersect: true
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        };

        var ctx = document.getElementById('chart_order').getContext('2d');
        new Chart(ctx, config);
    }
</script>
