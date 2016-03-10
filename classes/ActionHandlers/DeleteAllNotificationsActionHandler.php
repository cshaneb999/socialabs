<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Class that deletes all user notifications
 */
class DeleteAllNotificationsActionHandler {

  /**
   * Deletes all user notifications
   */
  public function __construct() {
    $guid = pageArray(2);
    if (getLoggedInUserGuid() == $guid) {
      $notifications = getEntities(array(
                  "type" => "Notification",
                  "owner_guid" => $guid
      ));
      foreach ($notifications as $notification) {
        $notification->delete();
      }
    }
    forward();
  }

}
