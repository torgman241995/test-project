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
		$default_city = 'Bryansk';
		$city_name = $request->city_name;
		if(!isset($city_name) || empty($city_name)){
			$city_name = $default_city;
		}
		
		//Токен для подключения к сервису погоды - openweathermap.org
        $key = env('WEATHER_KEY');

		$weather_data = WeatherController::curl_query($city_name, $key, $default_city);
		
		$temp_calvin = $weather_data['main']['temp'];
		$location_name = $weather_data['name'];
		
		//конвертируем температуру из кельвина в цельсий, и убираем лишние знаки
		$temp = round($temp_calvin - 273.15);
	
				
        return view('weather')
               ->with('city_name', $location_name)
               ->with('temp', $temp);
    }
	
	/** 
		Запрос к апи сервиса погоды
		@city_name - название города по которому тянем данные через API
		@key -  API
		@default_city - дефолтное значение названия города
	**/
	public function curl_query($city_name, $key, $default_city)
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
		//В случае неверного названия города - получаем дефолтное значение для Брянска
		if($weather_json['cod'] == 404){
			$weather_json = WeatherController::curl_query($default_city, $key, $default_city);
		}
	
		return $weather_json;
	}    
}
