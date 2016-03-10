var userpicker = {
    init: function () {
        userpicker.picker();
    },
    picker: function () {
        if ($(".user_picker").length > 0) {
            
            var array = $("#userpicker_input").val().split(",");
            
            $(".user_picker").each(function () {
                var element = $(this);
                var guid = element.children(".guid").eq(0).val();
                if ($.inArray(guid, array) !== - 1) {
                    element.addClass("alert-success").removeClass("alert-danger");
                }
            });
            
            $("body").on("click", ".user_picker", function (e) {
                var picker = $(this);
                e.preventDefault();
                e.stopPropagation();
                picker.toggleClass("alert-success").toggleClass("alert-danger");
            });
        }
    },
    save: function () {
        var guids = [];
        if ($(".user_picker").length > 0) {
            $(".user_picker").each(function () {
                if ($(this).hasClass("alert-success")) {
                    var guid = $(this).find(".guid").eq(0).val();
                    guids.push(guid);
                }
            });
            $("#userpicker_input").val(guids);
            $("#user_select_modal").modal("hide");
            userpicker.refresh_user_list(guids);
        } else {
            return false;
        }
    },
    refresh_user_list: function (guids) {
        $.post(site.url() + "plugins/userpicker/views/output/user_gallery.php", {
            guids: guids
        }, function (returnData) {
            $("#selected_users").html(returnData);
        });
    },
    update_modal_selections: function (value) {
        $.each(value, function () {
            $(".user_picker.user_" + value).addClass("alert-success").removeClass("alert-danger");
        });
    }
};
$(document).ready(function () {
    userpicker.init();
});