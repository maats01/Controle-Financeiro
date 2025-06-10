<?= $this->extend('Layouts/default') ?>
<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Editar Lançamento' ?></h1>
    <a href="/lancamentos" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Voltar para Lançamentos
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edite os dados do lançamento</h6>
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
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info">
                <?= session()->getFlashdata('info') ?>
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

        <form action="/lancamentos/atualizar" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= esc($transaction->id) ?>">

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="description">Descrição:</label>
                    <input type="text"
                           class="form-control"
                           id="description"
                           name="description"
                           value="<?= old('description', $transaction->description ?? '') ?>"
                           placeholder="Ex: Aluguel, Salário, Conta de Luz"
                           required
                           minlength="3"
                           maxlength="255">
                    <?php if (isset($errors['description'])): ?>
                        <small class="text-danger"><?= esc($errors['description']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="amount">Valor:</label>
                    <input type="number"
                           class="form-control"
                           id="amount"
                           name="amount"
                           value="<?= old('amount', $transaction->amount ?? '') ?>"
                           step="0.01"
                           placeholder="Ex: 1500.50"
                           required
                           min="0.01">
                    <?php if (isset($errors['amount'])): ?>
                        <small class="text-danger"><?= esc($errors['amount']) ?></small>
                    <?php endif; ?>
                </div>
                    <div class="form-group col-md-6">
                        <label>Tipo:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type_expense" value="0"
                                <?= old('type', $transaction->type ?? null) == 0 ? 'checked' : '' ?> required>
                            <label class="form-check-label" for="type_expense">
                            Despesa
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type_revenue" value="1"
                            <?= old('type', $transaction->type ?? null) == 1 ? 'checked' : '' ?>>
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
                        <?php
                        // Prioriza o valor old() em caso de erro de validação
                        $currentCategoryId = old('category_id', $transaction->category_id ?? null);
                        $currentCategoryText = '';

                        if ($currentCategoryId !== null) {
                            // Tenta encontrar a categoria nas categorias passadas para a view
                            foreach ($categories as $cat) { // $categories deve ser um array/Collection de objetos Category
                                if ($cat->id == $currentCategoryId) {
                                    $currentCategoryText = $cat->name;
                                    break;
                                }
                            }
                            // Se o texto foi encontrado ou se é um valor antigo que o Select2 pode resolver via AJAX
                            echo '<option value="' . esc($currentCategoryId) . '" selected>' . esc($currentCategoryText ?: 'ID: ' . $currentCategoryId) . '</option>';
                        }
                        ?>
                    </select>
                    <?php if (isset($errors['category_id'])): ?>
                        <small class="text-danger"><?= esc($errors['category_id']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="situation_id">Situação:</label>
                    <select id="situation_id" name="situation_id" class="form-control" required style="width: 100%;">
                        <option value="">Selecione uma situação</option>
                        <?php
                        $currentSituationId = old('situation_id', $transaction->situation_id ?? null);
                        $currentSituationText = '';

                        if ($currentSituationId !== null) {
                            foreach ($situations as $sit) { // $situations deve ser um array/Collection de objetos Situation
                                if ($sit->id == $currentSituationId) {
                                    $currentSituationText = $sit->description;
                                    break;
                                }
                            }
                            echo '<option value="' . esc($currentSituationId) . '" selected>' . esc($currentSituationText ?: 'ID: ' . $currentSituationId) . '</option>';
                        }
                        ?>
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
                        <?php
                        $currentPaymentMethodId = old('payment_method_id', $transaction->payment_method_id ?? null);
                        $currentPaymentMethodText = '';

                        if ($currentPaymentMethodId !== null) {
                            foreach ($payment_methods as $pm) { // $payment_methods deve ser um array/Collection de objetos PaymentMethod
                                if ($pm->id == $currentPaymentMethodId) {
                                    $currentPaymentMethodText = $pm->description;
                                    break;
                                }
                            }
                            echo '<option value="' . esc($currentPaymentMethodId) . '" selected>' . esc($currentPaymentMethodText ?: 'ID: ' . $currentPaymentMethodId) . '</option>';
                        }
                        ?>
                    </select>
                    <?php if (isset($errors['payment_method_id'])): ?>
                        <small class="text-danger"><?= esc($errors['payment_method_id']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="date">Data de Lançamento:</label>
                    <input type="date"
                           class="form-control"
                           id="date"
                           name="date"
                           value="<?= old('date', isset($transaction->date) ? date('Y-m-d', strtotime($transaction->date)) : '') ?>"
                           required>
                    <?php if (isset($errors['date'])): ?>
                        <small class="text-danger"><?= esc($errors['date']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Salvar Alterações
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