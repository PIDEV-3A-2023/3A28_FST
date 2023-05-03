// assets/js/search.js

$(function() {
    $('#evenement_search').select2({
        ajax: {
            url: '/evenements/search',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    term: params.term,
                };
            },
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: true,
        },
        minimumInputLength: 1,
    });
});
