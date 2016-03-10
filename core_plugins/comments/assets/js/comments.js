var comments = {
    init: function () {
        comments.inline_comment_form();
        comments.ajax_comments();
        comments.ajax_more_comments();
    },
    inline_comment_form: function () {
        $("body").on("click", ".show_inline_comment_form", function () {
            $(".inline_comment_form").slideUp("fast");
            var button = $(this);
            var form = button.parent().children(".inline_comment_form").eq(0);
            var submit_button = form.find(".add_comment_submig");
            form.slideDown();
            var input = form.find("input[type='text']").eq(0);
            input.focus().keyup(function (e) {
                if (e.keyCode == 13) {
                    submit_button.trigger("click");
                }
            });
        });
    },
    ajax_comments: function () {
        $("body").on("click", ".add_comment_submit", function (e) {
            var button = $(this);
            e.preventDefault();
            var comment_textarea = $(this).closest("form").find(".comment_textarea");
            var comment_message = comment_textarea.val();
            var guid = $(this).closest("form").find("[name='guid']").eq(0).val();
            var ajax_loader = $(this).closest("form").siblings(".bar-loader");
            $(this).closest(".inline_comment_form").hide();
            ajax_loader.show();
            $.post(site.url() + "ajax/ajax_action_handler.php", {
                action: "AddComment",
                data: {
                    "comment_body": comment_message,
                    "container_guid": guid
                }
            }, function () {
                var reverse = button.hasClass("reversed") ? true : false;
                $.post(site.url() + "ajax/ajax_view_handler.php", {
                    view: "output/comments",
                    vars: {
                        guid: guid,
                        reverse: reverse
                    }
                }, function (returnData) {
                    $(".comment_list_" + guid).html(returnData);
                    sitejs.timeago();
                    comment_textarea.val("");
                    ajax_loader.hide();
                });

            });
        });
    },
    ajax_more_comments: function () {
        $("body").on("click", ".more_comments", function (e) {
            e.stopPropagation();
            e.preventDefault();
            var button = $(this);
            var loader = button.closest(".bar-loader");
            loader.show();
            var reverse = button.data("reverse");
            var guid = button.data("guid");
            var offset = button.data("offset");
            $.post(site.url() + "ajax/ajax_view_handler.php", {
                view: "output/comments",
                vars: {
                    reverse: reverse,
                    guid: guid,
                    count: 10,
                    offset: offset
                }
            }, function (returnData) {
                $(".comment_list_" + guid).append(returnData);
                sitejs.timeago();
                button.remove();
            });
        });
    },
    delete_comment: function (guid) {
        if (confirm("Are you sure?")) {
            $(".comment_" + guid).remove();
            $.post(site.url() + "ajax/ajax_action_handler.php", {
                data: {
                    guid: guid
                },
                action: "deleteComment"
            });
        }
    }
}