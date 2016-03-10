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

/**
 * Class database action handler
 */
class CleanDatabaseActionHandler {

    /**
     * Cleans database
     */
    public function __construct() {
        set_time_limit(0);
        adminGateKeeper();
        $con = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM `entities`";
        $result = $con->query($query);
        while ($row = $result->fetch_assoc()) {
            $guid = $row['guid'];
            $type = $row['type'];
            $query = "SELECT * FROM `$type` WHERE `guid`='$guid'";
            $result2 = $con->query($query);
            if ($result2) {
                if ($result2->num_rows < 1) {
                    $query = "DELETE FROM `entities` WHERE `guid` = '$guid'";
                    $con->query($query);
                }
            }
        }
        new SystemMessage(translate("clean:database:success:system:message"));
        forward("admin");
    }

}
