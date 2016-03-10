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
 * Insert file page handler
 */
class InsertFilePageHandler extends PageHandler {

  /**
   * Creates html for insert file page
   * 
   * @param type $data
   */
  public function __construct($data) {
    $guid = $data['guid'];
    $file = getEntity($guid);
    $mime = $file->mime_type;
    switch ($mime) {
      case "image/jpeg":
      case "image/png":
      case "image/gif":
        $this->html = "<img alt='$file->title' src='" . Image::getImageURL($guid) . "'/>";
        break;
      default:
        $image_url = getSiteURL(). "plugins/files/assets/img/file_avatar.png";
        $this->html = "<div style='width:75px;'><a href='" . getSiteURL(). $file->getURL() . "'><img src='$image_url' title='$file->title' class='img-responsive' style='width:75px;' data-title='$file->title' alt='$file->title'/></a><p class='small text-center'><center>$file->title</center></p></div>";
        break;
    }
  }

}
