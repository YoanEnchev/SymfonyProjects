(function validate() {
//fields
    const fromYearBox = $('#search_car_fromYear');
    const toYearBox = $('#search_car_toYear');
    const maxPriceBox = $('#search_car_maxPrice');

//error boxes
    const fromYearError = $('#invalidFromYear');
    const toYearError = $('#invalidToYear');
    const maxPriceError = $('#invalidMaxPrice');

//attach events
    fromYearBox.keyup(() => validateYear(fromYearBox, fromYearError));
    toYearBox.keyup(() => validateYear(toYearBox, toYearError));
    maxPriceBox.keyup(validatePrice);


    function validateYear(boxValue, error) {
        if (isNaN(boxValue.val()) || boxValue.val() <= 1900 || boxValue.val() > (new Date()).getFullYear()) {
            error.show();
            removeErrorIfEmpty(boxValue, error);
        }
        else {
            error.hide();
        }
    }
    
    function validatePrice() {
        if( isNaN(maxPriceBox.val()) || maxPriceBox.val() <= 0 ||
            maxPriceBox.val() > 100000000) {
            maxPriceError.show();
            removeErrorIfEmpty(maxPriceBox, maxPriceError);
        }
        else {
            maxPriceError.hide();
        }
    }

    function removeErrorIfEmpty(boxValue, errorBox) {
        if(boxValue.val() === "") {
            errorBox.hide();
        }
    }
})();