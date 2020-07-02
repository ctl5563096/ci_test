<?php namespace App\Controllers;

use CodeIgniter\HTTP\Message;

class Home extends BaseController
{
    use \CodeIgniter\API\ResponseTrait;

	public function index()
	{
	    var_dump('index.php');die();
	    var_dump($this->request->getHeaders());
        $Message = new Message();
        var_dump($Message->getHeaders());die();
		return view('welcome_message');
	}

    public function notFound()
    {
        return $this->respond(json_encode(['',404,'api not found']));
    }
}
