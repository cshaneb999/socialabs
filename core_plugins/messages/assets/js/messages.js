var messages = {
    init: function () {
        $(".message_list_element").first().addClass("selected");
        $("body").on("click", ".new_message_button", function () {
            $("#new_message_modal").modal("show");
        });
        $("body").on("click", ".message_list_element", function () {
            $(".message_list_element").removeClass("selected");
            tinymce.execCommand('mceRemoveEditor', false, "reply");
            $("#preloader").fadeIn("slow");
            var button = $(this);
            button.addClass("selected");
            var guid = button.attr("data-guid");
            $.post(site.url() + "ajax/ajax_view_handler.php", {
                view: "pages/message",
                vars: {
                    guid: guid
                }
            }, function (returnData) {
                $("#message_wrapper").html(returnData);
                $('#preloader').fadeOut('slow');
                tinymce.execCommand('mceAddEditor', true, "reply");
                $("#message_wrapper").fadeIn();
                sitejs.timeago();
            });
        });
    }
}