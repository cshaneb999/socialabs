<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class SearchFriendsPageHandler extends PageHandler {

    public function __construct() {
        $body = display("pages/search_friends");
        $guid = getInput("guid");
        $button = "<a class='btn btn-success' href='" . getSiteURL(). "profile/$guid'>Return</a>";
        $this->html = drawPage(array(
            "header" => "Search Results",
            "body" => $body,
            "button" => $button
        ));
    }

}
