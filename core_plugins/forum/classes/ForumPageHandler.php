<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {

    class ForumPageHandler extends PageHandler {

        public function __construct() {
            $title = $body = $buttons = $breadcrumbs = NULL;
            switch (pageArray(1)) {
                default:
                    $body = display("pages/forum");
                    $title = "Forum Categories";
                    if (adminLoggedIn()) {
                        $add_category_url = getSiteURL() . "forum/add_category";
                        $buttons = "<a href='$add_category_url' class='btn btn-danger'>Add a Category</a>";
                    }
                    $breadcrumbs = array(
                        array(
                            "link" => getSiteURL() . "forum",
                            "label" => "Categories"
                        )
                    );
                    break;
                case 'add_category':
                    adminGateKeeper();
                    $body = drawForm(array(
                        "name" => "add_category",
                        "method" => "post",
                        "action" => "addCategory"
                    ));
                    $title = "Add a Forum Category";
                    break;
                case 'category':
                    $guid = pageArray(2);
                    if ($guid) {
                        $category = getEntity($guid);
                        $body = display("forum/category");
                        if (loggedIn()) {
                            $add_topic_url = getSiteURL() . "forum/add_topic/$guid";
                            $buttons = "<a href='$add_topic_url' class='btn btn-success'>Add Topic</a>";
                        }
                    }
                    $breadcrumbs = array(
                        array(
                            "link" => getSiteURL() . "forum",
                            "label" => "Categories"
                        ),
                        array(
                            "link" => getSiteURL() . "forum/category/" . $category->guid,
                            "label" => $category->title
                        )
                    );
                    break;
                case "add_topic":
                    gateKeeper();
                    $category_guid = pageArray(2);
                    $category = getEntity($category_guid);
                    $body = drawForm(array(
                        "name" => "add_topic",
                        "method" => "post",
                        "action" => "addTopic"
                    ));
                    $title = "Add a topic to $category->title";
                    break;
                case "topic":
                    $topic = getEntity(pageArray(2));
                    $category = getEntity($topic->container_guid);
                    $breadcrumbs = array(
                        array(
                            "link" => getSiteURL() . "forum",
                            "label" => "Categories"
                        ),
                        array(
                            "link" => getSiteURL() . "forum/category/" . $category->guid,
                            "label" => $category->title
                        ),
                        array(
                            "link" => getSiteURL() . "forum/topic/" . $topic->guid,
                            "label" => $topic->title
                        )
                    );
                    $body = display("forum/topic");
                    break;
                case "editCategory":
                    adminGateKeeper();
                    $title = "Edit Forum Category";
                    $body = drawForm(array(
                        "name" => "edit_category",
                        "method" => "post",
                        "action" => "editCategory'"
                    ));
                    break;
                case "editTopic":
                    adminGateKeeper();
                    $title = "Edit Forum Topic";
                    $body = drawForm(array(
                        "name" => "edit_topic",
                        "method" => "post",
                        "action" => "editTopic"
                    ));
                    break;
            }
            $this->html = drawPage(array(
                "header" => $title,
                "body" => $body,
                "button" => $buttons,
                "breadcrumbs" => $breadcrumbs
            ));
        }

    }

}