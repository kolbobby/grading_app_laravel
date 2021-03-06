<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('students', function($table) {
			$table->increments('id');

			$table->integer('parent_id')->unsigned();
			$table->foreign('parent_id')->references('id')->on('parents');

			$table->integer('sc_id')->unsigned();
			$table->foreign('sc_id')->references('id')->on('school_counselors');

			$table->string('name');

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
		Schema::drop('students');
	}

}
