<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExistingTableAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('attendance', function (Blueprint $table) {
        //     $table->string('kolom_baru')->nullable(); // Menambahkan kolom baru
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('attendance', function (Blueprint $table) {
        //     $table->dropColumn('kolom_baru'); // Menghapus kolom yang ditambahkan
        // });
    }
}
