<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
			.update_link{
				color: #030303;
				text-decoration: none;
				font-size: 13px;
			}
			td{
				padding-left: 30px;
				padding-right: 30px;
			}
			.btn{
				color: #ffffff;
				background-color: #000000;
				padding-top: 10px;
				padding-bottom: 10px;
				padding-left: 20px;
				padding-right: 20px;
				text-decoration: none;
			}
			.btn:hover{
				color: #ffffff;
				background-color: green;
				padding-top: 10px;
				padding-bottom: 10px;
				padding-left: 20px;
				padding-right: 20px;
				text-decoration: none;
			}
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
					Редактирование заказа
                </div>

				<div class="links">
				<hr>
                    <a href="/weather">Погода</a>
                    <a href="/orders">Заказы</a>
					<br>
					<br>
					<a href="/">Вернуться на главную</a>
					<hr>
                </div>
				<?php 
					if($order->status == 0){
						$status = 'Новый заказ';
					}
					if($order->status == 10){
						$status = 'Обработан';
					}
					if($order->status == 20){
						$status = 'Завершен';
					}
				?>
				<form role="form">
					
					<div style="text-align: right; margin-top: 20px!important;">
					  <label><b>E-mail клиента</b></label>
					  <input type="text" id="id" name="id" value="<?php echo $order->id; ?>" style="display: none;">
					  <input type="email" id="email" name="email" value="<?php echo $order->client_email; ?>"><br><br>
					  <label><b>Партнер</b></label>
					  <select name="partner_id" id="partner_id">
						  <optgroup label="Текущий партнер">
							<option value="<?php echo $order->partner->id; ?>"><?php echo $order->partner->name; ?></option>
						  </optgroup>
						  <?php 
							if($partners->count() > 0){
								?>
								<optgroup label="Выберите нового партнера">
								<?php
									foreach($partners as $partner){
										?>
											<option value="<?php echo $partner->id; ?>"><?php echo $partner->name; ?></option>
										<?php
									}?>
								</optgroup>
								<?php
							}
						  ?>					  
					  </select><br><br>
					  <label><b>Статус заказа</b></label>
					  <select name="status" id="status">
						  <optgroup label="Текущий статус">
							<option value="<?php echo $order->status; ?>"><?php echo $status; ?></option>
						  </optgroup>
						  <optgroup label="Выберите новый статус">
							<option value="0">Новый заказ</option>
							<option value="10">Обработан</option>
							<option value="20">Завершен</option>
						  </optgroup>
					  </select><br><br>
					  <a href="javascript:void(0);" class="btn">Сохранить</a>
				  </div>
				  <div style="text-align: left; margin-top: -120px;">
							<?php 
								$full_summ = 0;
								if($order->order_products->count() > 0){
									foreach($order->order_products as $product_data){
								?>
									<div data-id="<?php echo $product_data->id;?>" data-type="full_block" style="margin-bottom: 15px; align: left;">
										<a href="javascript:void(0);" data-id="<?php echo $product_data->id;?>" data-type="delete_block" class="btn delete_btn" style="margin-right: 10px;">x</a>
										<b><?php echo $product_data->product->name." - ".$product_data->product->price;?>руб x </b>
										<input style="width: 50px;" type="number" min="0" data-id="<?php echo $product_data->id;?>" data-type="count_block" data-price="" value="<?php echo $product_data->quantity;?>">
										<span>
											<div style="padding-left: 200px;">
												<b>(<b data-id="<?php echo $product_data->id;?>" data-type="summ_block">123</b> руб)</b>
											</div>
										</span>
									</div>
								<?php
										//суммируем чек
										$line_summ = $product_data->quantity * $product_data->price;
										$full_summ = $full_summ + $line_summ;
									} 
								}
								else{
									echo '---';
								}
											
							?>						
					</div>
				</form>
				
            </div>
        </div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
			$(function() {
				$(".btn" ).click(function() {
					var id = $("#id" ).val();
					var email = $("#email" ).val();
					var partner_id = $("#partner_id" ).val();
					var status = $("#status" ).val();
					
					
				});
			});
		</script>
    </body>
</html>