
$(document).ready(function(){
  Caserma.init();
});

(Caserma = new function(){
  return {
    init: function (){
      $('.autofill').click(function(){
        var id = 'txt' + $(this).attr('id');
        $('#'+id).val($(this).html());
      });
      $('.btn_trains').click(function(){
        var id = 'txt_' + $(this).attr('id');
        var numeroTruppe = $('#'+id).val() || 0;
        var unita = $('#unity_'+$(this).attr('id')).val();
        $.ajax({
          type: 'POST',
          url: 'index.php?page=caserma/addestra',
          data: {
            "unita" : unita,
            "numeroTruppe" : numeroTruppe
          },
          success: function(data) {
            alert("aggiorna sta pagina");
          },
          dataType:  'json'
        });
      });
      $('.texttruppe').keyup(function(){
        var Valore = $(this).val();
        var ContieneNumeri = /[0-9]/;
        var ContieneLettere = /[a-zA-Z]/;
        if(!(ContieneNumeri.test(Valore) && !ContieneLettere.test(Valore)))
          $(this).val('');
      });
    }
  }
});