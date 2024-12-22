<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Order;
use App\Models\Setting;
use App\Models\DeliveryMan;
use App\Models\OrderAddress;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\OrderProductVariant;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Country;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $orders = Order::with('user')->orderBy('id','desc')->get();
        $title = trans('admin_validation.All Orders');
        $setting = Setting::first();

        return view('admin.order', compact('orders','title','setting'));

    }

    public function pendingOrder(){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',0)->get();
        $title = trans('admin_validation.Pending Orders');
        $setting = Setting::first();

        return view('admin.order', compact('orders','title','setting'));
    }

    public function pregressOrder(){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',1)->get();
        $title = trans('admin_validation.Pregress Orders');
        $setting = Setting::first();

        return view('admin.order', compact('orders','title','setting'));
    }

    public function deliveredOrder(){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',2)->get();
        $title = trans('admin_validation.Delivered Orders');
        $setting = Setting::first();

        return view('admin.order', compact('orders','title','setting'));
    }

    public function completedOrder(){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',3)->get();
        $title = trans('admin_validation.Completed Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting'));
    }

    public function declinedOrder(){
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',4)->get();
        $title = trans('admin_validation.Declined Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting'));
    }

    public function cashOnDelivery(){
        $orders = Order::with('user')->orderBy('id','desc')->where('cash_on_delivery',1)->get();
        $title = trans('admin_validation.Cash On Delivery');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting'));
    }

    public function show($id){
        $order = Order::with('user','orderProducts.orderProductVariants','orderAddress')->find($id);
        $products = Product::where('status',1)->get();
        $deliverymans=DeliveryMan::latest()->get();
        $setting = Setting::first();

        $brands = Brand::all();
        $categories = Category::with('subCategories','products')->get();
        $countries = Country::all();
        return view('admin.show_order',compact('order', 'deliverymans', 'setting','products','brands','categories','countries'));
    }

    public function updateOrderStatus(Request $request , $id){
        $rules = [
            'order_status' => 'required',
            'payment_status' => 'required',
        ];
        $this->validate($request, $rules);

        $order = Order::find($id);
        if($request->order_status == 0){
            $order->order_status = 0;
            $order->save();
        }else if($request->order_status == 1){
            $order->order_status = 1;
            $order->order_approval_date = date('Y-m-d');
            $order->save();
           //return $this->sendFirebasePush($tokens,$data);
        }else if($request->order_status == 2){
            $order->order_status = 2;
            $order->order_delivered_date = date('Y-m-d');
            $order->save();
        }else if($request->order_status == 3){
            $order->order_status = 3;
            $order->order_completed_date = date('Y-m-d');
            $order->save();
        }else if($request->order_status == 4){
            $order->order_status = 4;
            $order->order_declined_date = date('Y-m-d');
            $order->save();
        }

        if($request->payment_status == 0){
            $order->payment_status = 0;
            $order->save();
        }elseif($request->payment_status == 1){
            $order->payment_status = 1;
            $order->payment_approval_date = date('Y-m-d');
            $order->save();
        }

        if($request->delivery_man_id == 0){
            $order->delivery_man_id = 0;
            $order->order_request = 0;
            $order->save();
        }else if($request->delivery_man_id > 0){
            $order->delivery_man_id = $request->delivery_man_id;
            $order->order_request = 0;
            $order->order_req_date = date('Y-m-d');
            $order->save();
        }

        

        $notification = trans('admin_validation.Order Status Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function destroy($id){
        $order = Order::find($id);
        $order->delete();
        $orderProducts = OrderProduct::where('order_id',$id)->get();
        $orderAddress = OrderAddress::where('order_id',$id)->first();
        foreach($orderProducts as $orderProduct){
            OrderProductVariant::where('order_product_id',$orderProduct->id)->delete();
            $orderProduct->delete();
        }
        OrderAddress::where('order_id',$id)->delete();

        $notification = trans('admin_validation.Delete successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.all-order')->with($notification);
    }

    public function addNewProduct(Request $request,$id)
    {
        $product = Product::find($request->product_id);
        if($product->offer_price == NULL)
        {
            $amount = $product->price;
        }else{
            $amount = $product->offer_price;
        }
        $order_product = new OrderProduct();
        $order_product->order_id = $id;
        $order_product->product_id = $request->product_id;
        $order_product->seller_id = $product->vendor_id;
        $order_product->product_name = $product->name;
        $order_product->unit_price = $amount;
        $order_product->qty = $request->quantity;
        $order_product->save();

        if($product->offer_price == NULL)
        {
            $add_amount = $product->price*$request->quantity;
        }else{
            $add_amount = $product->offer_price*$request->quantity;
        }
        $order = Order::find($id);
        Order::where('id',$id)->update([
            'total_amount' => $order->total_amount + $add_amount
        ]);

        $notification = trans('admin_validation.Order Status Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function incrementOrderQuantity($id,$order_id)
    {
        $orderProduct = OrderProduct::find($id);
        OrderProduct::where('id',$id)->update([
            'qty' => $orderProduct->qty + 1
        ]);

        $order = Order::find($order_id);
        Order::where('id',$order_id)->update([
            'total_amount' => $order->total_amount + $orderProduct->unit_price
        ]);

        $notification = trans('admin_validation.Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
    public function decrementOrderQuantity($id,$order_id)
    {
        $orderProduct = OrderProduct::find($id);
        
        OrderProduct::where('id',$id)->update([
            'qty' => $orderProduct->qty - 1
        ]);

        $order = Order::find($order_id);
        Order::where('id',$order_id)->update([
            'total_amount' => $order->total_amount - $orderProduct->unit_price
        ]);

        $notification = trans('admin_validation.Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function deleteOrderProduct($id,$order_id)
    {
        
        $orderProduct =  OrderProduct::find($id);
        
        $amount = $orderProduct->unit_price * $orderProduct->qty;
        $orderProduct->delete();
        $order = Order::find($order_id);
        Order::where('id',$order_id)->update([
            'total_amount' => $order->total_amount - $amount
        ]);

        $notification = trans('admin_validation.Delete successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
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

}