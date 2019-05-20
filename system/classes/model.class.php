<?php
abstract class model {
	protected $model;
	
	public $registry;
	public $language;
	protected $data;
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function __get($key) {
		return $this->registry->get($key);
	}
	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}


	public function getModel() {
		return $this->model;
	}
	
	public function setModel($model) {
		$this->model = $model;
	}

	public function getData($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : '');
	}
	
	public function setData($key, $value) {
		$this->data[$key] = (is_array($value)) ? $value[0] : $value;
	}

	public function getLanguage() {
		return $this->language;
	}
	
	public function setLanguage($language) {
		$this->language = $language;
	}


}
?>