<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

if (loggedIn()) {
    $photo_albums = listEntities(array(
        "type" => "Photoalbum",
        "metadata_name" => "owner_guid",
        "metadata_value" => getLoggedInUserGuid(),
        "view_type" => "photopicker_gallery"
    ));
    ?>
    <div class="modal fade" id="insert_photo_modal" tabindex="-1" role="dialog" aria-labelledby="insert_photo_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="insert_photo_modal_label">Select an album/photo.</h4>
                </div>
                <div class="modal-body clearfix">
                    <?php echo $photo_albums; ?>
                    <div id="photos"></div>
                    Or Upload a new Photo
                    <?php
                    echo drawForm(array(
                        "name" => "upload_photo",
                        "method" => "post",
                        "action" => "UploadPhoto",
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