<?php

namespace App\Http\Controllers;

use App\Models\Covid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CovidController extends Controller
{
    public function index()
    {
        return Inertia::render('Covid/Covid', [
            'localData' => Covid::selectRaw('gubun, localOccCnt + overFlowCnt as newDefCnt')->where([['stdDay', Covid::max('stdDay')], ['gubun', 'not like', '합계']])->orderByRaw('newDefCnt DESC')->get(),
            'totalData' => Covid::selectRaw('stdDay, defCnt, deathCnt, isolClearCnt, localOccCnt + overFlowCnt as newDefCnt')->where('gubun', '합계')->orderBy('stdDay', 'asc')->get(),
        ]);
    }
}
// 'localCovidData' => Covid::selectRaw('gubun, localOccCnt + overFlowCnt as newDefCnt')->where([['stdDay', Covid::max('stdDay')], ['gubun', 'not like', '합계'], ['gubun', 'not like', '검역']])->get(),