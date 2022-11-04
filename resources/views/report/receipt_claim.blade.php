<style>
    .dropzone .dz-preview .dz-image {
        width: 400px;
        height: auto;

    }

    .dz-image img {
        width: 100%;
        height: 100%;
    }


    .dropzone {
        border: 2px dashed #028AF4 !important;
        ;

    }
</style>


<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Detail Klaim Kwitansi <b>{{ $receipt->code }}</h5>
                        </div>
                    </div>
                    @if($receipt->claims)
                    <div class="form-group text-right">
                        <a href="{{ url('download/pdf?param=claim&type=receipts&id=' . $receipt->id) }}"
                            class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                </path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>&nbsp;Cetak</a>
                    </div>
                    @endif
                    <ul class="nav nav-tabs mb-5 mt-5 nav-fill" id="justifyTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="justify-destroy-tab" data-toggle="tab"
                                href="#justify-destroy" role="tab" aria-controls="justify-destroy"
                                aria-selected="true">Surat Jalan Pecah</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="justify-late-tab" data-toggle="tab" href="#justify-late" role="tab"
                                aria-controls="justify-late" aria-selected="false">Surat Jalan Terlambat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="justify-fine-tab" data-toggle="tab" href="#justify-fine" role="tab"
                                aria-controls="justify-fine" aria-selected="false">Surat Jalan Tidak Bermasalah</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="justifyTabContent">
                        <div class="tab-pane fade show active" id="justify-destroy" role="tabpanel"
                            aria-labelledby="justify-destroy-tab">
                            <p>
                            <div class="alert alert-arrow-left alert-icon-left alert-light-warning" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-alert-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12" y2="16"></line>
                                </svg>
                                <code class="font-weight-bold">
                                        Rumus = nominal - toleransi
                                    </code>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th class="align-middle">No</th>
                                        <th class="align-middle">Surat Jalan</th>
                                        <th class="align-middle">Status</th>
                                        <th class="align-middle">Nominal</th>
                                        <th class="align-middle">Toleransi</th>
                                        <th class="align-middle">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($receipt->claims)
                                    @if(array_key_exists('destroy', $letter_way))
                                    @foreach($letter_way['destroy'] as $key => $d)
                                    <tr class="text-center">
                                        <input type="hidden" name="claim_type[]" value="{{ $d->type }}">
                                        {!! $d->letter_way_id !!}
                                        {!! $d->late !!}
                                        {!! $d->percentage !!}
                                        <td class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $d->number }}</td>
                                        <td class="align-middle">{!! $d->status !!}</td>
                                        <td class="align-middle" width="20%">{!! $d->rupiah !!}</td>
                                        <td class="align-middle" width="20%">{!! $d->tolerance !!}</td>
                                        <td class="align-middle">{{ $d->total }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="text-center">
                                        <td class="align-middle" colspan="5">Tidak ada data</td>
                                    </tr>
                                    @endif
                                    @else
                                    <tr class="text-center">
                                        <td colspan="6">Tidak ada claim</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div id="photo_destroy" class="fallback dropzone">
                                <input id="type_destroy" name="type" type="hidden" value="destroy">
                                <input id="photoable_destroy_id" name="photoable_id" type="hidden"
                                    value="{{$receipt->id}}">
                            </div>
                            </p>
                        </div>
                        <div class="tab-pane fade" id="justify-late" role="tabpanel" aria-labelledby="justify-late-tab">
                            <p>
                            <div class="alert alert-arrow-left alert-icon-left alert-light-warning" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-alert-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12" y2="16"></line>
                                </svg>
                                <code class="font-weight-bold">
                                        Rumus = total terlambat * (perhitungan * harga customer) * persentase
                                    </code>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th class="align-middle">No</th>
                                        <th class="align-middle">Surat Jalan</th>
                                        <th class="align-middle">Status</th>
                                        <th class="align-middle">Persentase</th>
                                        <th class="align-middle">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($receipt->claims)
                                    @if(array_key_exists('late', $letter_way))
                                    @foreach($letter_way['late'] as $key => $d)
                                    <tr class="text-center">
                                        <input type="hidden" name="claim_type[]" value="{{ $d->type }}">
                                        {!! $d->letter_way_id !!}
                                        {!! $d->late !!}
                                        {!! $d->rupiah !!}
                                        {!! $d->tolerance !!}
                                        <td class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $d->number }}</td>
                                        <td class="align-middle">{!! $d->status !!}</td>
                                        <td class="align-middle">{!! $d->percentage !!}</td>
                                        <td class="align-middle">{{ $d->total }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="text-center">
                                        <td class="align-middle" colspan="6">Tidak ada data</td>
                                    </tr>
                                    @endif
                                    @else
                                    <tr class="text-center">
                                        <td colspan="5">Tidak ada claim</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div id="photo_late" class="fallback dropzone">
                                <input id="type_late" name="type" type="hidden" value="late">
                                <input id="photoable_late_id" name="photoable_id" type="hidden"
                                    value="{{$receipt->id}}">
                            </div>
                            </p>
                        </div>
                        <div class="tab-pane fade" id="justify-fine" role="tabpanel" aria-labelledby="justify-fine-tab">
                            <p>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="font-weight-bold text-center">
                                        <th>No</th>
                                        <th>Surat Jalan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($receipt->claims)
                                    @if(array_key_exists('fine', $letter_way))
                                    @foreach($letter_way['fine'] as $key => $d)
                                    <tr class="text-center">
                                        <td class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $d->number }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="text-center">
                                        <td class="align-middle" colspan="2">Tidak ada data</td>
                                    </tr>
                                    @endif
                                    @else
                                    <tr class="text-center">
                                        <td colspan="6">Tidak ada claim</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            </p>
                        </div>
                    </div>
                    @if($receipt->claims)
                    <div class="form-group">
                        <hr class="bg-success">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal :</label>
                                <input type="date" class="form-control form-control-plaintext"
                                    value="{{ $receipt->claims ? date('Y-m-d', strtotime($receipt->claims->date)) : date('Y-m-d') }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Description :</label>
                                <input type="text" name="description" id="description"
                                    class="form-control form-control-plaintext"
                                    value="{{ $receipt->claims ? $receipt->claims->description : '' }}" {{
                                    $receipt->claims ? 'disabled' : 'required' }}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Perhitungan :</label>
                                <select name="flag" id="flag" class="form-control" required {{ $receipt->claims ?
                                    'disabled' : 'required' }}>
                                    <option value="1" {{ $receipt->claims ? $receipt->claims->flag == 1 ? 'selected' :
                                        '' : '' }}>Berat</option>
                                    <option value="2" {{ $receipt->claims ? $receipt->claims->flag == 2 ? 'selected' :
                                        '' : '' }}>Qty</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <hr class="bg-success">
                    </div>
                    <div class="form-group text-right">
                        <a href="{{ url('report/receipt') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
        $('#datatable').DataTable();
    });

    Dropzone.autoDiscover = false;
     

     $("div#photo_destroy").dropzone({
        url: '{{url("sales/receipt/get_picture")}}',
         paramName:"file",
         maxFilesize: 4,
         acceptedFiles: ".jpeg,.jpg,.png",
         enctype: 'multipart/form-data',
         timeout: 50000,
         params: {
             type: $('#type_late').val(),
             photoable_id: $('#photoable_late_id').val()

         },
         init:function() {

             // Get images
             var myDropzone = this;
             myDropzone.disable()
             
             $.ajax({
                 url: '{{url("sales/receipt/get_picture")}}',
                 type: 'GET',
                 dataType: 'json',
                 data:{
                     type: $('#type_destroy').val(),
                     photoable_id: $('#photoable_destroy_id').val()
                 },
                 success: function(data){
                 $.each(data, function (key, value) {
                     var file = {name: value.name, size: value.size};
                     myDropzone.options.addedfile.call(myDropzone, file);
                     myDropzone.options.thumbnail.call(myDropzone, file, value.path);
                     myDropzone.emit("complete", file);
                 });
                 }
             });
         },
         removedfile: function(file) 
         {
             if (this.options.dictRemoveFile) {
               return Dropzone.confirm("Are You Sure to "+this.options.dictRemoveFile, function() {
                 if(file.previewElement.id != ""){
                     var name = file.previewElement.id;
                 }else{
                     var name = file.name;
                 }
         
                 $.ajax({
                     headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                           },
                     type: 'POST',
                     url: '{{url("sales/receipt/destroy_picture")}}',
                     data: {filename: name},
                     success: function (data){
                         notif(data.success +" File has been successfully removed!", '#198754');
                     },
                     error: function(e) {
                         notif('Server Error!', '#DC3545');
                     }});
                     var fileRef;
                     return (fileRef = file.previewElement) != null ? 
                     fileRef.parentNode.removeChild(file.previewElement) : void 0;
                });
             }		
         },
    
         success: function(file, response) 
         {
             file.previewElement.id = response.success;
 
             var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
             file.previewElement.querySelector("img").alt = response.success;
             olddatadzname.innerHTML = response.success;
         },
         error: function(file, response)
         {
            if($.type(response) === "string")
                 var message = response; //dropzone sends it's own error messages in string
             else
                 var message = response.message;
             file.previewElement.classList.add("dz-error");
             _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
             _results = [];
             for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                 node = _ref[_i];
                 _results.push(node.textContent = message);
             }
             return _results;
         }
     });
   
    $("div#photo_late").dropzone({
     url: '{{url("sales/receipt/get_picture")}}',
         paramName:"file",
         maxFilesize: 4,
         acceptedFiles: ".jpeg,.jpg,.png",
         enctype: 'multipart/form-data',
         timeout: 50000,
         params: {
             type: $('#type_late').val(),
             photoable_id: $('#photoable_late_id').val()

         },
         init:function() {

             // Get images
             var myDropzone = this;
             myDropzone.disable()
             
         $.ajax({
             url: '{{url("sales/receipt/get_picture")}}',
             type: 'GET',
             dataType: 'json',
             data:{
                 type: $('#type_late').val(),
                 photoable_id: $('#photoable_late_id').val()
             },
             success: function(data){
             $.each(data, function (key, value) {
                 var file = {name: value.name, size: value.size};
                 myDropzone.options.addedfile.call(myDropzone, file);
                 myDropzone.options.thumbnail.call(myDropzone, file, value.path);
                 myDropzone.emit("complete", file);
             });
             }
         });
         },
    
         success: function(file, response) 
         {
             file.previewElement.id = response.success;
             //console.log(file); 
             // set new images names in dropzone’s preview box.
             var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
             file.previewElement.querySelector("img").alt = response.success;
             olddatadzname.innerHTML = response.success;
         },
         error: function(file, response)
         {
            if($.type(response) === "string")
                 var message = response; //dropzone sends it's own error messages in string
             else
                 var message = response.message;
             file.previewElement.classList.add("dz-error");
             _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
             _results = [];
             for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                 node = _ref[_i];
                 _results.push(node.textContent = message);
             }
             return _results;
         }
     });
    </script>