<?php

namespace App\Http\Controllers\WEB\Deliveryman;

use Illuminate\Http\Request;
use App\Models\DeliveryManReview;
use App\Http\Controllers\Controller;
use Auth;

class MyReviewController extends Controller
{
    public function index(){
        $id=Auth::guard('deliveryman')->user()->id;
        $reviews=DeliveryManReview::with('user', 'order')->where('delivery_man_id', $id)->where('status', 1)->latest()->get();
        
        return view('deliveryman.review', compact('reviews'));
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