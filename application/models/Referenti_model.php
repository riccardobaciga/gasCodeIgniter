<?php
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."GAS_model.php");

    class Referenti_model extends GAS_model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function update_referenti($idFornitore = FALSE, $listaReferenti = FALSE)
        {
                $this->require($idFornitore, "update_referenti", "id Fornitore");
                $this->require($listaReferenti, "update_referenti", "lista Referenti");

                $param = [];
                foreach (explode(",", $listaReferenti) as $referente) {
                    $tmp = array(
                            'users_id' => $referente,
                            'idFornitore' => $idFornitore
                    );
                    $param[]= $tmp;
                }
                
                $this->db->trans_begin();
                $this->db->where('idFornitore', $idFornitore);
                $this->db->delete('gas_users_fornitore');
            
                $this->db->insert_batch('gas_users_fornitore', $param);
            
                if ($this->db->trans_status() === FALSE)
                {
                        $this->db->trans_rollback();
                }
                else
                {
                        $this->db->trans_commit();
                }
            
                return $this->get_referenti($idFornitore);
                
        }
    
        public function get_referenti($idFornitore = FALSE)
        {
                $this->require($idFornitore, "get_referenti", "id Fornitore");

                $sql = "SELECT userId, cognome, nome, COALESCE(found, \"\") AS 'checked' FROM 
                        (SELECT B.user_id AS 'userId', 
                                D.meta_value AS 'cognome', 
                                C.meta_value AS 'nome'
                        FROM ".$this->db->dbprefix('usermeta')." B
                        INNER JOIN ".$this->db->dbprefix('usermeta')." C ON B.user_id = C.user_id
                        INNER JOIN ".$this->db->dbprefix('usermeta')." D ON B.user_id = D.user_id
                        WHERE B.meta_key = 'wp_capabilities' AND B.meta_value LIKE '%editor%'
                          AND C.meta_key = 'first_name'
                          AND D.meta_key = 'last_name' ) E
                        LEFT JOIN 
                        (SELECT *, 'checked' as 'found'  FROM ".$this->db->dbprefix('gas_users_fornitore')." WHERE idFornitore = $idFornitore)
                        F ON E.userId = F.users_id
                        ORDER BY 4 DESC, 2, 3";
            
                $query = $this->db->query($sql);
                return $query->result_array();
        }
    }
