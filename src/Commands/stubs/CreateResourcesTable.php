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
			$table->string('resource_key');
			$table->string('resource_class');
			$table->timestamps();
			$table->softDeletes();

			$table->unique('resource_key','U_resource_key');
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
