<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct() {
		Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
		Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
		Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
	}

	public function index()
	{
		echo json_encode(array("status" => true));
	}

	public function login()
	{
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		echo json_encode(array("status" => true, "email" => $email, "password" => $password));
	}
}
