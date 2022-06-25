<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader
{
    public $CI;
    protected $data = array();
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
    }
}
