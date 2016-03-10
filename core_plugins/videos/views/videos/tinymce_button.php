<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
    ed.addButton('media', {
        title: 'Insert Video',
        image: false,
        onclick: function () {
            $("#insert_video_modal").modal("show");
            $("[name='editor_id']").val(ed.id);
        }
    });