<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Save extends CI_Controller {

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
      
    public function referenti(){
		$getData = $this->input->get();
        $this->load->model('referenti_model');
        $data['result'] = "OK";
        $data['referenti'] = $this->referenti_model->update_referenti($getData["idFornitore"], $getData["listaReferenti"]);
        
		echo json_encode($data);
	}
    
    public function spesa(){
		$getData = $this->input->get();
        // save/spesa?tipoOperazione=4&idUser=229&idOrdine=1403&descrizione=Ordine%20Manenti&importo=20
        $this->load->model('conto_model');
        $data['result'] = "OK";

        $data['spesa'] = $this->conto_model->salva_spesa($getData["tipoOperazione"], $getData["idUser"], $getData["idOrdine"], $getData["descrizione"], $getData["importo"]);
        echo json_encode($data);
    }
    
    public function ordine(){
		$postData = $this->input->post();
		// $postData = $this->input->get();
        // print_r($postData);
        $idOrdine= $postData["idOrdine"];
        $idUser= $postData["idUser"];
        $nomeFornitore= $postData["nomeFornitore"];
        $this->load->model('ordine_model');
        $data['result'] = "OK";
        $data['ordine'] = $this->ordine_model->save_ordine($idOrdine,$idUser, $postData["ordine"]);
 
        $totaleOrdine = $this->ordine_model->totale_ordine($idOrdine,$idUser);
        
        $this->load->model('conto_model');
        $data['disponibilita'] = $this->conto_model->salva_spesa("0", $idUser, $idOrdine, "Ordine a $nomeFornitore", $totaleOrdine->spesa);

        echo json_encode($data);
    }
    
    public function listino(){
		$postData = $this->input->post();
        $this->load->model('listino_model');
        $data['result'] = "OK";
        
        //  print_r($postData);
        $data['listino'] = $this->listino_model->save_listino($postData);
        echo json_encode($data);
	}
    
    public function datiFornitore(){
		$getData = $this->input->get();
        
        $this->load->model('fornitori_model');
        $data['result'] = "OK";
        $data['fornitore'] = $this->fornitori_model->update_fornitore($getData["idFornitore"], $getData["Intestazione"], $getData["attivita"], $getData["nomeConsegna"], $getData["spesaMinima"]);
        echo json_encode($data);
	}
}
