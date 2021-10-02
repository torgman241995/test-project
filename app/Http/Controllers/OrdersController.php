<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Partner;
use App\OrderProduct;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function __construct()
    {
        
    }
	
	/** 
		Список заказов
	**/
    public function index()
    {		
		$orders = Order::with('partner')
		->with('order_products')
		->with('order_products.product')
		->orderBy('orders.status')
		->get();
		
        return view('orders')
               ->with('orders', $orders);
    }
	
	/** 
		Редактирование заказа
	**/
	public function update(Request $request)
    {		
		//Данные заказа
		$order = Order::where('id', $request->id)
		->with('partner')
		->with('order_products')
		->with('order_products.product')
		->orderBy('orders.status')
		->first();
		
		//Партнеры
		$partners = Partner::get();
		
        return view('update')
               ->with('order', $order)
               ->with('partners', $partners);
    }
	
	/** 
		Обновление данных заказа
	**/
	public function save(Request $request)
    {					
		//Данные заказа
		if(isset($request) && !empty($request)){
			$id = $request['id'];
			$email = $request['email'];
			$partner_id = $request['partner_id'];
			$status = $request['status'];
			
			//обновляем данные по товарам добавленным в заказа
			if(isset($request['items']) && $request['items'] !== NULL){
				foreach($request['items'] as $lines => $items){
					
					$decode = json_decode($items);
					$item_id = $decode->item_id;
					$item_count = $decode->item_count;
					
					//обновляем данные по конкретному товару
					$item_line = OrderProduct::where('id', $item_id)->first();
					if($item_count > 0){
						$item_line->quantity = $item_count;
						$item_line->save();
					}
					else{
						$item_line->delete();
					}
				}
			}
			
			//обновляем данные основной части заказа
			$order_upd = Order::where('id', $id)->first();
			$order_upd->status = $status;
			$order_upd->client_email = $request['email'];
			$order_upd->partner_id = $partner_id;
			$order_upd->save();
		}
        //return view('save');
    }
}
