<?php

/*
 * Pdf Libraries
 *
 * @author	Meychel Danius F. Sambuari
 *              Agus Heriyanto
 * @copyright	Copyright (c) 2012, Sigma Solusi.
 */

// -----------------------------------------------------------------------------

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF {

    function __construct() {
        parent::__construct();
    }
    
    // Page footer
//    public function Footer() {
//        // Position at 15 mm from bottom
//        $this->SetY(-20);
//        // Set font
//        $this->SetFont('helvetica', 'I', 8);
//        
//        $image_file = THEMES_BACKEND .'/images/logo.png';
//
//        // write html image
//        $toolcopy = '<p style="display: block;margin-left: auto;margin-right: auto;"><img src="'.$image_file.'"  width="700" height="50"><p>';
//        $this->writeHTML($toolcopy, true, 0, true, 0);
//
//    }
    
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('times', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Halaman '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }

}

?>
