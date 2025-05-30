<?= $this->extend('Layouts/default') ?>
<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Usuários' ?></h1>
    <a href="/admin/usuarios/criar" class="btn btn-primary shadow-sm">
        <i class="fas fa-user-plus fa-sm text-white-50"></i> Adicionar Usuário
    </a>
</div>

<div class="card shadow mb-4">
    <a href="#collapseCardFiltros" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardFiltros">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filtros de Pesquisa</h6>
    </a>
    <div class="collapse show" id="collapseCardFiltros">
        <div class="card-body">
            <form action="/admin/usuarios" method="get">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="username">Username</label>
                        <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="Buscar por username..." value="<?= isset($_GET['username']) ? esc($_GET['username']) : '' ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Buscar por email..." value="<?= isset($_GET['email']) ? esc($_GET['email']) : '' ?>">
                    </div>
                     <div class="form-group col-md-2">
                        <label for="active">Status</label>
                        <select id="active" name="active" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="1" <?= (isset($_GET['active']) && $_GET['active'] === '1') ? 'selected' : '' ?>>Ativo</option>
                            <option value="0" <?= (isset($_GET['active']) && $_GET['active'] === '0') ? 'selected' : '' ?>>Inativo</option>
                            </select>
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <div class="btn-group btn-block" role="group" aria-label="Ações de Filtro">
                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-search"></i> Filtrar</button>
                            <a href="/admin/usuarios" class="btn btn-secondary btn-sm"><i class="fas fa-eraser"></i> Limpar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Usuários Cadastrados</h6>
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
                        <th style="width: 5%;">ID</th>
                        <th>Username</th>
                        <th>Email</th> 
                        <th>Grupo</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">Último Acesso</th>
                        <th style="width: 15%;">Criado em</th>
                        <th style="width: 15%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users_list) && is_array($users_list)): ?>
                        <?php foreach ($users_list as $user): ?>
                            <tr>
                                <td><?= esc($user->id) ?></td>
                                <td><?= esc($user->username) ?></td>
                                <td><?= esc($user->email ?? 'N/A') ?></td>
                                <td><?= esc($user->group) ?></td>
                                <td>
                                    <?php if ($user->isBanned()): ?>
                                        <span class="badge badge-dark">Banido</span>
                                    <?php elseif ($user->active): ?>
                                        <span class="badge badge-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $user->last_active ? esc(date('d/m/Y H:i', strtotime($user->last_active))) : 'Nunca' ?></td>
                                <td><?= esc(date('d/m/Y H:i', strtotime($user->created_at))) ?></td>
                                <td>
                                    <a href="/admin/usuarios/editar/<?= esc($user->id) ?>" class="btn btn-sm btn-info" title="Editar Usuário">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/admin/usuarios/grupos/<?= esc($user->id) ?>" class="btn btn-sm btn-secondary" title="Gerenciar Grupos">
                                        <i class="fas fa-users-cog"></i>
                                    </a>
                                    <a href="/admin/usuarios/deletar/<?= esc($user->id) ?>" class="btn btn-sm btn-danger" title="Excluir Usuário" onclick="return confirm('Tem certeza que deseja excluir este usuário? Esta ação é irreversível!');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Nenhum usuário encontrado.</td>
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
<?= $this->endSection() ?>