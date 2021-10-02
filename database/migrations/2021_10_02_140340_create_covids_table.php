<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covids', function (Blueprint $table) {
            $table->id();
            $table->date('stdDay'); //기준일시
            $table->integer('localOccCnt'); //지역발생 수
            $table->integer('overFlowCnt'); //해외유입 수
            $table->string('gubun'); //구분
            $table->integer('defCnt'); //확진자 수
            $table->integer('isolClearCnt'); //격리해제 수
            $table->integer('deathCnt'); //사망자 수
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('covids');
    }
}
