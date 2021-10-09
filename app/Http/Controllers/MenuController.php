<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Role;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        try {
            $menus = Menu::with('roles')->with('parent')->get();
            $roles = Role::all();
            return response()->json(['success' => true, 'menus' => $menus, 'roles' => $roles], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $menu = ($request->id) ? Menu::find($request->id) : new Menu();
            $menu->parent_id = $request->parent_id ?? '0';
            $menu->label = $request->label;
            $menu->url = $request->url;
            $menu->icon = $request->icon;

            $menu->save();

            $ids = [];

            foreach($request['roles'] as $role) 
            {
                array_push($ids, $role['id']);
            }

            $menu->roles()->sync($ids);

            return response()->json(['success' => true, 'msg' => 'Menu disimpan'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $menu = Menu::find($id);
            $menu->roles()->detach();
            $menu->delete();
        } catch (\Exception $e) {
            
        }
    }
}
