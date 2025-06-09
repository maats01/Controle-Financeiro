<?= $this->extend('Layouts/default') ?>
<?= $this->section('content') ?>
<?php
require_once APPPATH . 'Helpers\sortingHelper.php';

$currentSortBy = isset($_GET['sort']) ? $_GET['sort'] : ''; 
$currentSortOrder = isset($_GET['order']) ? strtolower($_GET['order']) : 'asc';

$currentFilters = [];
if (isset($_GET['desc'])) $currentFilters['desc'] = $_GET['desc'];
if (isset($_GET['type'])) $currentFilters['type'] = $_GET['type'];
if (isset($_GET['per_page'])) $currentFilters['per_page'] = $_GET['per_page'];
if (isset($_GET['start_date'])) $currentFilters['start_date'] = $_GET['start_date'];
if (isset($_GET['end_date'])) $currentFilters['end_date'] = $_GET['end_date'];
if (isset($_GET['category_id'])) $currentFilters['category_id'] = $_GET['category_id'];
if (isset($_GET['situation_id'])) $currentFilters['situation_id'] = $_GET['situation_id'];
if (isset($_GET['payment_method_id'])) $currentFilters['payment_method_id'] = $_GET['payment_method_id'];

$baseUrl = '/lancamentos';
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Lançamentos Financeiros' ?></h1>
    <a href="<?= base_url('/lancamentos/criar') ?>" class="btn btn-primary shadow-sm"> <i class="fas fa-plus fa-sm text-white-50"></i> Adicionar Lançamento
    </a>
</div>

<div class="card shadow mb-4">
    <a href="#collapseCardFiltros" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardFiltros">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filtros de Pesquisa</h6>
    </a>
    <div class="collapse show" id="collapseCardFiltros">
        <div class="card-body">
            <form action="<?= base_url('lancamentos') ?>" method="get">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="data_inicio">Data Início</label>
                        <input type="date" class="form-control form-control-sm" id="data_inicio" name="start_date" value="<?= isset($_GET['start_date']) ? esc($_GET['start_date'], 'attr') : '' ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="data_fim">Data Fim</label>
                        <input type="date" class="form-control form-control-sm" id="data_fim" name="end_date" value="<?= isset($_GET['end_date']) ? esc($_GET['end_date'], 'attr') : '' ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="type" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="1" <?= (isset($_GET['type']) && $_GET['type'] == '1') ? 'selected' : '' ?>>Receita</option>
                            <option value="0" <?= (isset($_GET['type']) && $_GET['type'] == '0') ? 'selected' : '' ?>>Despesa</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="categoria_id">Categoria</label>
                        <select id="categoria_id" name="category_id" class="form-control form-control-sm" style="width: 100%;">
                            <?php if (isset($selected_category)): ?>
                                <option value="<?= esc($selected_category->id, 'attr') ?>" selected>
                                    <?= esc($selected_category->name) ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="situacao_id">Situação</label>
                        <select id="situacao_id" name="situation_id" class="form-control form-control-sm" style="width: 100%;">
                            <?php if (isset($selected_situation)): ?>
                                <option value="<?= esc($selected_situation->id, 'attr') ?>" selected>
                                    <?= esc($selected_situation->description) ?>
                                </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3"> <label for="payment_method_id">Forma de Pagamento</label>
                        <select id="payment_method_id" name="payment_method_id" class="form-control form-control-sm" style="width: 100%;">
                            <option value="">Todas as formas de pagamento</option>
                            <?php if (isset($payment_methods) && is_array($payment_methods)): ?>
                                <?php foreach ($payment_methods as $pm): ?>
                                    <option value="<?= esc($pm->id) ?>" <?= (isset($_GET['payment_method_id']) && $_GET['payment_method_id'] == $pm->id) ? 'selected' : '' ?>>
                                        <?= esc($pm->description) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="descricao">Descrição</label>
                        <input type="text" class="form-control form-control-sm" id="descricao" name="desc" placeholder="Buscar por palavra-chave..." value="<?= isset($_GET['desc']) ? esc($_GET['desc'], 'attr') : '' ?>">
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <div class="btn-group btn-block" role="group" aria-label="Ações de Filtro">
                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-search"></i> Filtrar</button>
                            <a href="/lancamentos" class="btn btn-secondary btn-sm"><i class="fas fa-eraser"></i> Limpar</a>
                        </div>
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
        <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div id="dataTable_length" class="dataTables_length">
                    <label class="form-label">Mostrar
                        <select name="per_page" aria-controls="dataTable" class="custom-select custom-select-sm form-control-sm d-inline-block" style="width: auto;" oninput="handlePerPageChange(this)">
                            <?php
                            $per_page_options = [10, 25, 50, 100];
                            $current_per_page = isset($per_page) ? $per_page : 10;

                            foreach ($per_page_options as $option_val) {
                                $selected_attr = ($option_val == $current_per_page) ? 'selected' : '';
                                echo "<option value=\"{$option_val}\" {$selected_attr}>{$option_val}</option>";
                            }
                            ?>
                        </select> registros
                    </label>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th><?= generateSortLink($baseUrl, 'id', 'ID', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th><?= generateSortLink($baseUrl, 'date', 'Data', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th><?= generateSortLink($baseUrl, 'description', 'Descrição', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th><?= generateSortLink($baseUrl, 'category_name', 'Categoria', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th><?= generateSortLink($baseUrl, 'type', 'Tipo', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th><?= generateSortLink($baseUrl, 'amount', 'Valor', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th><?= generateSortLink($baseUrl, 'situation_desc', 'Situação', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th><?= generateSortLink($baseUrl, 'pm_desc', 'Forma de pag.', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactions_list) && is_array($transactions_list)): ?>
                        <?php foreach ($transactions_list as $transaction): ?>
                            <tr>
                                <td><?= esc($transaction->id) ?></td>
                                <td><?= esc(date('d/m/Y', strtotime($transaction->date))) ?></td>
                                <td><?= esc($transaction->description) ?></td>
                                <td><?= esc($transaction->category_name) ?></td>
                                <td>
                                    <?php if ($transaction->type): ?>
                                        <span class="badge badge-success">Receita</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Despesa</span>
                                    <?php endif; ?>
                                </td>
                                <td class="<?= $transaction->type ? 'text-success font-weight-bold' : ($transaction->type == false ? 'text-danger font-weight-bold' : '') ?>">
                                    <?= $transaction->type ? '+ ' : ($transaction->type == false ? '- ' : '') ?>
                                    R$ <?= esc(number_format($transaction->amount, 2, ',', '.')) ?>
                                </td>
                                <td><?= esc($transaction->situation_desc) ?></td>
                                <td><?= esc($transaction->pm_desc) ?></td>
                                <td>
                                    <a href="<?= base_url('/lancamentos/editar/' . esc($transaction->id, 'url')) ?>" class="btn btn-sm btn-info" title="Editar"> <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('/lancamentos/deletar/' . esc($transaction->id, 'url')) ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este lançamento?');"> <i class="fas fa-trash"></i>
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

        <?php if (isset($pager)): ?>
            <div class="d-flex justify-content-end">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="<?= base_url('js/handlePerPageChange.js') ?>"></script>
<script src="<?= base_url('js/customSelects.js') ?>"></script>
<?= $this->endSection() ?>