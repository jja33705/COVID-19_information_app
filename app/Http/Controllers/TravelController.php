<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class TravelController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page;  //페이지
        if (!$page) {
            $page = 1;
        }
        $searchWay = $request->searchWay;  //검색방법
        $search = $request->search; //검색어
        $lng = $request->lng;
        $lat = $request->lat;

        if ($searchWay == 'near') { //주변위치 검색
            $client = new Client();
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
            $client = new Client();
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
            'localData' => Covid::selectRaw('gubun, localOccCnt + overFlowCnt as newDefCnt')->where([['stdDay', Covid::max('stdDay')], ['gubun', 'not like', '합계'], ['gubun', 'not like', '검역']])->get(),
            'searchResult' => $searchResult,
            'page' => $page,
            'search' => $search,
            'searchWay' => $searchWay,
            'totalCount' => $totalCount,
            'lat' => $lat,
            'lng' => $lng,
        ]);
    }

    public function show(Request $request, $id)
    {
        $contentId = $id;

        $client = new Client(); //관광지 세부정보
        $res = $client->request('GET', 'http://api.visitkorea.or.kr/openapi/service/rest/KorService/detailCommon?serviceKey=' . env('DATA_PORTAL_KEY') . '&numOfRows=100&pageNo=1&MobileOS=ETC&MobileApp=AppTest&contentId=' . $contentId . '&contentTypeId=12&defaultYN=Y&firstImageYN=Y&areacodeYN=Y&catcodeYN=N&addrinfoYN=Y&mapinfoYN=Y&overviewYN=Y');
        $xml = simplexml_load_string($res->getBody());
        $json = json_encode($xml);
        $data = json_decode($json, true);
        $content = $data['body']['items']['item'];

        $client = new Client(); //이미지
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
    }
}
