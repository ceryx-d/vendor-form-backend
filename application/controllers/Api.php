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
		$this->db->where(array("email" => $email, "password" => $password));
		$count = $this->db->count_all_results("users");
		if ($count) {
			$token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c";
			echo json_encode(array("status" => true, "token" => $token));
		} else {
			echo json_encode(array("status" => false));
		}
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
			"logo_url" => "0.jpg",
			"rate_card_url" => "0.pdf",
		));
		$vendorId = $this->db->insert_id();
		echo json_encode(array("status" => false, "vendorId" => $vendorId));
	}

	public function uploadCompanyLogo() {
		$vendorId = $this->input->post("vendorId");
		if ($_FILES) {
			$target_dir = "uploads/";
			$image_name = "company_logo_" . rand(1, 99999) . rand(555, 88888);
			$imageFileType = strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
			$target_file = $target_dir . $image_name . "." . $imageFileType;
			$check = getimagesize($_FILES["file"]["tmp_name"]);
			if($check !== false) {
				if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
					$this->db->where("id", $vendorId)->set("logo_url", $target_file)->update("vendors");
					echo json_encode(array("status" => true)); 
				} else {
					echo json_encode(array("status" => false));
				}
			} else {
				echo json_encode(array("status" => false));
			}
		}
	}

	public function uploadRateCard() {
		$vendorId = $this->input->post("vendorId");
		if ($_FILES) {
			$target_dir = "uploads/";
			$image_name = "rate_card_" . rand(1, 99999) . rand(555, 88888);
			$imageFileType = strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
			$target_file = $target_dir . $image_name . "." . $imageFileType;
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
				$this->db->where("id", $vendorId)->set("rate_card_url", $target_file)->update("vendors");
				echo json_encode(array("status" => true)); 
			} else {
				echo json_encode(array("status" => false));
			}
		}
	}

	public function saveAdditionalFields() {
		$vendorId = $this->input->post("vendorId");
		$fields = $this->input->post("fields");
		$fieldsArray = json_decode($fields);
		$arrayToInsert = array();
		foreach ($fieldsArray as $field) {
			$arrayToInsert[] = array(
				"vendor_id" => $vendorId,
				"data_name" => $field->name,
				"data_value" => $field->value,
			);
		}
		$this->db->insert_batch("vendor_additional_data", $arrayToInsert);
		echo json_encode(array("status" => true, "vendorId" => $vendorId, "array" => $arrayToInsert));
	}

	public function getAllVendors()
	{
		$rawData = $this->input->raw_input_stream;
		$decoded = json_decode($rawData);
		$totalRecords = $this->db->count_all("vendors");
		$this->db->limit($decoded->rows, $decoded->first);
		if ($decoded->filter != "") {
			$this->db->like('fullname', $decoded->filter);
			$this->db->or_like('email', $decoded->filter);
			$this->db->or_like('contact', $decoded->filter);
			$this->db->or_like('phone', $decoded->filter);
			$this->db->or_like('company_name', $decoded->filter);
		}
		$vendors = $this->db->get("vendors")->result();
		echo json_encode(array("status" => true, "vendors" => $vendors, "totalRecords" => $totalRecords));
	}
	public function getVendorDetails()
	{
		$rawData = $this->input->raw_input_stream;
		$decoded = json_decode($rawData);
		$this->db->where("id", $decoded->vendorId);
		$vendors = $this->db->get("vendors")->row();
		echo json_encode(array("status" => true, "details" => $vendors));
	}
}
