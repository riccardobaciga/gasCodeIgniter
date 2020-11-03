<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ordine extends CI_Controller {

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
        $this->load->view('GasGestioneFornitori_page', $data);
	}
    
	public function anteprima()
	{
 		$getData = $this->input->get();
        $this->load->model('listino_model');
        $data['listino'] = $this->listino_model->get_listino($getData["idFornitore"]);
        
        $this->load->model('fornitori_model');
		$data['fornitore'] = $this->fornitori_model->get_fornitori($getData["idFornitore"]);
        
        $data['userId'] = -1;
        $this->load->view('Ordine_page', $data);
        
	}    
    
	public function stampa()
	{
 		$getData = $this->input->get();
        $this->load->model('listino_model');
        $data['listino'] = $this->listino_model->get_listino($getData["idFornitore"]);
        
        $this->load->model('fornitori_model');
		$data['fornitore'] = $this->fornitori_model->get_fornitori($getData["idFornitore"]);
        
        $data['userId'] = -1;
        $this->load->view('Stampa_page', $data);
        
	}

}
