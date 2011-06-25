<h1><?php echo $this->nomeEdificio; ?></h1>

<div class="paragrafo-edificio">In questo edificio è possibile effettuare scambi di risorse con altri utenti.</div>

<table>
  <tr>
    <td>cerco</td>
    <td><input type="text" id="cerco" class="input-risorsa" /></td>
    <td>
      <input type="radio" name="cerco" value="ferro" id="fc" checked="checked" /> <label for="fc">ferro</label> 
      <input type="radio" name="cerco" value="grano" id="gc" /> <label for="gc">grano</label> 
      <input type="radio" name="cerco" value="legno" id="lc" /> <label for="lc">legno</label> 
      <input type="radio" name="cerco" value="roccia" id="rc" /> <label for="rc">roccia</label> 
    </td>
  </tr>
  <tr>
    <td>offro</td>
    <td><input type="text" id="offro" class="input-risorsa" /></td>
    <td>
      <input type="radio" name="offro" value="ferro" id="fo" checked="checked" /> <label for="fo">ferro</label> 
      <input type="radio" name="offro" value="grano" id="go" /> <label for="go">grano</label> 
      <input type="radio" name="offro" value="legno" id="lo" /> <label for="lo">legno</label> 
      <input type="radio" name="offro" value="roccia" id="ro" /> <label for="ro">roccia</label> 
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <button id="invia-risorse">invia</button>
    </td>
  </tr>
</table>

<div id="elencoofferte"></div>