<?php
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."GAS_model.php");
    
    class Fornitori_model extends GAS_model {

        public function __construct()
        {
                $this->load->database();
        }
    
        public function get_fornitori($idFornitore = FALSE)
        {
                if ($idFornitore === FALSE)
                {
                      $this->db->select("*");
                      $this->db->order_by("Instestazione", "asc");

                      $query = $this->db->get('gas_fornitore');                
                      return $query->result_array();
                }

                $query = $this->db->get_where('gas_fornitore', array('idFornitore' => $idFornitore));
                return $query->row();
        }
    // insert or replace into Book (ID, Name, TypeID, Level, Seen) values
        
        public function update_fornitore($idFornitore = FALSE, $Instestazione = FALSE, $attivita = "", $nomeConsegna = "", $spesaMinima = 0)
        {
                $this->require($idFornitore, "update_fornitore", "id Fornitore");
                $this->require($Instestazione, "update_fornitore", "instestazione");
                
                
                $this->db->set('Instestazione', $Instestazione);
                $this->db->set('attivita', $attivita);
                $this->db->set('nomeConsegna', $nomeConsegna);
                $this->db->set('spesaMinima', $spesaMinima);
                
                $this->db->where('idFornitore', $idFornitore);
                $this->db->update('gas_fornitore');

                return $this->get_fornitori($idFornitore);
                
        }
        
        public function insert_fornitore($data)
        {
                // print_r($data);
                if ($data["Intestazione"] === "")
                {
                        die ('{"result":"KO","description":"Nome fornitore required"}');
                }
                
                 $tmp = array(
                            'Instestazione' => $data["Intestazione"],
                            'attivita' => $data["attivita"],
                            'nomeConsegna' => $data["nomeConsegna"],
                            'spesaMinima' => $data["spesaMinima"]
                    );
                $param[]= $tmp;
            
                $this->db->insert_batch('gas_fornitore', $param);
                
                return $this->db->insert_id();
                
        }

}
