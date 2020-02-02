<?php

/*
 * Image_lib Extended Libraries
 *
 * @author	Agus Heriyanto
 * @copyright	Copyright (c) 2014, Esoftdream.net
 */

// -----------------------------------------------------------------------------

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Image_lib extends CI_Image_lib {

    function __construct() {
        parent::__construct();
    }

    /**
     * Making thumbnail
     *
     */
    //makeThumbnail(image_source, image_destination, new_image_width, new_image_height);
    function makeThumbnail($path, $newpath, $new_width, $new_height) {
        $config['image_library'] = 'GD2';
        $config['source_image'] = $path;
        $config['new_image'] = $newpath;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $new_width;
        $config['height'] = $new_height;

        $this->initialize($config);
        $this->resize();
    }

    // --------------------------------------------------------------------

    /**
     * Making resize
     *
     */
    //makeResize(image_source, image_destination, new_image_width, new_image_height);
    function makeResize($path, $newpath, $new_width, $new_height) {
        $this->makeThumbnail($path, $newpath, $new_width, $new_height);
    }

    // --------------------------------------------------------------------

    /**
     * Resizing Image
     *
     */
    //resizeImage(image_source, image_destination, new_image_width, new_image_height);
    function resizeImage($path, $new_width, $new_height) {
        $config['image_library'] = 'GD2';
        $config['source_image'] = $path;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $new_width;
        $config['height'] = $new_height;

        $this->initialize($config);
        $this->resize();
    }

    // --------------------------------------------------------------------

    /**
     * Make Crop
     *
     */
    //makeCrop(image_source, image_destination, new_image_width, new_image_height, x_axis, Y_axis);
    function makeCrop($path, $newpath, $new_width, $new_height, $x_axis = '0', $y_axis = '0') {
        $config['image_library'] = 'GD2';
        $config['source_image'] = $path;
        $config['new_image'] = $newpath;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $new_width;
        $config['height'] = $new_height;
        $config['x_axis'] = $x_axis;
        $config['y_axis'] = $y_axis;

        $this->initialize($config);
        $this->crop();
    }

    // --------------------------------------------------------------------

    /**
     * Cropping Image
     *
     */
    //cropImage(image_source, new_image_width, new_image_height, x_axis, Y_axis);
    function cropImage($path, $new_width, $new_height, $x_axis = '0', $y_axis = '0') {
        $config['image_library'] = 'GD2';
        $config['source_image'] = $path;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $new_width;
        $config['height'] = $new_height;
        $config['x_axis'] = $x_axis;
        $config['y_axis'] = $y_axis;

        $this->initialize($config);
        $this->crop();
    }

    // --------------------------------------------------------------------

    /**
     * Cropping Center Image
     *
     */
    //cropImage(image_source, new_image_width, new_image_height);
    function cropCenterImage($path, $new_width, $new_height) {
        $size = getimagesize($path);
        $width = $size[0];
        $height = $size[1];
        $x_center = $width / 2;
        $y_center = $height / 2;
        $x_axis = $x_center - ($new_width / 2);
        $y_axis = $y_center - ($new_height / 2);

        $config['image_library'] = 'GD2';
        $config['source_image'] = $path;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $new_width;
        $config['height'] = $new_height;
        $config['x_axis'] = $x_axis;
        $config['y_axis'] = $y_axis;

        $this->initialize($config);
        $this->crop();
    }

    // --------------------------------------------------------------------

    /**
     * Get Proporsional Image Size
     *
     */
    function getProporsionalImgSize($img_src, $max_width, $max_height) {
        // dapatkan size img sesungguhnya
        $size = getimagesize($img_src);
        $width = $size[0];
        $height = $size[1];
        $ok = $ratio = $width_jadi = $height_jadi = array();
        $ok[0] = $ok[1] = 1;
        $ratio[0] = $ratio[1] = 1;

        //coba diitung kedua2nya dikecilkan
        if ($height > $max_height) {
            $ratio[0] = $max_height / $height;
            $height_jadi[0] = $max_height;
            $width_jadi[0] = $ratio[0] * $width;
        } else {
            $height_jadi[0] = $height;
            $width_jadi[0] = $width;
        }
        
        if ($width > $max_width) {
            $ratio[1] = $max_width / $width;
            $height_jadi[1] = $ratio[1] * $height;
            $width_jadi[1] = $max_width;
        } else {
            $height_jadi[1] = $height;
            $width_jadi[1] = $width;
        }

        if ($width_jadi[0] > $max_width) {
            $ok[0] = 0;
        }
        
        if ($height_jadi[1] > $max_height) {
            $ok[1] = 0;
        }

        // keputusan akhir
        if ($ok[0] == 1 && $ok[1] == 1) {
            return array('width' => round($width_jadi[0]), 'height' => round($height_jadi[0]));
        } elseif ($ok[0] == 1) {
            return array('width' => round($width_jadi[0]), 'height' => round($height_jadi[0]));
        } else {
            return array('width' => round($width_jadi[1]), 'height' => round($height_jadi[1]));
        }
    }

}

?>
