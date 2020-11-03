<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Drop extends CI_Controller {

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
      
    public function spesa(){
		$getData = $this->input->get();
        // drop/spesa?idUser=100&idOrdine=100
        $this->load->model('conto_model');
        $data['result'] = "OK";

        $data['spesa'] = $this->conto_model->cancella_spesa($getData["idUser"], $getData["idOrdine"]);
        echo json_encode($data);
    }     
    
    public function ordine(){
		$getData = $this->input->get();
        // drop/spesa?idUser=100&idOrdine=100
        $this->load->model('ordine_model');
        $data['result'] = "OK";
        $data['ordine'] = $this->ordine_model->cancella_ordine($getData["idOrdine"], $getData["idUser"]);
        echo json_encode($data);
    }

}
