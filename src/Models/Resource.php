<?php namespace Cviebrock\LaravelResources\Models;

use Config;
use Illuminate\Database\Eloquent\Model;


class Resource extends Model {

	protected $primaryKey = 'resource_id';

	protected $fillable = ['key','resource_class'];

	protected $with = ['translations'];


	/**
	 * Create a new Eloquent model instance, with prefixable table name.
	 *
	 * @param array $attributes
	 */
	public function __construct(array $attributes = []) {

		$prefix = Config::get('resources::config.tablePrefix', '');

		$this->setTable($prefix . 'resources');

		parent::__construct($attributes);
	}


	public static function firstByKey($key) {
		return static::where('key', $key)->first();
	}


	/**
	 * Relationship with ResourceTranslations models.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function translations() {
		return $this->hasMany('Cviebrock\LaravelResources\Models\ResourceTranslation');
	}

	public function findTranslation($locale) {
		return $this->translations->first(function($idx, $item) use ($locale) {
			return $item->locale === $locale;
		});
	}
}


