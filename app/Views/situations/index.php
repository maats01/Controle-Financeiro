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

$baseUrl = '/admin/situacoes';
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Situações' ?></h1>
    <a href="/admin/situacoes/criar" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Adicionar Situação
    </a>
</div>

<div class="card shadow mb-4">
    <a href="#collapseCardFiltros" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardFiltros">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filtro de Pesquisa</h6>
    </a>
    <div class="collapse show" id="collapseCardFiltros">
        <div class="card-body">
            <form action="/admin/situacoes" method="get">
                <div class="form-row">
                    <div class="form-group col-md-6"> <label for="descricao">Descrição da Situação</label>
                        <input type="text" class="form-control form-control-sm" id="descricao" name="desc" placeholder="Buscar por descrição..." value="<?= isset($_GET['desc']) ? esc($_GET['desc']) : '' ?>">
                    </div>
                    <div class="form-group col-md-3"> <label for="tipo">Tipo</label>
                        <select id="tipo" name="type" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="0" <?= (isset($_GET['type']) && $_GET['type'] === '0') ? 'selected' : '' ?>>Despesa</option>
                            <option value="1" <?= (isset($_GET['type']) && $_GET['type'] === '1') ? 'selected' : '' ?>>Receita</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <div class="btn-group btn-block" role="group" aria-label="Ações de Filtro">
                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-search"></i> Filtrar</button>
                            <a href="/admin/situacoes" class="btn btn-secondary btn-sm"><i class="fas fa-eraser"></i> Limpar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Situações Cadastradas</h6>
    </div>
    <div class="card-body">
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
                        <th style="width: 10%;"><?= generateSortLink($baseUrl, 'id', 'ID', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th><?= generateSortLink($baseUrl, 'description', 'Descrição', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th style="width: 15%;"><?= generateSortLink($baseUrl, 'type', 'Tipo', $currentSortBy, $currentSortOrder, $currentFilters)?></th>
                        <th style="width: 15%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($situations_list) && is_array($situations_list)): ?>
                        <?php foreach ($situations_list as $situation): ?>
                            <tr>
                                <td><?= esc($situation->id) ?></td>
                                <td><?= esc($situation->description) ?></td>
                                <td> <?php if ($situation->type): ?>
                                        <span class="badge badge-success">Receita</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Despesa</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                     <a href="/admin/situacoes/editar/<?= esc($situation->id) ?>" class="btn btn-sm btn-info" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-circle btn-sm" title="Deletar"
                                        data-toggle="modal"
                                        data-target="#deleteModal"
                                        data-id="<?= $situation->id ?>"
                                        data-name="<?= $situation->description ?>"
                                        data-controller="situacoes"
                                        data-base="/admin">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Nenhuma situação encontrada.</td>
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
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja excluir a situação <strong id="itemNameToDelete"></strong>?
                <p class="text-danger mt-2">Esta ação não pode ser desfeita e pode afetar lançamentos associados.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form id="deleteForm" action="" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="<?= base_url('js/confirmDeletion.js') ?>"></script>
<script src="<?= base_url('js/handlePerPageChange.js') ?>"></script>
<?= $this->endSection() ?>