<?= $this->extend('Layouts/default') ?>
<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Adicionar Novo Lançamento' ?></h1>
    <a href="/lancamentos" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Lançamentos
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Preencha os dados do novo lançamento</h6>
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

        <!--
            ATENÇÃO: A ação do formulário foi corrigida para '/lancamentos/salvar'
            Isso corresponde à rota POST definida em app/Config/Routes.php
            para o método createPost do TransactionsController.
        -->
        <form action="/lancamentos/salvar" method="post">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="description">Descrição:</label>
                    <input type="text" class="form-control" id="description" name="description" value="<?= old('description') ?>" placeholder="Ex: Aluguel, Salário, Conta de Luz" required minlength="3" maxlength="255">
                    <?php if (isset($errors['description'])): ?>
                        <small class="text-danger"><?= esc($errors['description']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="value">Valor:</label>
                    <input type="number" class="form-control" id="value" name="value" value="<?= old('value') ?>" step="0.01" placeholder="Ex: 1500.50" required min="0.01">
                    <?php if (isset($errors['value'])): ?>
                        <small class="text-danger"><?= esc($errors['value']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label>Tipo:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="type_expense" value="0" <?= old('type') === '0' ? 'checked' : '' ?> required>
                        <label class="form-check-label" for="type_expense">
                            Despesa
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="type_revenue" value="1" <?= old('type') === '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="type_revenue">
                            Receita
                        </label>
                    </div>
                    <?php if (isset($errors['type'])): ?>
                        <small class="text-danger"><?= esc($errors['type']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="category_id">Categoria:</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category->id) ?>" <?= old('category_id') == $category->id ? 'selected' : '' ?>>
                                <?= esc($category->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['category_id'])): ?>
                        <small class="text-danger"><?= esc($errors['category_id']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="situation_id">Situação:</label>
                    <select id="situation_id" name="situation_id" class="form-control" required>
                        <option value="">Selecione uma situação</option>
                        <?php foreach ($situations as $situation): ?>
                            <option value="<?= esc($situation->id) ?>" <?= old('situation_id') == $situation->id ? 'selected' : '' ?>>
                                <?= esc($situation->description) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['situation_id'])): ?>
                        <small class="text-danger"><?= esc($errors['situation_id']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="due_date">Data de Lançamento:</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" value="<?= old('due_date') ?>" required>
                    <?php if (isset($errors['due_date'])): ?>
                        <small class="text-danger"><?= esc($errors['due_date']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Salvar Lançamento
            </button>
            <a href="/lancamentos" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
