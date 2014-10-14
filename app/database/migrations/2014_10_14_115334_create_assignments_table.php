<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignments', function($table) {
			$table->increments('id');

			$table->integer('registered_class_id')->unsigned();
			$table->foreign('registered_class_id')->references('id')->on('registered_classes');

			$table->string('name');
			$table->string('type');
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
		Schema::drop('assignments');
	}

}
