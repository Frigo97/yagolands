Il magazzino è a livello <?php echo $this->livelloEdificio; ?> e può ospitare <?php echo $this->capienzaEdificio; ?> risorse.


<?php
for ($i = 1; $i < 15; $i++)
  echo '<br />Livello ' . $i . ': ' . Config::moltiplicatoreCapienzaEdificio($i);
?>