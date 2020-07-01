<?php namespace App\Controllers;

use CodeIgniter\HTTP\Message;

class Home extends BaseController
{
	public function index()
	{
	    var_dump($this->request->getHeaders());
        $Message = new Message();
        var_dump($Message->getHeaders());die();
		return view('welcome_message');
	}

	//--------------------------------------------------------------------

}
