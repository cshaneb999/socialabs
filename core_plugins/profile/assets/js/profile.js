var profile = {
    init: function () {
        profile.showdropdownoptions();
        profile.editavatarbutton();
    },
    showdropdownoptions: function () {
        if ($("[name='field_type']").length > 0) {
            $("[name='field_type']").on('change', function () {
                if ($(this).val() === 'dropdown') {
                    $("#profile_field_dropdown_options").show();
                } else {
                    $("#profile_field_dropdown_options").hide();
                }
            });
        }
    },
    editavatarbutton: function () {
        $("#profile_avatar").hoverIntent(function () {
            $(".edit_avatar_button").fadeIn();
        }, function () {
            $(".edit_avatar_button").fadeOut();
        });
    }
};