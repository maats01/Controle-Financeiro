$(document).ready(function() {
    const select2AjaxConfig = (placeholderText, ajaxUrl, textFieldName = 'name') => ({
        theme: 'bootstrap4',
        ajax: {
            url: ajaxUrl,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term
                };
            },
            processResults: function (data) {
                const results = data.map(item => ({
                    id: item.id,
                    text: item[textFieldName]
                }));

                return {
                    results: results,
                };
            },
            cache: true
        },
        placeholder: placeholderText,
        allowClear: true,
        minimumInputLength: 0,
        language: "pt-BR"
    });

    $('#categoria_id').select2(
        select2AjaxConfig('Todas as categorias', '/api/categorias', 'name')
    );

    $('#situacao_id').select2(
        select2AjaxConfig('Todas as situações', '/api/situacoes', 'description')
    );
});