<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/nearTravelSpots', function (Request $request) {
    $lng = $request->lng;
    $lat = $request->lat;
    $page = $request->page;
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'http://api.visitkorea.or.kr/openapi/service/rest/KorService/locationBasedList?serviceKey=' . env('DATA_PORTAL_KEY') . '&numOfRows=12&pageNo=' . $page . '&MobileOS=ETC&MobileApp=AppTest&arrange=B&contentTypeId=12&mapX=' . $lng . '&mapY=' . $lat . '&radius=10000&listYN=Y');
    $xml = simplexml_load_string($res->getBody());
    $json = json_encode($xml);
    $array = json_decode($json, true);
    return response()->json($array);
});

Route::get('/searchTravelSpots', function (Request $request) {
    $search = $request->search;
    $page = $request->page;
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'http://api.visitkorea.or.kr/openapi/service/rest/KorService/searchKeyword?serviceKey=' . env('DATA_PORTAL_KEY') . '&MobileApp=AppTest&MobileOS=ETC&pageNo=' . $page . '&numOfRows=12&listYN=Y&arrange=B&contentTypeId=12&keyword=' . $search);
    $xml = simplexml_load_string($res->getBody());
    $json = json_encode($xml);
    $array = json_decode($json, true);
    return response()->json($array);
});
