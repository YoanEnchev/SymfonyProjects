(function validate() {
    //fields:
    const phoneBox = $('#cart_purchase_phone');
    const cityBox = $('#cart_purchase_city');
    const addressBox = $('#cart_purchase_address');
    const cardNumberBox = $('#cart_purchase_creditCardNumber');
    const cardValidDate = $('#cart_purchase_inputDateValidThrough');

    //regexes:
    const quantityRegex = /^\d+$/;
    const emptyRegex = /.*/;
    const cardNumberRegex = /^\d{4} \d{4} \d{4} \d{4}$/;
    const cardDateRegex = /^(\d{1,2})\/(\d{4})$/;

    //error message boxes:
    const quantityErrorBox = $('#invalid-quantity');
    const cardNumberErrorBox = $('#invalid-card-number');
    const dateErrorBox = $('#invalid-card-date');

    //events:
    phoneBox.keyup(() => validAction(phoneBox, emptyRegex));
    cityBox.keyup(() => validAction(cityBox, emptyRegex));
    addressBox.keyup(() => validAction(addressBox, emptyRegex));

    cardNumberBox.keyup(() => invalidAction(cardNumberBox, cardNumberRegex, cardNumberErrorBox, 'Invalid Format. Example: 1234 5678 9101 1121'));
    cardNumberBox.keyup(() => validAction(cardNumberBox, cardNumberRegex, cardNumberErrorBox));

    cardValidDate.keyup(() => invalidAction(cardValidDate, cardDateRegex, dateErrorBox, 'Invalid Format. Example: 01/2019'));
    cardValidDate.keyup(() => validAction(cardValidDate, cardDateRegex, dateErrorBox));

    function invalidAction(textfield ,regex, errorBox, message) {
        if (!regex.test(textfield.val())) {
            textfield.css('border-color', 'red');
            textfield.css('background', '#FAF4F4');
            errorBox.text(message);
        }
    }

    function validAction(textfield ,regex, errorBox) {
        if (regex.test(textfield.val())) {
            textfield.css('border-color', 'green');
            textfield.css('background', '#EDF4EC');
            errorBox.text('');

            if(textfield === cardValidDate) {
                checkIfExpiredAndValid();
            }
        }
    }

    function checkIfExpiredAndValid() {
        let inputDate = cardValidDate.val();
        let monthAndYear = inputDate.split("/");
        let month = monthAndYear[0];
        let year = monthAndYear[1];

        let today = new Date();
        let currentYear = today.getFullYear();
        let currentMonth = today.getMonth()+1;

        if(currentYear > year || (currentYear == year && currentMonth > month)) {
            cardValidDate.css('border-color', 'red');
            cardValidDate.css('background', '#FAF4F4');
            dateErrorBox.text('Card Has Expired.');
        }

        else if(month > 12 || month < 1) {
            cardValidDate.css('border-color', 'red');
            cardValidDate.css('background', '#FAF4F4');
            dateErrorBox.text('Invalid month.');
        }

        else {
            cardValidDate.css('border-color', 'green');
            cardValidDate.css('background', '#EDF4EC');
            dateErrorBox.text('');
        }
    }
})();