<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStipendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stipends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title');
            $table->float('amount');
            $table->enum('interval', ['daily','weekly','monthly']);
            $table->enum('status', ['running', 'paused', 'dued','completed'])->default('running');
            $table->integer('success_billed')->default(0);
            $table->integer('failed_billed')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('next_billing');
            $table->dateTime('due_date');
            $table->integer('limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stipends');
    }
}
