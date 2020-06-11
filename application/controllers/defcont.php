<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class defcont extends CI_Controller {

	public function index()
	{
		$this->session->objects = null;
		$this->session->compass = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW");
		if(is_null($this->session->lat)||is_null($this->session->long))
		{
			$this->formlatlong();
		}
		else
		{
			$this->planetsnow();
		}
	}

	public function formlatlong()
	{
		$data["title"] = "StarGazer : Insert your Coordinates";
		$this->load->view("head", $data);
		$this->load->view("formlatlong");
		$this->load->view("end");
	}

	public function formdate()
	{
		$data["title"] = "StarGazer : Insert a Date";
		$this->load->view("head", $data);
		$this->load->view("formd");
		$this->load->view("end");
	}

	public function planetsnow()
	{
		$this->checkForm();

		$gmDate = gmdate("j;n;Y;G;i");
		$gmDateData = explode(";", $gmDate);
		
		$this->setD($gmDateData);

		$this->getData();

		$this->mainnow();
	}

	public function planetsbydate()
	{
		$this->checkForm();

		$date = $this->input->post('date');
		$dateData = explode("-", $date);
		$time = $this->input->post('time');
		$timeData = explode(":", $time);
		$gmDate = gmdate("j;n;Y;G;i", gmmktime($timeData[0], $timeData[1], 0, $dateData[1], $dateData[2], $dateData[0]));

		$gmDateData = explode(";", $gmDate);
		
		$this->setD($gmDateData);

		$this->getData();

		$this->maindate();
	}

	function checkForm()
	{
		if(!is_null($this->input->post('lat')))
		{
			if($this->input->post('lat') > 89)
				$this->session->lat = 89;
			if($this->input->post('lat') < -89)
				$this->session->lat = -89;
			else
				$this->session->lat = $this->input->post('lat');
		}
		if(!is_null($this->input->post('long')))
		{
			if($this->input->post('long') > 180)
				$this->session->long = 180;
			if($this->input->post('long') < -180)
				$this->session->long = -180;
			else
				$this->session->long = $this->input->post('long');
		}
	}

	function setD($gmDateData)
	{
		$this->session->day = (int) $gmDateData[0];
    	$this->session->month = (int) $gmDateData[1];
    	$this->session->year = (int) $gmDateData[2];
		$this->session->hUT = $gmDateData[3] + ($gmDateData[4] / 60);
		$this->session->lastTimeStamp = $gmDateData[0]."-".$gmDateData[1]."-".$gmDateData[2]." ".$gmDateData[3].":".$gmDateData[4]." GMT";
		
		$this->session->d = (367 * $this->session->year) - floor((7 * ($this->session->year + floor(($this->session->month + 9) / 12))) / 4) + floor((275 * $this->session->month) / 9) + $this->session->day - 730530;
		$this->session->d += $this->session->hUT/24;
	}

	function getData()
	{
		$array = $this->stargazer_model->getRADECL($this->session->d);
		$this->stargazer_model->getAA($array);
		$this->stargazer_model->format($array);
		$this->session->objects = $array;
	}

	public function mainnow()
	{
		$data["title"] = "StarGazer : Position Right Now";
		$this->load->view("head", $data);
		$this->load->view("buttons");
		$this->load->view('main');
		$this->load->view("footer");
		$this->load->view("end");
	}
	
	public function maindate()
	{
		$data["title"] = "StarGazer : ".$this->session->lastTimeStamp;
		$this->load->view("head", $data);
		$this->load->view("buttons");
		$this->load->view('main');
		$this->load->view("footer");
		$this->load->view("end");
	}

	function orbelem()
	{
		$ID = $this->input->get('id');
		foreach($this->session->objects as $object)
		{
			if($object->ID == $ID)
			{
				$data["title"] = "StarGazer : Orbital Elements of ".$object->Name;
				$data2["object"] = $object;
				$this->load->view("head", $data);
				$this->load->view("buttons");
				$this->load->view("orbelem", $data2);
				$this->load->view("footer");
				$this->load->view("end");
				break;
			}
		}
	}
}
