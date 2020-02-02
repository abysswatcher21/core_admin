<?php
/**
* One True Pairing (OTP) Library
*
* @author       Fahrur Rifai <mfahrurrifai@gmail.com>
* @copyright    Esoftdream.net 2017
* @version      2.0
*/
class Otp {

    /**
     * The OTP code, get from generate
     * @var string or numeric
     */
    private $otp_code;

    /**
     * Otp user. The OTP used for
     * @var string [administrator|member]
     */
    public $otp_user = 'administrator';

    /**
     * send by email
     * @var boolean
     */
    public $send_email = false;

    /**
     * send by email
     * @var boolean
     */
    public $send_sms = false;

    /**
     * save data otp
     * @var boolean
     */
    public $save_data = true;

    /**
     * start and expired otp valid date
     * @var time
     */
    private $start_time;
    private $expired_time;

    /**
     * active long time (in seconds)
     * @var numeric
     */
    public $active_time = 60*60*6;

    /**
     * get the last active otp data
     * @var array
     */
    protected $last_otp_data = array();

    /**
     * SMS configuration
     * @var string
     */
    public $sms_to = '0000000000';
    public $sms_message = 'Hello, kode otp : {code} expired : {expired}';

    /**
     * email configuration
     * @var string
     */
    public $email_to = 'user@domain.com';
    public $email_title = 'Kode OTP';
    public $email_header = 'Konfirmasi Kode OTP';
    public $email_footer = 'Kode OTP';
    public $email_from = 'no-reply@esoftdream.net';
    public $email_from_name = 'Esoftdream';
    public $email_message = '<p style="margin: 0 0 16px;">
                        ANDA TELAH MELAKUKAN PERMINTAAN KODE OTP: <br>
                        KODE OTP : <font style="color:red;"><strong>{code}</strong></font><br>
                        AKTIF s/d : {expired} </p>';
    public $email_sent = false;

    function __construct() {
        $this->datetime = date("Y-m-d H:i:s");
        $this->start_time = $this->datetime;
        $this->CI =& get_instance();
    }

    /**
     * set otp code manualy
     * @param string $code
     */
    function set_otp_code($code) {
        $this->otp_code = $code;
        return $this;
    }

    /**
     * set otp expired_datetime manualy
     * @param string $expired_datetime
     */
    function set_otp_expired($expired_datetime) {
        $this->expired_time = $expired_datetime;
        return $this;
    }

    /**
     * The OTP code generator
     * @param  integer $length length of the generated code
     * @return string
     */
    function generate($length=5) {
        // generate otp code
        $otp_code = $this->CI->function_lib->generate_alpha_number($length);
        $this->set_otp_code($otp_code);

        // generate expired time from $active_time
        $this->expired_time = date('Y-m-d H:i:s', strtotime($this->start_time) + $this->active_time);

        // throw back th otp code
        return $this->otp_code;
    }

    /**
     * get last otp data
     * @return array
     */
    function get_last_otp() {
        return $this->last_otp_data;
    }

    /**
     * get the active otp from user_id [otp_user & datetime]
     * @param  integer $user_id
     * @return boolean
     */
    function get_active_otp($user_id) {

        if ($this->otp_user == 'administrator') {
            $this->CI->db->where('administrator_access_otp_administrator_id', $user_id);
            $this->CI->db->where("'" . $this->datetime . "' BETWEEN administrator_access_otp_start_datetime AND administrator_access_otp_expired_datetime", NULL, FALSE);
            $this->CI->db->order_by('administrator_access_otp_expired_datetime', 'DESC');
            $this->CI->db->limit(1);
            $query = $this->CI->db->get('site_administrator_access_otp');
        } elseif ($this->otp_user == 'member') {
            $this->CI->db->where('member_access_otp_network_id', $user_id);
            $this->CI->db->where("'" . $this->datetime . "' BETWEEN member_access_otp_start_datetime AND member_access_otp_expired_datetime", NULL, FALSE);
            $this->CI->db->order_by('member_access_otp_expired_datetime', 'DESC');
            $this->CI->db->limit(1);
            $query = $this->CI->db->get('sys_member_access_otp');
        }

        if ($query->num_rows() > 0) {

            $data = array();
            $data['otp_user_id'] = $user_id;
            if ($this->otp_user == 'administrator') {
                $data['otp_id'] = $query->row('administrator_access_otp_id');
                $data['otp_code'] = $query->row('administrator_access_otp_code');
                $data['otp_start'] = $query->row('administrator_access_otp_start_datetime');
                $data['otp_expired_datetime'] = $query->row('administrator_access_otp_expired_datetime');
            } elseif ($this->otp_user == 'member') {
                $data['otp_id'] = $query->row('member_access_otp_id');
                $data['otp_code'] = $query->row('member_access_otp_code');
                $data['otp_start'] = $query->row('member_access_otp_start_datetime');
                $data['otp_expired_datetime'] = $query->row('member_access_otp_expired_datetime');
            }
            // update last otp data
            $this->last_otp_data = $data;
            return TRUE;
        } else {
            // clear last otp data
            $this->last_otp_data = array();
            return FALSE;
        }
    }

    /**
     * saving data into table
     * @param  integer $user_id
     * @return void
     */
    private function save($user_id) {
        $data = array();
        if ($this->otp_user == 'administrator') {
            $data['administrator_access_otp_code'] = $this->otp_code;
            $data['administrator_access_otp_administrator_id'] = $user_id;
            $data['administrator_access_otp_start_datetime'] = $this->start_time;
            $data['administrator_access_otp_expired_datetime'] = $this->expired_time;
            $this->CI->db->insert('site_administrator_access_otp', $data);
        } elseif ($this->otp_user == 'member') {
            $data['member_access_otp_code'] = $this->otp_code;
            $data['member_access_otp_network_id'] = $user_id;
            $data['member_access_otp_start_datetime'] = $this->start_time;
            $data['member_access_otp_expired_datetime'] = $this->expired_time;
            $this->CI->db->insert('sys_member_access_otp', $data);
        }
    }

    /**
     * send the otp code into [email (and or) sms]
     * @param  integer $user_id
     * @return void
     */
    // function send($user_id) {
    function send() {
        // // save records
        // if ($this->save_data) {
        //     $this->save($user_id);
        // }

        // // Compile the message
        // $this->compile_message();

        // send the otp by sms
        if ($this->send_sms == TRUE) {
            // Load the SMS Library
            $this->CI->load->library('Sms_lib');
            $this->CI->sms_lib->send_sms($this->sms_to, $this->sms_message);
        }

        // send the otp by email
        if($this->send_email == TRUE){
            $this->send_email();
        }
    }

    /**
     * compile the message. convert from {key} to value
     * @return void
     */
    private function compile_message() {
        defined('LD') OR define('LD', '{');
        defined('RD') OR define('RD', '}');
        $arr_replace = array(
                        'code'=>'otp_code',
                        'start'=>'start_time',
                        'expired'=>'expired_time');
        foreach ($arr_replace as $_key => $_val) {
            if ($_key == 'start' OR $_key == 'expired') {
                $_newval = date_converter($this->$_val, 'l, d F Y H:i:s');
            } else {
                $_newval = $this->$_val;
            }
            // replace message
            $this->sms_message = str_replace(LD.$_key.RD, $_newval, $this->sms_message);
            $this->email_message = str_replace(LD.$_key.RD, $_newval, $this->email_message);
        }
    }

    /**
     * sending the email message
     * @return boolean true if it sent otherwise false
     */
    function send_email() {
        $this->CI->load->library('email');

        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';

        $this->CI->email->initialize($config);
        $message = '<!DOCTYPE html>
                    <html dir="ltr">
                    <head>
                        <meta charset="UTF-8">
                        <title>' . $this->email_title . '</title>
                    </head>
                    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
                        <div id="wrapper" dir="ltr" style="background-color: #f5f5f5; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;">
                            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr>
                                <td align="center" valign="top">
                                    <div id="template_header_image">
                                    </div>
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #fdfdfd; border: 1px solid #dcdcdc; border-radius: 3px !important;">
                                        <tr>
                                            <td align="center" valign="top">
                                                <!-- Header -->
                                                <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style="background-color: #2e7d32; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;"><tr>
                                                    <td id="header_wrapper" style="padding: 36px 48px; display: block;">
                                                        <h1 style="color: #ffffff; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #7797b4; -webkit-font-smoothing: antialiased;">' . $this->email_header . '</h1>
                                                    </td>
                                                </tr></table>
                                                <!-- End Header -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top">
                                                <!-- Body -->
                                                <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body"><tr>
                                                    <td valign="top" id="body_content" style="background-color: #fdfdfd;">
                                                        <!-- Content -->
                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%"><tr>
                                                            <td valign="top" style="padding: 20px 48px;">
                                                                <div id="body_content_inner" style="font-family:sans-serif;font-family:sans-serif;padding:20px;color:#727e8d;font-size:13px;font-weight:lighter;text-align:left;line-height:23px;border-top:1px;">
                                                                    ' . $this->email_message . '
                                                                </div>
                                                            </td>
                                                        </tr></table>
                                                        <!-- End Content -->
                                                    </td>
                                                </tr></table>
                                                <!-- End Body -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top">
                                                <!-- Footer -->
                                                <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer"><tr>
                                                    <td valign="top" style="padding: 0; -webkit-border-radius: 6px;">
                                                        <table border="0" cellpadding="10" cellspacing="0" width="100%"><tr>
                                                            <td colspan="2" valign="middle" id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #99b1c7; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;">
                                                                <p>' . $this->email_footer . '</p>
                                                            </td>
                                                        </tr></table>
                                                    </td>
                                                </tr></table>
                                                <!-- End Footer -->
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr></table>
                        </div>
                    </body>
                    </html>';

        $this->CI->email->from($this->email_from, $this->email_from_name);
        $this->CI->email->to($this->email_to);

        $this->CI->email->subject($this->email_title);
        $this->CI->email->message($message);


        if (!$this->CI->email->send()) {
            $this->email_sent = FALSE;
        } else {
            $this->email_sent = TRUE;
        }

        return $this->email_sent;
    }

}
