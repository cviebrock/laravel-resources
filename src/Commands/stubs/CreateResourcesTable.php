<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('%PREFIX%resources', function(Blueprint $table){
			$table->increments('resource_id');
			$table->string('namespace');
			$table->string('key');
			$table->timestamps();
			$table->softDeletes();

			$table->index('namespace');
			$table->index('key');
			$table->unique(['namespace','key']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('%PREFIX%resources');
	}

}
