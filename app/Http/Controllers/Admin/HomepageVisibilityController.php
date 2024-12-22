<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomePageOneVisibility;
class HomepageVisibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index(){
        $sections = HomePageOneVisibility::all();
        return response()->json(['sections' => $sections], 200);
    }

    public function update(Request $request){

        foreach($request->ids as $index => $id){
            if($request->ids[$index] == null || $request->section_names[$index] == null || $request->quantities[$index] == null) {
                return response()->json(['message' => 'All field should be required'], 403);
            }else{
                $section = HomePageOneVisibility::find($request->ids[$index]);
                $section->section_name = $request->section_names[$index];
                $section->qty = $request->quantities[$index];
                $section->save();
            }
        }

        $notification= trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);
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


    /**
     * Handle the store action.
     */
    public function store()
    {
        // TODO: Implement store logic.
    }

}