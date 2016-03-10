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

class SiteTranslation extends Translation {

    private $english = array(
        "nav:home" => "Home",
        "nav:my_account" => "My Account",
        "nav:logout" => "Logout",
        "nav:admin" => "Admin",
        "nav:login" => "Login",
        "nav:register" => "Register",
        "login:form:heading" => "Login",
        "register:form:heading" => "Register",
        "verification_email_sent:heading" => "Please Verify Your Email",
        "login:form:label:email" => "Email",
        "login:form:label:password" => "Password",
        "login:form:button" => "Login",
        "register:form:label:first_name" => "First Name",
        "register:form:label:last_name" => "Last Name",
        "register:form:label:email" => "Email",
        "register:form:label:password" => "Password",
        "register:form:label:password2" => "Password (Again)",
        "register:form:button" => "Register",
        "verification_email_sent:lead" => "A verification email has been sent to the email address you've provided.  Please click the link in that email to verify your email.",
        "verification_email_sent:button" => "Resend Verification Email",
        "admin_panel:custom_pages" => "Custom Pages",
        "admin_panel:general" => "General Settings",
        "admin_panel:users" => "Users",
        "admin_panel:plugins" => "Plugins",
        "admin_panel:plugin:enable" => "Enable",
        "admin_panel:plugin:disable" => "Disable",
        "admin_panel:plugin:enable_all" => "Enable All",
        "admin_panel:plugin:disable_all" => "Disable All",
        "admin_panel:view_extensions" => "View Extensions",
        "admin_panel:hooks" => "Hooks",
        "admin_panel:page_handler_label" => "Page Handlers",
        "admin_panel:seo" => "SEO",
        "admin_panel:logo" => "Logo",
        "nav:toggle_navigation" => "Toggle Navigation",
        "file_input:select_file" => "Select File",
        "file_input:remove" => "Remove",
        "verify_email:subject" => "%s, please verify your email for %s",
        "verify_email:body" => "<h1 style='text-align:center;'>Just One More Step..</h1><h2 style='text-align:center;'>%s</h2><p style='text-align:center;'><a href='%saction/verifyEmail/%s/%s'>Click here to verify your email for %s.</a></p>",
        "password_reset:email:body" => "%s, please click the following link to reset your password for %s.  <a href='%s'>Click Here</a>",
        "system_message:must_be_logged_in" => "You must be logged in to view that page.",
        "system_message:must_be_logged_out" => "You must be logged out to view that page.",
        "system_message:not_allowed_to_view" => "You are not allowed to view that page.",
        "system_message:email_could_not_be_verified" => "Your email could not be verified.",
        "system_message:email_verified" => "Your email has been verified",
        "system_message:verification_email_sent" => "Verification email has been sent.",
        "system_message:passwords_must_match" => "Passwords must match",
        "system_message:email_taken" => "Sorry, that email is already taken.",
        "system_message:logged_out" => "You have been logged out.",
        "system_message:logged_in" => "You have been logged in.",
        "system_message:could_not_log_in" => "Sorry, we could not log you in.",
        "system_message:could_not_enable_plugin_require" => "Could not enable plugin.  Requires %s, but it is not enabled.",
        "system_message:could_not_enable_plugin_install" => "Could not enable plugin.  Requires %s, but it is not installed.",
        "system_message:plugin_enabled" => "Your plugin has been enabled.",
        "system_message:all_possible_plugins_enabled" => "All possible plugins have been enabled.",
        "system_message:plugin_disabled" => "Your plugin has been disabled.",
        "system_message:all_plugins_disabled" => "All plugins have been disabled.",
        "system_message:plugin_cant_be_enabled" => "Your plugin could not be enabled.  Check dependencies.",
        "continue" => "Continue",
        "admin:general_settings:show_translations" => "Show raw translation strings",
        "admin:general_settings:hide_socia_link" => "Hide Link to SociaLabs",
        "filter" => "Filter ",
        "avatar:new" => "%s has a new avatar.",
        "admin_panel:tables" => "Tables",
        "admin_panel:session_cache" => "Session Cache",
        "admin_panel:file_cache" => "File Cache",
        "admin_panel:home_page" => "Home Page",
        "register:button:text" => "Register",
        "login:button:text" => "Login",
        "activity_panel:all" => "All Activity",
        "activity_panel:mine" => "My Activity",
        "admin:general_settings:default_access" => "Default Access Level",
        "admin:general_settings:show_admin_panel" => "Show Admin Panel In Header",
        "admin_panel:page_cache" => "Page Cache",
        "comment:activity:message" => "<a href='%s'>%s</a> commented on <a href='%s'>%ss</a> <a href='%s'>%s</a> <br/>\"%s\"",
        "admin:general_settings:base_theme" => "Base Theme",
        "activity:joined" => "<a href='%s'>%s</a> joined the site.",
        "ProfileStatus" => "profile status",
        "access_handler:public" => "Public",
        "access_handler:private" => "Private",
        "access_handler:loggedIn" => "Logged In Users",
        "admin:general_settings:debug_mode" => "Enable debug mode?",
        "user_setting:notifications" => "Notifications",
        "user_setting_field:notify_when_like" => "When someone likes my content, notify me by:",
        "user_setting_field:notify_when_comment" => "When someone comments on my content, notify me by:",
        "user_setting_field:notify_when_friend_request_sent" => "When someone sends me a friend request, notify me by:",
        "user_setting_field:notify_when_friend_request_accepted" => "When someone accepts my friend request, notify me by:",
        "user_setting_field:notify_when_forum_comment_topic_i_own" => "When someone comments on a forum post that I've created, notify me by:",
        "user_setting_field:notify_when_forum_comment_topic_i_commented" => "When someone comments on a forum post that I've also commented on, notify me by:",
        "admin:general_settings:site_name" => "Site Name",
        "admin:general_settings:site_email" => "Site Email",
        "admin:general_settings:wrap_views" => "Wrap Views",
        "ban:user:success:system:message" => "You have successfully banned a user.",
        "clean:database:success:system:message" => "Your database has been cleaned.",
        "cache:cleaned:success:system:message" => "Site Caches have been cleared.",
        "system:cache:cleaned:success:system:message" => "The session cache has been cleared."
    );

    function __construct() {
        parent::__construct("en", $this->english);
    }

}
