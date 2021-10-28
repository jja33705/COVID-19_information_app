<?php

use App\Http\Controllers\GoogleOAuthController;
use App\Models\Covid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Covid/Covid', [
        'localData' => Covid::where([['stdDay', Covid::max('stdDay')], ['gubun', 'not like', '합계']])->orderByRaw('localOccCnt + overFlowCnt DESC')->get(),
        'totalData' => Covid::where('gubun', '합계')->orderByDesc('stdDay')->get(),
    ]);
})->name('covid');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/travel', function (Request $request) {
    $page = $request->page;  //페이지
    if (!$page) {
        $page = 1;
    }
    $searchWay = $request->searchWay;  //검색방법
    $search = $request->search; //검색어
    $lng = $request->lng;
    $lat = $request->lat;

    if ($searchWay == 'near') { //주변위치 검색
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', 'http://api.visitkorea.or.kr/openapi/service/rest/KorService/locationBasedList?serviceKey=' . env('DATA_PORTAL_KEY') . '&numOfRows=12&pageNo=' . $page . '&MobileOS=ETC&MobileApp=AppTest&arrange=B&contentTypeId=12&mapX=' . $lng . '&mapY=' . $lat . '&radius=10000&listYN=Y');
        $xml = simplexml_load_string($res->getBody());
        $json = json_encode($xml);
        $data = json_decode($json, true);
        $totalCount = $data['body']['totalCount'];
        if ($totalCount == 0) {
            $searchResult = $data['body']['items'];
        } else if ($totalCount == 1) {
            $searchResult = array();
            $searchResult[0] = $data['body']['items']['item'];
        } else {
            $searchResult = $data['body']['items']['item'];
        }
    } elseif ($searchWay == 'keyWord') {  //키워드 검색
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', 'http://api.visitkorea.or.kr/openapi/service/rest/KorService/searchKeyword?serviceKey=' . env('DATA_PORTAL_KEY') . '&MobileApp=AppTest&MobileOS=ETC&pageNo=' . $page . '&numOfRows=12&listYN=Y&arrange=B&contentTypeId=12&keyword=' . $search);
        $xml = simplexml_load_string($res->getBody());
        $json = json_encode($xml);
        $data = json_decode($json, true);
        $totalCount = $data['body']['totalCount'];
        if ($totalCount == 0) {
            $searchResult = $data['body']['items'];
        } else if ($totalCount == 1) {
            $searchResult = array();
            $searchResult[0] = $data['body']['items']['item'];
        } else {
            $searchResult = $data['body']['items']['item'];
        }
    }
    return Inertia::render('Travel/SearchTravel', [
        'localData' => Covid::where([['stdDay', Covid::max('stdDay')], ['gubun', 'not like', '합계'], ['gubun', 'not like', '검역']])->get(),
        'searchResult' => $searchResult,
        'page' => $page,
        'search' => $search,
        'searchWay' => $searchWay,
        'totalCount' => $totalCount,
        'lat' => $lat,
        'lng' => $lng
    ]);
})->name('travel.index');

Route::get('/travel/{id}', function (Request $request, $id) {
    $contentId = $id;

    $client = new GuzzleHttp\Client(); //관광지 세부정보
    $res = $client->request('GET', 'http://api.visitkorea.or.kr/openapi/service/rest/KorService/detailCommon?serviceKey=' . env('DATA_PORTAL_KEY') . '&numOfRows=100&pageNo=1&MobileOS=ETC&MobileApp=AppTest&contentId=' . $contentId . '&contentTypeId=12&defaultYN=Y&firstImageYN=Y&areacodeYN=Y&catcodeYN=N&addrinfoYN=Y&mapinfoYN=N&overviewYN=Y');
    $xml = simplexml_load_string($res->getBody());
    $json = json_encode($xml);
    $data = json_decode($json, true);
    $content = $data['body']['items']['item'];

    $client = new GuzzleHttp\Client(); //이미지
    $res = $client->request('GET', 'http://api.visitkorea.or.kr/openapi/service/rest/KorService/detailImage?serviceKey=' . env('DATA_PORTAL_KEY') . '&numOfRows=100&pageNo=1&MobileOS=ETC&MobileApp=AppTest&contentId=' . $contentId . '&imageYN=Y&subImageYN=Y');
    $xml = simplexml_load_string($res->getBody());
    $json = json_encode($xml);
    $data = json_decode($json, true);
    $totalCount = $data['body']['totalCount'];
    if ($totalCount == 0) {
        $images = $data['body']['items'];
    } else if ($totalCount == 1) {
        $images = array();
        $images[0] = $data['body']['items']['item'];
    } else {
        $images = $data['body']['items']['item'];
    }

    return Inertia::render('Travel/ShowTravel', [
        'content' => $content,
        'images' => $images,
        'page' => $request->page,
        'searchWay' => $request->searchWay,
        'search' => $request->search,
        'lat' => $request->lat,
        'lng' => $request->lng,
    ]);
})->name('travel.show');

Route::get('auth/google', [GoogleOAuthController::class, 'redirectToGoogle'])->name('googleLogin');

Route::get('/google/callback', [GoogleOAuthController::class, 'handleGoogleCallback']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
});
