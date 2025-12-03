<?php

class GenerateId{

	/*
	The Generate Id class is based primarily on the php uniqid() function
	with some little adjustment to allow for more dynamics.
	although the formatting may be a little bit rough, it works perfectly
	to generate random strings.

	The $pre argument to the constructor function indicates the prefix of the result,
	while the $post argument indicates the suffix.

	*/

	public function __construct($pre = null, $post = null){
		$this->pre = $pre;
		$this->post = $post;
	}

	/*

	public function __toString(){
		return $this->generate();
	}

	*/

	public function generate(){
		if(!is_null($this->pre) && !is_null($this->post)){
			$unique_id = uniqid($this->pre.'-', true);
			$unique_id = $unique_id."-"."$this->post";
		}
		elseif(is_null($this->pre) && is_null($this->post)){
			$unique_id = uniqid();
		}elseif(!is_null($this->pre) && is_null($this->post)){
			$unique_id = uniqid($this->pre.'-');
		}elseif(is_null($this->pre) && !is_null($this->post)){
			$unique_id = uniqid();
			$unique_id = $unique_id . "-"."$this->post";
		}
		return $unique_id;
	}
}

?>