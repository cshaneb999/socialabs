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
 * Creates a registration field object
 */
class Registrationfield extends Entity {

    /**
     * Constructs this registration field object
     * 
     * @param string $name  Name of registration field
     * @param string $label Label for registration field
     * @param string $field_type    Field type for registration field (text/dropdown/email/password)
     * @param int $weight   Weight to apply to registration field (determines position)
     */
    public function __construct($name = false, $label = false, $field_type = "text", $weight = 500, $checked = false, $unchecked = false) {
        if ($name) {
            $this->type = "Registrationfield";
            $params = array(
                "metadata_name" => "name",
                "metadata_value" => $name
            );
            if (!$this->exists($params)) {
                $this->name = $name;
                $this->label = $label;
                $this->field_type = $field_type;
                $this->weight = $weight;
                $this->checked = $checked;
                $this->unchecked = $unchecked;
                $this->save();
            }
        }
    }

    /**
     * Returns all registration fields
     * 
     * @return array    Array of registration fields
     */
    static function getAllRegistrationFields() {
        $registration_fields = getEntities(array(
            "type" => "Registrationfield",
            "order_by" => "weight"
        ));
        if ($registration_fields) {
            usort($registration_fields, function($a, $b) {
                return $a->weight > $b->weight;
            });
            return $registration_fields;
        }
        return array();
    }

    /**
     * Removes a registration field from the stack
     * 
     * @param string $name  Name of registration field to remove
     */
    static function removeRegistrationField($name) {
        $field = getEntity(array(
            "type" => "Registrationfield",
            "metadata_name" => "name",
            "metadata_value" => $name
        ));
        if ($field) {
            $field->delete();
        }
    }

}
