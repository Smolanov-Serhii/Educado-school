(function () {
    'use strict';
    document.querySelectorAll('[data-ed-pricing]').forEach(function (block) {
        var prices = JSON.parse(block.querySelector('.ed-pricing__data').textContent);
        var dimensions = block.dataset.pricingDimensions ? JSON.parse(block.dataset.pricingDimensions) : ['course', 'duration', 'level'];
        var cards = block.querySelector('.ed-pricing__cards');
        var empty = block.querySelector('.ed-pricing__empty');
        var formatter = new Intl.NumberFormat('uk-UA', { maximumFractionDigits: 2 });
        var groups = Array.from(block.querySelectorAll('[data-dimension]'));
        var availableRows = Object.keys(prices).map(function (key) {
            return key.split('|');
        });
        var groupFormat = block.dataset.groupFormat || '';
        var formatGroup = block.querySelector('[data-dimension="format"]');
        var formatIndex = dimensions.indexOf('format');
        function update() {
            var selectedLevel = block.querySelector('[data-group-level]:checked');
            block.querySelectorAll('[data-group-level-panel]').forEach(function (panel) {
                panel.hidden = panel.dataset.groupLevelPanel !== (selectedLevel ? selectedLevel.value : '0');
            });
            if (formatGroup && groupFormat) {
                var formatInputs = Array.from(formatGroup.querySelectorAll('input[type="radio"]'));
                formatInputs.forEach(function (input) {
                    var available = input.value === groupFormat || availableRows.some(function (row) { return row[formatIndex] === input.value; });
                    input.disabled = !available;
                    input.setAttribute('aria-disabled', String(!available));
                    input.closest('label').classList.toggle('ed-pricing__choice--disabled', !available);
                });
                if (!formatGroup.querySelector('input:checked:not(:disabled)')) {
                    formatInputs.forEach(function (input) { input.checked = false; });
                    var firstFormat = formatInputs.find(function (input) { return !input.disabled; });
                    if (firstFormat) firstFormat.checked = true;
                }
            }
            var selectedFormat = formatGroup && formatGroup.querySelector('input:checked');
            var isGroup = Boolean(groupFormat && selectedFormat && selectedFormat.value === groupFormat);
            block.querySelectorAll('[data-package-only]').forEach(function (node) { node.hidden = isGroup; });
            block.querySelectorAll('[data-group-only]').forEach(function (node) { node.hidden = !isGroup; });
            if (isGroup) return;
            var compatible = availableRows;
            // Earlier visible groups constrain later ones. Keep a valid selection where possible.
            groups.forEach(function (group) {
                var index = dimensions.indexOf(group.dataset.dimension);
                var inputs = Array.from(group.querySelectorAll('input[type="radio"]'));
                var selected = group.querySelector('input:checked');
                inputs.forEach(function (input) {
                    var available = (group.dataset.dimension === 'format' && input.value === groupFormat) || compatible.some(function (row) {
                        return row[index] === input.value;
                    });
                    input.disabled = !available;
                    input.setAttribute('aria-disabled', String(!available));
                    input.closest('label').classList.toggle('ed-pricing__choice--disabled', !available);
                });
                if (!selected || selected.disabled) {
                    inputs.forEach(function (input) { input.checked = false; });
                    selected = inputs.find(function (input) { return !input.disabled; });
                    if (selected) selected.checked = true;
                }
                if (selected) {
                    compatible = compatible.filter(function (row) {
                        return row[index] === selected.value;
                    });
                }
            });
            var ids = dimensions.map(function (dimension) {
                var selected = block.querySelector('[data-dimension="' + dimension + '"] input:checked');
                return selected ? selected.value : '';
            });
            var row = prices[ids.join('|')];
            cards.hidden = !row;
            empty.hidden = Boolean(row);
            block.querySelectorAll('[data-price]').forEach(function (price) {
                price.textContent = row ? formatter.format(Number(row[price.dataset.price])) : '—';
            });
        }
        block.addEventListener('change', update);
        update();
    });
})();
