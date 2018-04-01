$('.lastAdded').slick({
    dots: false,
    speed: 300,
    slidesToShow: 5,
    slidesToScroll: 1,
    rows: 2,
    infinite: true,
    responsive: [
        {
            breakpoint: 1300,
            settings: {
                slidesToShow: 4,
            }
        },
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 700,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
            }
        }
    ]
});