<?php // app/Helpers/sorting_helper.php

if (!function_exists('generateSortLink')) {
    /**
     * Gera um link de cabeçalho de tabela para ordenação.
     *
     * @param string $baseUrl           A URL base para o link (ex: '/admin/categorias').
     * @param string $columnName        O nome da coluna do banco de dados ou chave de ordenação.
     * @param string $displayName       O texto a ser exibido para o link.
     * @param string|null $currentSortBy A coluna atualmente ordenada.
     * @param string $currentSortOrder  A ordem de classificação atual ('asc' ou 'desc').
     * @param array $currentFilters    Um array de filtros atuais para preservar.
     * @return string                   HTML da tag <a>.
     */
    function generateSortLink(string $baseUrl, string $columnName, string $displayName, ?string $currentSortBy, string $currentSortOrder, array $currentFilters): string
    {
        $newSortOrder = 'asc';
        $iconClass = 'fas fa-sort text-muted';

        if ($currentSortBy === $columnName) {
            if ($currentSortOrder === 'asc') {
                $newSortOrder = 'desc';
                $iconClass = 'fas fa-sort-up';
            } else {
                $newSortOrder = 'asc';
                $iconClass = 'fas fa-sort-down';
            }
        }

        $queryParams = $currentFilters;
        $queryParams['sort'] = $columnName;
        $queryParams['order'] = $newSortOrder;

        unset($queryParams['page']);

        $baseUrl = rtrim($baseUrl, '?'); 
        $queryString = http_build_query($queryParams);
        
        $url = $baseUrl . ($queryString ? '?' . $queryString : '');

        return '<a href="' . $url . '" class="text-decoration-none">' . esc($displayName) . ' <i class="' . $iconClass . '"></i></a>';
    }
}