var members = {
    init: function () {
        $(".member_list_element").first().addClass("selected");
        $(".view_full_profile").hide();
        $(".view_full_profile").first().show();
        $("body").on("click", ".new_member_button", function () {
            $("#new_member_modal").modal("show");
        });
        $("body").on("click", ".member_list_element", function () {
            var button = $(this);
            $(".view_full_profile").slideUp("slow");
            var guid = button.attr("data-guid");
            if (button.hasClass("selected")) {
                window.location = site.url() + "profile/" + guid;
            } else {
                $(".member_list_element").removeClass("selected");
                button.find(".view_full_profile").slideDown("slow");
                $("#preloader").fadeIn("slow");
                button.addClass("selected");
                $.post(site.url() + "ajax/ajax_view_handler.php", {
                    view: "profile/mini_profile",
                    vars: {
                        guid: guid
                    }
                }, function (returnData) {
                    $("#member_wrapper").html(returnData);
                    $('#preloader').fadeOut('slow');
                    $("#member_wrapper").fadeIn();
                    sitejs.timeago();
                });
            }
        });
    }
}