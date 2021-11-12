<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use Illuminate\Http\Request;
use Inertia\Inertia;
// use GuzzleHttp\Client;

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

    public function show($id)
    {
        $contentId = $id;

        return Inertia::render('Travel/ShowTravel', [
            'localCovidData' => Covid::selectRaw('gubun, localOccCnt + overFlowCnt as newDefCnt')->where([['stdDay', Covid::max('stdDay')], ['gubun', 'not like', '합계'], ['gubun', 'not like', '검역']])->get(),
            'contentId' => $contentId,
        ]);
    }
}
