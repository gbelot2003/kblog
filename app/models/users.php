<?php

class users extends ActiveRecord{

	public function after_validation_on_create()
	{
		$this->pass = sha1($this->pass);
	}

	public function getUsuarios($page, $ppage=10)
	{
		return $this->paginate("page: $page", "per_page: $ppage", 'order: id asc');
	}
	
}