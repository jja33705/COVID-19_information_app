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
            'localData' => Covid::where([['stdDay', Covid::max('stdDay')], ['gubun', 'not like', '합계']])->orderByRaw('localOccCnt + overFlowCnt DESC')->get(),
            'totalData' => Covid::where('gubun', '합계')->orderByDesc('stdDay')->get(),
        ]);
    }
}
