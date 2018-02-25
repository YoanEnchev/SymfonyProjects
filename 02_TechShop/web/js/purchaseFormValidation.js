(function validate() {
    //fields:
    const quantityBox = $('#single_purchase_quantity');
    const phoneBox = $('#single_purchase_phone');
    const cityBox = $('#single_purchase_city');
    const addressBox = $('#single_purchase_address');
    const cardNumberBox = $('#single_purchase_creditCardNumber');
    const cardValidDate = $('#single_purchase_cardValidThrough');

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
    quantityBox.keyup(() => invalidAction(quantityBox, quantityRegex, quantityErrorBox, 'Quantity must be positive integer.'));
    quantityBox.keyup(() => validAction(quantityBox, quantityRegex, quantityErrorBox));

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
            checkIfExpired();
        }
    }

    function checkIfExpired() {
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
        else {
            cardValidDate.css('border-color', 'green');
            cardValidDate.css('background', '#EDF4EC');
            dateErrorBox.text('');
        }
    }
})();