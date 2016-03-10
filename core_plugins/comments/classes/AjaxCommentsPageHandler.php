<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {

    class AjaxCommentsPageHandler extends CommentsPlugin {

        public function __construct($data) {
            $guid = $data['guid'];
            $reverse = isset($data['reverse']) ? $data['reverse'] : "false";
            $inline = isset($data['inline']) ? $data['inline'] : "false";
            $this->html = viewComments($guid, $reverse, $inline, false);
        }

        public function view() {
            echo $this->html;
            die();
        }

    }

}