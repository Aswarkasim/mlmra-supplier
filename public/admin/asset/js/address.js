(function ($) {
    //ajax select kota asal
    $('select[name="province_origin"]').on('change', function () {
        let provindeId = $(this).val();
        jQuery.ajax({
            url: 'address/cities/'+provindeId,
            type: "GET",
            dataType: "json",
            success: function (response) {
                $('select[name="city_origin"]').empty();
                $('select[name="city_origin"]').append('<option value="">-- pilih kota asal --</option>');
                $.each(response, function (key, value) {
                    $('select[name="city_origin"]').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    });

    $('select[name="city_origin"]').on('change', function () {
        let cityId = $(this).val();
        jQuery.ajax({
            url: 'address/districts/'+cityId,
            type: "GET",
            dataType: "json",
            success: function (response) {
                $('select[name="district_origin"]').empty();
                $('select[name="district_origin"]').append('<option value="">-- pilih kecamatan asal --</option>');
                $.each(response, function (key, value) {
                    $('select[name="district_origin"]').append('<option value="' + value[0] + '">' + value[cityId] + '</option>');
                });
            }
        });
    });
})(jQuery);

