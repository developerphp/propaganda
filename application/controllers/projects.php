<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Projects extends CI_Controller {
	public function index() {
		$this->load->view('projectsView');
	}

	public function detail($id)  {
		if (is_numeric($id)) {
			$sql=$this->db->query("select * from projects where id=".$this->db->escape($id)." and publish=1");
			foreach($sql->result_array() as $project) {
				$datas["project"]=$project;
				$this->load->view("projectDetailView",$datas);
			}
		}
	}
}