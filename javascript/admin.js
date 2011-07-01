
/**
 * @todo aggiungere il tag label per migliorare l'usabilità della pagina
 */

$(document).ready(function(){   
    $('#side-left div.username .index').each(function(){
        var idutente = $(this).val();
        $(this).parent().click(function(){
            $.getJSON('index.php?'+idutente+'=site/permissions',function(data){
                html = '';
                for(i in data)
                    html += '<input type="checkbox" '+
                    ((data[i] == false) ? '' : 'checked="checked"') +
                    '/> - ' + i + '<br />';
                $('#contenuto').html(html);
            });
        });
    });
});