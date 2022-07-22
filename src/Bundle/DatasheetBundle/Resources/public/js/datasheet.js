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
        $('[data-datasheet="' + datasheetId + '"] [data-datasheet-column-filters="' + columnName + '"] [data-datasheet-column-filter="' + filterName + '"]')
            .show()
    }).change();

    $('[data-datasheet]').each(function () {
        var datasheetId = $(this).data('datasheet');
        var datasheetForm = $(this).find('[data-datasheet-form="' + datasheetId + '"]');

        // sort filter
        var filterSortTypeControl = $(this).find('[name="ds' + datasheetId + '[filter][sort][type]"]');

        if (filterSortTypeControl.length) {
            var filterSortByControl = $(this).find('[name="ds' + datasheetId + '[filter][sort][by]"]');
            var filterSortDirectionControl = $(this).find('[name="ds' + datasheetId + '[filter][sort][direction]"]');

            if (filterSortByControl.val()) {
                var symbol = filterSortDirectionControl.val() == 'descending' ? '&#128317;' : '&#128316;';
                $('[data-datasheet="' + datasheetId + '"] [data-datasheet-column-title="' + filterSortByControl.val() + '"]')
                    .prepend(symbol + ' ');
            }

            $('[data-datasheet="' + datasheetId + '"] [data-datasheet-column-title]').addClass('cursor-pointer').click(function () {
                var columnName = $(this).attr('data-datasheet-column-title');
                var sortDirection = 'ascending';

                if (filterSortByControl.val() == columnName) {
                    sortDirection = filterSortDirectionControl.val() == 'descending' ? 'ascending' : 'descending';
                }
                filterSortByControl.val(columnName);
                filterSortDirectionControl.val(sortDirection);
                datasheetForm.submit();
            })
        }

        // pagination
        var paginationContainer = $(this).find('[data-datasheet-items-total]');
        var paginationPageControl = $(this).find('[name="ds' + datasheetId + '[filter][pagination][page]"]');
        var paginationLimitControl = $(this).find('[name="ds' + datasheetId + '[filter][pagination][limit]"]');
        var itemsTotal = paginationContainer.attr('data-datasheet-items-total');
        var page = paginationPageControl.val();
        var limit = paginationLimitControl.val();
        var pagesTotal = Math.ceil(itemsTotal / limit);
        paginationContainer.append(getPaginationElement('First', 1));

        for (var i = 1; i <= pagesTotal; i++) {
            paginationContainer.append(getPaginationElement(i, i, i == page));
        }
        paginationContainer.append(getPaginationElement('Last', pagesTotal));

        $('[data-datasheet-items-total] li').click(function () {
            paginationPageControl.val($(this).attr('data-page'));
            datasheetForm.submit();
        })
    })

    function getPaginationElement(text, page, isActive = false) {
        var liElement = $('<li>');
        var aElement = $('<a>');
        liElement
            .addClass('paginate_button page-item cursor-pointer')
            .attr('data-page', page);

        if (isActive) {
            liElement.addClass('active');
        }
        aElement
            .addClass('page-link')
            .text(text)
            .appendTo(liElement);

        return liElement;
    }
})