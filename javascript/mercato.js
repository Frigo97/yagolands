
$(document).ready(function(){
  Mercato.init();
  Mercato.listOfOffers();
});

(Mercato = new function(){
  return {
    listOfOffers: function () {
      
      $.getJSON('index.php?json=json/elencoofferte',function(data){
        htmlOfferte = '<table width="100%" class="tabella-offerte-mercato">';
        htmlOfferte += '<tr>';
        htmlOfferte += '<th>&nbsp;</th>';
        htmlOfferte += '<th>cerco</th>';
        htmlOfferte += '<th>&nbsp;</th>';
        htmlOfferte += '<th>offro</th>';
        htmlOfferte += '<th>azione</th>';
        htmlOfferte += '</tr>';
        for (i in data) {
          
          var richiestoDaMe = data[i].quantitaofferta + ' di ' + data[i].risorsaofferta;
          var offertoDaMe = data[i].quantitacercata + ' di ' + data[i].risorsacercata;
          var idOfferta = data[i].id;
          
          htmlOfferte += '<tr id="tr_'+idOfferta+'">';
          htmlOfferte += '<td>ricevi</td>';
          htmlOfferte += '<td>'+richiestoDaMe+'</td>';
          htmlOfferte += '<td>in cambio di</td>';
          htmlOfferte += '<td>'+offertoDaMe+'</td>';
          htmlOfferte += '<td><button class="offerta" id="'+idOfferta+'">accetta l\'offerta</button></td>';
          htmlOfferte += '</tr>';
          
        }
        $('#elencoofferte').html(htmlOfferte);
        $('.offerta').each(function(){
          $(this).click(function(){
            $(this).hide().parent().html('accettazione offerta');
            
            var idOfferta = $(this).attr('id');
            $.getJSON('index.php?'+(idOfferta)+'=mercato/accettaofferta',function(data){
              $('#tr_'+idOfferta).hide();
            });
      
          });
        });
      });
      
      setTimeout('Mercato.listOfOffers();',5000);
    },
    init: function () {
      $('.input-risorsa').keyup(function(){
        var Valore = $(this).val();
        var ContieneNumeri = /[0-9]/;
        var ContieneLettere = /[a-zA-Z]/;
        if(!(ContieneNumeri.test(Valore) && !ContieneLettere.test(Valore)))
          $(this).val('');
      });
      
      $('#invia-risorse').click(function(){
    
        var quantitaOfferta = $('#offro').val();
        var quantitaCercata = $('#cerco').val();
        var nomeRisorsaCercata = $('input[name="cerco"]:checked').val();
        var nomeRisorsaOfferta = $('input[name="offro"]:checked').val();
        var booRisorseDifferenti = nomeRisorsaCercata != nomeRisorsaOfferta;
        var booRisorseUguali = !(booRisorseDifferenti);
        var booQuantitaInserite = quantitaOfferta && quantitaCercata;
        var booQuantitaNonInserite = !(booQuantitaInserite);
    
        if(booRisorseUguali)
          alert('Le risorse che si vogliono scambiare devono essere differenti!');
    
        if(booQuantitaNonInserite)
          alert('Le quantità delle risorse non possono essere nulle.');
    
        if(booRisorseDifferenti && booQuantitaInserite)
          $.ajax({
            type: 'POST',
            url: 'index.php?page=mercato/offerta',
            data: {
              "nomeRisorsaOfferta" : nomeRisorsaOfferta,
              "nomeRisorsaCercata" : nomeRisorsaCercata,
              "quantitaOfferta" : quantitaOfferta,
              "quantitaCercata" : quantitaCercata
            },
            success: function(data) {
              alert(data.message);
            },
            dataType:  'json'
          });
      
        $('#offro').val('');
        $('#cerco').val('');
    
      });
    }
    
  }
});