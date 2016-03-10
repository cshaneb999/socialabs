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
 * Shortcut function
 * 
 * Creates new Accesshandler
 * 
 * @param string $handler Name of the handler that needs to be created.
 */
function createAccessHandler($handler) {
    new Accesshandler($handler);
    return;
}

/**
 * Shortcut function
 * 
 * Returns array of all access handlers.
 * 
 * @return array Array of access handlers.
 */
function getAllAccessHandlers() {
    return Accesshandler::getAll();
}

/**
 * Shortcut function
 * 
 * Returns accesshandler
 * 
 * @param string $handler Name of handler to return
 * @return mixed  false if no handler found, string if handler found
 */
function getAccessHandler($handler) {
    return Accesshandler::get($handler);
}

/**
 * Shortcut function
 * 
 * Creates an activity tab
 * 
 * @param string $name  Name of the activity tab
 * @param integer $weight  Weight of the activity tab. Higher numbers loaded later.
 * @param string $buttons Buttons to display when tab is active
 */
function createActivityTab($name, $weight = 500, $buttons = array()) {
    new ActivityTab($name, $weight, $buttons);
    return;
}

/**
 * Shortcut function
 * 
 * Returns admin tab if $name provided, otherwise returns array of all admin tabs
 * 
 * @param string $name  Name of tab to return (if not provided, returns array of all admin tabs)
 * @return mixed  Admin tab by name if provided, array of admin tabs if not.
 */
function getActivityTab($name = false) {
    return ActivityTab::get($name);
}

/**
 * Shortcut function
 * 
 * Removes admin tab from the stack
 * 
 * @param string $name  Name of admin tab to remove
 */
function removeActivityTab($name) {
    return ActivityTab::remove($name);
}

/**
 * Shortcut function
 * 
 * Creates an admin tab
 * 
 * @param string $name  Name of the admin tab
 * @param int $weight   Weight of the admin tab (larger numbers 
 * @param string $buttons Buttons to show on the admin panel
 */
function createAdmintab($name, $weight = 500, $buttons = array()) {
    new Admintab($name, $weight, $buttons);
    return;
}

/**
 * Shortcut function
 * 
 * Returns an admin tab or array of all admin tabs
 * 
 * @param string $name  if provided, returns admin tab corresponding to that name, otherwise returns array of all admin tabs
 * @return mixed  Either named admin tab, or array of all admin tabs.
 */
function getAdmintab($name = false) {
    return Admintab::get($name);
}

/**
 * Shortcut function
 * 
 * Adds attribute to element
 * 
 * @param string $name  Name of the element
 * @param string $attribute Name of the attribute
 * @param type $value   Value of the attribute
 */
function createAttribute($name, $attribute, $value) {
    new Attribute($name, $attribute, $value);
    return;
}

/**
 * Shortcut function
 * 
 * Adds css to the stack
 * 
 * @param string $name Arbitrary name of the css (used primarily for removeCSS)
 * @param string $css Link to CSS
 * @param int $weight Weight of CSS (bigger numbers sink to the bottom of the stack)
 */
function createCSS($name, $css, $weight = 500, $combine = true) {
    new CSS($name, $css, $weight, $combine);
    return;
}

/**
 * Shortcut function
 * 
 * Tells the system whether or not to ignore access restrictions to entities
 * 
 * @param boolean $value    true to ignore access, false otherwise
 */
function setIgnoreAccess($value = true) {
    return Access::setIgnoreAccess($value);
}

/**
 * Shortcut function
 * 
 * Returns the current "ignore access" setting
 * 
 * @return boolean true if ignore access, false if not
 */
function getIgnoreAccess() {
    return Access::getIgnoreAccess();
}

/**
 * Shortcut function
 * 
 * Returns translation of string
 * 
 * @param string $string    String to be translated
 * @param array $args   Arguments to pass to string
 * @return string   Translated string
 */
function translate($string, $args = array()) {
    return Translation::translate($string, $args);
}

/**
 * Shortcut function
 * 
 * Function to get entities
 * 
 * @param array $params Array of parameters to get objects by, including:
 *                      "limit" Limit of items to return
 *                      "offset" Offset of items to return
 *                      "type" Type of items to get
 *                      "metadata_name" Used when getting based on metadata, but only one metadata name=>value is required.
 *                      "metadata_value" Used with metadata_name
 *                      "operand" Operand to use with metadata_name and metadata_value, defaults to =
 *                      "metadata_name_value_pairs" array of "name","value" pairs.  metadata_name_value pairs cannot be used with metadata_name, metadata_value
 *                      "metadata_name_value_pairs_operand" operand to use between metadata_name_value_pairs (defaults to AND)
 *                      "count" if set to true, function returns count of objects as opposed to array of objects or guids
 * @param boolean $return_object Whether to return objects or guids as array (true to return objects, false to return guids)
 * @return mixed array Array of objects, or boolean false if no objects are found
 */
function getEntities($params, $return_object = true) {
    return Entity::getEntities($params, $return_object);
}

/**
 * Shortcut function
 * 
 * Returns an entity based on the given guid, or first entity based on params array (like get Entities, but returns only the first entity)
 * 
 * @param int $params Guid of the entity to return, or array of params to pass to getEntities
 * @return boolean|classname False if entity doesn't exist|Entity
 */
function getEntity($params, $ignore_access = false) {
    return Entity::getEntity($params, $ignore_access);
}

/**
 * Shortcut function
 * 
 * Returns a count of notifications for a given guid
 * 
 * @param int $guid Guid of user to get notifications for
 * @return int  Count of notifications
 */
function getNotifictionCount($guid) {
    return Notification::getNotificationCount($guid);
}

/**
 * Shortcut function
 * 
 * Creates a notification object, and sends email to user
 * 
 * @param int $user_guid Guid of user to notify
 * @param string $message   Message to send to user
 * @param string $link  Link to object
 */
function notifyUser($user_guid, $message, $link) {
    return Notification::notifyUser($user_guid, $message, $link);
}

/**
 * Shortcut function
 * 
 * Returns a url for an image
 * 
 * @param int $guid Guid of image
 * @param int $thumbnail    Width of thumbnail
 * @return string   Url for image
 */
function getImageURL($guid, $thumbnail = NULL) {
    return Image::getImageURL($guid, $thumbnail);
}

/**
 * Shortcut function
 * 
 * Returns a formatted Bootstrap header, container, and button
 * 
 * @param string $body  Page body html
 * @param string $button    Button html
 * @return html 
 */
function drawPage($title = array(), $body = false, $button = false) {
    return Page::drawPage($title, $body, $button);
}

/**
 * Shortcut function
 * 
 * Gets an input (get, post, or cookie)
 *
 * @param string $name Name of input to get
 * @return mixed Output, or false if no output
 */
function getInput($name, $default = NULL, $allow_get = true) {
    return Security::getInput($name, $default, $allow_get);
}

/**
 * Shortcut function
 * 
 * Removes a view extension for the stack
 * 
 * @param string $target    View extension target
 * @param string $source    View extension source
 * @return boolean true if removed|false if not
 */
function removeViewExtension($target, $source) {
    return ViewExtension::remove($target, $source);
}

/**
 * Shortcut function
 * 
 * Returns a string of menu items
 * 
 * @param string $menu_name Name of menu
 * @return string   Formatted menu
 */
function getMenuItems($menu_name, $item_class = "", $parent = "ul", $child = "li", $child_wrapper = true) {
    return MenuItem::getAll($menu_name, $item_class, $parent, $child, $child_wrapper);
}

/**
 * Shortcut function
 * 
 * Removes a menu item from the stack
 * 
 * @param string $name  Name of menu item to remove
 * @param string $menu  Name of menu
 * @return boolean true
 */
function removeMenuItem($name, $menu = "header_left") {
    return MenuItem::remove($name, $menu);
}

/**
 * Shortcut function
 * 
 * Prevents non logged in users from viewing a page
 * 
 * @return boolean|null true if logged in, or forwards to home if not logged in
 */
function gateKeeper() {
    Security::gateKeeper();
    return;
}

/**
 * Shortcut function
 * 
 * Prevents logged in users from viewing a page
 * 
 * @return boolean|null true if logged out, or forwards to home if not logged out
 */
function reverseGateKeeper() {
    Security::reverseGatekeeper();
    return;
}

/**
 * Shortcut function
 * 
 * Gatekeeper function that checks to make sure an entity is a member of a certain class, and
 * forwards to the index if the check fails
 * 
 * @param string $entity Entity to test
 * @param string $class Class to test against
 * @return boolean true
 */
function classGateKeeper($entity, $class) {
    return Security::classGateKeeper($entity, $class);
}

/**
 * Shortcut function
 * 
 * Prevents non admins from viewing a page
 */
function adminGateKeeper() {
    Security::adminGateKeeper();
    return;
}

/**
 * Shortcut function
 * 
 * Removes css from the stack
 * 
 * @param string $name Name of CSS to remove from the stack
 * @return boolean|null true
 */
function removeCSS($name) {
    CSS::remove($name);
    return;
}

/**
 * Shortcut function
 * 
 * Removes footer javascript from the stack
 * 
 * @param string $name  Name of js to remove
 * @return boolean|null  true if removed, false if not
 */
function removeFooterJS($name) {
    FooterJS::removeFooterJS($name);
    return;
}

/**
 * Shortcut function
 * 
 * Removes header js from the stack
 * 
 * @param string $name  Name of header js to remove
 * @return boolean|null  true if removed, false if not
 */
function removeHeaderJS($name) {
    HeaderJS::removeHeaderJS($name);
    return;
}

/**
 * Shortcut function
 * 
 * Returns array of url elements
 * 
 * @param int $index    Index of array to return.
 * @return string false if no value, array if $index not passed, page name if value
 */
function pageArray($index = NULL) {
    return Page::urlArray($index);
}

/**
 * Shortcut function
 * 
 * Returns saved attributes for given element name
 * 
 * @param string $name  Name of attribute
 * @return string   Formatted attribute string
 */
function getAttributes($name) {
    return Attribute::getAttributes($name);
}

/**
 * Shortcut function
 * 
 * Forwards to url
 * 
 * @param type $string  String to add to getSiteURL
 * @param array $variables Variables to add to url as get variables
 */
function forward($string = false, $variables = array()) {
    return Page::forward($string, $variables);
}

/**
 * Shortcut function
 * 
 * Removes a registration field from the stack
 * 
 * @param string $name  Name of registration field to remove
 */
function removeRegistrationField($name) {
    return Registrationfield::removeRegistrationField($name);
}

/**
 * Shortcut function
 * 
 * @param array $params Email Paramaters.  Includes:
 *                          "to"=>array(
 *                              $name=>$email
 *                              ),
 *                          "from"=>array(
 *                              $name=>$email
 *                              ),
 *                          "subject",
 *                          "body",
 *                          "html"
 */
function sendEmail($params) {
    return Email::sendEmail($params);
}

/**
 * Shortcut function
 * 
 * Returns rendered html from view path
 * 
 * @global array $vars
 * @param string $path Path of view to render
 * @param array $variables Array of arguments to pass to view (passed as $vars)
 * @return string HTML rendered view
 */
function display($path, $variables = array()) {
    return Page::display($path, $variables);
}

/**
 * Shortcut function
 * 
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
function drawForm($params) {
    return Page::drawForm($params);
}

/**
 * Shortcut function
 * 
 * Provides a formatted list of entities based on same parameters as getEntities
 * 
 * @param array $params Array of parameters (see getEntities)
 * @return boolean false|formatted list of entities
 */
function listEntities($params) {
    return Entity::listEntities($params);
}

/**
 * Shortcut function
 * 
 * Renders a list of entities
 * 
 * @param array $entities Array of entities to view
 * @param string $view_type Type of view (list, gallery, etc.)
 * @param string $wrapper_class Class to wrap around list
 * @param string $item_class Class to warap around each item
 * @param boolean $link If true, elements are shown with links
 * @param string|boolean $size Some views accept a size argument to determine the size of the elements view
 * @return boolean false if no elements|string HTML rendered list of items
 */
function viewEntityList($entities, $view_type = "list", $wrapper_class = NULL, $item_class = NULL, $link = true, $size = "medium") {
    return Entity::viewEntityList($entities, $view_type, $wrapper_class, $item_class, $link, $size);
}

/**
 * Shortcut function
 * 
 * Retrieves the current page
 * 
 * @return string Current page
 */
function currentPage() {
    return Page::currentPage();
}

/**
 * Shortcut function
 * 
 * Imports CSV file to entities
 * 
 * @param string $file  Location of file
 * @param string $delimiter "," or "\t" Delimiter used in csv file
 * @param string $type  Type of elements to be created
 * @param array $fields Array of fields
 */
function importCSV($file, $delimiter = ",", $type, $fields = array(), $unique_field = false) {
    return FileSystem::importCSV($file, $delimiter, $type, $fields, $unique_field);
}

/**
 * Shortcut function
 * 
 * Removes admin tabs
 * 
 * @global type $admin_tabs All admin tabs
 * @param type $name  Name of admin tab to remove
 */
function removeAdmintab($name) {
    return Admintab::removeAdmintab($name);
}

/**
 * Shortcut function
 * 
 * Returns site url
 * 
 * @return string Site url
 */
function getSiteURL() {
    return Site::getSiteURL();
}

/**
 * Shortcut function
 * 
 * Returns site name
 * 
 * $return string Site name
 */
function getSiteName() {
    $setting = Setting::get("site_name");
    if ($setting) {
        return $setting;
    }
    return SITENAME;
}

function getSiteEmail() {
    $setting = Setting::get("site_email");
    if ($setting) {
        return $setting;
    }
    return SITEEMAIL;
}

/**
 * Shortcut function
 * 
 * Creates avatar for entity
 * 
 * @param object $entity  Entity to create avatar for
 * @param string $filename  Name of input field (defaults to 'avatar')
 * @param mixed $copy Icon to copy, or false to not copy
 */
function processImageFile($entity, $filename = "avatar", $copy = false) {
    return $entity->createAvatar($filename, $copy);
}

/**
 * Shortcut function
 * 
 * Returns array of admin guids
 * 
 * @return array Array of admin guids
 */
function getAdminGuidarray() {
    return Security::getAdminGuidArray();
}

/**
 * Shortcut function
 * 
 * Checks for empty fields on form submission
 * 
 * @param array $fieldnames Array of fieldnames that shouldn't be empty
 */
function checkForEmptyFields($fieldnames = array()) {
    return Security::checkForEmptyFields($fieldnames);
}

/**
 * Shortcut function
 * 
 * Checks if file field is empty on form submission
 * 
 * @param string $fieldname Fieldname that shouldn't be empty
 */
function checkForEmptyFilefield($fieldname) {
    return Security::checkForEmptyFileField($fieldname);
}

/**
 * Shortcut function
 * 
 * Returns array of online users
 * @return array  Array of online users
 */
function getOnlineUsers() {
    return User::getOnlineUsers();
}

/**
 * Shortcut function
 * 
 * Checks if logged in user can delete entity
 * 
 * @param object $entity  Entity to check
 * @return boolean  true if can delete, false if can't
 */
function loggedInUserCanDelete($entity) {
    return User::loggedInUserCanDelete($entity);
}

/**
 * Shortcut function
 * 
 * Gets site data path
 * 
 * @return string Site data path
 */
function getDataPath() {
    return Site::getDataPath();
}

/**
 * Shortcut function
 * 
 * Gets site path
 * 
 * @return string   Site path
 */
function getSitePath() {
    return Site::getSitePath();
}

/**
 * Shortcut function
 * 
 * Creates file path
 * 
 * @param string $pathname  Path
 * @param int $mode Access mode (0777)
 * @return boolean  true if path is made, false if not
 */
function makePath($pathname, $mode = 0777) {
    return FileSystem::makePath($pathname, $mode);
}

/**
 * Shortcut function
 * 
 * Runs a mysqli query
 *
 * @param string $query Query to run
 * @return boolean next entity guid
 */
function runDbaseQuery($query) {
    return Dbase::query($query);
}

/**
 * Shortcut function
 * 
 * Gets results from mysqli query
 *
 * @global connection $con Mysqli connection
 * @param string $query Mysqli query to run
 * @return mysqli_results Results of query
 */
function getDbaseResults($query) {
    return Dbase::getResults($query);
}

/**
 * Shortcut function
 * 
 * Gets results array from mysqli query
 *
 * @param string $query Mysqli query to run
 * @return array Results array
 */
function getDbaseResultsArray($query) {
    return Dbase::getResultsArray($query);
}

/**
 * Shortcut function
 * 
 * Used to view entity
 * 
 * @param object  $entity Entity to view, or guid of entity to view
 * @return string HTML display of entity
 */
function viewEntity($entity) {
    if (!is_object($entity)) {
        $entity = getEntity($entity);
    }
    return $entity->view();
}

/**
 * Shortcut function
 * 
 * Returns array of menu items
 * 
 * @global type $menus  All site menu items
 * @param type $name  Name of menu item
 * @param type $menu  Menu that menu item belongs to
 * @return mixed  array of menu items, or false if no menu items exist
 */
function getMenuItem($name, $menu) {
    return MenuItem::get($name, $menu);
}

/**
 * Shortcut function
 * 
 * Updates a menu item
 * 
 * @global array $menus All site menu items
 * @param object $menu_item Menu item to update
 */
function updateMenuItem($menu_item) {
    return MenuItem::update($menu_item);
}

/**
 * Shortcut function
 * 
 * Saves a system variable
 * 
 * @param type $name  Name of variable to save
 * @param type $value Value of variable to save
 */
function set($name, $value) {
    return SystemVariable::set($name, $value);
}

/**
 * Shortcut function
 * 
 * Gets a system setting
 * 
 * @param type $name  Name of setting to get
 * @return string Value of setting
 */
function get($name) {
    return SystemVariable::get($name);
}

/**
 * Shortcut function
 * 
 * Checks if user is online
 * 
 * @return boolean  true if loggedin, false if not logged in
 */
function isOnline($user) {
    if (!is_object($user)) {
        $user = getEntity($user);
    }
    return $user->online();
}

/**
 * Shortcut function
 * 
 * Checks if user is logged in
 * 
 * @return boolean|int false if not logged , user guid if logged in
 */
function loggedIn() {
    return User::loggedIn();
}

/**
 * Shortcut function
 * 
 * Returns true if user logged out
 * @return boolean  true if logged out, false if logged in
 */
function loggedOut() {
    return !loggedIn();
}

/**
 * Shortcut function
 * 
 * Returns the logged in user object
 * 
 * @return boolean false if not logged in|object logged in user object
 */
function getLoggedInUser() {
    return User::getLoggedInUser();
}

/**
 * Shortcut function
 * 
 * Returns logged in user guid
 * 
 * @return int|boolean  guid of logged in user, or false if no user logged in
 */
function getLoggedInUserGuid() {
    return User::getLoggedInUserGuid();
}

/**
 * Shortcut function
 * 
 * Checks if the site administrator is logged in
 * 
 * @return boolean false if admin not logged in|boolean true if admin is logged in
 */
function adminLoggedIn() {
    return User::adminLoggedIn();
}

/**
 * Shortcut function
 * 
 * Gets the value of a vars variable by name
 * 
 * @param string $name  Name of variable
 * @return mixed  Value of variable
 */
function Vars($name) {
    return Vars::get($name);
}

/**
 * Shortcut function
 * 
 * Adds a token to a url
 * 
 * @param string $url   Url to add token to
 * @return string   url with token added
 */
function addTokenToURL($url) {
    return Security::addTokenToURL($url);
}

/**
 * Shortcut function
 * 
 * Checks if user is an administrator
 * 
 * @param object $user  User object, or guid to check
 * @return boolean  true if user is an admin, false if not
 */
function isAdmin($user) {
    if (!is_object($user)) {
        $user = getEntity($user);
    }
    if (!is_a($user, "SociaLabs\\User")) {
        return false;
    }
    if ($user->level == "admin") {
        return true;
    }
    return false;
}

/**
 * Shortcut function
 * 
 * Displays friendly time
 * 
 * @param int $timestamp  Timestamp to convert
 * @return string Formatted friendly time that updates via javascript
 */
function friendlytime($timestamp) {
    return display("output/friendly_time", array(
        "timestamp" => $timestamp
    ));
}

/**
 * Shortcut function
 * 
 * Checks if plugin is enabled
 * 
 * @param string $name  Name of plugin
 * @return mixed  Plugin object if enabled, false if not
 */
function isEnabledPlugin($name) {
    return Plugin::isEnabledPlugin($name);
}

/**
 * Runs hooks set by plugins
 * 
 * @global array $hooks
 * @param string $hook_name Name of the hook to call
 * @param type $params parameters to send to the called function
 * @return mixed Data returned from the called function
 */
function runHook($hook_name, $params = NULL) {
    return Hook::run($hook_name, $params);
}

/**
 * Determines of the current logged in user can view an entity
 * 
 * @param object $entity    Entity to test
 * @return boolean  true if user can view entity, false if user cannot view entity
 */
function loggedInUserCanViewEntity($entity, $ignore_access = false) {
    return Security::loggedInUserCanViewEntity($entity, $ignore_access);
}

/**
 * Returns all registration fields
 * 
 * @return array    Array of registration fields
 */
function getAllRegistrationFields() {
    return Registrationfield::getAllRegistrationFields();
}

/**
 * Shortcut function to upload file
 * 
 * @param string $filename Name of uploaded file form element
 * @param int $file_guid Guid of filestore item to reference file
 * @param string[] $allowed_extensions Array of allowed extensions.  Other extentions will not be uploaded
 * @return false|string false if upload fails|string Name of uploaded file 
 */
function uploadFile($filename, $file_guid, $allowed_extensions = array("png", "jpg", "jpeg", "gif", "doc", "docx", "ods")) {
    return FileSystem::uploadFile($filename, $file_guid, $allowed_extensions);
}

function entityExists($params = array()) {
    $test = getEntity($params);
    if ($test) {
        return true;
    }
    return false;
}

function br2nl($string) {
    return preg_replace('#<br\s*?/?>#i', "\n", $string);
}

function getSiteLogo() {
    $logo = getEntity(array(
        "type" => "Logo"
    ));
    if ($logo) {
        return $logo->icon("", "site_logo");
    }
    $url = getSiteURL() . "assets/img/logo.png";
    return "<img src='$url' class='site_logo'/>";
}

function normalizeURL($url) {
    if (strpos($url, getSiteURL()) !== false) {
        return $url;
    }
    return getSiteURL() . $url;
}

function clearCache() {
    Cache::clear();
}