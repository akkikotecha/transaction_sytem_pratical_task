<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomFieldToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('custom_id', 20)->unique()->after('id');
            $table->dropPrimary();
            $table->primary('custom_id');
            $table->dropColumn('id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dropPrimary();
            $table->primary('id');
            $table->dropColumn('custom_id');
        });
    }
}