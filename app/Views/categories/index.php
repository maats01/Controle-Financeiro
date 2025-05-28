<h2>Categorias cadastradas</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Tipo</th>
        <th>Descrição</th>
    </tr>
    <?php foreach ($categories_list as $category): ?>
        <tr>
            <td><?= esc($category->id) ?></td>
            <td><?= esc($category->type ? 'Despesa' : 'Receita'); ?></td>
            <td><?= esc($category->name) ?></td>
        </tr>
    <?php endforeach; ?>
</table>