$(function () {

    // column filter form submit
    $('[data-datasheet-form]').on('submit', function (e) {
        var datasheetId = $(this).parents('[data-datasheet]').attr('data-datasheet');
        // remove unused filters
        $('[data-datasheet="' + datasheetId + '"] [data-datasheet-column-filter]:hidden').remove();

        return true;
    })

    // column filter selector
    $('[data-datasheet-column-filter-selector]').on('change', function () {
        var datasheetId = $(this).parents('[data-datasheet]').attr('data-datasheet');
        var columnName = $(this).attr('data-datasheet-column-filter-selector');
        var filterName = $(this).val();
        $('[data-datasheet="' + datasheetId + '"] [data-datasheet-column-filters="' + columnName + '"]').children().hide();
        console.log('[data-datasheet="' + datasheetId + '"] [data-datasheet-column-filters="' + columnName + '"] [data-datasheet-column-filter="' + filterName + '"]');
        $('[data-datasheet="' + datasheetId + '"] [data-datasheet-column-filters="' + columnName + '"] [data-datasheet-column-filter="' + filterName + '"]')
            .show()
    }).change();

})