<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ress', function($table) {

			$table->increments('id');
			$table->integer('thread_id');
			$table->integer('res_no');
			$table->text('body');
			$table->string('uploaded_img');
			$table->integer('user_id');

			$table->softDeletes();
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
		Schema::drop('ress');
	}

}
