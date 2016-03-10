<?php

namespace SociaLabs {

    require_once((dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php");
    header("Content-type: application/javascript");
    $toolbar = NULL;
    if (isEnabledPlugin("photos")) {
        $toolbar .= "browse";
    }
    if (isEnabledPlugin("videos")) {
        $toolbar .= " media";
    }
    $toolbar .= " smileys";
    ?>

            var profile_status = {
                init: function () {
                    profile_status.tinymce();
                    profile_status.updatestatus();
                },
                tinymce: function () {
                    tinymce.init({
                        menubar: false,
                        skin_url: '<?php echo getSiteURL(); ?>assets/css/tinymce/lightgray',
                        selector: ".tinymce_status_input",
                        statusbar: false,
                        toolbar: "browse media smileys",
                        extended_valid_elements: 'img[style|class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]|a[href|test] data',
                        convert_urls: false,
                        verify_html: false,
                        plugins: "autoresize smileys",
                        autoresize_bottom_margin: 0,
                        parser: tinymce.html.DomParser,
                        toolbar:"<?php echo $toolbar; ?>",
        <?php echo display("page_elements/tinymce_buttons_wrapper"); ?>
                    });
                },
                updatestatus: function () {
                    $("body").on("click", ".update-status", function (e) {
                        e.preventDefault();
                        var button = $(this);
                        var loader = button.siblings(".bar-loader");
                        loader.show();
                        var form = button.closest("form");
                        var guid = form.find("[name='guid']").val();
                        var status = tinyMCE.get('status').getContent();
                        $.post(site.url() + "ajax/ajax_action_handler.php", {
                            guid: guid,
                            status: status,
                            action: "UpdateStatus"
                        }, function () {
                            $.post(site.url() + "ajax/ajax_view_handler.php", {
                                view: "ajax/entity_list",
                                vars: {
                                    params: {
                                        type: "Profilestatus",
                                        limit: 10,
                                        offset: 0,
                                        "metadata_name": "container_guid",
                                        "metadata_value": guid,
                                        "order_by": "time_created",
                                        "order_reverse": true
                                    },
                                    id: "profile_status",
                                    title: ""
                                }
                            }, function (returnData) {
                                $("#ajax_profile_status").html(returnData);
                                tinyMCE.get("status").setContent('');
                                loader.fadeOut("fast");
                                sitejs.timeago();
                            });
                        });
                    });
                }
            };
        <?php
    }