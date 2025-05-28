<h2>Formas de pagamento cadastradas</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Descrição</th>
    </tr>
    <?php foreach ($payment_methods_list as $pm): ?>
        <tr>
            <td><?= esc($pm->id) ?></td>
            <td><?= esc($pm->description) ?></td>
        </tr>
    <?php endforeach; ?>
</table>