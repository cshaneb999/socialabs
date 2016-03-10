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
 * The default data type for all metadata is varchar(50).  If an objects metadata requires a different data type
 * it can be setup with this function.
 */
class StorageType {

    /**
     * Constructor updates the database according to the storage type
     * 
     * @param string $class The name of the class.
     * @param string $metadata_name   The metadata name that needs it's data type changed.
     * @param string $type The data type to change it to, or index.  Examples (text,blob,index)
     */
    public function __construct($class, $metadata_name, $type) {
        $storage_types_complete = Cache::get("storage_types_complete", "site");
        if (!$storage_types_complete) {
            $class = strtolower($class);
            $query = "CREATE TABLE IF NOT EXISTS `$class` (guid INT(12) UNSIGNED PRIMARY KEY)";
            Dbase::con()->query($query);
            if ($type != "index") {
                $query = "ALTER TABLE `$class` ADD `$metadata_name` $type;";
                Dbase::con()->query($query);
            } else {
                $query = "SELECT COUNT(1) IndexIsThere FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema=DATABASE() AND table_name='$class' AND index_name='$metadata_name';";
                $result = Dbase::con()->query($query);
                $row = $result->fetch_assoc();
                if (isset($row['IndexIsThere'])) {
                    if ($row['IndexIsThere'] == 0) {
                        $query = "CREATE INDEX `$metadata_name` ON `$class`(`$metadata_name`);";
                        Dbase::con()->query($query);
                    }
                }
            }
        }
    }

}
