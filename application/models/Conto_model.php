<?php
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."GAS_model.php");
    class Conto_model extends GAS_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function get_disponibilita($idUser = FALSE, $idOrdine = FALSE ){
                
                $this->require($idOrdine, "get_disponibilita", "id Ordine");
           
                $conOrdine = "";
                if ($idOrdine !== FALSE){
                    $conOrdine = " AND idOrdine <> $idOrdine ";
                }
                 // echo $idUser . "-" . $conOrdine . "<br>";
                 $sql = "SELECT spesi.idUser, 
                                ROUND(totaleSpesi,2) as totaleSpesi, 
                                ROUND(totaleVersati,2)  as totaleVersati, 
                                ROUND((totaleVersati-totaleSpesi),2)  as disponibilita
                        FROM 
                        (SELECT sum(importo) AS totaleSpesi, idUser
                        FROM ".$this->db->dbprefix('gas_contoUser')." 
                        WHERE tipoOperazione in (0, 2, 3) AND idUser = $idUser $conOrdine group by idUser) spesi,
                        (SELECT sum(importo) AS totaleVersati, idUser
                        FROM ".$this->db->dbprefix('gas_contoUser' )." 
                        WHERE tipoOperazione = 1 AND idUser = $idUser group by idUser) versati";
                // echo $sql."<br>";
                $query = $this->db->query($sql);
                
                return ($query->row() === null)?0:$query->row();
        }
        
        public function salva_spesa($tipoOperazione = FALSE, $idUser = FALSE, $idOrdine = FALSE, $descrizione = FALSE, $importo = FALSE){
                $operatore = -1;
                
                $this->require($tipoOperazione, "salva_spesa", "tipo Operazione");
                $this->require($idUser, "salva_spesa", "id Utente");
                $this->require($idOrdine, "salva_spesa", "id ordine");
                $this->require($descrizione, "salva_spesa", "descrizione spesa");
                $this->require($importo, "salva_spesa", "importo spesa");
            
            
                $timestampOperazione = date("Y-m-d H:i:s");
                $this->db->trans_begin();
                // memorizza o aggiorna on line
                $sql = "REPLACE INTO ".$this->db->dbprefix('gas_contoUser')." (idOperazione,tipoOperazione,dataOperazione,idUser,idOrdine,descrizione,importo,idOperatore) VALUES ((SELECT idOperazione FROM ".$this->db->dbprefix('gas_contoUser')." where idOrdine = $idOrdine AND idUser = $idUser), $tipoOperazione, '$timestampOperazione' ,$idUser,$idOrdine,'$descrizione',$importo,$operatore)";
            
                $query = $this->db->query($sql);               

                // inserisce sullo storico
                $sql = "INSERT INTO ".$this->db->dbprefix('gas_contoUserStorico')." (idOperazione,tipoOperazione,dataOperazione,idUser,idOrdine,descrizione,importo,idOperatore) VALUES ((SELECT COALESCE(idOperazione, 0) FROM ".$this->db->dbprefix('gas_contoUser')." where idOrdine = $idOrdine AND idUser = $idUser), $tipoOperazione, '$timestampOperazione' ,$idUser,$idOrdine,'$descrizione',$importo,$operatore)";
                $query = $this->db->query($sql);
            
                if ($this->db->trans_status() === FALSE)
                {
                        $this->db->trans_rollback();
                }
                else
                {
                        $this->db->trans_commit();
                }
            
                return $this->get_disponibilita($idUser, $idOrdine);
        }
        
        public function cancella_spesa($idUser = FALSE, $idOrdine = FALSE ){
                $this->require($idUser, "cancella_spesa", "id Utente");
                $this->require($idOrdine, "cancella_spesa", "id ordine");
            
                $operatore = -1;
                $sql = "DELETE FROM ".$this->db->dbprefix('gas_contoUser')." WHERE idOperazione =  (SELECT idOperazione FROM ".$this->db->dbprefix('gas_contoUser')." where idOrdine = $idOrdine AND idUser = $idUser)";
                
                $query = $this->db->query($sql);
                return $this->get_disponibilita($idUser);
        }

}
