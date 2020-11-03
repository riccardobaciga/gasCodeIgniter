<script>
    // gestione referenti

    function chiediReferentiFornitore() {
        if (myApp.idFornitore !== "-1") {
            doAjax("get/referenti", "GET", {"idFornitore" : myApp.idFornitore}, "caricaReferentiFornitore");
        }
    }

    function salvaReferentiFornitore() {
        var referentiVett = [];
        var items = document.getElementById("listaReferenti").getElementsByTagName("li");
        for (var i = 0; i < items.length; ++i) {
            if (items[i].firstChild.checked) {
                referentiVett.push(items[i].firstChild.name);
            }
        }
        console.log(referentiVett);
        param = "?idFornitore=" + myApp.idFornitore + "&listaReferenti=" + encodeURIComponent(referentiVett)
        doAjax("save/referenti" + param, 'POST', "", "caricaReferentiFornitore");
    }

    function caricaReferentiFornitore() {
        var tmpStr = "";
        myApp.AJAXObj.referenti.forEach(function(item) {
            tmpStr += '<li><input class="w3-check" type="checkbox" name="' + item.userId + '" ' + item.checked + '> <label>' + item.cognome + '  ' + item.nome + '</label></li>';
        });
        $("#listaReferenti").html(tmpStr);

        $("#referentiFornitore").show();
        $("#loadData").hide();
    }

</script>
