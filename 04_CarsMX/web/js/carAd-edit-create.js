(function validate() {
    //fields
    const priceBox = $('#car_ad_create_price');
    const enginePowerBox = $('#car_ad_create_enginePower');
    const capacityBox = $('#car_ad_create_cubicCapacity');
    const manufactureYear = $('#car_ad_create_manufactureYear');
    const mileageBox = $('#car_ad_create_mileage');

    //error boxes
    const priceError = $('#invalidPrice');
    const powerError = $('#invalidEnginePower');
    const capacityError = $('#invalidCapacity');
    const ordinaryYearError = $('#invalidYear-ordinary');
    const futureYearError = $('#invalidYear-future');
    const mileageError = $('#invalidMileage');

    const submitBtn = $('#car_ad_create_submit');

    //attach events
    priceBox.keyup(() => validateParametersAndPrice(priceBox, priceError));
    enginePowerBox.keyup(() => validateParametersAndPrice(enginePowerBox, powerError));
    capacityBox.keyup(() => validateParametersAndPrice(capacityBox, capacityError));
    mileageBox.keyup(() => validateParametersAndPrice(mileageBox, mileageError));
    manufactureYear.keyup(validateYear);
    $('input, textarea').keyup(allValidAction);


    function validateParametersAndPrice(textfield, errorBox) {
        if (isNaN(textfield.val()) || textfield.val() <= 0 || textfield.val() > 100000000) {
            errorBox.show();
        } else {
            errorBox.hide();
        }
    }

    function validateYear() {
        if (isNaN(manufactureYear.val()) || manufactureYear.val() <= 1900) {
            ordinaryYearError.show();
            futureYearError.hide();
        } else if (manufactureYear.val() > (new Date()).getFullYear()) {
            ordinaryYearError.hide();
            futureYearError.show();
        }
        else {
            ordinaryYearError.hide();
            futureYearError.hide();
        }
    }
    
    function allValidAction() {
        if (!(isNaN(priceBox.val()) || priceBox.val() <= 0 || priceBox.val() > 100000000 || //valid data
            isNaN(enginePowerBox.val()) || enginePowerBox.val() <= 0 || enginePowerBox.val() > 100000000 ||
            isNaN(capacityBox.val()) || capacityBox.val() <= 0 || capacityBox.val() > 100000000 ||
            isNaN(mileageBox.val()) || mileageBox.val() <= 0 || mileageBox.val() > 100000000 ||
            isNaN(manufactureYear.val()) || manufactureYear.val() <= 1900 ||
            manufactureYear.val() > (new Date()).getFullYear()))
        {
            submitBtn.removeAttr('disabled');
        }
        else {
            submitBtn.css('disabled', 'true');
        }
    }
})();