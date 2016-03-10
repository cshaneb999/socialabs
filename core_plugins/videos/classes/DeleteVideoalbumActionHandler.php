<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {

    class DeleteVideoalbumActionHandler {

        public function __construct() {
            $guid = pageArray(2);
            $entity = getEntity($guid);
            classGateKeeper($entity, "Videoalbum");
            if (loggedInUserCanDelete($entity)) {
                $entity->delete();
            }
            new SystemMessage('Your video album and all videos it contained have been deleted.');
            forward("videos");
        }

    }

}