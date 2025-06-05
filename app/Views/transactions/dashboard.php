<?= $this->extend('Layouts/default') ?>
<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
    <h1 class="h3 mb-0 text-gray-800"><?= isset($title) ? esc($title) : 'Dashboard Financeiro' ?></h1>
    <a href="<?= base_url('/lancamentos/criar') ?>" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Adicionar Lançamento
    </a>
</div>

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

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Receitas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?= isset($receitasMes) ? esc(number_format($receitasMes, 2, ',', '.')) : '0,00' ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Despesas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?= isset($despesasMes) ? esc(number_format($despesasMes, 2, ',', '.')) : '0,00' ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments-dollar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Saldo
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            R$ <?= isset($saldoAtualMes) ? esc(number_format($saldoAtualMes, 2, ',', '.')) : '0,00' ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Últimos Lançamentos</h6>
                <a href="<?= base_url('/lancamentos') ?>">Ver Todos &rarr;</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTableUltimos" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Descrição</th>
                                <th>Categoria</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Situação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions_list) && is_array($transactions_list)): ?>
                                <?php foreach ($transactions_list as $transaction): ?>
                                    <tr>
                                        <td><?= esc(date('d/m/Y', strtotime($transaction->date))) ?></td>
                                        <td><?= esc($transaction->description) ?></td>
                                        <td><?= esc($transaction->category_name ?? 'N/A') ?></td>
                                        <td>
                                            <?php
                                            if ($transaction->type): ?>
                                                <span class="badge badge-success">Receita</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Despesa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="<?= $transaction->type ? 'text-success font-weight-bold' : ($transaction->type == false ? 'text-danger font-weight-bold' : '') ?>">
                                            <?= ($transaction->type ? '+ ' : ($transaction->type == false ? '- ' : '')) ?>
                                            R$ <?= esc(number_format($transaction->amount, 2, ',', '.')) ?>
                                        </td>
                                        <td><?= esc($transaction->situation_desc ?? 'N/A')?></td>
                                        <td>
                                            <a href="<?= base_url('/lancamentos/editar/' . esc($transaction->id, 'url')) ?>" class="btn btn-sm btn-info" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('/lancamentos/deletar/' . esc($transaction->id, 'url')) ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este lançamento?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum lançamento recente encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Exemplo: Se precisar de alguma inicialização de JS para o dashboard.
    // $(document).ready(function() {
    //     // Seu código JS aqui
    // });
</script>
<?= $this->endSection() ?>