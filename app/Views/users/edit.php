<?= $this->extend('Layouts/default') ?>
<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Editar Usuário' ?></h1>
    <a href="/admin/usuarios" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Usuários
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edite os dados do usuário</h6>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('errors') ?>
            </div>
        <?php endif; ?>

        <form action="/admin/usuarios/atualizar/<?= esc($user->id) ?>" method="post">
            <?= csrf_field() ?> 
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $user->username) ?>" required minlength="3">
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user->getEmail()) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="active">Status:</label>
                    <select class="form-control" id="active" name="active" required>
                        <option value="1" <?= old('active', $user->active ? '1' : '0') === '1' ? 'selected' : '' ?>>Ativo</option>
                        <option value="0" <?= old('active', $user->active ? '1' : '0') === '0' ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="group">Grupo do Usuário:</label>
                    <select class="form-control" id="group" name="group" required>
                        <?php 

                        $config = config(\Config\AuthGroups::class);
                        $availableGroups = array_keys($config->groups);
                        $currentUserGroup = $user->getGroups()[0] ?? '';
                        
                        foreach ($availableGroups as $groupName): ?>
                            <option value="<?= esc($groupName) ?>" <?= old('group', $currentUserGroup) === $groupName ? 'selected' : '' ?>>
                                <?= esc(ucfirst($groupName)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
            <a href="/admin/usuarios" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>