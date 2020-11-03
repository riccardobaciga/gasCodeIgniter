  <script>
      myApp = {};

      function doAjax(theUrl, type, dataToSend, callBackFunction) {
          $("#loadData").show();
          var myUrl = "<?= base_url() ?>" + theUrl;
          $.ajax({
                  type: type,
                  url: myUrl,
                  data: $.param(dataToSend),
                  dataType: "text",
                  cache: false
              }).done(function(data) {
                  try {
                      myApp.AJAXObj = JSON.parse(data);
                      if (myApp.AJAXObj.result === "OK") {
                          window[callBackFunction]();
                      } else {
                          errore("ERRORE FUnzionale KO chiamata <p class='w3-text-red'>" + this.url + "</p><b>" + myApp.AJAXObj.description + "</b>");

                      }
                  } catch (e) {
                      errore("ERRORE try catch per la chiamata <p class='w3-text-red'>" + this.url + "</p><b><br>" + e.name + ": " + data + "<br>" + this.responseText + " </b>");
                  }
              })
              .fail(function() {
                  errore("ERRORE ajax nella chiamata <p class='w3-text-red'>" + this.url + "</p><b>" + this.statusText + "</b>");
                  $("#loadData").hide();
              })
              .always(function() {

              });
      }


      function errore(messaggio) {
          $("#loggingReport").html(messaggio);
          $("#loadData").hide();
          $("#errorWindow").show();
      }

      function arrotonda(numero, decimali) {
          base = Math.pow(10, decimali);
          return Math.round(numero * base) / base
      }

  </script>
