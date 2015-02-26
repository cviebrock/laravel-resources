<?php namespace Cviebrock\LaravelResources\Contracts;

interface ResourceNamespace {

	public function getNamespace();

	public function getResources();

	public function getRecord($group, $item);

}
