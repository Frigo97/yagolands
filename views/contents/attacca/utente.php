<div style="margin: 0px 200px;">
  <div style="padding: 10px;">
    <h1>Edifici attaccabili</h1>
    <div class="edificiAttaccabili"><?php
$costruzioni = new MCostruzioni;
$edifici = new MEdifici;
$costruzioni = new MCostruzioni;
$esercito = new MEsercito;
$truppa = new MTruppe;
?>

      <table>
        <thead>
          <tr>
            <th>Edificio</th>
            <?php foreach ($esercito->findAll(array(), array('idutente' => $this->contest)) as $itemEsercito): ?>
              <th><?php echo $truppa->getNome($itemEsercito['idtruppa']); ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($costruzioni->findAll(array('idedificio'), array('idutente' => $this->contest)) as $itemCostruzione): ?>
            <?php if ($edifici->isBuilding($itemCostruzione['idedificio'])): ?>
              <tr>
                <td><?php echo $edifici->getNome($itemCostruzione['idedificio'], new MEdifici()) ?></td>
                <?php foreach ($truppa->findAll() as $itemTruppe): ?>
                  <td>
              <center>
                <input type="text" size="8" />
              </center>
              </td>
            <?php endforeach; ?>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4">
              <div style="text-align: right;">
                <button id="attaccaVillaggio">
                  attacca!
                </button>
              </div>
            </td>
          </tr>
        </tfoot>
      </table>




    </div>
  </div>
</div>