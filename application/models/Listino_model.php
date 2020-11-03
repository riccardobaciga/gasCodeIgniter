<?php
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."GAS_model.php");

    class Listino_model extends GAS_model {

        public function __construct()
        {
                $this->load->database();
        }
    
        public function get_listino($idFornitore = FALSE)
        {
              $this->require($idFornitore, "get_listino", "id Fornitore");

              $this->db->select("*");
              $this->db->where("idFornitore",$idFornitore);

              /*********** order by *********** */
              $this->db->order_by("progressivo", "asc");

              $query = $this->db->get('gas_listini');                
              return $query->result_array();
        }

        public function save_listino($listino)
        {
            $this->require($idFornitore, "save_listino", "id Fornitore");
            
            $globalParam = [];
            $param = [];
            $i=0;
            $curr=0;
            foreach($listino["listino"] as $riga){
                $i++;
                $tmp = array(
                        'idFornitore' => $listino["idFornitore"],
                        'tipoRiga' => $riga[0],
                        'colonna1' => $riga[1],
                        'colonna2' => $riga[2],
                        'colonna3' => $riga[3],
                        'colonna4' => $riga[4],
                        'colonna5' => $riga[5],
                        'prezzo' => $riga[6],
                        'progressivo' => $riga[7]
                );
                $param[]= $tmp;
                if (($i % 30) == 0){
                    $curr ++;
                    $i = 0;
                }
            }
            $globalParam[$curr][$curr] = $param;

            $this->db->trans_begin();
            $this->db->where('idFornitore', $listino["idFornitore"]);
            $this->db->delete('gas_listini');
            foreach($globalParam[$curr] as $currentParam){
                $this->db->insert_batch('gas_listini', $currentParam);
            }

            if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
            }
            else
            {
                    $this->db->trans_commit();
            }

            return $this->get_listino($listino["idFornitore"]);   
        }   
    }
