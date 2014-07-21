<?php
class adminController extends spController
{
    function __construct(){
		parent::__construct();
//        if (!$_SESSION['admin']){
//            throw new Exception('You don\'t have permission!');
//        }
	}
}