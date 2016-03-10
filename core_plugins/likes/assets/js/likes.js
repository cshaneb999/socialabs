var likes = {
    add: function (guid) {
        $.post(site.url() + "ajax/ajax_action_handler.php", {
            action: "AddLike",
            data: {
                guid: guid
            }
        }, function () {
            $.post(site.url() + "ajax/ajax_view_handler.php", {
                view: "ajax/likes",
                vars: {
                    guid: guid
                }
            }, function (returnData) {
                $(".like_" + guid).html(returnData);
                sitejs.timeago();
            });
        });
    }
}