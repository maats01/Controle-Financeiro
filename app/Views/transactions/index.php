<h2>Lançamentos</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Tipo</th>
        <th>Data</th>
        <th>Categoria</th>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Situação</th>
        <th>Forma de Pagamento</th>
        <th>Criado em</th>
    </tr>
    <?php foreach ($transactions_list as $transaction): ?>
        <tr>
            <td><?= esc($transaction['id']) ?></td>
            <td><?= esc($transaction['type']); ?></td>
            <td><?= esc($transaction['date']) ?></td>
            <td><?= esc($transaction['category_name']) ?></td>
            <td><?= esc($transaction['description']) ?></td>
            <td><?= esc($transaction['amount']) ?></td>
            <td><?= esc($transaction['situation_desc']) ?></td>
            <td><?= esc($transaction['pm_desc']) ?></td>
            <td><?= esc($transaction['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>