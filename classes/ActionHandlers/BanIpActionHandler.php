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

class BanIpActionHandler {

    function __construct() {
        adminGateKeeper();
        $ip = pageArray(2);
        if ($ip) {
            new BlacklistIp($ip);
            $params = array(
                "type" => "User",
                "metadata_name_value_pairs" => array(
                    array(
                        "name" => "ip1",
                        "value" => $ip
                    ),
                    array(
                        "name" => "ip2",
                        "value" => $ip
                    )
                ),
                "metadata_name_value_pairs_operand" => "OR"
            );
            $users = getEntities($params);
            $tables = Dbase::getAllTables(false);
            foreach ($users as $user) {
                new BlacklistEmail($user->email);
                $guid = $user->guid;
                foreach ($tables as $table) {
                    $entities = getEntities(array(
                        "type" => $table,
                        "metadata_name_value_pairs" => array(
                            array(
                                "name" => "owner_guid",
                                "value" => $guid
                            ),
                            array(
                                "name" => "container_guid",
                                "value" => $guid
                            )
                        ),
                        "metadata_name_value_pairs_operand" => "OR"
                    ));
                    if ($entities) {
                        foreach ($entities as $entity) {
                            $entity->delete();
                        }
                    }
                }
                $user->delete();
            }
            new SystemMessage("Ip $ip has been banned, and all users using it have been deleted.");
            forward("home");
        }
    }

}
