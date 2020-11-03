<?php
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."GAS_model.php");
    
    class Ordine_model extends GAS_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function totale_ordine($idOrdine,$idUser)
        {
            $this->require($idOrdine, "save_ordine", "id Ordine");
            $this->require($idUser, "save_ordine", "id User");
            
            $sql="  SELECT sum(totale) as spesa FROM ".$this->db->dbprefix('gas_ordiniFatti')." WHERE idUser = $idUser AND idOrdine = $idOrdine ";
             
            $query = $this->db->query($sql);
            
            return ($query->row() === null) ? 0 : $query->row();
        }
        
        public function save_ordine($idOrdine,$idUser, $ordine)
        {
            $this->require($idOrdine, "save_ordine", "id Ordine");
            $this->require($idUser, "save_ordine", "id User");
            $this->require($ordine, "save_ordine", "Ordine");
            $cond = array('idOrdine' => $idOrdine, 'idUser' => $idUser);
            
            $this->db->trans_begin();
            $this->db->where($cond);
            $this->db->delete('gas_ordiniFatti');
            // print_r($ordine);
            $this->db->insert_batch('gas_ordiniFatti', $ordine);
            
            if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
            }
            else
            {
                    $this->db->trans_commit();
            }
            return $this->get_ordine($idOrdine, $idUser);
        }
        
         public function cancella_ordine($idOrdine,$idUser)
        {
            $this->require($idOrdine, "save_ordine", "id Ordine");
            $this->require($idUser, "save_ordine", "id User");
             
            $cond = array('idOrdine' => $idOrdine, 'idUser' => $idUser);
            
            $this->db->trans_begin();
            $this->db->where($cond);
            $this->db->delete('gas_ordiniFatti');
            
            if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
            }
            else
            {
                    $this->db->trans_commit();
            }
            return "OK";
        }
        
        public function get_gasisti($idFornitore = FALSE){
            $this->require($idFornitore, "get_gasisti", "id Fornitore");
            
            $sql="  SELECT userId, cognome, nome, COALESCE(ordinato, \"\") AS 'ordine' FROM 
                        (SELECT C.user_id AS 'userId', 
                                D.meta_value AS 'cognome', 
                                C.meta_value AS 'nome'
                        FROM  ".$this->db->dbprefix('usermeta')." C 
                        INNER JOIN ".$this->db->dbprefix('usermeta')." D ON C.user_id = D.user_id
                        WHERE C.meta_key = 'first_name' AND D.meta_key = 'last_name') E
                        LEFT JOIN (SELECT Y.idUser, \"S\" AS \"ordinato\" FROM ".$this->db->dbprefix('gas_ordini')." X 
                                    INNER JOIN ".$this->db->dbprefix('gas_ordiniFatti')." Y ON X.idOrdine = y.idOrdine 
                                    WHERE X.idFornitore = $idFornitore AND X.ordineChiuso <> \"S\") 
                        F ON E.userId = F.idUser
                    GROUP BY userId, cognome, nome, ordine
                    ORDER BY 4, 2, 3";
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        
        public function ordine_aperto($idFornitore = FALSE){
            $this->require($idFornitore, "ordine_aperto", "id Fornitore");
            
            $query = $this->db->get_where('gas_ordini', array('idFornitore' => $idFornitore, 'ordineChiuso !=' => "S"));
            return $query->result_array();
        }
        
          public function get_ordine($idOrdine = FALSE, $userId = FALSE){
            $this->require($idOrdine, "get_ordine", "id Ordine");
            $this->require($userId, "get_ordine", "id User");
              
            $sql = "SELECT A.*, COALESCE(quantita, 0) AS quantita, COALESCE(totale, 0) totale from 
                        (SELECT * FROM wp_gas_ordiniAttivi WHERE idOrdine = $idOrdine) A
                        LEFT JOIN 
                        (SELECT * FROM wp_gas_ordiniFatti WHERE idUser = $userId) B
                        ON A.idOrdine = B.idOrdine AND A.progressivo = B.progressivo
                    ORDER BY progressivo"  ;
            
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        
        
        public function open_ordine($idFornitore = FALSE, $dataInizio = FALSE, $dataFine = FALSE, $soloPezzi = "N", $descrizione = FALSE, $ordineChiuso = "N")
        {
                
            $this->require($idFornitore, "open_ordine", "id Fornitore");
            $this->require($dataInizio, "open_ordine", "data Inizio");
            $this->require($dataFine, "open_ordine", "data Fine");
            $this->require($descrizione, "open_ordine", "descrizione");

            if ($dataInizio > $dataFine)
            {
                    die ('{"result":"KO","description":"data Inizio > data fine "}');
            }
            
            $query = $this->db->get_where('gas_ordini', array('idFornitore' => $idFornitore, 'ordineChiuso !=' => "S"));
            if( $query->num_rows() > 0){
                die ('{"result":"KO","description":"Esiste già un ordine aperto per questo fornitore"}');
            }
            $tmp = array(
                        'idFornitore' => $idFornitore,
                        'dataInizio' => $dataInizio,
                        'dataFine' => $dataFine,
                        'soloPezzi' => $soloPezzi,
                        'descrizione' => $descrizione,
                        'ordineChiuso' => $ordineChiuso
            );
            
            $param[]= $tmp;
            $this->db->insert_batch('gas_ordini', $param);

            return $this->db->insert_id();
        }
    }
