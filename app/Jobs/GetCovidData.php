<?php

namespace App\Jobs;

use App\Models\Covid;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetCovidData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        date_default_timezone_set('Asia/Seoul');
        $currentDate = date('Ymd', strtotime('Now'));
        $previousDate = date('Ymd', strtotime('-1 week'));
        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://openapi.data.go.kr/openapi/service/rest/Covid19/getCovid19SidoInfStateJson?serviceKey=4lyV1AhLwS2E8AbWo7qJKIsGqL8UPCTIqKP7LkFo62%2BZbmluePY8GC9jW7J0d5IlpfRGcRPk5e3er8Nvg08YIQ%3D%3D&pageNo=1&numOfRows=10&startCreateDt=' . $previousDate . '&endCreateDt=' . $currentDate);
        $promise = $client->sendAsync($request)->then(function ($response) {
            $xml = simplexml_load_string($response->getBody());
            $json = json_encode($xml);
            $array = json_decode($json, true);
            $lastWeekData = array_slice($array['body']['items']['item'], 0, 19 * 7);
            echo var_dump($lastWeekData);
            //한주치 데이터를 가져온다.

            foreach ($lastWeekData as $key => $data) {
                try {
                    $covidData = Covid::find($key + 1);

                    $day = $data['stdDay'];
                    $formattedDay = substr(str_replace(array('년 ', '월 ', '일 ', '시'), '-', $day), 0, -4);
                    $stdDay = date('Y-m-d 00:00:00', strtotime($formattedDay));
                    //날짜포멧 설정


                    $covidData->stdDay = $stdDay;
                    $covidData->localOccCnt = $data['localOccCnt'];
                    $covidData->overFlowCnt = $data['overFlowCnt'];
                    $covidData->gubun = $data['gubun'];
                    $covidData->defCnt = $data['defCnt'];
                    $covidData->isolClearCnt = $data['isolClearCnt'];
                    $covidData->deathCnt = $data['deathCnt'];
                    $covidData->save();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        });
        $promise->wait();
    }
}
