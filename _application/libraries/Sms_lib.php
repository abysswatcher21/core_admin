<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sms_lib => Library akses api zenziva
 * Library SMS Gateway
 * @author hanan
 * @copyright @esoftdream
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sms_lib {

    var $CI;

    function __construct() {
        $this->CI = & get_instance();
//        $this->CI->load->database();
//        
        $this->userkey = "d84z721125"; // userkey lihat di zenziva
        $this->passkey = "tvnz56x2dm"; // set passkey di zenziva
        $this->type = "reguler"; // set passkey di zenziva
    }

    function send_message($phone, $message) {
        $url = "http://luxury-qiu9.zenziva.com/api/sendsms.php";

        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $url);

        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey=' . $this->userkey . '&passkey=' . $this->passkey . '&nohp=' . $phone . '&tipe=' . $this->type . '&pesan=' . urlencode($message));

        curl_setopt($curlHandle, CURLOPT_HEADER, 0);

        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);

        curl_setopt($curlHandle, CURLOPT_POST, 1);

        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curlHandle);
        curl_close($curlHandle);

        $sXML = simplexml_load_string($response);

        return $sXML;
    }

    function add_phonebook() {

        $params = array();
        $params['userkey'] = $this->userkey;
        $params['passkey'] = $this->passkey;
        $params['nama'] = "Mang Haku";
        $params['alamat'] = "Jogjakarta";
        $params['nohp'] = '08874932484';

        $params['jenis_kelamin'] = 'Laki-laki';
        $params['tgl_lahir'] = '';
        $params['pekerjaan'] = '';
        $params['grup'] = '';
        $params['kota'] = '';
        $params['agama'] = '';

        $url = "luxury-qiu9.zenziva.com/api/pbadd.php";

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $response = curl_exec($curlHandle);

        curl_close($curlHandle);
        $response = simplexml_load_string($response);

        // return $response;
    }

    function check_credit() {

        $arr_response = array();
        $url = "http://luxury-qiu9.zenziva.com/api/credit.php";
        $params['userkey'] = $this->userkey;
        $params['passkey'] = $this->passkey;

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $response = curl_exec($curlHandle);

        $xml_string = <<<XML
$response
XML;
        curl_close($curlHandle);

        $sXML = simplexml_load_string($xml_string,"SimpleXMLElement", LIBXML_NOCDATA);
        
        return $sXML;
    }

    function getAllSentItem() {

        $url = "http://luxury-qiu9.zenziva.com/api/outboxgetall.php";
        $params['userkey'] = $this->userkey;
        $params['passkey'] = $this->passkey;

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $response = curl_exec($curlHandle);

        curl_close($curlHandle);

        $response = simplexml_load_string($response);

        echo '<pre>';
        print_r($response);
        echo '<br>';
        $count = count($response);

        if ($count > 0) {
            $arr_response = array();
            foreach ($response as $arr) {
                $arr_response['id'] = $arr->id;
                $arr_response['tanggal'] = $arr->tgl;
            }
        }

        die();
    }

    function getOutboxStatusbyDate( $from, $to) {
        $url = "http://luxury-qiu9.zenziva.com/api/outboxgetbydate.php";
        
        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $url);

        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey=' . $this->userkey . '&passkey=' . $this->passkey . '&from='.$from.'&to='.$to);

        curl_setopt($curlHandle, CURLOPT_HEADER, 0);

        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);

        curl_setopt($curlHandle, CURLOPT_POST, 1);

        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curlHandle);
        curl_close($curlHandle);

        $sXML = simplexml_load_string($response);

        return $sXML;
    }

}
