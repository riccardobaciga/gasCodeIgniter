<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    
    public function datiFornitore()
	{
        $getData = $this->input->get();
        $this->load->model('fornitori_model');
        $data['result'] = "OK";
		$data['fornitore'] = $this->fornitori_model->get_fornitori($getData["idFornitore"]);
        
        echo json_encode($data);
	}
    public function listino(){
		// POST data
		$getData = $this->input->get();
        $this->load->model('listino_model');
        $data['result'] = "OK";
        $data['listino'] = $this->listino_model->get_listino($getData["idFornitore"]);
        
		echo json_encode($data);
	}
    public function referenti(){
		// POST data
		$getData = $this->input->get();
        $this->load->model('referenti_model');
        $data['result'] = "OK";
        $data['referenti'] = $this->referenti_model->get_referenti($getData["idFornitore"]);
        
		echo json_encode($data);
	} 
    public function fornitori(){
		// POST data
		$getData = $this->input->get();
        $this->load->model('fornitori_model');
        $data['result'] = "OK";
		$data['fornitori'] = $this->fornitori_model->get_fornitori();
        
		echo json_encode($data);
	}    
    public function disponibilita(){
		$getData = $this->input->get();
        $this->load->model('conto_model');
        $data['result'] = "OK";
        $ordine = (isset ($getData["idOrdine"])) ? $getData["idOrdine"] : FALSE;
        $data['disponibilita'] = $this->conto_model->get_disponibilita($getData["userId"], $ordine);
		echo json_encode($data);
	}
    public function ordine(){
		$getData = $this->input->get();
        $data['result'] = "OK";
        $this->load->model('conto_model');
        $data['disponibilita'] = $this->conto_model->get_disponibilita($getData["idUser"], $getData["idOrdine"]);
        $this->load->model('ordine_model');
        $data['ordine'] = $this->ordine_model->get_ordine($getData["idOrdine"], $getData["idUser"]);
		echo json_encode($data);
	}
}
