<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('grades', function($table) {
			$table->increments('id');

			$table->integer('assignment_id')->unsigned();
			$table->foreign('assignment_id')->references('id')->on('assignments');

			$table->integer('student_id')->unsigned();
			$table->foreign('student_id')->references('id')->on('students');

			$table->integer('points');

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
		Schema::drop('grades');
	}

}
