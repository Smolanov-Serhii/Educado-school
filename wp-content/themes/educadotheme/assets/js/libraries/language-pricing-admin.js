(function ($, acf) {
    'use strict';
    if (!acf) return;
    var dimensions = window.educadoPricingAdmin.dimensions || ['course', 'duration', 'level'];
    var busy = false;

    function fieldInput($row, name) {
        return $row.find('[data-key="field_edp_' + name + '"] :input').first();
    }

    function optionId() {
        var bytes = new Uint8Array(8);
        window.crypto.getRandomValues(bytes);
        return 'opt_' + Array.from(bytes, function (byte) {
            return byte.toString(16).padStart(2, '0');
        }).join('');
    }

    function options() {
        var lists = {};
        dimensions.forEach(function (dimension) {
            lists[dimension] = [];
            var seen = new Set();
            var field = acf.getField('field_edp_' + dimension + '_options');
            var enabled = acf.getField('field_edp_' + dimension + '_enabled');
            if (!field || (enabled && !enabled.$el.find('input[type="checkbox"]').prop('checked'))) return;
            field.$rows().each(function () {
                var $row = $(this);
                var $id = fieldInput($row, dimension + '_id');
                if (!$id.val() || seen.has($id.val())) $id.val(optionId());
                seen.add($id.val());
                var label = fieldInput($row, dimension + '_label').val().trim();
                if (label) lists[dimension].push({ id: $id.val(), label: label });
            });
        });
        return lists;
    }

    function refresh() {
        if (busy) return;
        var table = acf.getField('field_edp_price_rows');
        if (!table) return;
        busy = true;
        var lists = options();
        table.$el.addClass('edp-price-table');
        dimensions.forEach(function (dimension) {
            table.$el.find('[data-key="field_edp_' + dimension + '_ref"]').toggle(lists[dimension].length > 0);
        });
        table.$rows().each(function () {
            var $row = $(this);
            dimensions.forEach(function (dimension) {
                var $select = fieldInput($row, dimension + '_ref');
                var current = $select.val();
                $select.empty().append(new Option('Оберіть варіант', ''));
                lists[dimension].forEach(function (option) {
                    $select.append(new Option(option.label, option.id));
                });
                if (current && !lists[dimension].some(function (option) { return option.id === current; })) {
                    $select.append(new Option('Видалений варіант — ціни збережено', current));
                }
                $select.val(current || (dimension === 'format' && lists.format.length ? lists.format[0].id : ''));
            });
        });
        var $tools = table.$el.children('.edp-tools');
        if (!$tools.length) {
            $tools = $('<div class="edp-tools"><button type="button" class="button">Створити відсутні комбінації</button><p role="status" aria-live="polite"></p></div>');
            table.$el.children('.acf-label').after($tools);
            $tools.find('button').on('click', generate);
        }
        var expected = dimensions.filter(function (d) { return lists[d].length; }).reduce(function (count, d) { return count * lists[d].length; }, 1);
        $tools.find('p').text('Варіантів комбінацій: ' + expected + '. Цін для заповнення: ' + expected * 3 + '. Введені ціни зберігаються під час перейменування та зміни порядку.');
        busy = false;
    }

    function generate() {
        refresh();
        var lists = options();
        var table = acf.getField('field_edp_price_rows');
        var active = dimensions.filter(function (d) { return lists[d].length; });
        var existing = new Set();
        table.$rows().each(function () {
            var $row = $(this);
            existing.add(active.map(function (dimension) { return fieldInput($row, dimension + '_ref').val(); }).join('|'));
        });
        var combinations = [{}];
        active.forEach(function (dimension) {
            var expanded = [];
            combinations.forEach(function (combination) {
                lists[dimension].forEach(function (option) {
                    var next = Object.assign({}, combination);
                    next[dimension] = option.id;
                    expanded.push(next);
                });
            });
            combinations = expanded;
        });
        var missing = combinations.filter(function (combination) {
            return !existing.has(active.map(function (d) { return combination[d]; }).join('|'));
        });
        var limit = Number(window.educadoPricingAdmin && window.educadoPricingAdmin.inputLimit);
        var inputCount = $('#post').serializeArray().length;
        if (limit > 0 && inputCount + missing.length * (dimensions.length + 5) + 100 > limit) {
            table.$el.find('.edp-tools p').text('Для цієї кількості комбінацій потрібно збільшити max_input_vars на сервері. Зверніться до розробника. Наявні ціни збережено.');
            return;
        }
        busy = true;
        missing.forEach(function (values) {
            var $row = table.add();
            active.forEach(function (dimension) {
                var $select = fieldInput($row, dimension + '_ref');
                lists[dimension].forEach(function (option) {
                    $select.append(new Option(option.label, option.id));
                });
                $select.val(values[dimension]);
            });
        });
        busy = false;
        refresh();
        table.$el.find('.edp-tools p').append(document.createTextNode(' Додано рядків: ' + missing.length + '.'));
    }

    acf.addAction('ready', refresh);
    acf.addAction('append', refresh);
    acf.addAction('remove', function () { window.setTimeout(refresh, 0); });
    acf.addAction('sortstop', refresh);
    $(document).on('input change', '[data-key^="field_edp_"][data-key$="_label"] input, [data-key^="field_edp_"][data-key$="_enabled"] input', refresh);
    acf.addFilter('validation_complete', function (result, $form) {
        var limit = Number(window.educadoPricingAdmin && window.educadoPricingAdmin.inputLimit);
        if (acf.getField('field_edp_price_rows') && limit > 0 && $form.serializeArray().length + 50 > limit) {
            result.valid = false;
            result.errors = Array.isArray(result.errors) ? result.errors : [];
            result.errors.push({ input: '', message: 'Не вдалося безпечно зберегти всі поля. Попросіть розробника збільшити max_input_vars на сервері.' });
        }
        return result;
    });
})(jQuery, window.acf);
