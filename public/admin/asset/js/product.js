(function ($) {
    // form Product
    let _formProduct = $('form[name="form-product"]');
    _formProduct.find('.js-product-action').on('click', function () {
        var _this = $(this);
        var _actionType = _this.data('action-type');
        _formProduct.find('input[name="action_type"]').val(_actionType);
        _formProduct.submit();
    });

    _formProduct.find('input[name="opsi_commision"]').on('change', function () {
        var _this = $(this);
        if (_this.is(':checked')) {
            _formProduct.find('.js-commision_rp').removeClass('d-none');
            $( ".js-commision_rp").rules( "add", {
                required: true,
                messages: {
                    required: "This field is required.",
                },
            });
        } else {
            _formProduct.find('.js-commision_rp').addClass('d-none');
        }
    });

    _formProduct.find('input[name="opsi_commision_1"]').on('change', function () {
        var _this = $(this);
        if (_this.is(':checked')) {
            console.log("teeeuss");
            $('.custom-checkbox').removeAttr('disabled');
        } else {
            console.log("falsee");
            $('.custom-checkbox').attr('disabled', 'disabled' );
        }
    });

    //ajax select subcategory asal
    $('select[name="category"]').on('change', function () {
        let categoryId = $(this).val();
        jQuery.ajax({
            url: 'category/'+categoryId,
            type: "GET",
            dataType: "json",
            success: function (response) {
                $('select[name="subcategory"]').empty();
                $.each(response, function (key, value) {
                    $('select[name="subcategory"]').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    });

    // $('select[name="subcategory"]').on('change', function () {
    //     let categoryId = $(this).val();
    //     jQuery.ajax({
    //         url: 'category/'+categoryId,
    //         type: "GET",
    //         dataType: "json",
    //         success: function (response) {
    //             $('select[name="subcategory"]').empty();
    //             $.each(response, function (key, value) {
    //                 $('select[name="subcategory"]').append('<option value="' + key + '">' + value + '</option>');
    //             });
    //         }
    //     });
    // });
})(jQuery);

