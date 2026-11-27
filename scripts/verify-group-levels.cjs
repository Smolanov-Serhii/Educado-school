/** Frontend regression without browser dependencies: node scripts/verify-group-levels.cjs */
const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
let onChange;
let selectedLevel = { value: '0' };
const groupFormat = { value: 'groups', checked: true, closest: () => ({ classList: { toggle() {} } }), setAttribute() {} };
const formats = {
    querySelectorAll: () => [groupFormat],
    querySelector: () => groupFormat,
};
const panels = ['0', '1', '0', '1'].map(value => ({ dataset: { groupLevelPanel: value }, hidden: false }));
const packageNodes = [{ hidden: false }];
const groupNodes = [{ hidden: true }];
const cards = {};
const empty = {};
const price = { dataset: { price: '8' }, textContent: '' };
const block = {
    dataset: { pricingDimensions: '["format"]', groupFormat: 'groups' },
    querySelector(selector) {
        if (selector === '.ed-pricing__data') return { textContent: '{"individual":{"8":"559"}}' };
        if (selector === '.ed-pricing__cards') return cards;
        if (selector === '.ed-pricing__empty') return empty;
        if (selector === '[data-dimension="format"]') return formats;
        if (selector === '[data-dimension="format"] input:checked') return groupFormat;
        if (selector === '[data-group-level]:checked') return selectedLevel;
        return {};
    },
    querySelectorAll(selector) {
        return ({ '[data-dimension]': [], '[data-group-level-panel]': panels, '[data-package-only]': packageNodes, '[data-group-only]': groupNodes, '[data-price]': [price] })[selector] || [];
    },
    addEventListener(event, callback) { assert.equal(event, 'change'); onChange = callback; },
};
const source = fs.readFileSync('wp-content/themes/educadotheme/src/js/libraries/language-pricing.js', 'utf8');
vm.runInNewContext(source, { document: { querySelectorAll: () => [block] }, Intl });
assert.deepEqual(panels.map(panel => panel.hidden), [false, true, false, true]);
assert.equal(packageNodes[0].hidden, true);
assert.equal(groupNodes[0].hidden, false);
selectedLevel.value = '1'; onChange();
assert.deepEqual(panels.map(panel => panel.hidden), [true, false, true, false]);
onChange();
assert.deepEqual(panels.map(panel => panel.hidden), [true, false, true, false]);
groupFormat.value = 'individual'; onChange();
assert.equal(packageNodes[0].hidden, false);
assert.equal(groupNodes[0].hidden, true);
assert.equal(price.textContent, '559');
assert.equal(cards.hidden, false);
groupFormat.value = 'groups'; onChange();
assert.deepEqual(panels.map(panel => panel.hidden), [true, false, true, false]);
selectedLevel = null; onChange();
assert.deepEqual(panels.map(panel => panel.hidden), [false, true, false, true]);
assert.equal(source, fs.readFileSync('wp-content/themes/educadotheme/assets/js/libraries/language-pricing.js', 'utf8'));
console.log('PASS: group level switches both price and note panels, retains selection and supports legacy offers; source/runtime JS match.');
