<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Lançamentos Financeiros' ?></h1>
    <a href="/lancamentos/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Adicionar Lançamento
    </a>
</div>

<div class="card shadow mb-4">
    <a href="#collapseCardFiltros" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardFiltros">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filtros de Pesquisa</h6>
    </a>
    <div class="collapse show" id="collapseCardFiltros">
        <div class="card-body">
            <form action="lancamentos" method="get">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="data_inicio">Data Início</label>
                        <input type="date" class="form-control form-control-sm" id="data_inicio" name="data_inicio" value="<?= isset($_GET['data_inicio']) ? esc($_GET['data_inicio']) : '' ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="data_fim">Data Fim</label>
                        <input type="date" class="form-control form-control-sm" id="data_fim" name="data_fim" value="<?= isset($_GET['data_fim']) ? esc($_GET['data_fim']) : '' ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="receita" <?= (isset($_GET['tipo']) && $_GET['tipo'] == 'receita') ? 'selected' : '' ?>>Receita</option>
                            <option value="despesa" <?= (isset($_GET['tipo']) && $_GET['tipo'] == 'despesa') ? 'selected' : '' ?>>Despesa</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="categoria_id">Categoria</label>
                        <select id="categoria_id" name="categoria_id" class="form-control form-control-sm">
                            <option value="">Todas</option>
                            <?php if (!empty($categories_list)): ?> <?php foreach ($categories_list as $category): ?>
                                    <option value="<?= esc($category['id']) ?>" <?= (isset($_GET['categoria_id']) && $_GET['categoria_id'] == $category['id']) ? 'selected' : '' ?>>
                                        <?= esc($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="situacao_id">Situação</label>
                        <select id="situacao_id" name="situacao_id" class="form-control form-control-sm">
                            <option value="">Todas</option>
                            <?php if (!empty($situations_list)): ?> <?php foreach ($situations_list as $situation): ?>
                                    <option value="<?= esc($situation['id']) ?>" <?= (isset($_GET['situacao_id']) && $_GET['situacao_id'] == $situation['id']) ? 'selected' : '' ?>>
                                        <?= esc($situation['description']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="descricao">Descrição</label>
                        <input type="text" class="form-control form-control-sm" id="descricao" name="descricao" placeholder="Buscar por palavra-chave..." value="<?= isset($_GET['descricao']) ? esc($_GET['descricao']) : '' ?>">
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-info btn-sm btn-block"><i class="fas fa-search"></i> Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Lançamentos</h6>
    </div>
    <div class="card-body">
        <?php if (isset($validation)): ?> <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?> <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?> <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <div class="col-sm-12 col-md-6">
            <div id="dataTable_length" class="dataTables_length">
                <label class="form-label">Mostrar
                    <select name="per_page" aria-controls="dataTable" class="custom-select custom-select-sm form-control-sm d-inline-block" style="width: auto;" oninput="handlePerPageChange(this)">
                        <?php
                        $per_page_options = [10, 25, 50, 100];
                        $current_per_page = $per_page;

                        foreach ($per_page_options as $option_val) {
                            $selected_attr = ($option_val == $current_per_page) ? 'selected' : '';
                            echo "<option value=\"{$option_val}\" {$selected_attr}>{$option_val}</option>";
                        }
                        ?>
                    </select> registros
                </label>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Situação</th>
                        <th>Forma Pag.</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactions_list) && is_array($transactions_list)): ?>
                        <?php foreach ($transactions_list as $transaction): ?>
                            <tr>
                                <td><?= esc($transaction['id']) ?></td>
                                <td><?= esc(date('d/m/Y', strtotime($transaction['date']))) ?></td>
                                <td><?= esc($transaction['description']) ?></td>
                                <td><?= esc($transaction['category_name']) // Vem do seu JOIN no controller 
                                    ?></td>
                                <td>
                                    <?php if (strtolower($transaction['type']) == 'receita'): ?>
                                        <span class="badge badge-success">Receita</span>
                                    <?php elseif (strtolower($transaction['type']) == 'despesa'): ?>
                                        <span class="badge badge-danger">Despesa</span>
                                    <?php else: ?>
                                        <?= esc($transaction['type']) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="<?= strtolower($transaction['type']) == 'receita' ? 'text-success font-weight-bold' : (strtolower($transaction['type']) == 'despesa' ? 'text-danger font-weight-bold' : '') ?>">
                                    <?= (strtolower($transaction['type']) == 'receita' ? '+ ' : (strtolower($transaction['type']) == 'despesa' ? '- ' : '')) ?>
                                    R$ <?= esc(number_format($transaction['amount'], 2, ',', '.')) ?>
                                </td>
                                <td><?= esc($transaction['situation_desc'])
                                    ?></td>
                                <td><?= esc($transaction['pm_desc'])
                                    ?></td>
                                <td>
                                    <a href="/lancamentos/edit/<?= esc($transaction['id']) ?>" class="btn btn-sm btn-info" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/lancamentos/delete/<?= esc($transaction['id']) ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este lançamento?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Nenhum lançamento encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($pager)):
        ?>
            <div class="d-flex justify-content-end">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
        <script src="<?= base_url('js/handlePerPageChange.js') ?>"></script>
    </div>
</div>