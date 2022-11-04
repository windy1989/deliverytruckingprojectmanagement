<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row justify-content-center layout-top-spacing">
            <div class="col-xl-8 col-lg-8 col-sm-8 layout-spacing" id="timelineProfile">
                <div class="widget-content widget-content-area br-6">
                    <h5 class="mt-2 mb-5">Aktivitas</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ url('activity_log') }}" method="GET">
                                @csrf
                                <div class="form-group">
                                    <label>Tanggal :</label>
                                    <div class="input-group">
                                        <input type="date" name="start_date" id="start_date" max="{{ date('Y-m-d') }}" class="form-control" value="{{ $start_date ? $start_date : null }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">s/d</span>
                                        </div>
                                        <input type="date" name="finish_date" id="finish_date" max="{{ date('Y-m-d') }}" class="form-control" value="{{ $finish_date ? $finish_date : null }}">
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <a href="{{ url('activity_log') }}" class="btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg>&nbsp;Reset</a>
                                    <button type="submit" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>&nbsp;Filter</button>
                                </div>
                            </form>
                            <div class="form-group"><hr class="bg-success"></div>
                        </div>
                    </div>
                    <div class="p-3">
                        @if($activity->count() > 0)
                            @foreach($activity as $a)
                                <h4 class="mb-3">{{ $a->created_at->isoFormat('dddd, D MMMM Y') }}<hr></h4>
                                @if($a->getDataByDate($a->created_at)->count() > 0)
                                    <div class="ml-5">
                                        <div class="timeline-line mb-4">
                                            @foreach($a->getDataByDate($a->created_at) as $val)
                                                <div class="item-timeline">
                                                    <p class="t-time font-weight-bold">{{ date('H:i', strtotime($val->created_at)) }}</p>
                                                    <div class="t-dot t-dot-primary"></div>
                                                    <div class="t-text">
                                                        <p class="mb-1">{{ $val->description }}</p>
                                                        <p class="text-muted">{{ $val->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">Tidak ada aktivitas</div>
                                @endif
                            @endforeach
                        @else
                            <div class="alert alert-info text-center">Tidak ada aktivitas</div>
                        @endif
                        @if($activity->hasMorePages())
                            <div class="form-group"><hr></div>
                        @endif
                        {{ $activity->withQueryString()->onEachSide(3)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
