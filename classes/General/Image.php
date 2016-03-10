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

class Image {

    /**
     * Fixes image rotation (for apple devices)
     * 
     * @param string $file  File location
     */
    static function fixImageRotation($file) {
        $degrees = NULL;
        $image = imagecreatefromstring(file_get_contents($file));
        $exif = exif_read_data($file);
        if (isset($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $degrees = 180;
                    break;

                case 6:
                    $degrees = 90;
                    break;

                case 8:
                    $degrees = -90;
                    break;
            }
        }
        if (class_exists("\Imagick")) {
            try {
                $img = new \Imagick($file);
                $img->stripImage();
                if ($degrees) {
                    $img->rotateimage("#ffffff", $degrees);
                }
                $img->writeImage($file);
                $img->clear();
                $img->destroy();
            } catch (Exception $e) {
                
            }
        }
        return;
    }

    /**
     * Copy avatar from one entity to another
     * 
     * @param Photo $source_entity Entity whos avatar to copy
     * @param Photoalbum $target_entity Entity whos avatar to create from copy
     */
    static function copyAvatar($source_entity, $target_entity) {
        $icon = $source_entity->icon;
        $target_entity->icon = $icon;
        $target_entity->save();
    }

    /**
     * Creates thumbnail of image
     * 
     * @param int $guid Guid of image
     * @param int $width    Desired width of thumbnail
     * @return false|null true
     */
    static function createThumbnail($guid, $width) {
        $file = getEntity($guid);
        $mime_type = $file->mime_type;
        $path = $file->path;
        $filename = $file->filename;
        $owner_guid = $file->owner_guid;
        switch ($mime_type) {
            case "image/jpeg":
                $im = imagecreatefromjpeg($path);
                break;
            case "image/gif":
                $im = imagecreatefromgif($path);
                break;
            case "image/png":
                $im = imagecreatefrompng($path);
                break;
            default:
                return false;
                break;
        }

        $ox = imagesx($im);
        $oy = imagesy($im);

        $nx = $width;
        $ny = floor($oy * ($width / $ox));

        $nm = imagecreatetruecolor($nx, $ny);
        imagefilledrectangle($nm, 0, 0, $nx, $ny, imagecolorallocate($nm, 255, 255, 255));
        imagecopyresampled($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);

        $thumbnail_path = getDataPath() . "files" . "/" . $guid . "/" . "thumbnail" . "/" . $width;
        if (!file_exists($thumbnail_path)) {
            makePath($thumbnail_path, 0777);
        }
        switch ($mime_type) {
            case "image/jpeg":
                imagejpeg($nm, getDataPath() . "files" . "/" . $guid . "/" . "thumbnail" . "/" . $width . "/" . $filename);
                break;
            case "image/gif":
                imagegif($nm, getDataPath() . "files" . "/" . $guid . "/" . "thumbnail" . "/" . $width . "/" . $filename);
                break;
            case "image/png":
                imagepng($nm, getDataPath() . "files" . "/" . $guid . "/" . "thumbnail" . "/" . $width . "/" . $filename);
                break;
            default:
                return false;
                break;
        }
        imagedestroy($nm);
    }

    /**
     * Returns a url for an image
     * 
     * @param int $guid Guid of image
     * @param int $thumbnail    Width of thumbnail
     * @return string   Url for image
     */
    static function getImageURL($guid, $thumbnail = NULL) {
        if (!$thumbnail) {
            $thumbnail = HUGE;
        }
        return getSiteURL() . "views/output/image_viewer.php?guid=$guid&amp;thumbnail=$thumbnail";
    }

}
