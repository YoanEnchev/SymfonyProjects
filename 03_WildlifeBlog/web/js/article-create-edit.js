$(function () {
    const form = $('.tag-form');
    const addTag = $('.add-tag');
    const removeTag = $('.remove-tag');
    const numberOfTags = $('#numberOfTags');

    let tagIndex = numberOfTags.val(); //starting value for editing article

    if(!numberOfTags.length) { //when element does not exists (when creating new article)
        tagIndex = 3;
    }

    addTag.click(addTagField);
    removeTag.click(removeLast);

    function addTagField() {
        let id = "article_tags_" + tagIndex;

        let container = $('<div>');
        let main = $("<div id=" + id +  ">");
        let subContainer = $('<div>');
        let label = $("<label for=" + id + "_name" +  "class=" + "required\>" + "Tag " + (tagIndex + 1) + "</label>");
        let input = $("<input>");

        input.attr('type', 'text');
        input.attr('id', "article_tags_" + tagIndex + "_name");
        input.attr('name', "article[tags][" + tagIndex + "]" + "[name]");
        input.attr('required', "required");
        input.attr('class', "form-control");

        subContainer.append(label)
        .append(input);
        main.append(subContainer);
        container.append(main);
        form.append(container);

        tagIndex++;
    }

    function removeLast()
    {
        if(tagIndex > 1) {
            let lastTag = form.children().last();
            lastTag.remove();
            tagIndex--;
        }
    }
});