<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;
?>
    ed.addButton('browse', {
        title: 'Insert Photo',
        image: false,
        onclick: function () {
            $("#insert_photo_modal").modal("show");
            $("[name='editor_id']").val(ed.id);
        }
    });