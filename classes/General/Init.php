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

/**
 * Class that initializes website
 */
class Init {

    /**
     * Initializes website
     */
    public function __construct() {
        self::loadDefaultAccessHandlers();
        self::loadDefaultStorageTypes();
        self::loadDefaultMetatags();
        self::loadDefaultActivityTabs();
        self::loadDefaultSettings();
        self::loadDefaultRegistrationFields();
        self::loadPluginAutoloaders();
        self::loadDefaultCSS();
        self::loadDefaultHeaderJS();
        self::loadDefaultFooterJS();
        self::loadDefaultViewExtensions();
        self::loadDefaultAdmintabs();
        self::loadDefaultMenus();
        self::loadDefaultTranslations();
        self::loadPluginClasses();
        self::createTranslationString();
        self::createHooksCache();
        self::handleActions();
    }

    static function loadDefaultTranslations() {
        new SiteTranslation;
    }

    static function createTranslationString() {
        Translation::writeToFileCache();
    }

    static function createHooksCache() {
        $test = Cache::get("hooks", "site");
        if (!$test) {
            $hooks = Cache::get("hooks", "session");
            new Cache("hooks", $hooks, "site");
        }
        return;
    }

    /**
     * Cron tab for sites without cron access
     */
    static function poorMansCron() {
        $intervals = array(
            "minute",
            "five",
            "fifteen",
            "hour",
            "week"
        );
        foreach ($intervals as $interval) {
            $entity = getEntity(array(
                "type" => "Cron",
                "metadata_name" => "interval",
                "metadata_value" => $interval
            ));
            if (!$entity) {
                $entity = new Cron;
                $entity->interval = $interval;
                $entity->save();
            }
            Cron::run($interval, false);
        }
        return;
    }

    /**
     * Loads default access handlers
     */
    static function loadDefaultAccessHandlers() {
        $access_handlers = Cache::get("access_handlers", "site");
        if (!$access_handlers) {
            $default_access_handlers = json_decode(file_get_contents(getSitePath() . "data/default_access_handlers.json"));
            foreach ($default_access_handlers as $handler) {
                new Accesshandler($handler);
            }
        }
        return;
    }

    /**
     * Loads default registration fields
     */
    static function loadDefaultRegistrationFields() {
        $registration_fields = Cache::get("registration_fields", "site");
        if (!$registration_fields) {
            $default_registration_fields = json_decode(file_get_contents(getSitePath() . "data/default_registration_fields.json"), true);
            foreach ($default_registration_fields as $field) {
                new Registrationfield($field['name'], $field['label'], $field['field_type'], $field['weight']);
            }
        }
        return;
    }

    /**
     * Loads default CSS
     */
    static function loadDefaultCSS() {
        new CSS("bootstrap", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css", 400, false);
        new CSS("sweet_alert", getSitePath() . "assets/vendor/sweetalert/sweet-alert.css", 590);
        $base_theme = Setting::get("base_theme");
        if (isset($base_theme) && ($base_theme != "default") && ($base_theme != NULL)) {
            new CSS("base_theme", getSitePath() . "assets/css/bootswatch/{$base_theme}.css", 1399);
        } else {
            removeCSS("base_theme");
        }
        new CSS("bootstrap_flat", getSitePath() . "assets/vendor/bootstrap-flat/css/bootstrap-flat.css", 590);
        new CSS("bootstrap_flat_extras", getSitePath() . "assets/vendor/bootstrap-flat/css/bootstrap-flat-extras.css", 590);
        new CSS("fontawesome", "https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css", 410, false);
        new CSS("webui_popover", getSitePath() . "assets/vendor/webui-popover/dist/jquery.webui-popover.min.css", 550);
        new CSS("lightbox", getSitePath() . "assets/vendor/lightbox/dist/css/lightbox.css", 570);
        new CSS("jquery_ui_bootstrap", getSitePath() . "assets/vendor/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css", 580);
        new CSS("ionicons", "http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css", 590);
        new CSS("site", getSitePath() . "assets/css/site.css", 1400);
        return;
    }

    /**
     * Loads default header javascript
     */
    static function loadDefaultHeaderJS() {
        new HeaderJS("spinjs", getSiteURL() . "assets/vendor/spin.js/spin.js", 500);
        new HeaderJS("tinymce", getSiteURL() . "assets/vendor/tinymce/tinymce.min.js", 501);
        return;
    }

    /**
     * Loads default footer javascript
     */
    static function loadDefaultFooterJS() {
        new FooterJS("hover_intent", getSiteURL() . "assets/js/jquery.hoverIntent.minified.js", 400);
        new FooterJS("jquery_ui", "https://code.jquery.com/ui/1.11.4/jquery-ui.min.js", 500);
        new FooterJS("bootstrap", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js", 501);
        new FooterJS("hoverintent", getSiteURL() . "assets/vendor/jquery-hoverintent/jquery.hoverIntent.js", 503);
        new FooterJS("timeago", getSiteURL() . "assets/vendor/jquery-timeago/jquery.timeago.js", 506);
        new FooterJS("webui_popover", getSiteURL() . "assets/vendor/webui-popover/dist/jquery.webui-popover.min.js", 507);
        new FooterJS("typeahead", getSiteURL() . "assets/vendor/bootstrap3-typeahead/bootstrap3-typeahead.min.js", 508);
        new FooterJS("lightbox", getSiteURL() . "assets/vendor/lightbox/dist/js/lightbox.min.js", 509);
        new FooterJS("masonry", getSiteURL() . "assets/vendor/masonry/dist/masonry.pkgd.min.js", 510);
        new FooterJS("imagesloaded", getSiteURL() . "assets/vendor/imagesloaded/imagesloaded.pkgd.min.js", 520);
        new FooterJS("jquery-color", "https://code.jquery.com/color/jquery.color-2.1.2.min.js", 540);
        new FooterJS("sweet-alert", getSiteURL() . "assets/vendor/sweetalert/sweet-alert.min.js", 550);
        new FooterJS("typeahead", getSiteURL() . "assets/vendor/typeahead/bootstrap3-typeahead.min.js", 560);
        new FooterJS("google-maps", "http://maps.google.com/maps/api/js?libraries=places", 570);
        new FooterJS("location", getSiteURL() . "assets/js/jquery.geocomplete.min.js", 580);
        new FooterJS("notify", getSiteURL() . "assets/vendor/notify.min.js", 590);
        new FooterJS("sitejs", getSiteURL() . "assets/js/site_js.php", 90000, true);
        return;
    }

    /**
     * Load default view extensions
     */
    static function loadDefaultViewExtensions() {
        new ViewExtension("page_elements/foot", "page_elements/footer_menu");
        if (adminLoggedIn()) {
            new ViewExtension("user/buttons", "admin/login_as");
        } else {
            ViewExtension::remove("user/buttons", "admin/login_as");
        }
        return;
    }

    /**
     * Load default menus
     */
    static function loadDefaultMenus() {

        if (isEnabledPlugin("members") || isEnabledPlugin("groups")) {
            $params = array(
                "name" => "directory",
                "label" => "<i class='icon ion-information-circled'></i><p>Directory</p>",
                "page" => "#",
                "menu" => "header_left",
                "weight" => 1
            );

            new MenuItem($params);
        }
        if (!loggedIn()) {
            $params = array(
                "name" => "login",
                "label" => "Login",
                "page" => "login",
                "menu" => "my_account_right",
                "weight" => 10
            );
            new MenuItem($params);

            $params = array(
                "name" => "register",
                "label" => "Register",
                "page" => "register",
                "menu" => "my_account_right",
                "weight" => 10
            );
            new MenuItem($params);
        } else {

            $params = array(
                "name" => "my_account",
                "label" => "My Account",
                "page" => "#",
                "menu" => "header_right",
                "list_class" => "visible-xs visible-sm"
            );

            new MenuItem($params);


            $params = array(
                "name" => "dashboard",
                "label" => "Dashboard",
                "page" => "home",
                "menu" => "my_account",
                "weight" => 0
            );

            new MenuItem($params);

            $notifications = Notification::getNotificationCount(getLoggedInUserGuid());
            if (!$notifications) {
                $notifications = 0;
            }

            $params = array(
                "name" => "notifications",
                "label" => "Notifications <span class='badge'>$notifications</span>",
                "page" => "notifications",
                "menu" => "my_account",
                "weight" => 10
            );
            new MenuItem($params);

            $params = array(
                "name" => "logout",
                "label" => "Logout",
                "page" => "action/logout",
                "menu" => "my_account_right",
                "weight" => 9000
            );
            new MenuItem($params);

            $params = array(
                "name" => "my_settings",
                "label" => "My Settings",
                "page" => "mySettings",
                "menu" => "my_account_right",
                "weight" => 100
            );
            new MenuItem($params);

            if (adminLoggedIn()) {
                $params = array(
                    "name" => "admin",
                    "label" => "<i class='icon ion-gear-b'></i><p>Admin</p>",
                    "page" => "admin",
                    "menu" => "header_right"
                );
                new MenuItem($params);
            }
        }
        return;
    }

    /**
     * Load default field types
     */
    static function loadDefaultFieldTypes() {
        new FieldType("text", "Text");
        new FieldType("dropdown", "Dropdown");
        new FieldType("email", "Email");
        new FieldType("textarea", "Textarea");
        new FieldType("editor", "Editor");
        new FieldType("video", "Video");
        return;
    }

    /**
     * Load default storage types
     */
    static function loadDefaultStorageTypes() {
        new StorageType("Custompage", "label", "text");
        new StorageType("Custompage", "title", "text");
        new StorageType("Custompage", "body", "text");
        new StorageType("Plugin", "plugin_order", "int");
        new StorageType("User", "password", "text");
        new StorageType("User", "email", "index");
        new StorageType("User", "session", "index");
        new StorageType("User", "email_verification_code", "text");
        new StorageType("User", "avatar", "text");
        new StorageType("User", "url", "text");
        new StorageType("Plugin", "requires", "text");
        new StorageType("Setting", "options", "text");
        new StorageType("Setting", "value", "text");
        new StorageType("Notification", "link", "text");
        new StorageType("Notification", "message", "text");
        new StorageType("Activity", "message", "text");
        new StorageType("Activity", "text", "text");
        new StorageType("Activity", "params", "text");
        new StorageType("Usersetting", "options", "text");
        new StorageType("Usersetting", "name", "text");
        new StorageType("BlacklistEmail", "email", "text");
        return;
    }

    /**
     * Handles all form actions
     */
    static function handleActions() {
        if (currentPage() == "action") {
            $ignore = array(
                "logout",
                "verifyEmail"
            );
            $action = pageArray(1);
            if ($action) {
                if (!in_array($action, $ignore)) {
                    Security::tokenGatekeeper();
                }
                $action_class = "SociaLabs\\" . ucfirst($action) . "ActionHandler";
                if (class_exists($action_class)) {
                    new $action_class;
                } else {
                    forward("home");
                }
            } else {
                forward("home");
            }
            exit();
        } else {
            return true;
        }
    }

    /**
     * Load default settings
     */
    static function loadDefaultSettings() {
        new Setting("home_page", "editor", "", "home_page");
        new Setting("site_name", "text", "", "general", SITENAME);
        new Setting("site_email", "email", "", "general", SITEEMAIL);
        new Setting("base_theme", "dropdown", array(
            "default" => "Default",
            "cerulean" => "Cerulean",
            "cosmo" => "Cosmo",
            "cyborg" => "Cyborg",
            "darkly" => "Darkly",
            "flatly" => "Flatly",
            "journal" => "Journal",
            "lumen" => "Lumen",
            "paper" => "Paper",
            "readable" => "Readable",
            "sandstone" => "Sandstone",
            "simplex" => "Simplex",
            "slate" => "Slate",
            "spacelab" => "Spacelab",
            "superhero" => "Superhero",
            "united" => "United",
            "yeti" => "Yeti"
        ));
        new Setting("default_access", "dropdown", Access::accessHandlerDropdown());
        new Setting("debug_mode", "dropdown", array(
            "no" => "No",
            "yes" => "Yes"
        ));
        new Setting("wrap_views", "dropdown", array(
            "no" => "No",
            "yes" => "Yes"
        ));
        new Setting("show_translations", "dropdown", array(
            "no" => "No",
            "yes" => "Yes"
        ));
        new Setting("hide_socia_link", "dropdown", array(
            "no" => "No",
            "yes" => "Yes"
        ));
    }

    /**
     * Loads default admin tabs
     */
    static function loadDefaultAdmintabs() {
        if (adminLoggedIn()) {
            new Admintab("custom_pages");
            new Admintab("general");
            new Admintab("home_page");
            new Admintab("hooks");
            new Admintab("logo");
            new Admintab("plugins");
            new Admintab("seo");
            new Admintab("tables");
            new Admintab("users");
            new Admintab("view_extensions");
        }
        return;
    }

    /**
     * Loads default activity tabs
     */
    static function loadDefaultActivityTabs() {
        new ActivityTab("all", 500);
        if (loggedIn()) {
            new ActivityTab("mine", 500);
        }
        return;
    }

    /**
     * Loads autoloaders from enabled plugins
     */
    static function loadPluginAutoloaders() {
        $plugins = Plugin::getEnabledPlugins(false);
        if ($plugins) {
            foreach ($plugins as $plugin) {
                if (is_a($plugin, "SociaLabs\\Plugin")) {

                    $plugin_name = $plugin->name;
                    if (file_exists(SITEPATH . "plugins/$plugin_name/vendor/autoload.php")) {
                        require_once(SITEPATH . "plugins/$plugin_name/vendor/autoload.php");
                    }
                }
            }
        }
        return;
    }

    /**
     * Load plugin classes from enabled plugins
     */
    static function loadPluginClasses() {
        $plugins = Plugin::getEnabledPlugins(false);
        if ($plugins) {
            foreach ($plugins as $plugin) {
                if (is_a($plugin, "SociaLabs\\Plugin")) {
                    $plugin_class_name = "SociaLabs\\" . ucfirst($plugin->name) . "Plugin";
                    $translation_class_name = "SociaLabs\\" . ucfirst($plugin->name) . "Translation";
                    if (!class_exists($plugin_class_name, false)) {
                        if (class_exists($plugin_class_name)) {
                            if (class_exists($translation_class_name)) {
                                new $translation_class_name;
                            }
                            new $plugin_class_name;
                        }
                    }
                }
            }
        }
        return;
    }

    /**
     * Loads default metatags
     */
    static function loadDefaultMetatags() {
        $metatags = array(
            "activity" => array(
                "title" => getSiteName() . " | Activity Stream",
                "description" => ""
            ),
            "addcircle" => array(
                "title" => getSiteName() . " | Add a Circle",
                "description" => ""
            ),
            "admin" => array(
                "title" => getSiteName() . " | Admin",
                "description" => ""
            ),
            "blogs" => array(
                "title" => getSiteName() . " | Blogs",
                "description" => ""
            ),
            "createcustompage" => array(
                "title" => getSiteName() . " | Create Custom Page",
                "description" => ""
            ),
            "editavatar" => array(
                "title" => getSiteName() . " | Edit Your Avatar",
                "description" => ""
            ),
            "editcustompage" => array(
                "title" => getSiteName() . " | Edit Custom Page",
                "description" => ""
            ),
            "editprofile" => array(
                "title" => getSiteName() . " | Edit Your Profile",
                "description" => ""
            ),
            "emailsent" => array(
                "title" => getSiteName() . " | Email Sent",
                "description" => ""
            ),
            "files" => array(
                "title" => getSiteName() . " | Files",
                "description" => ""
            ),
            "file" => array(
                "title" => getSiteName() . " | File",
                "description" => ""
            ),
            "followers" => array(
                "title" => getSiteName() . " | Your Followers",
                "description" => ""
            ),
            "forgotpassword" => array(
                "title" => getSiteName() . " | Forgot Password",
                "description" => ""
            ),
            "forum" => array(
                "title" => getSiteName() . " | Forum",
                "description" => ""
            ),
            "friends" => array(
                "title" => getSiteName() . " | " . translate("your_friends"),
                "description" => ""
            ),
            "friendrequests" => array(
                "title" => getSiteName() . " | Friend Requests",
                "description" => ""
            ),
            "groups" => array(
                "title" => getSiteName() . " | Groups",
                "description" => ""
            ),
            "home" => array(
                "title" => getSiteName() . " | Home",
                "description" => ""
            ),
            "inbox" => array(
                "title" => getSiteName() . " | Your Inbox",
                "description" => ""
            ),
            "insertFile" => array(
                "title" => getSiteName() . " | Insert File",
                "description" => ""
            ),
            "login" => array(
                "title" => getSiteName() . " | Login",
                "description" => ""
            ),
            "members" => array(
                "title" => getSiteName() . " | Site Members",
                "description" => ""
            ),
            "mySettings" => array(
                "title" => getSiteName() . " | My Settings",
                "description" => ""
            ),
            "notifications" => array(
                "title" => getSiteName() . " | Notifications",
                "description" => ""
            ),
            "pages" => array(
                "title" => getSiteName() . " | Pages",
                "description" => ""
            ),
            "passwordresetemailsent" => array(
                "title" => getSiteName() . " | Password Reset Email Sent",
                "description" => ""
            ),
            "photos" => array(
                "title" => getSiteName() . " | Photos",
                "description" => ""
            ),
            "profile" => array(
                "title" => getSiteName() . " | Profile",
                "description" => ""
            ),
            "register" => array(
                "title" => getSiteName() . " | Register",
                "description" => ""
            ),
            "searchFriends" => array(
                "title" => getSiteName() . " | Search " . translate("friends"),
                "description" => ""
            ),
            "terms" => array(
                "title" => getSiteName() . " | Terms",
                "description" => ""
            ),
            "videos" => array(
                "title" => getSiteName() . " | Videos",
                "description" => ""
            ),
        );
        foreach ($metatags as $page => $vars) {
            new Metatag($page, "title", $vars['title']);
            new Metatag($page, "description", $vars['description']);
        }
        return true;
    }

}
