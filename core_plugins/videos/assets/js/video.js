var video = {
    init: function () {
        if (("#videos").length > 0) {
            $(".videopicker_album_gallery_item").on("click", function () {
                var album_element = $(this);
                $(".videopicker_album_gallery_item").removeClass("active");
                album_element.addClass('active');
                var classes = album_element.attr("class");
                $.post(site.url() + "ajax/ajax_view_handler.php", {
                    vars: {
                        classes: classes
                    },
                    view: "ajax/AjaxvideoAlbum"
                }, function (returnData) {
                    $("#videos").html("<br/>Select a video</br>" + returnData);
                });
            });
        }
    }
};

$("body").on("click", ".save_upload_video", function () {
    $(".bar-loader").show();
});
$("body").on("click", ".videopicker_gallery_item", function () {
    var editor_id = $("[name='editor_id']").val();
    var element_class = $(this).attr("class");
    guid = element_class.replace("img-responsive img-responsive well well-sm videopicker_gallery_item video_", "");
    $.post(site.url() + "ajax/ajax_view_handler.php", {
        view: "ajax/insertvideo",
        vars: {
            guid: guid
        }
    }, function (returnData) {
        tinymce.get(editor_id).insertContent(returnData);
        $("#insert_video_modal").modal("hide");
    });
});