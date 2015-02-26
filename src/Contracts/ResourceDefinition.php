<?php namespace Cviebrock\LaravelResources\Contracts;

interface ResourceDefinition {

	public function getNamespace();

	public function getResources();

	public function getRecord($group, $item);

}
