<?php

namespace App\Http\Controllers\WEB\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use Auth;
class SellerProductReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }


    public function index(){
        $seller = Auth::guard('web')->user()->seller;
        $reviews = ProductReview::with('user','product')->orderBy('id','desc')->where(['product_vendor_id' => $seller->id])->get();
        return view('seller.product_review', compact('reviews'));
    }

    public function show($id){
        $review = ProductReview::with('user','product')->find($id);
        if($review){
            $seller = Auth::guard('web')->user()->seller;
            if($review->product_vendor_id == $seller->id){
                return view('seller.show_product_review',compact('review'));
            }else{
                $notification= trans('admin_validation.Something went wrong');
                $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('seller.product-review')->with($notification);
            }

        }else{
            $notification= trans('admin_validation.Something went wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('seller.product-review')->with($notification);
        }

    }
    public function changeStatus()
    {
        // TODO: Implement create logic.
    }

    /**
     * Handle the create action.
     */
    public function create()
    {
        // TODO: Implement create logic.
    }

    /**
     * Handle the store action.
     */
    public function store()
    {
        // TODO: Implement store logic.
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

}