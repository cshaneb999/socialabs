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
 * Class that creates Metatag entities
 */
class Metatag extends Entity {

    /**
     * Creates metatag entity
     * @param string $value
     */
    public function __construct($name = false, $type = "title", $value = NULL) {
        if ($name) {
            $test = getEntity(array(
                "type" => "Metatag",
                "metadata_name_value_pairs" => array(
                    array(
                        "name" => "metatag_name",
                        "value" => $name
                    ),
                    array(
                        "name" => "metatag_type",
                        "value" => $type
                    )
                )
            ));
            if (!$test) {
                $this->type = "Metatag";
                $this->access_id = "system";
                $this->metatag_name = $name;
                $this->metatag_type = $type;
                $this->metatag_value = $value;
                $this->save();
            }
        }
    }

    static function update($name = false, $type = false, $value = false) {
        $metatag = getEntity(array(
            "type" => "Metatag",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "metatag_name",
                    "value" => $name
                ),
                array(
                    "name" => "metatag_type",
                    "value" => $type
                )
            )
        ));
        if ($metatag) {
            $metatag->value = $value;
            $metatag->save();
        }
    }

    /**
     * Returns metatag
     * 
     * @param string $name  Name of metatag
     * @param string $type  Type of metatag(title or description)
     * @return object  Metatag entity
     */
    static function get($name = false, $type = false) {
        $params = array(
            "type" => "Metatag",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "metatag_name",
                    "value" => $name
                ),
                array(
                    "name" => "metatag_type",
                    "value" => $type
                )
            )
        );
        if ($name && $type) {
            return getEntity($params);
        } else {
            unset($params['metadata_name_value_pairs']);
            return getEntities($params);
        }
    }

    /**
     * Gets text from metatag
     * 
     * @param string $type Type of metatag(title or description)
     * @param string $name Name of metatag
     * @return type
     */
    static function getMetatagText($type, $name) {
        $params = array(
            "type" => "Metatag",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "metatag_name",
                    "value" => $name
                ),
                array(
                    "name" => "metatag_type",
                    "value" => $type
                )
            )
        );
        $metatag = getEntity($params);
        if (!$metatag) {
            $params['metadata_name_value_pairs'][0]['name'] = "home";
            $metatag = getEntity($params);
        }
        if (isset($metatag->metatag_value)) {
            return $metatag->metatag_value;
        } else {
            return NULL;
        }
    }

}
