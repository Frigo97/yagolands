<?php if ( $this->can ( 'viewstat' ) ): ?>
    <table class="tabella" cellspacing="0" cellspadding="0">
        <thead>
            <tr>
                <th>id</th>
                <th>username</th>
                <th>ferro</th>
                <th>grano</th>
                <th>legno</th>
                <th>roccia</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $this->model as $item ): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['username']; ?></td>
                    <td><?php echo $item['ferro']; ?></td>
                    <td><?php echo $item['grano']; ?></td>
                    <td><?php echo $item['legno']; ?></td>
                    <td><?php echo $item['roccia']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="tfoot">id</td>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>