
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row justify-content-center layout-spacing">
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 layout-top-spacing">
                <div class="user-profile layout-spacing">
                    <div class="widget-content widget-content-area">
                        <h3 class="">Profil</h3>
                        <div class="text-center user-info mb-5">
                            <img src="{{ $user->photo() }}" style="max-width:90px; max-height:90px;" alt="avatar" class="mb-2">
                            <p class="">{{ $user->name }}</p>
                        </div>
                        <div class="user-info-list">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif(session('success'))
                                <div class="alert alert-light-success" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <strong>Berhasil!</strong> {{ session('success') }}
                                </div>
                            @elseif(session('failed'))
                                <div class="alert alert-light-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                    <strong>Gagal!</strong> {{ session('failed') }}
                                </div>
                            @endif
                            <form action="{{ url('profile') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>Foto :</label>
                                            <input type="file" name="photo" id="photo" class="form-control" onchange="previewImage(this, '#preview_photo img', '#preview_photo')">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ asset("website/empty.png") }}" id="preview_photo" data-lightbox="Foto" data-title="Preview Foto"><img src="{{ asset("website/empty.png") }}" style="width:100px; height:75px;"></a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nama :</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" placeholder="Masukan nama">
                                </div>
                                <div class="form-group">
                                    <label>Username :</label>
                                    <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" placeholder="Masukan username">
                                </div>
                                <div class="form-group">
                                    <label>Email :</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" placeholder="Masukan email">
                                </div>
                                <div class="form-group">
                                    <label>No Telp :</label>
                                    <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}" placeholder="Masukan phone">
                                </div>
                                <div class="form-group">
                                    <label>Alamat :</label>
                                    <textarea name="address" id="address" class="form-control" placeholder="Masukan alamat" style="resize:none;">{{ $user->address }}</textarea>
                                </div>
                                <div id="toggleAccordion" class="mt-4">
                                    <div class="form-group">
                                        <div class="n-chk">
                                            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
                                                <input type="checkbox" name="change_password" id="change_password" class="new-control-input collapsed" data-toggle="collapse" data-target="#field_change_password" aria-expanded="{{ old('change_password') ? 'true' : 'false' }}" aria-controls="defaultAccordionOne" {{ old('change_password') ? 'checked' : '' }}>
                                                <span class="new-control-indicator"></span> Ganti Password
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="field_change_password" class="collapse {{ old('change_password') ? 'show' : '' }}" aria-labelledby="..." data-parent="#toggleAccordion">
                                    <div class="form-group">
                                        <label>Password :</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukan password">
                                    </div>
                                    <div class="form-group">
                                        <label>Konfirmasi Password :</label>
                                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Masukan konfirmasi password">
                                    </div>
                                </div>
                                <div class="form-group"><hr></div>
                                <div class="form-group">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>&nbsp;Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
