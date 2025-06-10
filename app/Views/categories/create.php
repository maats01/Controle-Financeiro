<?= $this->extend('Layouts/default') ?>
<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Adicionar Nova Categoria' ?></h1>
    <a href="/admin/categorias" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Categorias
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Preencha os dados da nova categoria</h6>
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

        <?php if (isset($errors) && is_array($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="salvar" method="post">
            <?= csrf_field() ?> <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="name">Nome da Categoria:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required minlength="3">
                    <?php if (isset($errors['name'])): ?>
                        <small class="text-danger"><?= esc($errors['name']) ?></small>
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

            <form action="/categorias/salvar" method="post">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Salvar Categoria
                </button>
                 <a href="/admin/categorias" class="btn btn-secondary">Cancelar</a>
            </form>
        </form>
    </div>
</div>
<?= $this->endSection() ?>