<script>
    function apriAnteprima() {
        var win = window.open("/ordine/anteprima?idFornitore=" + myApp.idFornitore, '_blank');
        win.focus();
    }

    function chiudiCSVModal(){
        $("#csvFile").val("");
        $('#csvModal').hide();
    }
   function apriCSVModal() {
        $('#csvModal').show();
    }

    function apriFinestraOrdine() {
        $("#descrizioneOrdine").val("");
        $("#dataInizioOrdine").val("");
        $("#dataFineOrdine").val("");

        $("#finestraOrdine").show();
    }

    function ordineAperto() {

        $("#loadData").hide();
    }

    function apriNuovoOrdine() {
        var param = {};
        param.idFornitore = myApp.idFornitore;
        dataInizio = $("#dataInizioOrdine").val();
        dataFine = $("#dataFineOrdine").val();
        param.soloPezzi = ($("#soloPezzi").is(':checked')) ? "S" : "N";
        param.descrizione = $("#descrizioneOrdine").val();
        param.ordineChiuso = "N";

        var erroreMSG = ""
        if (param.descrizione.length < 5) {
            erroreMSG += "<b > Descrizione troppo corta <span class='w3-text-red'>" + param.descrizione + " </span></b><br>";
        }
        if (dataInizio.length < 5) {
            erroreMSG += "<b> DATA inizio non valida <span class='w3-text-red'>" + dataInizio + " </span></b><br>";
        }
        if (dataFine.length < 5) {
            erroreMSG += "<b> DATA chiusura non valida <span class='w3-text-red'>" + dataFine + " </span></b><br>";
        }
        if (dataInizio > dataFine) {
            erroreMSG += "<b> DATA inizio <span class='w3-text-red'>" + dataInizio + "</span> posterire alla data chiusura <span class='w3-text-red'>" + dataFine + " </span></b><br>";
        }
        if (erroreMSG.length > 1) {
            erroreMSG = "<h3> Impossibile aprire un ordine </h3>" + erroreMSG
            errore(erroreMSG);
            return;
        }

        param.dataInizio = dataInizio.substr(6, 4) + dataInizio.substr(3, 2) + dataInizio.substr(0, 2);
        param.dataFine = dataFine.substr(6, 4) + dataFine.substr(3, 2) + dataFine.substr(0, 2);

        doAjax("open/ordine", 'POST', param, "ordineAperto");
    }

    document.getElementById('csvFile')
        .addEventListener('change', function() {
            
            $("#csvModal").hide();
            $("#loadData").show();
            var fr = new FileReader();
            fr.onload = function() {
                myApp.listino = [];
                righe = fr.result.split("\n");
                for (i = 0; i < righe.length; i++) {
                    riga = righe[i].trim().split(";")

                    switch (riga[0].toUpperCase()) {
                        case 'I':
                            riga[0] = "riga3";
                            break;
                        case 'C':
                            riga[0] = "riga0";
                            break;
                        default:
                            riga[0] = "riga1";
                    }
                    for (j = 1; j < 6; j++) {
                        riga[j] = riga[j].trim();
                    }
                    riga[6] = riga[6].replaceAll(',', '.');
                    riga[7] = i;
                    myApp.listino.push(riga);
                }

                // console.log(myApp.listino);
                costruisciTabellaListino();
                $("#loadData").hide();
            }

            fr.readAsText(this.files[0]);
        })

</script>
