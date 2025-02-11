<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintainanceText;
use App\Models\AnnouncementModal;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\ShopPage;
use App\Models\SeoSetting;
use Image;
use File;
class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function maintainanceMode()
    {
        $maintainance = MaintainanceText::first();

        return response()->json(['maintainance' => $maintainance], 200);

    }

    public function maintainanceModeUpdate(Request $request)
    {
        $rules = [
            'description'=> 'required',
            'status'=> 'required',
        ];
        $customMessages = [
            'description.required' => trans('Description is required'),
            'status.required' => trans('Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $maintainance = MaintainanceText::first();
        if($request->image){
            $old_image=$maintainance->image;
            $image=$request->image;
            $ext=$image->getClientOriginalExtension();
            $image_name= 'maintainance-mode-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $maintainance->image=$image_name;
            $maintainance->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }
        $maintainance->status = $request->maintainance_mode ? 1 : 0;
        $maintainance->description = $request->description;
        $maintainance->save();

        $notification= trans('Updated Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function announcementModal(){
        $announcement = AnnouncementModal::first();

        return response()->json(['announcement' => $announcement], 200);
    }

    public function announcementModalUpdate(Request $request)
    {
        $rules = [
            'description' => 'required',
            'title' => 'required',
            'expired_date' => 'required',
            'status' => 'required',
        ];
        $customMessages = [
            'description.required' => trans('Description is required'),
            'title.required' => trans('Title is required'),
            'status.required' => trans('Status is required'),
            'expired_date.required' => trans('Expired date is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $announcement = AnnouncementModal::first();
        if($request->image){
            $old_image=$announcement->image;
            $image=$request->image;
            $ext=$image->getClientOriginalExtension();
            $image_name= 'announcement-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $announcement->image=$image_name;
            $announcement->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }
        $announcement->description = $request->description;
        $announcement->title = $request->title;
        $announcement->expired_date = $request->expired_date;
        $announcement->status = $request->status ? 1 : 0;
        $announcement->save();

        $notification= trans('Updated Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function headerPhoneNumber(){
        $setting = Setting::select('topbar_phone','topbar_email')->first();

        return response()->json(['setting' => $setting], 200);
    }

    public function updateHeaderPhoneNumber(Request $request){
        $rules = [
            'topbar_phone'=>'required',
            'topbar_email'=>'required',
        ];
        $customMessages = [
            'topbar_phone.required' => trans('Topbar phone is required'),
            'topbar_email.required' => trans('Topbar email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $setting = Setting::first();
        $setting->topbar_phone = $request->topbar_phone;
        $setting->topbar_email = $request->topbar_email;
        $setting->save();

        $notification= trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function loginPage(){
        $banner = BannerImage::select('image')->whereId('13')->first();
        return response()->json(['banner' => $banner], 200);

    }

    public function updateloginPage(Request $request){

        $banner = BannerImage::whereId('13')->first();
        if($request->image){
            $existing_banner = $banner->image;
            $extention = $request->image->getClientOriginalExtension();
            $banner_name = 'banner'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/website-images/'.$banner_name;
            Image::make($request->image)
                ->save(public_path().'/'.$banner_name);
            $banner->image = $banner_name;
            $banner->save();
            if($existing_banner){
                if(File::exists(public_path().'/'.$existing_banner))unlink(public_path().'/'.$existing_banner);
            }
        }

        $notification = trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function shopPage(){
        $shop_page = ShopPage::first();
        return response()->json(['shop_page' => $shop_page], 200);
    }

    public function updateFilterPrice(Request $request){
        $rules = [
            'filter_price_range' => 'required|numeric',
        ];
        $customMessages = [
            'filter_price_range.required' => trans('Filter price is required'),
            'filter_price_range.numeric' => trans('Filter price should be numeric number'),
        ];
        $this->validate($request, $rules,$customMessages);

        $shop_page = ShopPage::first();
        $shop_page->filter_price_range = $request->filter_price_range;
        $shop_page->save();
        $notification = trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function seoSetup(){
        $pages = SeoSetting::all();
        return response()->json(['pages' => $pages], 200);
    }

    public function getSeoSetup($id){
        $page = SeoSetting::find($id);
        return response()->json(['page' => $page], 200);
    }

    public function updateSeoSetup(Request $request, $id){
        $rules = [
            'seo_title' => 'required',
            'seo_description' => 'required'
        ];
        $customMessages = [
            'seo_title.required' => trans('Seo title is required'),
            'seo_description.required' => trans('Seo description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $page = SeoSetting::find($id);
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->save();

        $notification = trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);

    }

    public function productProgressbar(){
        $setting = Setting::select('show_product_progressbar')->first();
        return response()->json(['setting' => $setting], 200);
    }


    public function updateProductProgressbar(){
        $setting = Setting::first();
        if($setting->show_product_progressbar == 1){
            $setting->show_product_progressbar = 0;
            $setting->save();
            $message = trans('Inactive Successfully');
        }else{
            $setting->show_product_progressbar = 1;
            $setting->save();
            $message = trans('Active Successfully');
        }
        return response()->json($message);
    }

    public function defaultAvatar(){
        $defaultProfile = BannerImage::select('title','image')->whereId('15')->first();
        return response()->json(['defaultProfile' => $defaultProfile], 200);
    }

    public function updateDefaultAvatar(Request $request){
        $defaultProfile = BannerImage::whereId('15')->first();
        if($request->avatar){
            $existing_avatar = $defaultProfile->image;
            $extention = $request->avatar->getClientOriginalExtension();
            $default_avatar = 'default-avatar'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $default_avatar = 'uploads/website-images/'.$default_avatar;
            Image::make($request->avatar)
                ->save(public_path().'/'.$default_avatar);
            $defaultProfile->image = $default_avatar;
            $defaultProfile->save();
            if($existing_avatar){
                if(File::exists(public_path().'/'.$existing_avatar))unlink(public_path().'/'.$existing_avatar);
            }
        }

        $notification = trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function sellerCondition(){
        $seller_condition = Setting::select('seller_condition')->first();
        return response()->json(['seller_condition' => $seller_condition], 200);
    }

    public function updatesellerCondition(Request $request){
        $rules = [
            'terms_and_condition' => 'required'
        ];
        $customMessages = [
            'terms_and_condition.required' => trans('Terms and condition is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $setting = Setting::first();
        $setting->seller_condition = $request->terms_and_condition;
        $setting->save();
        $notification = trans('Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function subscriptionBanner(){
        $subscription_banner = BannerImage::select('id','image','banner_location','header','title')->find(27);
        return response()->json(['subscription_banner' => $subscription_banner], 200);
    }

    public function updatesubscriptionBanner(Request $request){
        $rules = [
            'title' => 'required',
            'header' => 'required'
        ];
        $customMessages = [
            'title.required' => trans('Title is required'),
            'header.required' => trans('Header is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $subscription_banner = BannerImage::find(27);
        if($request->image){
            $existing_banner = $subscription_banner->image;
            $extention = $request->image->getClientOriginalExtension();
            $banner_name = 'banner'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/website-images/'.$banner_name;
            Image::make($request->image)
                ->save(public_path().'/'.$banner_name);
            $subscription_banner->image = $banner_name;
            $subscription_banner->save();
            if($existing_banner){
                if(File::exists(public_path().'/'.$existing_banner))unlink(public_path().'/'.$existing_banner);
            }
        }
        $subscription_banner->title = $request->title;
        $subscription_banner->header = $request->header;
        $subscription_banner->save();

        $notification = trans('Update Successfully');
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