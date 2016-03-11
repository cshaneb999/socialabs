<?php

/**
 * PHP version 5
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 SociaLabs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 *
 * @author     Shane Barron <admin@socialabs.co>
 * @author     Aaustin Barron <aaustin@socialabs.co>
 * @copyright  2015 SociaLabs
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    1
 * @link       http://socialabs.co
 */

namespace SociaLabs;

class Page {

    /**
     * Draws the page
     */
    static function draw() {
        $page = self::urlArray(0);
        if ($page) {
            $page_handler_class = "SociaLabs\\" . ucfirst($page) . "PageHandler";
        } else {
            $page_handler_class = "SociaLabs\\HomePageHandler";
        }
        if (!class_exists($page_handler_class)) {
            forward("notfound");
        }
        Vars::clear();
        $body = (new $page_handler_class)->view();
        Vars::clear();
        $header = display("page_elements/header");
        Vars::clear();
        $nav = display("page_elements/navigation");
        Vars::clear();
        $footer = display("page_elements/footer");
        Vars::clear();
        echo $header;
        echo $nav;
        echo $body;
        echo $footer;
        Debug::clear();
        Dbase::con()->close();
        die();
    }

    /**
     * Returns a formatted Bootstrap header, container, and button
     * 
     * @param string $header    Page header html
     * @param string $body  Page body html
     * @param string $button    Button html
     * @return html 
     */
    static function drawPage($header = array(), $body = false, $button = false) {
        if (is_array($header)) {
            $params = $header;
            $defaults = array(
                "header"        => NULL,
                "button"        => NULL,
                "wrapper_class" => NULL,
                "breadcrumbs"   => NULL,
                "footer"        => NULL
            );

            $params = array_merge($defaults, $params);
        } else {
            $params = array(
                "header" => $header,
                "body"   => $body,
                "button" => $button
            );
        }

        return display("page_elements/container", $params);
    }

    /**
     * Returns array of url elements
     * 
     * @param int $index    Index of array to return.
     * @return string false if no value, array if $index not passed, page name if value
     */
    static function urlArray($index = NULL) {
        $page = getInput("page");
        if (!$page) {
            $page = "home";
        }
        $page = htmlspecialchars($page);
        $page_array = explode("/", $page);
        if ($index === NULL) {
            return $page_array;
        } else {
            if (isset($page_array[$index])) {
                return $page_array[$index];
            }
        }
        return false;
    }

    /**
     * Forwards to url
     * 
     * @param type $string  String to add to getSiteURL
     * @param array $variables Variables to add to url as get variables
     */
    static function forward($string = false, $variables = array()) {
        $args = NULL;
        if (!empty($variables)) {
            $args = "?" . http_build_query($variables);
        }
        if ($string) {
            if (strpos($string, getSiteURL()) === false) {
                header("Location: " . getSiteURL() . $string . $args);
            } else {
                header("Location: " . $string) . $args;
            }
            exit();
        } else {
            if (isset($_SERVER["HTTP_REFERER"])) {
                $referer = htmlspecialchars($_SERVER["HTTP_REFERER"]);
                $referer = strtok($referer, '?');
                header("Location: " . $referer . $args);
                exit();
            } else {
                header("Location: " . getSiteURL() . $args);
                exit();
            }
        }
    }

    /**
     * Returns rendered html from view path
     * 
     * @global array $vars
     * @param string $path Path of view to render
     * @param array $variables Array of arguments to pass to view (passed as $vars)
     * @return string HTML rendered view
     */
    static function display($path, $variables = array()) {
        if (!isset($variables['value'])) {
            $variables['value'] = NULL;
        }
        if (!isset($variables['container'])) {
            $variables['container'] = true;
        }
        $return = NULL;
        if (Setting::get("wrap_views") == "yes") {
            $return = "<!-- $path -->";
        }
        $view_exists = false;
        $plugin_view = false;
        foreach ($variables as $name => $value) {
            new Vars($name, $value);
        }
        $plugins = Plugin::getEnabledPlugins(true);
        $static_vars = Cache::get("vars", "session");
        $return = runHook("view:before", array(
            "view"   => $path,
            "return" => $return
        ));
        new Cache("vars", $static_vars, "session");
        $return .= ViewExtension::display($path, "before");
        if ($plugins) {
            foreach ($plugins as $plugin) {
                if (is_a($plugin, "SociaLabs\\Plugin")) {
                    $name = $plugin->name;
                    if (!$plugin_view) {
                        if (file_exists(SITEPATH . "plugins/$name/views/$path.php")) {
                            $return .= self::getRenderedHTML(SITEPATH . "plugins/$name/views/$path.php");
                            $plugin_view = true;
                            $view_exists = true;
                        } elseif (file_exists(SITEPATH . "core_plugins/$name/views/$path.php")) {
                            $return .= self::getRenderedHTML(SITEPATH . "core_plugins/$name/views/$path.php");
                            $plugin_view = true;
                            $view_exists = true;
                        }
                    }
                }
            }
        }

        if (!$plugin_view) {
            $file_path = SITEPATH . "views/" . $path . ".php";
            if (file_exists($file_path)) {
                $view_exists = true;
                $return .= self::getRenderedHTML($file_path);
            }
        }

        new Cache("vars", $static_vars, "session");
        $return .= viewExtension::display($path, "after");

        return $return;
    }

    /**
     * Returns rendered html from path
     *
     * @param string path Path to file to be rendered
     * @param string $path
     * @return string Rendered html
     */
    static function getRenderedHTML($path) {
        ob_start();
        include($path);
        $var = ob_get_contents();
        ob_end_clean();
        return $var;
    }

    /**
     * Renders a form
     * 
     * @param array $params parameters to pass to the form including:
     *                      "form_params" array of parameters to pass to form view
     *                      "action" name of action file
     *                      "method" method to use with form, usually "get" or "post"
     *                      "page" used if method is "get"  
     *                      * other params are passed to the form element as arguments
     * @return string Rendered form
     */
    static function drawForm($params) {
        $name = (isset($params['name']) ? $params['name'] : "");
        unset($params['name']);
        $leader = (isset($params['leader']) ? $params['leader'] : false);
        $url = getSiteURL();
        $body = NULL;
        $target_page = NULL;
        $action = (isset($params['action']) ? $params['action'] : "");
        if ($action || $params ['method'] == "get") {
            unset($params['action']);
            if (isset($params['page'])) {
                $target_page = $params['page'];
                unset($params['page']);
            }
            $form = display("forms/$name", $params);

            $token = Security::generateToken();
            new Cache("token", $token, "session");
            if (isset($params['files']) && $params['files'] == true) {
                $params['enctype'] = "multipart/form-data";
                unset($params['files']);
            }
            unset($params['inputs']);
            $args = arrayToArgs($params);
            if ($leader) {
                $body = "<div class='label'>$leader</div>";
            }
            if ($params ['method'] != "get") {
                $body .= "<form action='{$url}action/$action?token=$token' $args>";
            } else {
                $body .= "<form action='{$url}$target_page?token=$token' $args>";
            }
            $body .= <<<HTML
$form
</form>
HTML;

            return $body;
        }

        return "Form has no action.";
    }

    /**
     * Retrieves the current page
     * 
     * @return string Current page
     */
    static function currentPage() {
        if (self::urlArray(0)) {
            return self::urlArray(0);
        } else {
            return "home";
        }
    }

}
