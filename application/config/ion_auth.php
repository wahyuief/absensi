<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Name:    Ion Auth
 * Author:  Ben Edmunds
 *           ben.edmunds@gmail.com
 *           @benedmunds
 *
 * Added Awesomeness: Phil Sturgeon
 *
 * Created:  10.01.2009
 *
 * Description:  Modified auth system based on redux_auth with extensive customization. This is basically what Redux Auth 2 should be.
 * Original Author name has been kept but that does not mean that the method has not been modified.
 *
 * Requirements: PHP5.6 or above
 *
 * @package    CodeIgniter-Ion-Auth
 * @author     Ben Edmunds
 * @link       http://github.com/benedmunds/CodeIgniter-Ion-Auth
 * @filesource
 */

$config['database_group_name'] = '';
$config['tables']['users'] = 'users';
$config['tables']['groups'] = 'groups';
$config['tables']['users_groups'] = 'users_groups';
$config['tables']['login_attempts'] = 'login_attempts';
$config['join']['users'] = 'user_id';
$config['join']['groups'] = 'group_id';
$config['hash_method'] = 'argon2id';
$config['bcrypt_default_cost'] = 11;
$config['argon2_default_params'] = ['memory_cost' => 1 << 11, 'time_cost' => 1, 'threads' => 1];
$config['site_title'] = get_option('site_name');
$config['admin_email'] = get_option('email');
$config['default_group'] = 'member';
$config['admin_group'] = 'admin';
$config['identity'] = 'email';
$config['min_password_length'] = 8;
$config['email_activation'] = FALSE;
$config['manual_activation'] = FALSE;
$config['remember_users'] = TRUE;
$config['user_expire'] = 86500;
$config['user_extend_on_login'] = FALSE;
$config['track_login_attempts'] = TRUE;
$config['track_login_ip_address'] = TRUE;
$config['maximum_login_attempts'] = 3;
$config['lockout_time'] = 600;
$config['forgot_password_expiration'] = 1800;
$config['recheck_timer'] = 0;
$config['session_hash'] = 'd02b2951-4c1e-4998-e291-f21ae9d5fc38';
$config['remember_cookie_name'] = 'e2780230-3d25-ed14-2d02-498a63fbfaf3';
$config['use_ci_email'] = TRUE;
$config['email_config'] = ['mailtype' => 'html'];
$config['email_templates'] = 'auth/email/';
$config['email_activate'] = 'activate.tpl.php';
$config['email_forgot_password'] = 'forgot_password.tpl.php';
$config['delimiters_source'] = 'config';
$config['message_start_delimiter'] = '';
$config['message_end_delimiter'] = '|';
$config['error_start_delimiter'] = '';
$config['error_end_delimiter'] = '|';