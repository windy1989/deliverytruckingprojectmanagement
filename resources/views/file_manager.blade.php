<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row justify-content-center layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing" id="timelineProfile">
                <div class="widget-content widget-content-area br-6">
                    <h5 class="mt-2 mb-5">File Manager</h5>
                    <div class="container">
                        @if($errors->any())
                            <div class="alert alert-warning" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif(session('success'))
                            <div class="alert bg-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                {{ session('success') }}
                            </div>
                        @elseif(session('failed'))
                            <div class="alert bg-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                {{ session('failed') }}
                            </div>
                        @endif
                        <form method="POST" enctype="multipart/form-data" id="form_submit">
                            @csrf
                            <div class="form-group">
                                <div class="custom-file-container" data-upload-id="myFirstImage">
                                    <label><a href="javascript:void(0)" class="custom-file-container__image-clear badge badge-danger text-white">Buang</a></label>
                                    <label class="custom-file-container__custom-file" >
                                        <input type="file" name="file[]" class="custom-file-container__custom-file__custom-file-input" accept="image/*" multiple>
                                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                    </label>
                                    <div class="custom-file-container__image-preview"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success" id="btn_submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload-cloud"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path><polyline points="16 16 12 12 8 16"></polyline></svg> Upload
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="form-group mb-0"><hr class="bg-success"></div>
                        @if($file_manager->total() > 0)
                            <form action="{{ url('file_manager/destroy') }}" method="POST">
                                @csrf
                                <div class="form-group mb-0">
                                    <div class="text-center">
                                        <button type="button" class="btn btn-danger" onclick="checked('uncheck')">Uncheck All</button>
                                        <button type="button" class="btn btn-info" onclick="checked('check')">Check All</button>
                                        <button type="submit" class="btn btn-secondary">Hapus Banyak</button>
                                    </div>
                                </div>
                                <div id="pricingWrapper" class="row mt-5">
                                    @foreach($file_manager as $fm)
                                        <div class="col-md-4">
                                            <div class="card stacked mb-5">
                                                <div class="card-header" style="width:306px; height:270px;">
                                                    <div class="form-group text-center">
                                                        <label class="switch s-icons s-outline s-outline-danger">
                                                            <input type="checkbox" name="id[]" value="{{ $fm->id }}">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <p class="text-center my-auto">
                                                        <a href="{{ $fm->file() }}" data-lightbox="Gallery" data-title="{{ $fm->name }}"><img src="{{ $fm->file() }}" class="img-fluid" style="max-height:170px;"></a>
                                                    </p>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-group list-group-minimal mb-3">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Nama : {{ str_replace(['.jpg', '.jpeg', '.png', '.PNG', '.JPG', '.JPEG'], '', $fm->name) }}
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Extension : {{ $fm->extension }}
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Size : {{ $fm->formatSize() }}
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Tanggal : {{ $fm->created_at->format('d M Y') }}
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            Jam : {{ $fm->created_at->format('H:i A') }}
                                                        </li>
                                                    </ul>
                                                    <div class="form-group mb-0">
                                                        <div class="text-center">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <a href="{{ url('file_manager/destroy/' . $fm->id) }}" class="btn btn-block btn-danger">Hapus</a>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <a href="{{ url('file_manager/download?param=' . base64_encode($fm->file)) }}" class="btn btn-block btn-success">Download</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                            @if($file_manager->hasPages())
                                {{ $file_manager->withQueryString()->onEachSide(3)->links() }}
                            @endif
                        @else
                            <div class="alert alert-info">Belum ada file.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    $(function() {
        var firstUpload = new FileUploadWithPreview('myFirstImage');

        $('#btn_submit').click(function() {
            loadingOpen('.main-content');
            $('#form_submit').submit();
        });
    });

    function checked(param) {
        if(param == 'uncheck') {
            $('input[name="id[]"]').prop('checked', false);
        } else {
            $('input[name="id[]"]').prop('checked', true);
        }
    }
</script>
