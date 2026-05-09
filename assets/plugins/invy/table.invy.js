(function($) {
    $.fn.ssDtTable = function(options) {
        var settings = $.extend({
            identitiy: '',
            table_main: true,
            url: 'question',
            style: {
                orderableCol: {},
                colNowrap: {},
                colAlRight: {},
                colAlCenter: {},
                colAlLeft: {},
                colW20: {},
                colW40: {},
                colW60: {},
            }
        }, options);

        return this.each(function() {
            let module = settings.identitiy

            // define local session for table search
            let STORAGE_QUERY = "",
                TABLE_SRCS = "",
                STATE_REDIRECT = "";

            if (settings.table_main) {
                module = jsURI[1].toUpperCase();

                STORAGE_QUERY = "WHERECLAUSE_" + module;
                TABLE_SRCS = "TABLE_SRCS_" + module;
                STATE_REDIRECT = "STATE_REDT" + module;
            }

            function setLocalStorage(key, value) {
                if (settings.table_main) {
                    localStorage.setItem(key, value)
                }
            }

            const $tableID = $(this);

            if ($("#inbox").children().length) {
                $("#inbox").children().children().attr("id", "tb-inbox-" + module)
            }

            // Run function state on load page
            state()

            function state() {
                // Run function for build table search
                searchColBuilder()

                let srcSchemeSession = localStorage.getItem(TABLE_SRCS);
                let wcQuerySession = localStorage.getItem(STORAGE_QUERY);
                let lastSearchColumn = '';

                // Setup last search on table search
                if (srcSchemeSession) {
                    if (isJson(srcSchemeSession)) {
                        let loop = 0;
                        $.each(srcSchemeSession = JSON.parse(srcSchemeSession), function(i) {
                            let column = srcSchemeSession[i].column;
                            let colValue = srcSchemeSession[i].colValue;

                            if (colValue != "") {
                                $tableID.find("#col-" + column).val(srcSchemeSession[i].colValue)

                                if (loop == 0) {
                                    lastSearchColumn = column;
                                }
                                loop += 1;
                            }

                        })
                    }
                }

                // Focus and select all last search column
                if (lastSearchColumn) {
                    const $input = $tableID.find('input[name="' + lastSearchColumn + '"]');
                    setTimeout(function() {
                        $input.focus();
                        $input.select();
                    }, 100);
                }

                // Run refresh render table with last wc query
                setTimeout(function() {
                    refreshTable(wcQuerySession);
                }, 100);
            }

            /* -------------- Function for render column to search field -------------- */
            function searchColBuilder() {
                /* 
                    Column attributs :
                    - class "th-src" on table row
                    - data-type {optional}
                */

                // Render search field
                $tableID.find('thead .th-src th').each(function() {
                    let columnText = $(this).text();
                    let inputType = $(this).attr("col-type");
                    if (inputType === '') {
                        inputType = "text";
                    }

                    let html = '';
                    if (columnText != '') {
                        let inputId = columnText.replace('.', '-');
                        let inputTitle = columnText.replace('-', '.');
                        let identitiy = bin2hex(columnText);

                        if (inputType == "datetimerange") {
                            html = `<div class="inHvr" title="Colum Name : ${inputTitle}" id="hvr-${inputId}">`;
                            html += `<input class="form-control form-control-border field-col-search float-right table-datetimerange" name="${identitiy}" id="col-${identitiy}" value="" type="text" data-type="daterange">`;
                            html += `</div>`;
                        } else {
                            html = `<div class="inHvr" title="Colum Name : [${inputTitle}]" id="hvr-${inputId}">`;
                            html += `<input class="form-control form-control-border field-col-search" name="${identitiy}" id="col-${identitiy}" type="${inputType}" />`;
                            html += `</div>`;
                        }

                        $(this).html(html);
                    }
                });

                // Init datetimerange
                if ($('.table-datetimerange').length) {
                    $('.table-datetimerange').daterangepicker({
                        autoUpdateInput: false,
                        timePicker: true,
                        locale: {
                            cancelLabel: 'Clear',
                            timePicker: true,
                            format: 'YYYY/MM/DD HH:mm:ss'
                        }
                    });

                    $('.table-datetimerange').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('YYYY/MM/DD HH:mm:ss') + ' - ' + picker.endDate.format('YYYY/MM/DD HH:mm:ss'));
                        const buildWhereClause = wcQueryBuilder("table");
                        refreshTable(buildWhereClause);
                    });

                    $('.table-datetimerange').on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('')
                        picker.setStartDate({})
                        picker.setEndDate({})
                        const buildWhereClause = wcQueryBuilder("table");
                        refreshTable(buildWhereClause);
                    });
                }
            }
            /* ------------------------------------------------------------------------ */

            // Start section switch page in main
            var switchPageQuery = '';
            // $(".page-switch").on('click', function () {

            //     if ($(this).attr('href') === '#inbox') {
            //         $tableID = "#tb-inbox-";
            //         $(".th-src").hide()
            //     } else {
            //         $tableID = "#tb-";
            //         $(".th-src").show()
            //     }

            //     searchColBuilder()
            //     let direcPage = $(this).attr('href')
            //     let direcPageDataQuery = $(this).data('inboxtabquery')
            //     let wcSesion = localStorage.getItem(STORAGE_QUERY);

            //     if (direcPage === "#main") {
            //         switchPageQuery = "";
            //     } else if ((direcPageDataQuery != undefined) || (direcPageDataQuery != "")) {
            //         let mergeQuery = wcSesion;
            //         if (wcSesion != "") {
            //             mergeQuery += " AND " + direcPageDataQuery;
            //         } else {
            //             mergeQuery += direcPageDataQuery;
            //         }
            //         switchPageQuery = wcQueryBuilder("external", mergeQuery);
            //     }

            //     refreshTable(wcSesion)
            // });
            // End section switch page in main

            function wcQueryBuilder(source = '', value = '') {
                let output = false;
                let queryBuilderDatas = [];

                if (typeof source === 'string' && source === "table") {
                    let srcColStructure = {};

                    let loop = 0;
                    $tableID.find('.field-col-search').each(function() {
                        let $searchField = $(this)
                        let fieldValue = $searchField.val()
                        if (typeof fieldValue === 'string' && fieldValue !== '') {
                            srcColStructure[$searchField.attr("name")] = fieldValue;
                            queryBuilderDatas[loop] = {
                                column: $searchField.attr("name"),
                                colValue: fieldValue
                            }

                            if ($searchField.data("type") == "daterange") {
                                queryBuilderDatas[loop].type = "between";
                            }

                            loop += 1;
                        }
                    });

                } else if (typeof source === 'string' && source === "query") {
                    queryBuilderDatas = $('#where-clause-view').val();
                } else if (typeof source === 'string' && source === "external") {
                    queryBuilderDatas = value;
                }

                if (queryBuilderDatas !== '[]') {
                    $.ajax({
                        url: BASE_URL + jsURI[1] + '/query_builder',
                        type: 'GET',
                        dataType: 'json',
                        async: false,
                        data: {
                            search: queryBuilderDatas,
                            row_status: jsURI[2] == 'recycle' ? 1 : 0,
                        },
                        success: function(response) {
                            if (response.status) {
                                if (response.data != null) {
                                    output = response.data.wc_query;
                                    setLocalStorage(TABLE_SRCS, JSON.stringify(queryBuilderDatas))
                                } else {
                                    output = '';
                                }
                            } else {
                                output = localStorage.getItem(STORAGE_QUERY);
                                let errInfo = $.getErrorInfo(response)

                                if (!$.empty(errInfo)) {
                                    $.invyAlert({
                                        title: errInfo.code,
                                        text: errInfo.message,
                                        icon: errInfo.level,
                                        cabtn: errInfo.cabtn,
                                        catext: errInfo.catext
                                    })
                                }
                            }
                        },
                        error: function() {
                            let errInfo = $.getErrorInfo('SYS-BUG-E001')

                            if (!$.empty(errInfo)) {
                                $.invyAlert({
                                    title: errInfo.code,
                                    text: errInfo.message,
                                    icon: errInfo.level,
                                    cabtn: errInfo.cabtn,
                                    catext: errInfo.catext
                                })
                            }
                        }
                    });
                } else {
                    output = '';
                }

                return output;
            }

            function refreshTable(wcQuery) {
                $.loader('show')
                $tableID.DataTable().destroy();
                renderDataTable(wcQuery);
            }

            function renderDataTable(wcQuery = null) {
                if (wcQuery == null) {
                    wcQuery = ''
                } else {
                    $.wcQueryView('set', wcQuery)
                }

                $tableID.DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: settings.url,
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            whereclause: (((switchPageQuery !== undefined) && (switchPageQuery !== '')) ? switchPageQuery : wcQuery),
                            page: ((switchPageQuery !== '') ? 'inbox' : 'main'),
                            real_page: jsURI[2],
                            row_status: jsURI[2] == 'recycle' ? 0 : 1,
                        }
                    },
                    fnDrawCallback: function(response) {
                        response = response.json;
                        if ((response.recordsFiltered === 0) && (switchPageQuery === '')) {
                            // if (jsURI[2] != 'recycle') {
                            //     if ((localStorage.getItem(STATE_REDIRECT) != jsURI[1].toUpperCase()) && (localStorage.getItem(STATE_REDIRECT) == '')) {
                            //         setLocalStorage(STATE_REDIRECT, jsURI[1].toUpperCase())
                            //         window.location.replace(BASE_URL + jsURI[1] + '/edit/' + response.firstItem);
                            //     } else {

                            //     }
                            // }
                            localStorage.removeItem(STORAGE_QUERY);
                            localStorage.removeItem(TABLE_SRCS);
                        } else {
                            setLocalStorage(STATE_REDIRECT, '')
                            setLocalStorage(STORAGE_QUERY, wcQuery)
                        }

                        $.loader('hide')
                    },
                    columnDefs: [{
                            targets: settings.style.orderableCol,
                            orderable: false
                        },
                        {
                            targets: settings.style.colNowrap,
                            className: 'colum-nowrap'
                        },
                        {
                            targets: settings.style.colAlRight,
                            className: 'colum-right'
                        },
                        {
                            targets: settings.style.colAlLeft,
                            className: 'colum-left'
                        },
                        {
                            targets: settings.style.colAlCenter,
                            className: 'colum-center'
                        },
                        {
                            targets: settings.style.colW20,
                            width: '20%'
                        },
                        {
                            targets: settings.style.colW40,
                            width: '40%'
                        },
                        {
                            targets: settings.style.colW60,
                            width: '60%'
                        },
                    ],
                    order: []
                });
            }

            // function clearTableSearch() {
            //     $('.field-col-search').each(function () {
            //         $(this).val('');
            //     });
            // }

            // // Start button clear and referh table
            // $('#clear-filtering').on('click', function () {
            //     if (!$('#clear-filtering').hasClass('disabled')) {
            //         $('#clear-filtering').addClass('disabled');
            //         clearTableSearch();
            //         $('#where-clause-view').html('');
            //         $('#where-clause-view').val('');
            //         refreshTable('');
            //         localStorage.removeItem(STORAGE_QUERY);
            //         localStorage.removeItem(TABLE_SRCS);
            //     }
            // });

            $tableID.find('.field-col-search').on('keyup', function(event) {
                if (event.keyCode === 13) {
                    const wcQuery = wcQueryBuilder('table');
                    refreshTable(wcQuery);
                    $('#col-' + $(this).attr('name')).select();
                }
            })

            $tableID.find('#btn-col-search').on('click', function() {
                const wcQuery = wcQueryBuilder('table');
                refreshTable(wcQuery);
            })

            // $('#excute-whereclauseuser').on('click', function () {
            //     const buildWhereClause = wcQueryBuilder('query');
            //     if (jsURI[2] != 'main') {
            //         setLocalStorage(STORAGE_QUERY, buildWhereClause)
            //         window.location.replace(BASE_URL + jsURI[1] + '/main');
            //     }

            //     clearTableSearch();
            //     refreshTable(buildWhereClause);
            //     $('#modal-where-clause').modal('toggle');
            // });
            // // End button clear and referh table
        })
    };
})(jQuery);