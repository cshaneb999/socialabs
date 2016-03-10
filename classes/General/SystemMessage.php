<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class SystemMessage {

    /**
     * Generates a site system message to be displayed to the user
     * 
     * @param string $message   Message to be displayed
     * @param string $message_type  Type of message (success,warning,danger,info)
     * @return boolean|null  true
     */
    public function __construct($message, $message_type = "info") {

        $system_messages = Cache::get("system_messages", "session");
        $system_messages[] = array(
            "message" => $message,
            "message_type" => $message_type
        );
        new Cache("system_messages", $system_messages, "session");
    }

    /**
     * Displays system messages
     * 
     * @return string   String of system messages
     */
    static function displaySystemMessages() {
        $return = NULL;
        $system_messages = Cache::get("system_messages", "session");
        if ($system_messages) {

            foreach ($system_messages as $system_message) {
                $type = $system_message['message_type'];
                $message = $system_message['message'];
                $return .= display("page_elements/system_messages", array(
                    "message" => $message
                ));
            }
        }
        new Cache("system_messages", NULL, "session");
        return $return;
    }

}
