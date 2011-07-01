<!-- Pagina delle statistiche -->
<?php if ($this->can('viewstat')): ?>
  <table class="tabella" cellspacing="0" cellspadding="0">
    <thead>
      <tr>
        <th>id</th>
        <th>username</th>
        <th>ferro</th>
        <th>grano</th>
        <th>legno</th>
        <th>roccia</th>
        <th>capienza</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->model as $item): ?>
        <tr>
          <td><?php echo $item['id']; ?></td>
          <td><?php echo $item['username']; ?></td>
          <td><?php echo $item['ferro']; ?></td>
          <td><?php echo $item['grano']; ?></td>
          <td><?php echo $item['legno']; ?></td>
          <td><?php echo $item['roccia']; ?></td>
          <td><?php echo $item['livellomagazzino'] ? $item['livellomagazzino'] : 0; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="7" class="tfoot">
          <?php if ($this->page > 0): ?>
            <a href="index.php?<?php echo ($this->page - 1); ?>=stats/stats">&Lt;</a>
          <?php endif; ?>
          <?php if ($this->page < $this->totalPages - 1): ?>
            <a href="index.php?<?php echo ($this->page + 1); ?>=stats/stats">&Gt;</a>
          <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
<?php endif; ?>