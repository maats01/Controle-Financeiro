$(document).ready(function () {

    // Manipula a abertura do modal de exclusão genérico
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botão que acionou o modal

        // Extrai os dados do botão usando atributos data-*
        var id = button.data('id');                 // O ID do item a ser excluído
        var name = button.data('name');               // O nome/descrição para exibição
        var controller = button.data('controller');   // O nome do Controller (ex: "Stocks")
        var action = 'deletar'; 
        var base = button.data('base');

        var modal = $(this);

        // Constrói a URL para a action do formulário
        var actionUrl = base + "/" + controller + "/" + action + "/" + id;

        // Atualiza o conteúdo do modal
        modal.find('.modal-body #itemNameToDelete').text(name || 'este item'); // Exibe o nome ou um texto padrão
        modal.find('#deleteForm').attr('action', actionUrl); // Define a action do formulário
        modal.find('#deleteForm').show(); // Garante que o formulário está visível
    });

});