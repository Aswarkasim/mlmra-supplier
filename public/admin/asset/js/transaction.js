(function ($) {
    $("#modal-transaction").click(function(){
        $('#transaction-id').val($(this).data('id'));
    });

})(jQuery);

