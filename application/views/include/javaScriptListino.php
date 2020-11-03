<script>
    // gestione Listino

    function salvaListino() {
        var param = {};
        param.idFornitore = myApp.idFornitore;
        param.listino = myApp.listino;
        
        $("#loadData").show();
        doAjax("save/listino", 'POST', param, "visualizzaListino");
    }


    function caricaListino() {
        parameter = $("#fornitoreId").val().split("_");
        myApp.soloPezziValue = parameter[1];
        myApp.idFornitore = parameter[0];

        if (myApp.idFornitore !== "-1") {
            doAjax("get/listino", "GET", {
                "idFornitore": myApp.idFornitore
            }, "visualizzaListino");
        } else {
            $("#menuListino").hide();
            $("#tabellaDati").html('');
            $("#fornitoreBtn").html('<i class="fa fa-plus-circle"></i> Nuovo');
            $("#referentiBtn").hide();
        }
    }

    function visualizzaListino() {
        myApp.listino = [];
        myApp.AJAXObj.listino.forEach(function(item) {
            myApp.listino.push([item.tipoRiga, item.colonna1, item.colonna2, item.colonna3, item.colonna5, item.colonna5, item.prezzo, item.progressivo]);
        });
        costruisciTabellaListino();
        $("#menuListino").show();
        $("#fornitoreBtn").html('<i class="fa fa-edit"></i> Modifica');
        $("#referentiBtn").show();
        $("#loadData").hide();
    }

    function costruisciTabellaListino() {
        aggiungi = '<div class="w3-dropdown-hover w3-black w3-tiny"><button class="w3-button">+</button><div class="w3-dropdown-content w3-bar-block w3-card-4"><a href="#" class="w3-bar-item w3-button" onclick="inserisciRiga(#id#, -1)">Inserisci sopra</a><a href="#" class="w3-bar-item w3-button"  onclick="inserisciRiga(#id#, 0)">Inserisci sotto</a></div></div>';

        myRowStr = "<tr #className# ondblclick='apriRigaListino(#id#)' id='#id#' ><td><span class='w3-button w3-black w3-tiny' onClick='apriRigaListino(#id#)'>M</span>" + aggiungi + "<span class='w3-button w3-black w3-tiny'onClick='selezionaRiga(this, #id#)' >-</span></td><td>#col1#</td><td>#col2#</td><td>#col3#</td><td>#col4#</td><td>#col5#</td><td>#costo#</td></tr>";

        righe = '';

        for (i = 0; i < myApp.listino.length; i++) {
            myRiga = myApp.listino[i];
            rowStyle = "";
            costo = "";

            switch (myRiga[0]) {
                case 'riga3':
                    rowStyle = "class = 'w3-teal'";
                    break;

                case 'riga0':
                    rowStyle = "class = 'w3-lime'";
                    costo = "Prezzo";
                    break;
                    
                default:
                    costo = myRiga[6];
            }
            tmp2 = myRowStr.replace("#className#", rowStyle).replace("#costo#", costo).replaceAll("#id#", myRiga[7]);

            for (j = 1; j < 6; j++) {
                // tmp2 = tmp2.replace("#col"+j+"#", decodeURI(myRiga[j]));
                tmp2 = tmp2.replace("#col" + j + "#", myRiga[j]);
            }

            righe += tmp2;
        }

        $("#tabellaDati").html(righe);
        $("#soloPezzi").attr('checked', (myApp.soloPezziValue === "S"));
    }
    // funzioni di editing

    function selezionaRiga(elem, numero) {
        rigaTabella = elem.parentNode.parentNode;
        rigaTabella.classList.toggle("w3-red");
    }

    function inserisciRiga(idRiga, dove) {
        diff = idRiga + dove;
        rigaVuota = ["riga1", "", "", "", "", "", " ", -1]
        if (diff < 0) {
            myApp.listino.unshift(rigaVuota)
        }
        if ((diff + 1) == myApp.listino.length) {
            myApp.listino.push(rigaVuota)
        } else {
            var tmp = []
            for (i = 0; i < myApp.listino.length; i++) {
                if (i === (diff + 1)) {
                    tmp.push(rigaVuota);
                }
                tmp.push(myApp.listino[i]);
            }
            myApp.listino = tmp;

        }

        for (i = 0; i < myApp.listino.length; i++) {
            myRiga = myApp.listino[i];
            myRiga[7] = i;
        }
        costruisciTabellaListino();
    }

    function cancellaRigheListino() {

        tabellaDati = document.getElementById("tabellaDati");

        if (confirm("vuoi cancellare le riga evidenziate in rosso?")) {
            daCancellare = [];
            for (j = 0; j < tabellaDati.rows.length; j++) {

                if (tabellaDati.rows[j].classList.contains("w3-red")) {
                    daCancellare.push(tabellaDati.rows[j].id)
                }
            }
            for (i = myApp.listino.length - 1; i >= 0; i--) {
                myRiga = myApp.listino[i];
                if (daCancellare.indexOf(myRiga[7]) != -1) {
                    myApp.listino.splice(i, 1);
                }
            }
            costruisciTabellaListino();
        } else {
            for (j = 0; j < tabellaDati.rows.length; j++) {
                tabellaDati.rows[j].classList.remove("w3-red")
            }
        }

    }

    function apriRigaListino(numero) {
        myRiga = myApp.listino[numero]
        for (j = 1; j < 6; j++) {
            // document.getElementById("col"+j).value = decodeURI(myRiga[j])
            document.getElementById("col" + j).value = myRiga[j]
        }
        document.getElementById("costo").value = myRiga[6]
        document.getElementById("numRiga").value = numero
        document.getElementById("idRiga").value = myRiga[7]
        document.getElementById("tipoRiga").value = myRiga[0];
        cambiaTipoRiga();
        document.getElementById('dettaglioRigaListino').style.display = 'block'
    }

    function cambiaTipoRiga() {
        rowStyle = "w3-select";
        switch (document.getElementById("tipoRiga").value) {
            case 'riga3':
                rowStyle += " w3-teal";
                break;
            case 'riga0':
                rowStyle += " w3-lime";
                break;
            default:
                rowStyle += " w3-white";
        }
        document.getElementById("tipoRiga").className = rowStyle
    }

    function salvaRigaListino(flag) {
        numero = document.getElementById("numRiga").value
        myRiga = myApp.listino[numero]

        for (j = 1; j < 6; j++) {
            // myApp.listino[numero][j] = encodeURI(document.getElementById("col"+j).value)
            myApp.listino[numero][j] = document.getElementById("col" + j).value
        }
        myApp.listino[numero][6] = document.getElementById("costo").value
        myApp.listino[numero][0] = document.getElementById("tipoRiga").value;
        costruisciTabellaListino();
        if (flag === 0) {
            chiudiDettaglioRigaListino();
        } else {
            if (flag > 0 && numero < (myApp.listino.length - 1)) {
                apriRigaListino((numero * 1) + 1)
            } else {
                if (flag < 0 && numero > 0) {
                    apriRigaListino((numero * 1) - 1)
                }
            }
        }

    }

    function chiudiDettaglioRigaListino() {
        document.getElementById('dettaglioRigaListino').style.display = 'none';
    }

</script>
