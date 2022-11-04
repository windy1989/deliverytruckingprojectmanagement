<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Menu',
            'content' => 'setting.menu'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function getParent()
    {
        $data = Menu::where('parent_id', 0)->get();
        return response()->json($data);
    }

    public function getSub(Request $request)
    {
        $parent   = Menu::where('name', $request->name)->first();
        $data     = Menu::where('parent_id', $parent->id)->oldest('order')->get();
        $response = [];

        foreach($data as $d) {
            $response[] = [
                'name'   => $d->name,
                'url'    => $d->url,
                'order'  => $d->order,
                'action' => '
                    <a href="javascript:void(0);" class="text-warning" onclick="show(' . $d->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                    <a href="javascript:void(0);" class="text-danger" onclick="destroy(' . $d->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                '
            ];
        }

        return response()->json($response);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'name',
            'url',
            'icon',
            'order'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Menu::where('parent_id', 0)
            ->count();

        $query_data = Menu::where('parent_id', 0)
            ->where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('url', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Menu::where('parent_id', 0)
            ->where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('url', 'like', "%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    '<span class="badge badge-success"><i class="fas fa-plus"></i></span>',
                    $nomor,
                    $val->name,
                    $val->url ? url($val->url) : null,
                    $val->icon,
                    $val->order,
                    '
                        <button type="button" class="btn btn-warning btn-sm" onclick="show(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="destroy(' . $val->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus</button>
                    '
                ];
                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'  => 'required',
            'order' => 'required'
        ], [
            'code.required'  => 'Mohon mengisi nama.',
            'order.required' => 'Mohon mengisi urutan.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Menu::create([
                'name'      => $request->name,
                'url'       => $request->url,
                'icon'      => $request->icon,
                'parent_id' => $request->parent_id,
                'order'     => $request->order
            ]);

            if($query) {
                activity()
                    ->performedOn(new Menu())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data menu ' . $query->name);

                $response = [
                    'status'  => 200,
                    'message' => 'Data telah diproses.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data gagal diproses.'
                ];
            }
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $data = Menu::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'  => 'required',
            'order' => 'required'
        ], [
            'code.required'  => 'Mohon mengisi nama.',
            'order.required' => 'Mohon mengisi urutan.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Menu::where('id', $id)->update([
                'name'      => $request->name,
                'url'       => $request->url,
                'icon'      => $request->icon,
                'parent_id' => $request->parent_id,
                'order'     => $request->order
            ]);

            if($query) {
                activity()
                    ->performedOn(new Menu())
                    ->causedBy(session('id'))
                    ->log('Mengubah data menu');

                $response = [
                    'status'  => 200,
                    'message' => 'Data telah diproses.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data gagal diproses.'
                ];
            }
        }

        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        $query = Menu::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Menu())
                ->causedBy(session('id'))
                ->log('Menghapus data menu');

            $response = [
                'status'  => 200,
                'message' => 'Data telah dihapus.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data gagal dihapus.'
            ];
        }

        return response()->json($response);
    }

}
