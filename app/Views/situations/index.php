<h2>Situações cadastradas</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Tipo</th>
        <th>Descrição</th>
    </tr>
    <?php foreach ($situations_list as $situation): ?>
        <tr>
            <td><?= esc($situation->id) ?></td>
            <td><?= esc($situation->type ? 'Despesa' : 'Receita'); ?></td>
            <td><?= esc($situation->description) ?></td>
        </tr>
    <?php endforeach; ?>
</table>