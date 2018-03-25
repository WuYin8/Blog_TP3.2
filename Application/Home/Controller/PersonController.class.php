<?php
namespace Home\Controller;
use Think\Controller;
class PersonController extends Controller {
	function person()
	{
		$this->error('该功能未开放' , '/index' , 3);
		exit;
	}

}