<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use Illuminate\Http\Request;
use Inertia\Inertia;
use GuzzleHttp\Client;

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
        $areaCode = $request->areaCode;
        $sigunguCode = $request->sigunguCode;
        $cat1 = $request->cat1;
        $cat2 = $request->cat2;
        $cat3 = $request->cat3;

        return Inertia::render('Travel/SearchTravel', [
            'localCovidData' => Covid::selectRaw('gubun, localOccCnt + overFlowCnt as newDefCnt')->where([['stdDay', Covid::max('stdDay')], ['gubun', 'not like', '합계'], ['gubun', 'not like', '검역']])->get(),
            'page' => $page,
            'search' => $search,
            'searchWay' => $searchWay,
            'lat' => $lat,
            'lng' => $lng,
            'areaCode' => $areaCode,
            'sigunguCode' => $sigunguCode,
            'cat1' => $cat1,
            'cat2' => $cat2,
            'cat3' => $cat3,
        ]);
    }

    public function show(Request $request, $id)
    {
        $contentId = $id;

        $areaCode = $request->areaCode;
        $sigunguCode = $request->sigunguCode;
        $cat1 = $request->cat1;
        $cat2 = $request->cat2;
        $cat3 = $request->cat3;

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
            'localCovidData' => Covid::selectRaw('gubun, localOccCnt + overFlowCnt as newDefCnt')->where([['stdDay', Covid::max('stdDay')], ['gubun', 'not like', '합계'], ['gubun', 'not like', '검역']])->get(),
            'content' => $content,
            'images' => $images,
            'page' => $request->page,
            'searchWay' => $request->searchWay,
            'search' => $request->search,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'areaCode' => $areaCode,
            'sigunguCode' => $sigunguCode,
            'cat1' => $cat1,
            'cat2' => $cat2,
            'cat3' => $cat3,
        ]);
    }
}
