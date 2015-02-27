<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;


abstract class Descriptor implements ResourceDescriptor {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var array
	 */
	protected $seedValues = [];

	/**
	 * @var
	 */
	private $key;

	/**
	 * @var
	 */
	private $locale;


	public function __construct($key, $locale) {

		$this->key = $key;
		$this->locale = $locale;
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}


	/**
	 * @return array
	 */
	public function getSeedValues() {
		return $this->seedValues;
	}


	/**
	 * @param array $seedValues
	 */
	public function setSeedValues($seedValues) {
		$this->seedValues = $seedValues;
	}


	/**
	 * Transform the native value into a format suitable for storage.
	 *
	 * @param $value mixed
	 * @return string
	 */
	public function toStorage($value) {
		return serialize($value);
	}


	/**
	 * Transform the value from the stored value to a native value.
	 *
	 * @param $value string
	 * @return mixed
	 */
	public function fromStorage($value) {
		return unserialize($value);
	}


	/**
	 * @return mixed
	 */
	public function getName() {
		if (!$this->name) {
			throw (new ResourceDescriptorNameNotDefinedException)->setReference(get_called_class());
		}
	}


	/**
	 * @param mixed $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
}
