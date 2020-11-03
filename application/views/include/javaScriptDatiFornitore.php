<script>
    // gestione Fornitori
    
    function inizializzaDatiFornitore() {
        $("#instestazioneFornitore").val("");
        $("#attivitaFornitore").val("");
        $("#nomeConsegna").val("");
        $("#spesaMinima").val(0);
        if (myApp.idFornitore === "-1") {
            $("#salvaFornitore").html('<i class="fa fa-plus-circle"></i> Inserisci');
            $("#referentiBtn").hide();
        }

    }

    function salvaDatiFornitore() {
        if (myApp.idFornitore !== "-1") {
            var param =  "idFornitore="+ myApp.idFornitore;
            param += "&Intestazione="+$("#instestazioneFornitore").val();
            param += "&attivita="+$("#attivitaFornitore").val();
            param += "&nomeConsegna="+$("#nomeConsegna").val();
            param += "&spesaMinima="+$("#spesaMinima").val();
            doAjax("save/datiFornitore?" + param, "GET", "", "aggiornaDatiFornitore");
        } else {
            $("#datiFornitoreForm").submit();
        }
    }

    function chiediDatiFornitore() {
        if (myApp.idFornitore !== "-1") {
            doAjax("get/datiFornitore", "GET", {"idFornitore" : myApp.idFornitore}, "caricaDatiFornitore");
        } else {
            inizializzaDatiFornitore();
            $("#datiFornitore").show();
        }
    }

    function aggiornaDatiFornitore(){
        chiediFornitori();
        caricaDatiFornitore();
    }
    
    function caricaDatiFornitore() {
        $("#instestazioneFornitore").val(myApp.AJAXObj.fornitore.Instestazione);
        $("#attivitaFornitore").val(myApp.AJAXObj.fornitore.attivita);
        $("#nomeConsegna").val(myApp.AJAXObj.fornitore.nomeConsegna);
        $("#spesaMinima").val(myApp.AJAXObj.fornitore.spesaMinima);

        $("#salvaFornitore").html('<i class="fa fa-save"></i> Salva');
        
        $("#datiFornitore").show();
        $("#loadData").hide();
    }
    
    function caricaFornitori() {
        console.log(myApp.AJAXObj);
        var tmpStr = "<option value=-1_N> - Nuovo Fornitore - </option>";
        for(i=0; i < myApp.AJAXObj.fornitori.length;i++){
            tmpF = myApp.AJAXObj.fornitori[i];
            checkStr = "";
            if (tmpF.idFornitore === myApp.idFornitore){
                checkStr = " selected "
            }
            tmpStr += "<option value="+tmpF.idFornitore+"_"+tmpF.soloPezzi+" "+checkStr+">"+tmpF.Instestazione+"</option>";
        }
        $("#fornitoreId").html(tmpStr);
        $("#loadData").hide();
        $("#datiFornitore").hide();
    }
    
    
    
    function chiediFornitori() {
        doAjax("get/fornitori", "GET", "", "caricaFornitori");
    }
</script>
