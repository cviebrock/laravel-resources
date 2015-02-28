<?php namespace Cviebrock\LaravelResources\Models;

use Illuminate\Database\Eloquent\Model;
use Config;


class ResourceTranslation extends Model {

	protected $primaryKey = 'resource_translation_id';

	protected $fillable = ['locale','value'];


	/**
	 * Create a new Eloquent model instance, with prefixable table name.
	 *
	 * @param array $attributes
	 */
	public function __construct(array $attributes = []) {

		$prefix = Config::get('resources::config.tablePrefix', '');

		$this->setTable($prefix . 'resource_translations');

		parent::__construct($attributes);
	}


	/**
	 * Relationship with Resource Model
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function resource() {
		return $this->belongsTo('Resource');
	}

}
