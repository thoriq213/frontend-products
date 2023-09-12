<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model(['Api_m']);
	}

	public function index()
	{
		$data['area'] = $this->Api_m->get_area();
		$data['data_chart'] = $this->Api_m->get_all_data_chart();
		$data['data_table'] = $this->Api_m->get_all_data_table();

		$this->load->view('products_report', $data);
	}

	public function get_data_chart()
	{
		echo json_encode(["status" => "success"]);
	}

	public function get_data_table()
	{
		echo json_encode(["status" => "success"]);
	}
}
