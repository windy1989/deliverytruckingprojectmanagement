<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Digitrans - Role',
            'menu'    => Menu::where('parent_id', 0)->get(),
            'content' => 'setting.role'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'name'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Role::count();

        $query_data = Role::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Role::where(function($query) use ($search) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->name,
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
            'name' => 'required'
        ], [
            'name.required' => 'Mohon mengisi nama.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Role::create([
                'name' => $request->name
            ]);

            if($query) {
                if($request->has('menu_id')) {
                    foreach($request->menu_id as $mi) {
                        RoleAccess::create([
                            'role_id' => $query->id,
                            'menu_id' => $mi
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Role())
                    ->causedBy(session('id'))
                    ->withProperties($query)
                    ->log('Menambah data role ' . $query->name);

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
        $data = Role::find($request->id);
        return response()->json([
            'role'        => $data,
            'role_access' => $data->roleAccess
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required'
        ], [
            'name.required' => 'Mohon mengisi nama.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Role::where('id', $id)->update([
                'name' => $request->name
            ]);

            if($query) {
                if($request->has('menu_id')) {
                    RoleAccess::where('role_id', $id)->delete();
                    foreach($request->menu_id as $mi) {
                        RoleAccess::create([
                            'role_id' => $id,
                            'menu_id' => $mi
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Role())
                    ->causedBy(session('id'))
                    ->log('Mengubah data role');

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
        $query = Role::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Role())
                ->causedBy(session('id'))
                ->log('Menghapus data role');

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
