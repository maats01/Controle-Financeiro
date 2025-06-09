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

    $('#category_id').select2(
        select2AjaxConfig('Todas as categorias', '/api/categorias', 'name')
    );

    $('#situation_id').select2(
        select2AjaxConfig('Todas as situações', '/api/situacoes', 'description')
    );

    $('#payment_method_id').select2(
        select2AjaxConfig('Todas as as formas de pagamento', '/api/formas-de-pagamento', 'description')
    );
});