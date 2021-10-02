<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(Request $request)
    {
		$city_name = $request->city_name;
		if(!isset($city_name) || empty($city_name)){
			$city_name = 'Bryansk';
		}
		
		//Токен для подключения к сервису погоды - openweathermap.org
        $key = env('WEATHER_KEY');

		$weather_data = WeatherController::curl_query($city_name, $key);
		
		$temp_calvin = $weather_data['main']['temp'];
		
		//конвертируем температуру из кельвина в цельсий, и убираем лишние знаки
		$temp = round($temp_calvin - 273.15);
	
				
        return view('weather')
               ->with('city_name', $city_name)
               ->with('temp', $temp);
    }
	
	/** 
		Запрос к апи сервиса погоды
		@city_name - название города по которому тянем данные через API
		@key -  API
	**/
	public function curl_query($city_name, $key)
    {
		$query_url = "https://api.openweathermap.org/data/2.5/weather?q=".$city_name."&appid=".$key;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_URL, $query_url);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
		$curl_data = curl_exec($curl);
		curl_close($curl);
		$weather_json = json_decode($curl_data, true);
		
		return $weather_json;
	}    
}
