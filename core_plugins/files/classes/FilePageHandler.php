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

class FilePageHandler extends PageHandler {

    public function __construct() {
        $page = pageArray();
        switch ($page[1]) {
            case "upload":
                $form = drawForm(array(
                    "name" => "file/upload",
                    "method" => "post",
                    "action" => "fileUpload",
                    "enctype" => "multipart/form-data"
                ));
                new Vars("container_guid", pageArray());
                $this->html = drawPage(array(
                    "header" => "Upload File",
                    "body" => $form
                ));
                break;
            default:
                if (is_numeric($page[1])) {
                    $guid = $page[1];
                    $file = getEntity($page[1]);
                    $image_url = Image::getImageURL($guid);
                    $image_title = $file->name;
                    $left_content = "<a href='$image_url' data-lightbox='image-$guid' data-title='$image_title'><img src='$image_url' class='img-responsive' alt=''/></a>";
                    $right_content = html_entity_decode($file->description);
                    $comments = display("output/block_comments", array(
                        "guid" => $guid,
                        "show_form" => true
                    ));
                    $download_button = "<a href='" . getSiteURL() . "views/output/file_download.php?guid=$guid' class='btn btn-success btn-block' style='margin-top:18px;'>Download</a>";
                    $body = <<<HTML
        <div class='col-sm-4'>
            $left_content
            $download_button
        </div>
        <div class='col-sm-8'>
            <div class='well'>
                $right_content
            </div>
            $comments
        </div>
HTML;
                    $this->html = drawPage(array(
                        "header" => $file->title,
                        "body" => $body
                    ));
                }
                return false;
                break;
        }
    }

}
