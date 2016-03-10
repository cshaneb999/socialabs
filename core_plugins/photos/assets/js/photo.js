var photo = {
    init: function () {
        if (("#photos").length > 0) {
            $(".photopicker_album_gallery_item").on("click", function () {
                var album_element = $(this);
                $(".photopicker_album_gallery_item").removeClass("active");
                album_element.addClass('active');
                var classes = album_element.attr("class");
                $.post(site.url() + "ajax/ajax_view_handler.php", {
                    vars: {
                        classes: classes
                    },
                    view: "ajax/AjaxPhotoalbum"
                }, function (returnData) {
                    $("#photos").html("<br/>Select a Photo</br>" + returnData);
                });
            });
        }
    }
};

$("body").on("click", ".photopicker_gallery_item", function () {
    var editor_id = $("[name='editor_id']").val();
    var element_class = $(this).attr("class");
    guid = element_class.replace("well well-sm photopicker_gallery_item photo_", "");
    $.post(site.url() + "ajax/ajax_view_handler.php", {
        view: "ajax/insertPhoto",
        vars: {
            guid: guid
        }
    }, function (returnData) {
        tinymce.get(editor_id).insertContent(returnData);
        $("#insert_photo_modal").modal("hide");
    });
});