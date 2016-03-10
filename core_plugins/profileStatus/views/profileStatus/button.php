<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
ed.on('init', function (evt) {
    var toolbar = $(evt.target.editorContainer)
            .find('>.mce-container-body >.mce-toolbar-grp');
    var editor = $(evt.target.editorContainer)
            .find('>.mce-container-body >.mce-edit-area');
    toolbar.detach().insertAfter(editor);
});