<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Activity class used for creating activity entities
 */
class Activity extends Entity {

    /**
     * Creates an activity entity
     * 
     * @param int $owner_guid Guid of activity owner
     * @param string $text  Body of activity entity passes through translate funciton.
     * @param array $params  Array of parameters to pass to $text.
     * @param int $container_guid  Guid of item activity performed on
     * @param int $access_id  Access id of activity entity
     */
    public function __construct($owner_guid = false, $text = false, $params = array(), $container_guid = NULL, $access_id = NULL) {
        if ($owner_guid) {
            if (!$access_id) {
                $access_id = Security::getDefaultAccessId();
            }
            $this->type = "Activity";
            $this->owner_guid = $owner_guid;
            $this->text = $text;
            $this->params = $params;
            $this->access_id = $access_id;
            if ($container_guid) {
                $container = getEntity($container_guid);
                $this->container_guid = $container_guid;
                $this->access_id = $container->access_id;
            }
            $this->save();
        }
    }

}
