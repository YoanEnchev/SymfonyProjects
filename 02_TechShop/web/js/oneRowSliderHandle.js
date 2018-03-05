$(function () {
    $('.slider-products').slick({
        infinite: true,
        initialSlide: 0,
        slidesToShow: 4,
        arrow: true,
        dots: true,
        speed: 300,
        centerMode: true,
        rows: 1,
        responsive: [
            {
                breakpoint: 1624,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 1
                }
            },
        ]
    });
});