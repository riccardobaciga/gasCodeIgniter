<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GasGestioneFornitori extends CI_Controller {

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
    public function __construct()
    {
            parent::__construct();
            $this->load->helper('url_helper');
    }
    
	public function index()
	{
        $this->load->model('fornitori_model');
		$data['fornitori'] = $this->fornitori_model->get_fornitori();
        $data['idFornitore'] = "-1";
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topBarInizio', $data);
        $this->load->view('listino', $data);
        $this->load->view('templates/footer', $data);
	}
    
    public function nuovoFornitore()
	{
        $getData = $this->input->get();
        $this->load->model('fornitori_model');
        $data['idFornitore'] = $this->fornitori_model->insert_fornitore($getData);
        $data['soloPezzi'] = "N";
        $data['fornitori'] = $this->fornitori_model->get_fornitori();
        $this->load->view('ListinoFornitori_page.php', $data); 
        
	}
    
    public function ordineListino()
	{
        $getData = $this->input->get();
        $param = explode("_",$getData["idFornitore"]);
        $this->load->model('fornitori_model');
        $data['idFornitore'] = $param[0];
        $data['soloPezzi'] = $param[1];
        $data['fornitori'] = $this->fornitori_model->get_fornitori();
        $this->load->model('ordine_model');
        $ord = $this->ordine_model->ordine_aperto($param[0]);
        // print_r($ord);
        if(count($ord) < 1){
            $this->load->view('ListinoFornitori_page.php', $data);            
        }else{ 
            $data['gasisti'] = $this->ordine_model->get_gasisti($param[0]);
            $data['ordineCorrente'] = $ord[0];
            $this->load->view('OrdineFornitori_page.php', $data);
        }
        
	}
}
