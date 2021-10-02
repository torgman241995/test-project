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
					Заказы
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
				<?php if($orders->count() > 0){
					?>
						<table border="1">
						   <tr>
								<th>ID</th>
								<th>Партнер</th>								
								<th>Состав</th>
								<th>Стоимость заказа</th>
								<th>Статус</th>
								<th>Дополнительно</th>
						   </tr>
							<?php foreach($orders as $order){
									if($order->status == 0){
										$status = 'Новый заказ';
										$status_color = "red";
									}
									if($order->status == 10){
										$status = 'Подтвержден';
										$status_color = "orange";
									}
									if($order->status == 20){
										$status = 'Завершен';
										$status_color = "green";
									}
							?>
							   <tr>
									<td>
										<b><?php echo $order->id; ?></b>
									</td>
									<td>
										<b><?php echo $order->partner->name; ?></b>
									</td>
									<td>
										<b><?php 
											$full_summ = 0;
											if($order->order_products->count() > 0){
												foreach($order->order_products as $product){
												?>
													<p><?php echo $product->product->name." - ".$product->quantity."шт x ".$product->price."руб."; ?></p>
												<?php
													//суммируем чек
													$line_summ = $product->quantity * $product->price;
													$full_summ = $full_summ + $line_summ;
												} 
											}
											else{
												echo '---';
											}
											
										?></b>
									</td>
									<td>
										<b><?php echo $full_summ; ?></b>
									</td>
									<td>
										<b style="color: <?php echo $status_color; ?>"><?php echo $status; ?></b>
									</td>
									<td>
										<a class="update_link" target="_blank" href="update/<?php echo $order->id; ?>"><b>Редактировать</b></a>
									</td>
								</tr>
						    <?php }?>
						</table>
					<?php
				}else{
					?>
						<h2>В базе данных ордера не найдены</h2>
					<?php
				}?>
				
            </div>
        </div>
    </body>
</html>
