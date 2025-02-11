<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class LanguageController extends Controller
{

    public function adminLnagugae(){
        $data = include(resource_path('lang/en/admin.php'));

        return response()->json(['data' => $data], 200);

    }

    public function updateAdminLanguage(Request $request){

        $dataArray = [];
        foreach($request->values as $index => $value){
            $dataArray[$index] = $value;
        }
        file_put_contents(resource_path('lang/en/admin.php'), "");
        $dataArray = var_export($dataArray, true);
        file_put_contents(resource_path('lang/en/admin.php'), "<?php\n return {$dataArray};\n ?>");

        $notification= trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function adminValidationLnagugae(){
        $data = include(resource_path('lang/en/admin_validation.php'));

        return response()->json(['data' => $data], 200);
    }

    public function updateAdminValidationLnagugae(Request $request){
        $dataArray = [];
        foreach($request->values as $index => $value){
            $dataArray[$index] = $value;
        }
        file_put_contents(resource_path('lang/en/admin_validation.php'), "");
        $dataArray = var_export($dataArray, true);
        file_put_contents(resource_path('lang/en/admin_validation.php'), "<?php\n return {$dataArray};\n ?>");

        $notification= trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function websiteLanguage(){

        $data = include(resource_path('lang/en/user.php'));

        return response()->json(['data' => $data], 200);
    }

    public function updateLanguage(Request $request){

        $dataArray = [];
        foreach($request->values as $index => $value){
            $dataArray[$index] = $value;
        }
        file_put_contents(resource_path('lang/en/user.php'), "");
        $dataArray = var_export($dataArray, true);
        file_put_contents(resource_path('lang/en/user.php'), "<?php\n return {$dataArray};\n ?>");

        $notification= trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }


    public function websiteValidationLanguage(){
        $data = include(resource_path('lang/en/user_validation.php'));

        return response()->json(['data' => $data], 200);
    }

    public function updateValidationLanguage(Request $request){

        $dataArray = [];
        foreach($request->values as $index => $value){
            $dataArray[$index] = $value;
        }
        file_put_contents(resource_path('lang/en/user_validation.php'), "");
        $dataArray = var_export($dataArray, true);
        file_put_contents(resource_path('lang/en/user_validation.php'), "<?php\n return {$dataArray};\n ?>");

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
     * Handle the update action.
     */
    public function update()
    {
        // TODO: Implement update logic.
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