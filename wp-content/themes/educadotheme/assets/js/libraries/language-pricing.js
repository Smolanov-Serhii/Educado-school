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
        function update() {
            var compatible = availableRows;
            // Earlier visible groups constrain later ones. Keep a valid selection where possible.
            groups.forEach(function (group) {
                var index = dimensions.indexOf(group.dataset.dimension);
                var inputs = Array.from(group.querySelectorAll('input[type="radio"]'));
                var selected = group.querySelector('input:checked');
                inputs.forEach(function (input) {
                    var available = compatible.some(function (row) {
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
