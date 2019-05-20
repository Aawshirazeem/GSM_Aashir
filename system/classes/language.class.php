<?php
class language{
	protected $data;
	public function __construct($data) {
		$this->data = $data;
	}
	
	public function get($key){
		//return (isset($this->data['lang'][$key]) ? ('*' . $this->data['lang'][$key]) : "************" . $key);
		//if (defined("lang_test"))
		{
			if(!isset($this->data['lang'][$key])){
				$keytemp = substr($key, 4);
				$keytemp = str_replace('_', ' ', $keytemp);
				$keytemp = ucwords($keytemp);
				return ('' . $keytemp);
			}
		}
		return (isset($this->data['lang'][$key]) ? ($this->data['lang'][$key]) : $key);
		//return (isset($this->data['lang'][$key]) ? ($this->data['lang'][$key]) : $key);
	}	

	public function prints($key){
		return $this->get($key);
	}
}
?>