
/**
 * Attendo il caricamento del DOM e poi lancio i miei script javascript.
 */
$(document).ready(function(){   
  Yago.defineOnResize();
  Yago.reloadjson();
});

(Yago = new function(){
  
  var dimensioneCella = 6*4;
  var numerodigiri = 10;
    
  return {
    
    callEndCodaCalled: false,
    
    callEndCoda: function () {
      if(!Yago.callEndCodaCalled)
        $.getJSON('index.php?json=json/endcoda',function(data){
          $('#cosa-lavori').html('');
          htmlcodalavori = '';
          if(data.codavuota == 'false') {
            for(i=0;i<data.costruzioniincoda;i++) {
              htmlcodalavori += '<div class="coda-di-produzione"><input type="hidden" id="the_final_countdown_'+i+'" value="'+data[i].secondtstoleft+'" />L\'edificio <strong>'+data[i].nome+'</strong> di <strong>livello '+data[i].livello+'</strong> sarà terminato tra <span id="the_final_countdown_'+i+'_text" ></span></div>';
            }
          }
          $('#coda-lavori').html(htmlcodalavori);
          for(i=0;i<data.costruzioniincoda;i++) {
            $('#the_final_countdown_'+i+'_text').createTimer({
              time_in_seconds: $('#the_final_countdown_'+i).val(),
              time_format: 'HH:MM:ss',
              buzzer: function () {
                Yago.callEndCodaCalled = false;
                this.callEndCoda();
              }
            });
          }
          Yago.callEndCodaCalled = true;
        });
    },
    
    buildBuilding: function (i) {
      $.ajax({
        type: 'POST',
        url: 'index.php?action=construct/building',
        data: {
          idedificio:i
        }
      }).success(function(){
        Yago.callEndCodaCalled = false;
        Yago.canTouchCells=true;
        Yago.redraw();
      });
    },
    
    defineOnResize: function () {
      $(window).resize(function(){
        Yago.redraw();
        Yago.loadjson();
      });
    },
    
    canTouchCells: true,
    pollingTimeout: 5000,
        
    redraw: function(){
      $.getJSON('index.php?json=json/posizione', function(data) {
        Yago.draw(data);
      });
    },
        
    loadjson: function() {
            
      /**
       * @todo mostrare anche il nome del proprietario ed eventualmente un link per "tornare a casa"
       * @todo salvare tutta la mappa in un cookie o in uno storage e disegnarla lato client?
       */
            
      $.getJSON('index.php?json=json/cells', function(data){
        $('.alveare').each(function(){
          $(this).attr('src','images/celle/cella.svg');
        });
        $.each(data, function(key, val) {
          if(val.owner == 'you')
            $('#x_'+val.x+'_y_'+val.y).attr('src','images/celle/'+val.cell+'.svg');
          else
            $('#x_'+val.x+'_y_'+val.y).attr('src','images/celle/others_'+val.cell+'.svg');
        });
        Yago.canTouchCells = false;
      });
            
            
      this.callEndCoda();
      
      $.getJSON('index.php?json=json/endcodatruppe',function(data){
        $('#cosa-addestramenti').html('');
        htmlcodaaddestramenti = '';
        if(data.codavuota == 'false')
          htmlcodaaddestramenti += '<div class="coda-di-addestramento">'+data.addestramentiincoda+' truppe in addestramento</div>';
        $('#coda-addestramenti').html(htmlcodaaddestramenti);
      });
            
      /** @todo consentire di accedere alla pagina dell'edificio */
      $.getJSON('index.php?json=json/costruzionipresenti',function(data){
        $('#costruzionipresenti').html(
          data.nome == null ? '':
          ('<a href="index.php?' + 
            (data.nomepercontest) +
            '=edifici/dettaglio">Entra in ' + 
            (data.nome) +
            '</a>')
          );
      });
            
      $.getJSON('index.php?json=json/buildable',function(data){
        $('#buildable').html('');
        for (i in data) {
          $('#buildable').append('<div><a href="javascript:return false;" onclick="Yago.buildBuilding('+i+');"> costruisci '+(data[i].nome)+' livello '+(data[i].livello)+'</a> (fe: '+ data[i].ferro +',gr: '+ data[i].grano +',ro: '+ data[i].roccia +',le: '+ data[i].legno +')');
        }
      });
            
      $.getJSON('index.php?json=json/myfields',function(data){
        $('#myfields').html('');
        for (i in data ) {
          $('#myfields').append('<div>'+data[i].nome+' livello '+data[i].livello+'</div>');   
        }
      });
            
      $.getJSON('index.php?json=json/mybuildings',function(data){
        $('#mybuildings').html('');
        for (i in data ) {
          $('#mybuildings').append('<div><a href="javascript:$.ajax({type: \'POST\',url: \'index.php?action=moveto/building\',data: {idedificio:'+i+'}}).success(function(){Yago.redraw();/**/});">'+data[i].nome+' livello '+data[i].livello+'</a></div>');   
        }
      });
            
      $.getJSON('index.php?json=json/mytroops',function(data){
        $('#mytroops').html('');
        for (i in data ) {
          $('#mytroops').append('<div>'+data[i].quantita+' '+data[i].nome+'</div>');   
        }
      });
            
      /**
       * Questo javascript è visibile. Siamo sicuri che vada bene così?
       */
      $.getJSON('index.php?json=json/createplace',function(data){
        $('#createplace').html(data.empty == "false" ? 
          '' : '<a href="javascript:$.ajax(\'index.php?json=cells/createplace\');Yago.redraw();">crea terreni</a>');
      });
            
      $.getJSON('index.php?json=json/risorse',function(data){
        $('#ferro').html(data.ferro);
        $('#grano').html(data.grano);
        $('#legno').html(data.legno);
        $('#roccia').html(data.roccia);
      });
            
    },
        
    /**
     *
     */
    reloadjson: function() {
      Yago.redraw();
      setTimeout('Yago.reloadjson();',Yago.pollingTimeout);
    },
        
    /**
     * Questo metodo disegna la vista
     */
    draw: function (pos) {
      
      if(Yago.canTouchCells) {
      
        // $('#posizione').html('<strong>x:</strong>'+pos.x+', <strong>y:</strong>'+pos.y);
        $.ajax({
          type: 'POST',
          url: 'index.php?json=utenti/salvaposizione',
          data: pos
        });
            
        /**
         * Ripulisco la parte di schermo che deve contenere l'alveare
         */
        $('#vista').html('');
        
        /**
         * Ricalcolo tutte le dimensioni
         */
        this.heightCell = dimensioneCella;
        this.x = pos.x;
        this.y = pos.y;
        this.top = $('#vista').height() / 2 - dimensioneCella;
        this.left = $('#vista').width() / 2 - this.heightCell;
        //            this.inc = 0;
        this.html = '';
        this.addHtml = function () {
          this.html += '<img src="images/celle/cella.svg" style="width:'+dimensioneCella+'px;height:'+this.heightCell+'px;position:absolute;top:'+(this.top)+'px;left:'+(this.left)+'px;" id="x_'+(this.x)+'_y_'+(this.y)+'" onclick="Yago.canTouchCells=true;Yago.draw({x:'+(this.x)+',y:'+(this.y)+'});" class="alveare" />';
        }
        this.moveLeft = function () {
          this.x--;
          this.left-=dimensioneCella;
          this.addHtml();
        }
        this.moveRight = function () {
          this.x++;
          this.left+=dimensioneCella;
          this.addHtml();
        }
        this.moveRightUp = function () {
          this.top-=this.heightCell/4*3;
          this.left+=dimensioneCella/2;
          this.y++
          if(this.y%2==1 || this.y%2==-1) {
            this.x++;
          }
          this.addHtml();
        }
        this.moveRightDown = function () {
          this.top+=this.heightCell/4*3;
          this.left+=dimensioneCella/2;
          if(this.y--%2==0){
            this.x++;
          }
          this.addHtml();
        }
        this.moveLeftDown = function () {
          this.top+=this.heightCell/4*3;
          this.left-=dimensioneCella/2;
          this.y--;
          if(this.y%2==0) {
            this.x--;
          }
          this.addHtml();
        }
        this.moveLeftUp = function () {
          this.top-=this.heightCell/4*3;
          this.left-=dimensioneCella/2;
          this.y++;
          if(this.y%2==0) {
            this.x--;
          }
          this.addHtml();
        }
        this.posiziona = function () {
          this.top-=this.heightCell/4*3;
          this.left-=dimensioneCella/2;
          this.y++;
          if(this.y%2==0) {
            this.x--;
          }
        }
        this.init = function() {
          this.addHtml();
          for(i=0;i<numerodigiri;i++) {
            this.posiziona();
            for(j=i;j>=0;j--,this.moveRight());
            for(j=i;j>=0;j--,this.moveRightDown());
            for(j=i;j>=0;j--,this.moveLeftDown());
            for(j=i;j>=0;j--,this.moveLeft());
            for(j=i;j>=0;j--,this.moveLeftUp());
            for(j=i;j>=0;j--,this.moveRightUp());
          }
          $('#vista').append(this.html);
        }
        this.init();

        Yago.canTouchCells = false;
      } // canTouchCells
      Yago.loadjson();
    } // function 
  }
});