<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
		parent::__construct();
		Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
		Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
		Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
	}
	public function index() {
		echo base_url();
	}

	public function login() {
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		echo json_encode(array("status" => true, "email" => $email, "password" => $password));
	}

	public function addVendor()
	{
		$fullname = $this->input->post("fullname");
		$email = $this->input->post("email");
		$company_name = $this->input->post("company_name");
		$phone = $this->input->post("phone");
		$contact = $this->input->post("contact");
		$expertise = $this->input->post("expertise");
		$comments = $this->input->post("comments");
		$this->db->insert("vendors", array(
			"fullname" => $fullname,
			"email" => $email,
			"company_name" => $company_name,
			"phone" => $phone,
			"contact" => $contact,
			"expertise" => $expertise,
			"comments" => $comments,
			"logo_url" => "ab233.jpg",
			"rate_card_url" => "dsicjjs.pdf",
		));
		echo json_encode(array("status" => false, "fullname" => $fullname));
	}
}
