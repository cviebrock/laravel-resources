<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('%PREFIX%resource_translations', function(Blueprint $table){
			$table->increments('resource_translation_id');
			$table->unsignedInteger('resource_id');
			$table->string('locale', 10);
			$table->text('value')->nullable()->default(null);
			$table->timestamps();
			$table->softDeletes();

			$table->index('locale');
			$table->unique(['resource_id','locale']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('%PREFIX%resource_translations');
	}

}
