<?php

namespace App\Http\Controllers;

use App\Models\FileManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileManagerController extends Controller {

    public function index(Request $request)
    {
        if($request->has('_token')) {
            $validation = Validator::make($request->all(), [
                'file'   => 'required',
                'file.*' => 'mimes:png,jpg,jpeg'
            ], [
                'file.required' => 'Tidak ada image yang diupload.',
                'file.mimes'    => 'File yang di izinkan png, jpg, jpeg.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                foreach($request->file('file') as $f) {
                    $query = FileManager::create([
                        'user_id'   => session('id'),
                        'name'      => $f->getClientOriginalName(),
                        'extension' => $f->getClientOriginalExtension(),
                        'size'      => $f->getSize(),
                        'file'      => $f->store('public/file_manager')
                    ]);
                }

                activity()
                    ->performedOn(new FIleManager())
                    ->causedBy(session('id'))
                    ->log('Menambah data file manager');

                session()->flash('success', 'File telah disimpan.');
                return redirect()->back();
            }
        } else {
            $data = [
                'title'        => 'Digitrans - File Manager',
                'file_manager' => FileManager::where('user_id', session('id'))->paginate(18),
                'content'      => 'file_manager'
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

    public function download(Request $request)
    {
        $file = base64_decode($request->param);
        $data = FileManager::where('file', $file)->first();

        if($data) {
            return Storage::download($data->file, $data->name);
        } else {
            abort(404);
        }
    }

    public function destroy(Request $request, $id = null)
    {
        if($request->has('_token')) {
            foreach($request->id as $i) {
                $data = FileManager::find($i);
                if(Storage::exists($data->file)) {
                    Storage::delete($data->file);
                }

                $data->delete();
            }
        } else {
            $data = FileManager::find($id);
            if(Storage::exists($data->file)) {
                Storage::delete($data->file);
            }

            $data->delete();
        }

        session()->flash('success', 'File telah dihapus.');
        return redirect()->back();
    }

}
