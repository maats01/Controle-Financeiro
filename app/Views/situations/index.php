<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Situações' ?></h1>
    <a href="/situacoes/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Adicionar Situação
    </a>
</div>

<div class="card shadow mb-4">
    <a href="#collapseCardFiltros" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardFiltros">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filtro de Pesquisa</h6>
    </a>
    <div class="collapse show" id="collapseCardFiltros">
        <div class="card-body">
            <form action="/situacoes" method="get">
                <div class="form-row">
                    <div class="form-group col-md-6"> <label for="descricao">Descrição da Situação</label>
                        <input type="text" class="form-control form-control-sm" id="descricao" name="descricao" placeholder="Buscar por descrição..." value="<?= isset($_GET['descricao']) ? esc($_GET['descricao']) : '' ?>">
                    </div>
                    <div class="form-group col-md-3"> <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="0" <?= (isset($_GET['tipo']) && $_GET['tipo'] === '0') ? 'selected' : '' ?>>Receita</option>
                            <option value="1" <?= (isset($_GET['tipo']) && $_GET['tipo'] === '1') ? 'selected' : '' ?>>Despesa</option>
                        </select>
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
                        <th style="width: 10%;">ID</th>
                        <th>Descrição</th>
                        <th style="width: 15%;">Tipo</th>
                        <th style="width: 15%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($situations_list) && is_array($situations_list)): ?>
                        <?php foreach ($situations_list as $situation): ?>
                            <tr>
                                <td><?= esc($situation->id) ?></td>
                                <td><?= esc($situation->description) ?></td>
                                <td> <?php if ($situation->type): // Assumindo 1 = Despesa 
                                        ?>
                                        <span class="badge badge-danger">Despesa</span>
                                    <?php else: // Assumindo 0 = Receita 
                                    ?>
                                        <span class="badge badge-success">Receita</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/situacoes/edit/<?= esc($situation->id) ?>" class="btn btn-sm btn-info" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/situacoes/delete/<?= esc($situation->id) ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta situação? Atenção: Lançamentos associados podem ser afetados.');">
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
        <script src="<?= base_url('js/handlePerPageChange.js') ?>"></script>
    </div>
</div>