<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentClassesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_classes', function($table) {
			$table->increments('id');

			$table->integer('registered_class_id')->unsigned();
			$table->foreign('registered_class_id')->references('id')->on('registered_classes');

			$table->integer('student_id')->unsigned();
			$table->foreign('student_id')->references('id')->on('students');

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
		Schema::drop('student_classes');
	}

}
