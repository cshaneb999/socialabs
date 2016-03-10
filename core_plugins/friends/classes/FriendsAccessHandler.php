<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class FriendsAccessHandler {

    public function init($entity) {
        if (FriendsPlugin::friends($entity->owner_guid, getLoggedInUserGuid())) {
            return true;
        }
        return false;
    }

}
