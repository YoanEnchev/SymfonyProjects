$(function () {
    $("#rateUser").rateYo({
        rating: 0,
        fullStar: true,
        starWidth: "30px"
    });

    $("#average").rateYo({
        rating: $('#average-grade').val(),
        readOnly: true,
        starWidth: "30px"
    });

    $(".stars-1").rateYo({
        rating: 1,
        readOnly: true,
        starWidth: "15px"
    });

    $(".stars-2").rateYo({
        rating: 2,
        readOnly: true,
        starWidth: "15px"
    });

    $(".stars-3").rateYo({
        rating: 3,
        readOnly: true,
        starWidth: "15px"
    });

    $(".stars-4").rateYo({
        rating: 4,
        readOnly: true,
        starWidth: "15px"
    });

    $(".stars-5").rateYo({
        rating: 5,
        readOnly: true,
        starWidth: "15px"
    });


    const ratingStars = $('#rateUser');
    const ratingValue = $('#app_bundle_review_type_grade');

    ratingStars.click(setRateValue);

    function setRateValue() {
        let changesToRates = ratingStars.find('.jq-ry-rated-group.jq-ry-group');
        let stars = Math.ceil((changesToRates.width() / (changesToRates.parent().width())) * 5);
        ratingValue.val(stars);
    }
});