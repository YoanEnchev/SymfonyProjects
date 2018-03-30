(function () {
    const mainImage = $('#mainImg img');

    $('.ad-images img').click(setMainImage);
    
    function setMainImage() {
        $(mainImage).attr('src', $(this).attr('src'));
    }
})();