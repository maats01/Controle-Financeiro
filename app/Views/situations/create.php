<?= $this->extend('Layouts/default') ?>
<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Adicionar Descrição de situação' ?></h1>
    <a href="/admin/situacoes" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Situação
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Preencha os dados da nova situação</h6>
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
                <?php if (session()->getFlashdata('model_validation_errors')): ?>
                    <ul>
                    <?php foreach (session()->getFlashdata('model_validation_errors') as $field => $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Mensagens de erro de validação do Controller (se houver) -->
        <?php if (isset($errors) && is_array($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <h5>Problemas de preenchimento:</h5>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulário principal - Ação deve apontar para o método createPost -->
        <form action="/admin/situacoes/salvar" method="post">
            <?= csrf_field() ?> 
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="description">Descrição da situação:</label>
                    <!-- O name do input deve corresponder ao campo do DB e da validação -->
                    <input type="text" class="form-control" id="description" name="description" value="<?= old('description') ?>" required minlength="3">
                    <?php if (isset($errors['description'])): ?>
                        <small class="text-danger"><?= esc($errors['description']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-4">
                    <label for="type">Tipo:</label>
                    <select id="type" name="type" class="form-control" required>
                        <option value="">Selecione o Tipo</option>
                        <option value="0" <?= old('type') === '0' ? 'selected' : '' ?>>Despesa</option>
                        <option value="1" <?= old('type') === '1' ? 'selected' : '' ?>>Receita</option>
                    </select>
                    <?php if (isset($errors['type'])): ?>
                        <small class="text-danger"><?= esc($errors['type']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Salvar Situação
            </button>
            <a href="/admin/situacoes" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>