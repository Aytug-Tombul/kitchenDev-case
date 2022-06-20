<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreateRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::with('order_items.Product')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(OrderCreateRequest $request)
    {

       /* DB::transaction(function () {


        });*/
        DB::beginTransaction();
       
        try {
            $order = new Order();
            //$order= Order::create(request()->all());
            $order['description'] = $request->description;
            $order['delivery_address'] = $request->delivery_address;
            $order->save();
            $cost=0;
            foreach ($request["order_items"] as $item) {
                $resultItem = $this->quantityController($item, $order['id']);
                if (!$resultItem['quantity_has']) {
                     throw new Exception("quantity error.", 1);
                }
                $cost+=$resultItem['item_cost'];
            }
            $order['cost'] = $cost;
            $order->update();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                $e->getMessage()
            ],409);

        }

        return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
 
        return Order::with('order_items.Product')->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
       
        try {
            $order = Order::with('order_items')->findOrFail($id);
            foreach ($order['order_items'] as $item) {
                $product=Product::findOrFail($item['id']);
                $product['quantity_available'] += $item['quantity'];
                $product->update();
            }
            OrderItem::where('order_id' , $id)->delete();
           $order->delete($id);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                $e->getMessage()
            ],409);
        }

        return 'success';
    }



    public function quantityController($product, $orderID){
        $productRow=Product::where('id' , $product['product_id'])->first();
        if($productRow['quantity_available'] >= $product['quantity']){
            $orderItem = new OrderItem();
            $orderItem->product_id = $product['product_id'];
            $orderItem->order_id = $orderID;
            $orderItem->quantity = $product['quantity'];
            $orderItem->save();
            $productRow['quantity_available'] = $productRow['quantity_available'] -  $product['quantity'];
            $productRow->update();
            return[
                'quantity_has' => true,
                'item_cost'=> $productRow['cost'] * $product['quantity']
            ];
        }else{
            return[
                'quantity_has' => false
            ];
        }

    }
}
