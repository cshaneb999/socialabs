<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Access handler for logged in users
 */
class LoggedInAccessHandler {

  /**
   * Determines if user can view entity
   * @return boolean  true if can view, false if can't
   */
  public function init() {
    if (loggedIn()) {
      return true;
    }
    return false;
  }

}
