<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router
{
    public $CI;
    protected $data = array();
    public function __construct()
    {
        parent::__construct();
        // $CI =& get_instance();
    }
}
