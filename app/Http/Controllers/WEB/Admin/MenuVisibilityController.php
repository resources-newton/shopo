<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuVisibility;
class MenuVisibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index(){
        $menus = MenuVisibility::all();
        return response()->json(['menus' => $menus], 200);
    }

    public function update($id){
        $menu = MenuVisibility::find($id);
        if($menu->status==1){
            $menu->status=0;
            $menu->save();
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $menu->status=1;
            $menu->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    /**
     * Handle the create action.
     */
    public function create()
    {
        // TODO: Implement create logic.
    }

    /**
     * Handle the show action.
     */
    public function show()
    {
        // TODO: Implement show logic.
    }

    /**
     * Handle the store action.
     */
    public function store()
    {
        // TODO: Implement store logic.
    }

    /**
     * Handle the edit action.
     */
    public function edit()
    {
        // TODO: Implement edit logic.
    }

    /**
     * Handle the destroy action.
     */
    public function destroy()
    {
        // TODO: Implement destroy logic.
    }

}