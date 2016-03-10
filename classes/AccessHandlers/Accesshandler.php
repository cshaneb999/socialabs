<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Handles all site access handlers.
 * 
 * Class that handles all site access handlers.
 * 
 */
class Accesshandler extends Entity {

    /**
     * Creates new Accesshandler
     * 
     * @param string $handler Name of the handler that needs to be created.
     */
    public function __construct($handler = false) {
        if ($handler) {
            $this->type = "Accesshandler";
            $this->access_id = "system";
            $params = array(
                "metadata_name" => "handler",
                "metadata_value" => $handler
            );
            if (!$this->exists($params)) {
                $this->handler = $handler;
                $this->save();
            }
        }
    }

    /**
     * Returns array of all access handlers.
     * 
     * @return array Array of access handlers.
     */
    static function getAll() {
        return getEntities(array(
            "type" => "Accesshandler"
        ));
    }

    /**
     * Returns accesshandler
     * 
     * @param string $handler Name of handler to return
     * @return mixed  false if no handler found, string if handler found
     */
    static function get($handler) {
        $return = getEntity(array(
            "type" => "Accesshandler",
            "metadata_name" => "handler",
            "metadata_value" => $handler
        ));
        if ($return) {
            return $return->handler;
        }
        return false;
    }

}
