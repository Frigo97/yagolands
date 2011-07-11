<div style="margin: 0px 200px;">
  <div style="padding: 10px;">
    <h1>Edifici attaccabili</h1>
    <div class="edificiAttaccabili"><?php
$costruzioni = new MCostruzioni;
$edifici = new MEdifici;
foreach ($costruzioni->findAll(array(), array('idutente' => $this->contest)) as $item) {
  if ($edifici->isBuilding($item['idedificio'])) {
    echo $edifici->getNome($item['idedificio']) . '<br />';
  }
}
?></div>
  </div>
</div>