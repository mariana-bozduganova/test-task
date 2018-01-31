var sizeValue = '';
var select = document.getElementById('size');

function loadSizes(forGender)
{
    forGender = typeof forGender !== 'undefined' ? forGender : getSelectedGender();
    var options = sizes[forGender];

    setSizeValue(options);

    clearSize();
    clearPrice();

    for (var i = 0; i < options.length; i++) {
        select.appendChild(generateSelectOption(options[i], sizeValue));
    }

    loadPrice(sizeValue);
}

function loadPrice(forSize)
{
    forSize = typeof forSize !== 'undefined' && forSize ? forSize : getSelectedSize();

    var priceForSubscriptionCriteria = prices[getSelectedGender() + '-' + forSize];

    document.getElementById('price').innerHTML = priceForSubscriptionCriteria;
    document.getElementById('selected_price').value = priceForSubscriptionCriteria;
}

function generateSelectOption(value, defaultValue)
{
    var el = document.createElement('option');
    el.textContent = value;
    el.value = value;
    if (value === defaultValue) {
        el.selected = "selected";
    }

    return el;
}

function getSelectedGender()
{
    return document.querySelector('input[name="gender"]:checked').value;
}

function getSelectedSize()
{
    var selectedSizeOption = select.options[select.selectedIndex];
    if (selectedSizeOption) {
        return selectedSizeOption.value;
    }

    return selectDefaultSize();
}

function selectDefaultSize()
{
    var defaultSize = select.options[0];
    defaultSize.selected = 'selected';

    return defaultSize.value;
}

function setSizeValue(newSizeOptions)
{
    if ( ! select.options.length) {
        sizeValue = preSelectedSize ? preSelectedSize : '';
        return;
    }

    sizeValue = getSelectedSize();
    if (newSizeOptions.indexOf(sizeValue) === -1) {
        sizeValue = '';
    }
}

function clearSize()
{
    select.innerHTML ='';
}

function clearPrice()
{
    document.getElementById('price').innerHTML = '';
}

loadSizes();
loadPrice();