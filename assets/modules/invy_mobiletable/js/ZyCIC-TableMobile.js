(function ($) {
    $.fn.tableMobile = function (optionsOrMethod) {

        if (typeof optionsOrMethod === 'string' && optionsOrMethod === 'destroy') {
            return this.each(function () {
                const $table = $(this);
                const tableID = $table.attr('id');
                const $container = $('#' + tableID + '-container');

                $table.off('.tableMobile');
                $container.off('.tableMobile');

                $('#' + tableID + '-loading').remove();

                $table.removeData('tableMobile');
            });
        }

        const settings = $.extend({
            pageSize: 10,
            ajax: {
                url: '',
                data: []
            }
        }, optionsOrMethod);

        return this.each(function () {
            const $table = $(this);
            const tableID = $table.attr('id');
            const $container = $('#' + tableID + '-container');

            let start = 0;
            let limit = settings.pageSize;
            let loading = false;
            let endData = false;
            const holdDuration = 500;

            // Hindari event double (bersihkan dulu event lama)
            $table.off('.tableMobile');
            $container.off('.tableMobile');

            /* ---------------- RENDER ---------------- */
            function renderLoader() {
                if ($('#' + tableID + '-loading').length === 0) {
                    const loading = $(
                        `<div class="table-mobile-loading" id="${tableID}-loading">
                            <img src="${BASE_URL}assets/img/loader/eclipse-loader.svg" alt="Loading...">
                        </div>`
                    );
                    $table.after(loading);
                }
            }

            function renderEmptyRows() {
                $table.find('tbody').html(`
                    <tr>
                        <td class="text-center"><i class="text-muted">Data empty</i></td>
                    </tr>
                `);
            }

            function renderRows(data) {
                let rows = '';
                data.forEach(item => {
                    rows += `<tr>`;
                    rows += `<td class="tm-detailed">${item.content}</td>`;
                    if (item.action) {
                        rows += `<td align="right">${item.action}</td>`;
                    }
                    rows += `</tr>`;
                });
                $table.find('tbody').append(rows);
            }

            /* ---------------- AJAX LOAD ---------------- */
            function loadData() {
                $.ajax({
                    url: settings.ajax.url,
                    type: 'GET',
                    dataType: 'json',
                    async: true,
                    data: {
                        start: start,
                        limit: limit,
                        ...settings.ajax.data
                    },
                    success: function (response) {
                        if (response.response) {
                            if (Array.isArray(response.data.data) && response.data.data.length > 0) {
                                renderRows(response.data.data);
                                start += limit;
                            } else if (start === 0) {
                                renderEmptyRows();
                            } else {
                                endData = true;
                            }
                        }
                        $('#' + tableID + '-loading').hide();
                        loading = false;
                    },
                    error: function () {
                        $('#' + tableID + '-loading').hide();
                        loading = false;
                    }
                });
            }

            /* ---------------- EVENT: SCROLL ---------------- */
            $container.on('scroll.tableMobile', function () {
                const container = $(this);
                const scrollTop = container.scrollTop();
                const scrollHeight = container.prop('scrollHeight');
                const containerHeight = container.height();

                if (scrollTop + containerHeight >= scrollHeight - 10) {
                    if (loading || endData) return;
                    loading = true;
                    $('#' + tableID + '-loading').show();
                    loadData();
                }
            });

            /* ---------------- EVENT: CLICK / HOLD ---------------- */
            $table.on('mousedown.tableMobile touchstart.tableMobile', '.tm-detailed', function () {
                const $this = $(this);
                $this.addClass('tm-row-active');

                if ($('td').hasClass('tm-rowhold-active')) {
                    $this.toggleClass('tm-rowhold-active');
                }

                $this.data('holdTimer', setTimeout(function () {
                    if (!$this.hasClass('tm-rowhold-active')) {
                        $this.addClass('tm-rowhold-active');
                    }
                }, holdDuration));
            });

            $table.on('mouseup.tableMobile mouseleave.tableMobile touchend.tableMobile', '.tm-detailed', function (e) {
                e.preventDefault();
                $(this).removeClass('tm-row-active');
                clearTimeout($(this).data('holdTimer'));
            });

            /* ---------------- INIT ---------------- */
            function init() {
                $table.addClass('table table-small');
                renderLoader();
                loadData();
            }

            init();

            $table.data('tableMobile', { destroy: () => $table.tableMobile('destroy') });
        });
    };
})(jQuery);