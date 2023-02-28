<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

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
		$rawData = $this->input->raw_input_stream;
		$decoded = json_decode($rawData);
		$email = $decoded->email;
		$password = $decoded->password;
		echo json_encode(array("status" => true, "email" => $email, "password" => $password));
	}

	public function addVendor()
	{
		$rawData = $this->input->raw_input_stream;
		$decoded = json_decode($rawData);
		$fullname = $decoded->fullname;
		$email = $decoded->email;
		$company_name = $decoded->company_name;
		$phone = $decoded->phone;
		$contact = $decoded->contact;
		$expertise = $decoded->expertise;
		$comments = $decoded->comments;
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

	public function getAllVendors()
	{
		$rawData = $this->input->raw_input_stream;
		$decoded = json_decode($rawData);
		$totalRecords = $this->db->count_all("vendors");
		$this->db->limit($decoded->rows, $decoded->first);
		$vendors = $this->db->get("vendors")->result();
		echo json_encode(array("status" => true, "vendors" => $vendors, "totalRecords" => $totalRecords));
	}
}
