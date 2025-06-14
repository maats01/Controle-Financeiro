<?= $this->extend('Layouts/default') ?>
<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
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
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('errors') ?>
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
                    <label for="amount">Valor:</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="<?= old('amount') ?>" step="0.01" placeholder="Ex: 1500.50" required min="0.01">
                    <?php if (isset($errors['amount'])): ?>
                        <small class="text-danger"><?= esc($errors['amount']) ?></small>
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
                    <select id="category_id" name="category_id" class="form-control" required style="width: 100%;">
                        <option value="">Selecione uma categoria</option>
                        <?php if (old('category_id')):?>

                             <option value="<?= esc(old('category_id')) ?>" selected>
                                <?= esc($categories->where('id', old('category_id'))->first()->name ?? 'Categoria Selecionada') ?>
                            </option>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['category_id'])): ?>
                        <small class="text-danger"><?= esc($errors['category_id']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="situation_id">Situação:</label>
                    <select id="situation_id" name="situation_id" class="form-control" required style="width: 100%;">
                        <option value="">Selecione uma situação</option>
                        <?php if (old('situation_id')): ?>
                             <option value="<?= esc(old('situation_id')) ?>" selected>
                                <?= esc($situations->where('id', old('situation_id'))->first()->description ?? 'Situação Selecionada') ?>
                            </option>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['situation_id'])): ?>
                        <small class="text-danger"><?= esc($errors['situation_id']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="payment_method_id">Forma de Pagamento:</label>
                    <select id="payment_method_id" name="payment_method_id" class="form-control" required style="width: 100%;">
                        <option value="">Selecione uma forma de pagamento</option>
                        <?php if (old('payment_method_id')): ?>
                            <option value="<?= esc(old('payment_method_id')) ?>" selected>
                                <?= esc($payment_methods->where('id', old('payment_method_id'))->first()->description ?? 'Forma de Pagamento Selecionada') ?>
                            </option>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($errors['payment_method_id'])): ?>
                        <small class="text-danger"><?= esc($errors['payment_method_id']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="date">Data de Lançamento:</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?= old('date') ?>" required>
                    <?php if (isset($errors['date'])): ?>
                        <small class="text-danger"><?= esc($errors['date']) ?></small>
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

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url('js/customSelects.js') ?>"></script>
<?= $this->endSection() ?>