<?php

namespace SociaLabs;

require_once((dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
header("Content-type: application/javascript");
?>
/* <script> /* */


    $(window).load(function () {
        $('#preloader').fadeOut('slow');
    });
    var sitejs = {
        init: function () {
            sitejs.modal();
            sitejs.profile();
            sitejs.tinymce();
            sitejs.timeago();
            sitejs.confirm();
            sitejs.sortable_plugins();
            sitejs.sortable_pages();
            sitejs.tooltip();
            sitejs.dismiss_notification();
            sitejs.vertical();
            sitejs.filepicker();
            sitejs.datepicker();
            sitejs.ajax_fields();
            sitejs.masonry();
            sitejs.timeline();
            sitejs.breakwords();
            sitejs.tabs();
            sitejs.dismiss_alert();
            sitejs.ajax_entity_list();
            sitejs.location_input();
            sitejs.navbar();
            sitejs.typography();
        },
        typography: function () {
            $("h1").each(function (index, value) {
                if (index > 0) {
                    $(this).replaceWith(function () {
                        return "<h2>" + $(this).text() + "<h2>";
                    });
                }
            });
        },
        navbar: function () {
            $('#slide-nav.navbar-inverse').after($('<div class="inverse" id="navbar-height-col"></div>'));
            $('#slide-nav.navbar-default').after($('<div id="navbar-height-col"></div>'));
            var toggler = '.navbar-toggle';
            var pagewrapper = '#page-content';
            var navigationwrapper = '.navbar-header';
            var menuwidth = '100%'; // the menu inside the slide menu itself
            var slidewidth = '80%';
            var menuneg = '-100%';
            var slideneg = '-80%';
            $("#slide-nav").on("click", toggler, function (e) {
                var selected = $(this).hasClass('slide-active');
                $('#slidemenu').stop().animate({
                    left: selected ? menuneg : '0px'
                });
                $('#navbar-height-col').stop().animate({
                    left: selected ? slideneg : '0px'
                });
                $(pagewrapper).stop().animate({
                    left: selected ? '0px' : slidewidth
                });
                $(navigationwrapper).stop().animate({
                    left: selected ? '0px' : slidewidth
                });
                $(this).toggleClass('slide-active', !selected);
                $('#slidemenu').toggleClass('slide-active');
                $('#page-content, .navbar, body, .navbar-header').toggleClass('slide-active');
            });
            var selected = '#slidemenu, #page-content, body, .navbar, .navbar-header';
            $(window).on("resize", function () {
                if ($(window).width() > 767 && $('.navbar-toggle').is(':hidden')) {
                    $(selected).removeClass('slide-active');
                }
            });
        },
        location_input: function () {
            $(".location_input").geocomplete({details: "form"});
        },
        ajax_entity_list: function () {
            $("body").on("click", ".show_more_entities", function () {
                var button = $(this);
                var params = button.data("params");
                var id = button.data("id");
                var count = button.data("count");
                var count_shown = button.data("count_shown");
                button.remove();
                $.post(site.url() + "ajax/ajax_view_handler.php", {
                    view: "ajax/entity_list_body",
                    vars: {
                        params: params,
                        id: id,
                        count: count,
                        count_shown: count_shown
                    }
                }, function (returnData) {
                    $("#" + id).append(returnData);
                    sitejs.timeago();
                });
            });
        },
        tabs: function () {
            $('.tabs a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
        },
        breakwords: function () {
            $(".timeline p").css({
                wordWrap: "break-word"
            });
        },
        timeline: function () {
            if ($("ul.timeline").length > 0) {
                $("ul.timeline li:odd").addClass("timeline-inverted");
                $(".timeline-icon").each(function () {
                    var image_height = parseInt($(this).css("height"));
                    var top_position = ((64 - image_height) / 2) - 4;
                    $(this).css({
                        top: top_position + "px"
                    });
                });
            }
        },
        masonry: function () {
            if ($(".masonry3col").length > 0) {
                $(".masonry3col").each(function () {
                    var div = $(this);
                    div.imagesLoaded(function () {
                        $.when(
                                div.masonry({
                                    itemSelector: '.masonry_element',
                                    columnwidth: '.col-xs-4',
                                    percentPosition: true
                                })
                                ).then(function () {
                            div.css({
                                opacity: 1
                            });
                        });
                    });
                });
            }
            if ($(".masonry4col").length > 0) {
                $(".masonry4col").each(function () {
                    var div = $(this);
                    div.imagesLoaded(function () {
                        $.when(
                                div.masonry({
                                    itemSelector: '.masonry_element',
                                    columnwidth: '.col-xs-3',
                                    percentPosition: true
                                })
                                ).then(function () {
                            div.css({
                                opacity: 1
                            });
                        });
                    });
                });
            }
        },
        dismiss_notification: function () {
            if ($(".dismiss_notification").length > 0) {
                $(".dismiss_notification").on("click", function (e) {
                    var element = $(this);
                    e.preventDefault();
                    var guid = element.siblings(".guid").eq(0).val();
                    $.post("ajax/ajax_action_handler.php", {
                        action: "DismissNotification",
                        data: {
                            guid: guid
                        }
                    }, function () {
                        if (element.attr('href') === undefined) {
                            location.reload();
                        } else {
                            window.location = element.attr('href');
                        }
                    });
                });
            }
        },
        tooltip: function () {
            $('[data-toggle="tooltip"]').tooltip({
                container: "body"
            });
        },
        modal: function () {
            if ($('#modal').length > 0) {
                $('#modal').modal(
                        {
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        }
                );
            }
        },
        profile: function () {
            if ($('.avatar').length > 0) {
                $('.avatar').hoverIntent(function () {
                    $('.edit-avatar').fadeIn();
                }, function () {
                    $('.edit-avatar').fadeOut();
                });
            }
        },
        tinymce: function () {
            tinyMCE.init({
                mode: "specific_textareas",
                content_css: '',
                plugins: "textcolor",
                editor_selector: "tinymce",
                plugins: [
                    "textcolor advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "autoresize smileys"
                ],
                        autoresize_bottom_margin: 0,
                toolbar: "forecolor backcolor insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link browse media code smileys",
                extended_valid_elements: 'img[style|class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]|a[href|test] data',
                convert_urls: false,
                verify_html: false,
                parser: tinymce.html.DomParser,
                menubar: true,
                statusbar: true,
                content_css: [site.url() + 'assets/vendor/bootstrap/dist/css/bootstrap.min.css', site.url() + 'assets/css/tinymce_content.css'],
    <?php
    echo display("page_elements/tinymce_buttons_wrapper");
    ?>
            });
        },
        filepicker: function () {
            $(".filepicker").on("click", function (e) {
                e.stopPropagation();
                var filepicker = $(this);
                $(".filepicker").removeClass("active");
                filepicker.addClass("active");
            });
        },
        timeago: function () {
            $('.timeago').timeago();
        },
        alert: function (params) {
            var title = button.attr("data-title");
            var text = button.attr("data-text");
            var type = button.attr("data-type");
            var confirmbuttontext = button.attr("data-confirm-button-text");
            if (!params.text) {
                params.text = "Are you sure you want to do this?";
            }
            if (!params.type) {
                params.type = "warning";
            }
            swal({
                title: title,
                text: text,
                type: type,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: confirmbuttontext,
            });
        },
        confirm: function () {
            $('body').on('click', '.confirm', function (e) {
                e.preventDefault();
                var button = $(this);
                var href = button.attr("href");
                var title = button.attr("data-title");
                var text = button.attr("data-text");
                var type = button.attr("data-type");
                var cancelbutton = button.attr('data-cancel-button');
                var confirmbuttontext = button.attr("data-confirm-button-text");
                var successtitle = button.attr("data-success-title");
                var successtext = button.attr("data-success-text");
                if (!title) {
                    title = "Are you sure?";
                }
                if (!text) {
                    text = "Are you sure you want to do this?";
                }
                if (!type) {
                    type = "warning";
                }
                if (!cancelbutton) {
                    cancelbutton = true;
                }
                if (!successtitle) {
                    successtitle = "Success!";
                }
                if (!successtext) {
                    successtext = "It worked!";
                }
                swal({
                    title: title,
                    text: text,
                    type: type,
                    showCancelButton: cancelbutton,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: confirmbuttontext,
                    closeOnConfirm: true
                },
                        function (confirm) {
                            if (confirm) {
                                window.location = href;
                            }
                        });
                return false;
            }
            );
        },
        urlParam: function (name) {
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if (results === null) {
                return null;
            } else {
                return results[1] || 0;
            }
        },
        sortable_plugins: function () {
            if ($('.sortable-list').length > 0) {
                $('.sortable-list').sortable({
                    connectWith: '.sortable-list',
                    update: function () {
                        var order = $(this).sortable('toArray');
                        $.post(site.url() + 'ajax/ajax_action_handler.php', {
                            action: "UpdatePluginOrder",
                            data: {
                                order: order
                            }
                        });
                    }
                });
            }
        },
        sortable_pages: function () {
            if ($('.sortable-pages').length > 0) {
                $('.sortable-pages').sortable({
                    connectWith: '.sortable-pages',
                    update: function () {
                        var order = $(this).sortable('toArray');
                        $.post(site.url() + 'ajax/ajax_action_handler.php', {
                            action: "UpdatePageOrder",
                            data: {
                                order: order
                            }
                        });
                    }
                });
            }
        },
        vertical: function () {
            if ($(".vertical").length > 0) {
                $(".vertical").each(function () {
                    var div = $(this);
                    var parent = $(this).parent();
                    var div_height = div.height();
                    var parent_height = parent.height();
                    var top = (parent_height - div_height) / 2;
                    div.css("margin-top", top + "px");
                    div.css("top", top + "px");
                });
            }
        },
        gotoplugin: function (guid) {
            var element = $("#guid_" + guid);
            $('html,body').animate({scrollTop: element.offset().top}, 800);
            element.css("border", "4px dashed #AFAAAA");
        },
        datepicker: function () {
            $(".datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });
            $(".datepicker").each(function () {
                var div = $(this);
                div.on("change", function () {
                    var val = div.val();
                    var d = new Date(val);
                    var da = d.getTime() / 1000;
                    div.parent().children(".actual_date").val(da);
                });
            });
        },
        ajax_fields: function () {
            if ($(".ajax_field").length > 0) {
                $.each($(".ajax_field"), function () {
                    var field = $(this);
                    field.click(function () {
                        if ($(".ajax_field.active").length == 0) {
                            var value = field.html();
                            var action = field.attr("data-action");
                            var form = field.attr("data-form");
                            var guid = field.attr("data-guid");
                            var name = field.attr("data-name");
                            $.post(site.url() + "ajax/ajax_view_handler.php", {
                                view: "ajax/form",
                                vars: {
                                    inputs: [
                                        {
                                            name: name,
                                            value: value,
                                            type: "text"
                                        },
                                        {
                                            name: "guid",
                                            value: guid,
                                            type: "hidden"
                                        },
                                        {
                                            name: "",
                                            value: "",
                                            type: "submit",
                                            label: "Save",
                                            class: "btn btn-success"
                                        }
                                    ],
                                    action: action,
                                    form_name: form
                                }
                            }, function (returnData) {
                                field.addClass("active");
                                field.html(returnData);
                            });
                        }
                    });
                });
                $(".ajax_field .ajax_input_field").click(function (e) {
                    e.stopPropagation();
                });
            }
        },
        dismiss_alert: function () {
            $(".system_message").delay(5000).fadeTo(2000, 500).slideUp(500, function () {
                $(".system_message").alert('close');
            });
            $("body").on("click", ".system_message", function () {
                $(".system_message").delay(5000).fadeTo(2000, 500).slideUp(500, function () {
                    $(".system_message").alert("close");
                });
            });
        }
    };

