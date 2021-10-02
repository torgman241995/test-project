<?php

namespace App;

use App\Partner;
use App\OrderProduct;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
            'client_email',
            'delivery_dt',
        ];

    protected $casts = [
            'status'  => 'integer',
            'partner_id'  => 'integer',
        ];
		

	  public function partner(){
		return $this->hasOne(Partner::class, "id", "partner_id");
	  }
	  
	  public function order_products(){
		return $this->hasMany(OrderProduct::class, "order_id", "id");
	  }	
}
