    <script>
        // inizializza variabili
        $(document).ready(function() {
            myApp.idFornitore = <?= $idFornitore; ?>;
            inizializzaDatiFornitore();

            myApp.dataInizioOrdine = new Pikaday({
                field: document.getElementById('dataInizioOrdine'),
                firstDay: 1,
                minDate: new Date(),
                maxDate: new Date(2020, 12, 31),
                yearRange: [2000, 2020],
                format: 'DD/MM/YYYY'
            });

            myApp.dataFineOrdine = new Pikaday({
                field: document.getElementById('dataFineOrdine'),
                firstDay: 1,
                minDate: new Date(),
                maxDate: new Date(2020, 12, 31),
                yearRange: [2000, 2020],
                format: 'DD/MM/YYYY'
            });
        })

    </script>
