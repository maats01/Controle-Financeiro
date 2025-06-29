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
                            R$ <?= isset($month_revenues) ? esc(number_format($month_revenues, 2, ',', '.')) : '0,00' ?>
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
                            R$ <?= isset($month_expenses) ? esc(number_format($month_expenses, 2, ',', '.')) : '0,00' ?>
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
                            R$ <?= isset($current_balance) ? esc(number_format($current_balance, 2, ',', '.')) : '0,00' ?>
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
    <div class="col-xl-7 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Visão Geral - Lançamentos do Ano</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 450px">
                    <canvas id="revenueExpenseChart"
                        data-labels='<?= json_encode($labels_for_line_graph) ?>'
                        data-expenses='<?= json_encode($latest_expenses) ?>'
                        data-revenues='<?= json_encode($latest_revenues) ?>'>>
                    </canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Gastos por Categoria no Mês Atual</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 450px">
                    <canvas id="expensesByCategory"
                        data-labels='<?= json_encode($labels_for_pie_graph) ?>'
                        data-data='<?= json_encode($data_for_pie_graph) ?>'>
                    </canvas>
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
                                        <td><?= esc($transaction->situation_desc ?? 'N/A') ?></td>
                                        <td>
                                            <a href="<?= base_url('/lancamentos/editar/' . esc($transaction->id, 'url')) ?>" class="btn btn-sm btn-info" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-danger" title="Excluir"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-id="<?= esc($transaction->id) ?>"
                                                data-name="<?= esc($transaction->description) ?>"
                                                data-controller="lancamentos"
                                                data-base="">
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
                Você tem certeza que deseja excluir o lançamento <strong id="itemNameToDelete"></strong>?
                <p class="text-danger mt-2">Esta ação não pode ser desfeita e pode afetar saldos e relatórios.</p>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@^2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/hw-chartjs-plugin-colorschemes"></script>
<script src="<?= base_url('js/graphicsForDashboard.js') ?>"></script>
<?= $this->endSection() ?>