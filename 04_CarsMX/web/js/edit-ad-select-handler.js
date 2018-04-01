(function validate() {
    //data
    const make = $('#make');
    const transmission = $('#transmission');
    const fuel = $('#fuel');
    const doors = $('#doors');
    const color = $('#color');

    //selectors
    const makeSelector = $('#car_ad_create_make');
    const transmissionSelector = $('#car_ad_create_transmission');
    const fuelSelector = $('#car_ad_create_fuel');
    const doorsSelector = $('#car_ad_create_doors');
    const colorSelector = $('#car_ad_create_color');

    //setting values
    setSelectorValue(make, makeSelector);
    setSelectorValue(transmission, transmissionSelector);
    setSelectorValue(fuel, fuelSelector);
    setSelectorValue(doors, doorsSelector);
    setSelectorValue(color, colorSelector);


    function setSelectorValue(dataField, selector) {
        selector.val(dataField.val()).prop('selected', true);
    }
}());