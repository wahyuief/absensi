<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{
    public $CI;
    protected $data = array();
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        date_default_timezone_set(get_option('timezone', 'Asia/Jakarta'));
    }

    public function _set_message($type = 'info', $message)
    {
        // type = success, info, warning, error
        $this->session->set_flashdata('wahtype', $type);
        $this->session->set_flashdata('wahmessage', $message);
    }

    public function _show_message($type = null, $message = null)
    {
        if ($type && $message) {
            $output = '';
            if (strpos($message, '|') !== false) {
                $message = explode('|', $message);
                $output = '<script>var notyf = new Notyf({position: {x:\'right\',y:\'top\'},dismissible:true});';
                foreach ($message as $key => $value) {
                    $value = str_replace("\n", '', $value);
                    if (!empty($value)) $output .= 'notyf.' . $type . '({message:"' . strip_tags($value) . '",duration:'.(5000+($key*1000)).'});';
                }
                $output .= '</script>';
            } else {
                $output = '<script>var notyf = new Notyf({position: {x:\'right\',y:\'top\'},dismissible:true});';
                if (!empty($message)) $output .= 'notyf.' . $type . '({message:"' . strip_tags($message) . '",duration:'.(5000+($key*1000)).'});';
                $output .= '</script>';
            }
            return $output;
        }

        if ($this->session->flashdata('wahtype') && $this->session->flashdata('wahmessage')) {
            $type = $this->session->flashdata('wahtype');
            $message = $this->session->flashdata('wahmessage');
            $output = '';
            if (strpos($message, '|') !== false) {
                $message = explode('|', $message);
                $output = '<script>var notyf = new Notyf({position: {x:\'right\',y:\'top\'},dismissible:true});';
                foreach ($message as $key => $value) {
                    $value = str_replace("\n", '', $value);
                    if (!empty($value)) $output .= 'notyf.' . $type . '({message:"' . $value . '",duration:'.(5000+($key*1000)).'});';
                }
                $output .= '</script>';
            } else {
                $output = '<script>var notyf = new Notyf({position: {x:\'right\',y:\'top\'},dismissible:true});';
                if (!empty($message)) $output .= 'notyf.' . $type . '({message:"' . strip_tags($message) . '",duration:'.(5000+($key*1000)).'});';
                $output .= '</script>';
            }
            return $output;
        }
    }
}

require_once(APPPATH.'core/Backend_Controller.php');
require_once(APPPATH.'core/Frontend_Controller.php');