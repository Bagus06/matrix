$(document).ready(function() {
    const country = $('select[name="country_id"]');
    const state = $('select[name="state_id"]');
    const city = $('select[name="city_id"]');
    const district = $('select[name="district_id"]');
    const village = $('select[name="village_id"]');

    geolocation({
        url: BASE_URL + `geolocation/geo`,
        selector: 'select[name="country_id"]',
        geo: 'geo_countries',
    })
    geolocation({
        url: BASE_URL + `geolocation/geo`,
        selector: 'select[name="state_id"]',
        geo: 'geo_states',
        relation_id: country.val()
    })
    geolocation({
        url: BASE_URL + `geolocation/geo`,
        selector: 'select[name="city_id"]',
        geo: 'geo_cities',
        relation_id: state.val()
    })
    geolocation({
        url: BASE_URL + `geolocation/geo`,
        selector: 'select[name="district_id"]',
        geo: 'geo_districts',
        relation_id: city.val()
    })
    geolocation({
        url: BASE_URL + `geolocation/geo`,
        selector: 'select[name="village_id"]',
        geo: 'geo_villages',
        relation_id: district.val()
    })

    country.on('select2:select', function(e) {
        state.val(null).trigger('change');
        city.val(null).trigger('change');
        district.val(null).trigger('change');
        village.val(null).trigger('change');

        let data = e.params.data;
        geolocation({
            url: BASE_URL + `geolocation/geo`,
            selector: 'select[name="state_id"]',
            geo: 'geo_states',
            relation_id: data.id
        })
    });

    state.on('select2:select', function(e) {
        city.val(null).trigger('change');
        district.val(null).trigger('change');
        village.val(null).trigger('change');

        let data = e.params.data;
        geolocation({
            url: BASE_URL + `geolocation/geo`,
            selector: 'select[name="city_id"]',
            geo: 'geo_cities',
            relation_id: data.id
        })
    });

    city.on('select2:select', function(e) {
        district.val(null).trigger('change');
        village.val(null).trigger('change');

        let data = e.params.data;
        geolocation({
            url: BASE_URL + `geolocation/geo`,
            selector: 'select[name="district_id"]',
            geo: 'geo_districts',
            relation_id: data.id
        })
    });

    district.on('select2:select', function(e) {
        village.val(null).trigger('change');

        let data = e.params.data;
        geolocation({
            url: BASE_URL + `geolocation/geo`,
            selector: 'select[name="village_id"]',
            geo: 'geo_villages',
            relation_id: data.id
        })
    });

    function geolocation(options) {
        var settings = $.extend({
            url: '',
            selector: '.select2',
            geo: 'geo_countries',
            relation_id: 0,
            selected: '',
            disabled: {},
        }, options);
        let countFiltered = 0;

        $(settings.selector).select2({
            ajax: {
                url: settings.url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        geo: settings.geo,
                        relation_id: settings.relation_id,
                        page: params.page || 0,
                        selected: settings.selected,
                        disabled: settings.disabled,
                        count_filtered: countFiltered
                    };
                },
                processResults: function(data, params) {
                    countFiltered = data.count_filtered || 0
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Search for a repository',
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo(repo) {
            if (repo.loading) {
                return repo.text;
            }

            return repo.name;
        }

        function formatRepoSelection(repo) {
            return repo.name || repo.text;
        }
    }
})