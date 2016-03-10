<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

if (loggedIn()) {
    $video_albums = listEntities(array(
        "type" => "Videoalbum",
        "metadata_name" => "owner_guid",
        "metadata_value" => getLoggedInUserGuid(),
        "view_type" => "videopicker_gallery"
    ));
    ?>
    <div class="modal fade" id="insert_video_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body clearfix">
                    <h3>Select an album/video.</h3>
                    <?php echo $video_albums; ?>
                    <div id="videos" class="clearfix"></div>
                    <h3>-or-</h3>
                    <?php
                    echo drawForm(array(
                        "name" => "upload_video",
                        "method" => "post",
                        "action" => "UploadVideo",
                        "enctype" => "multipart/form-data"
                    ));
                    ?>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="editor_id" value="">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}