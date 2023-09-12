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
		$data['brand'] = $this->Api_m->get_brand();
		$data['data_chart'] = $this->Api_m->get_data_chart();
		$data['data_table'] = $this->Api_m->get_data_table();

		if(!$data['area']){
			die('gagal get area');
		}

		if(!$data['brand']){
			die('gagal get area');
		}
		
		if(!$data['data_chart']){
			die('gagal get chart');
		}

		if(!$data['data_table']){
			die('gagal get table');
		}

		$this->load->view('products_report', $data);
	}

	public function get_data_chart()
	{
		$area_id = $this->input->post('area_id');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		$get_data = $this->Api_m->get_data_chart($area_id, $from_date, $to_date);
		
		echo json_encode($get_data);
	}

	public function get_data_table()
	{

		$area_id = $this->input->post('area_id');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		$get_data = $this->Api_m->get_data_table($area_id, $from_date, $to_date);

		echo json_encode($get_data);
	}
}
