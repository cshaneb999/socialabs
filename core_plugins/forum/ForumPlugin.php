<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {

    class ForumPlugin {

        public function __construct() {
            new StorageType("Forumcategory", "description", "text");
            new StorageType("Forumcomment", "description", "text");
            new StorageType("Forumtopic", "description", "text");
            new MenuItem(array(
                "name" => "forum",
                "page" => "forum",
                "label" => "<i class='icon ion-chatbubbles'></i><p>Forum</p>",
                "menu" => "header_left"
            ));
            new Metatag("forum", "title", getSiteName() . " | Forum");
            new Usersetting(array(
                "name" => "notify_when_forum_comment_topic_i_own",
                "field_type" => "dropdown",
                "options" => array(
                    "email" => "Email",
                    "site" => "Site",
                    "both" => "Both",
                    "none" => "None"
                ),
                "tab" => "notifications",
                "default_value" => "both"
            ));
            new Usersetting(array(
                "name" => "notify_when_forum_comment_topic_i_commented",
                "field_type" => "dropdown",
                "options" => array(
                    "email" => "Email",
                    "site" => "Site",
                    "both" => "Both",
                    "none" => "None"
                ),
                "tab" => "notifications",
                "default_value" => "both"
            ));
        }

    }

}