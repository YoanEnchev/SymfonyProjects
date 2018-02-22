$(function () {
    $("#rateUser").rateYo({
        rating: 0,
        fullStar: true
    });

    $("#average").rateYo({
        rating: $('#average-grade').val(),
        readOnly: true
    });

const ratingStars = $('#rateUser');
const ratingValue = $('#app_bundle_review_type_grade');

ratingStars.click(setRateValue);

function setRateValue() {
    let changesToRates = ratingStars.find('.jq-ry-rated-group.jq-ry-group');
    let stars = Math.ceil((changesToRates.width() /(changesToRates.parent().width())) * 5);
    ratingValue.val(stars);
}
});