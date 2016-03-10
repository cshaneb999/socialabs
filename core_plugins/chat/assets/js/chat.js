var chat = {
    init: function () {
        chat.scrollTop();
        chat.min_max();
        chat.close();
        chat.send();
        chat.update();
    },
    update: function () {
        setInterval(function () {
            $(".chat-window").each(function () {
                var id = $(this).attr("id");
                var guid = id.replace("chat_window_", "");
                $.post(site.url() + "ajax/ajax_view_handler.php", {
                    view: "chat/messages",
                    vars: {
                        guid: guid
                    }
                }, function (returnData) {
                    $("#messages_" + guid).html(returnData);
                    var height = $("#messages_" + guid)[0].scrollHeight;
                    $("#messages_" + guid).scrollTop(height);
                });

            });
        }, 6000);
    },
    min_max: function () {
        $(document).on('click', '.panel-heading span.icon_minim', function (e) {
            var $this = $(this);
            var id = $this.attr("id");
            var guid = id.replace("minim_chat_window_", "");
            var panelbody = $this.parents('.panel').find('.panel-body');
            if (panelbody.is(":visible")) {
                $this.parents('.panel').find('.panel-body').slideUp();
                $this.removeClass('glyphicon-minus').addClass('glyphicon-plus');
                $.post(site.url() + "ajax/ajax_action_handler.php", {
                    action: "MinimizeChat",
                    data: {
                        guid: guid
                    }
                });
            } else {
                $this.parents('.panel').find('.panel-body').slideDown();
                $this.removeClass('panel-collapsed');
                $this.removeClass('glyphicon-plus').addClass('glyphicon-minus');
                $.post(site.url() + "ajax/ajax_action_handler.php", {
                    action: "MaximizeChat",
                    data: {
                        guid: guid
                    }
                });
            }
        });
        $(document).on('focus', '.panel-footer input.chat_input', function (e) {
            var $this = $(this);
            var id = $this.attr("id");
            var guid = id.replace('btn-input_', '');
            var panelbody = $this.parents('.panel').find('.panel-body');
            if (!panelbody.is(":visible")) {
                $this.parents('.panel').find('.panel-body').slideDown();
                $('#minim_chat_window_' + guid).removeClass('glyphicon-plus').addClass('glyphicon-minus');
            }
        });
    },
    close: function () {
        $(document).on('click', '.icon_close', function (e) {
            $(this).parent().parent().parent().parent().remove();
            var id = $(this).data("id");
            var guid = id.replace("chat_window_", "");
            $.post(site.url() + "ajax/ajax_action_handler.php", {
                action: "CloseChat",
                data: {
                    guid: guid
                }
            });
        });
    },
    send: function () {
        $(document).on("keyup", ".chat_input", function (e) {
            if (e.which == 13) {
                var id = $(this).attr("id");
                var guid = id.replace("btn-input_", "");
                var text = $(this).val();
                $(this).val("");
                chat.postMessage(text, guid);
            }
        });
        $(document).on("click", ".btn-send-chat", function () {
            var id = $(this).attr("id");
            var guid = id.replace('btn-chat_', '');
            var text = $("#btn-input_" + guid).val();
            $("#btn-input_" + guid).val("");
            chat.postMessage(text, guid);
        });
    },
    postMessage: function (text, guid) {
        if (text) {
            $.post(site.url() + "ajax/ajax_action_handler.php", {
                action: "AddChatMessage",
                data: {
                    guid: guid,
                    text: text
                }
            }, function () {
                $.post(site.url() + "ajax/ajax_view_handler.php", {
                    view: "chat/messages",
                    vars: {
                        guid: guid
                    }
                }, function (returnData) {
                    $("#messages_" + guid).html(returnData);
                    var height = $("#messages_" + guid)[0].scrollHeight;
                    $("#messages_" + guid).scrollTop(height);
                });
            });
        }
    },
    scrollTop: function () {
        $(".msg_container_base").each(function () {
            var div = $(this);
            var height = div[0].scrollHeight;
            div.scrollTop(height);
        });
    }
}




