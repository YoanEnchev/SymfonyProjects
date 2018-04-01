$(function () {
    $("#rateUser").rateYo({
        rating: 0,
        fullStar: true,
        starWidth: "30px"
    });

    $("#average").rateYo({
        rating: $('#average-grade').val(),
        readOnly: true,
        starWidth: "22px"
    });

    $(".stars-1").rateYo({
        rating: 1,
        readOnly: true,
        starWidth: "22px"
    });

    $(".stars-2").rateYo({
        rating: 2,
        readOnly: true,
        starWidth: "22px"
    });

    $(".stars-3").rateYo({
        rating: 3,
        readOnly: true,
        starWidth: "22px"
    });

    $(".stars-4").rateYo({
        rating: 4,
        readOnly: true,
        starWidth: "22px"
    });

    $(".stars-5").rateYo({
        rating: 5,
        readOnly: true,
        starWidth: "22px"
    });


    const ratingStars = $('#rateUser');
    const ratingValue = $('#comment_gradeNumber');
    const submitBtn = $('#comment_submit');
    const content = $('#comment_content');
    const contentWarning = $('.content-overload');
    let starsSet = false;

    ratingStars.click(setRateValue);
    content.keyup(warnForContentLimit);

    function setRateValue() {
        let changesToRates = ratingStars.find('.jq-ry-rated-group.jq-ry-group');
        let stars = Math.ceil((changesToRates.width() / (changesToRates.parent().width())) * 5);
        ratingValue.val(stars);
        starsSet = true;
        warnForContentLimit();
    }

    function warnForContentLimit() {
        if(content.val().length > 1000) {
            contentWarning.show();
            submitBtn.attr('disabled', true);
        }

        else if(content.val().length === 0) {
            submitBtn.attr('disabled', true);
        }
        else {
            contentWarning.hide();
            if(starsSet) {
                submitBtn.removeAttr('disabled');
            }
        }
    }
});