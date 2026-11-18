(function () {
    'use strict';
    document.querySelectorAll('[data-ed-pricing]').forEach(function (block) {
        var prices = JSON.parse(block.querySelector('.ed-pricing__data').textContent);
        var dimensions = block.dataset.pricingDimensions ? JSON.parse(block.dataset.pricingDimensions) : ['course', 'duration', 'level'];
        var cards = block.querySelector('.ed-pricing__cards');
        var empty = block.querySelector('.ed-pricing__empty');
        var formatter = new Intl.NumberFormat('uk-UA', { maximumFractionDigits: 2 });
        function update() {
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
