$(function () {
    const form = $('.additional-images');
    const addImage = $('.add-image');
    const removeImage = $('.remove-image');
    const numberOfImages = $('#numberOfImages');
    const submitBtn = $('#car_ad_create_submit');


    let imageIndex = Number(numberOfImages.val()); //starting value for editing article

    if(!numberOfImages.length) { //when creating new article
        imageIndex = 3;
    }

    addImage.click(addImageField);
    removeImage.click(removeLast);

    function addImageField() {
        if(imageIndex < 15) {
            let container = $('<div>');
            let subContainer = $('<div>');
            let main = $("<div>")
                .attr('id', "car_ad_create_additionalImages_" + imageIndex + "_address")
                .attr('class', 'form-group');

            let label = $("<label>")
                .attr('for', "car_ad_create_additionalImages_" + imageIndex + "_address")
                .attr('class', 'required')
                .html('Image Link ' + Number(imageIndex + 1));

            let input = $("<input>");
            input.attr('type', 'text');
            input.attr('id', "car_ad_create_additionalImages_" + imageIndex + "_address");
            input.attr('name', "car_ad_create[additionalImages][" + imageIndex + "][address]");
            input.attr('required', "required");
            input.attr('class', "form-control");

            subContainer.append(label)
                .append(input);
            main.append(subContainer);
            container.append(main);
            form.append(container);

            imageIndex++;
        }
    }

    function removeLast()
    {
        if(imageIndex > 3) {
            let lastTag = form.children().last();
            lastTag.remove();
            imageIndex--;
        }
    }
});