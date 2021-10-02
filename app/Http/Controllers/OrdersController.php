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
		
        return view('save');
    }
}
