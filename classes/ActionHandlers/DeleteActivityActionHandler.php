<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Class that deletes activity item
 */
class DeleteActivityActionHandler {

  /**
   * Deletes activity item
   */
  public function __construct() {
    adminGateKeeper();
    $guid = pageArray(2);
    $activity = getEntity($guid);
    $activity->delete();
    new SystemMessage("Your activity has been deleted.");
    forward();
  }

}
