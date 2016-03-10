<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Class that creates a new menuitem
 */
class MenuItem {

    /**
     * Constructs a new menuitem
     * 
     */
    public function __construct($params) {
        if (!$params) {
            $params = array();
        }
        global $menus;
        $defaults = array(
            "name" => NULL,
            "label" => NULL,
            "page" => NULL,
            "menu" => "header_left",
            "weight" => 500,
            "link_class" => NULL,
            "button_class" => NULL,
            "external" => NULL,
            "action" => false,
            "confirm" => false,
            "list_class" => NULL
        );
        $params = array_merge($defaults, $params);
        if ($params['name'] && !$params['page']) {
            $params['page'] = $params['name'];
        }
        if (!isset($menus[$params['menu']][$params['name']])) {
            $menu_item = new \stdClass;
            $menu_item->name = isset($params['name']) ? $params['name'] : "";
            $menu_item->label = isset($params['label']) ? $params['label'] : "";
            $menu_item->page = isset($params['page']) ? $params['page'] : "";
            $menu_item->weight = isset($params['weight']) ? $params['weight'] : "";
            $menu_item->menu = isset($params['menu']) ? $params['menu'] : "";
            $menu_item->link_class = isset($params['link_class']) ? $params['link_class'] : "";
            $menu_item->button_class = isset($params['button_class']) ? $params['button_class'] : "";
            $menu_item->external = isset($params['external']) ? $params['external'] : "";
            $menu_item->list_class = isset($params['list_class']) ? $params['list_class'] : "";
            $menus[$params['menu']][$params['name']] = $menu_item;
        }
    }

    /**
     * Returns array of menu items
     * 
     * @global type $menus  All site menu items
     * @param type $name  Name of menu item
     * @param type $menu  Menu that menu item belongs to
     * @return mixed  array of menu items, or false if no menu items exist
     */
    static function get($name, $menu) {
        global $menus;
        if (isset($menus[$menu][$name])) {
            return $menus[$menu][$name];
        }
        return false;
    }

    /**
     * Updates a menu item
     * 
     * @global array $menus All site menu items
     * @param object $menu_item Menu item to update
     */
    static function update($menu_item) {
        global $menus;
        $menu_name = $menu_item->menu;
        $name = $menu_item->name;
        $menus[$menu_name][$name] = $menu_item;
    }

    /**
     * Returns a string of menu items
     * 
     * @param string $menu_name Name of menu
     * @return string   Formatted menu
     */
    static function getAll($menu_name, $item_class = "", $parent = "ul", $child = "li", $child_wrapper = true) {
        global $menus;
        $return = NULL;
        if (!empty($menus[$menu_name])) {
            $menuarray = $menus[$menu_name];
            if (!empty($menuarray)) {
                if (count($menuarray) > 1) {
                    uasort($menuarray, function($a, $b) {
                        if ($a->weight == $b->weight) {
                            return ($a->name < $b->name) ? -1 : 1;
                        }

                        return ($a->weight < $b->weight) ? -1 : 1;
                    });
                }
                foreach ($menuarray as $menuitemname => $menu) {
                    $list_class = isset($menu->list_class) ? $menu->list_class : "";
                    if (isset($menu->page) && ($menu->page == "#") || (!isset($menu->page))) {
                        $submenu = getMenuItems($menuitemname);
                        $return .= "<$child class='dropdown $item_class $list_class'>";
                        $return .= "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>" . $menu->label . "</a>";
                        $return .= "<$parent class='dropdown-menu' role='menu'>";
                        $return .= $submenu;
                        $return .= "</$parent>";
                        $return .= "</$child>";
                    } else {
                        $link_class = (isset($menu->link_class) && $menu->link_class ? $menu->link_class : NULL);
                        $external = $menu->external;
                        if (!$external) {
                            $link = getSiteURL() . $menu->page;
                            if (strpos($link, 'action') !== false) {
                                $link = addTokenToURL($link);
                            }
                        } else {
                            $link = $menu->page;
                        }
                        $label = $menu->label;
                        $active_class = (($menuitemname == currentPage()) || ($menu->page == currentPage())) ? "active" : NULL;
                        if ($child_wrapper) {
                            $return .= "<$child class='$active_class $item_class $list_class'>";
                        }
                        if ($menu->button_class) {
                            $return .= "<form action='$link'>";
                            $return .= "<button class='{$menu->button_class}'>$label</button>";
                            $return .= "</form>";
                        } else {
                            $return .= "<a class='$link_class' href='$link'>$label</a>";
                        }
                        if ($child_wrapper) {
                            $return .= "</$child>";
                        }
                    }
                }
                return $return;
            }
        }
        return NULL;
    }

    /**
     * Removes a menu item from the stack
     * 
     * @param string $name  Name of menu item to remove
     * @param string $menu  Name of menu
     * @return boolean true
     */
    static function remove($name, $menu = "header_left") {
        global $menus;
        if (isset($menus[$menu][$name])) {
            unset($menus[$menu][$name]);
            new Cache("menus", $menus);
        }
        return true;
    }

}
