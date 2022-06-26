<?php

function unique_id($mode = false, $length = 16) {
    if ($mode === 'symbol') return substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz~!@#$%^&*()_+{}|:<>?-=[];,.", $length)), 0, $length);
    if ($mode === 'uuid') return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
    if ($length) return substr(bin2hex(random_bytes($length)), 0, $length);
}

function wah_encode($value) {
    if (!$value) return false;
    $ci = &get_instance();
    $value = base64_encode(unique_id('symbol', 3) . '|' . base64_encode($value) . '|' . unique_id(false, 3));
    $key = hash('sha256', $ci->config->item('encryption_key'), true);
    $strLen = strlen($value);
    $keyLen = strlen($key);
    $j = 0;
    $crypttext = '';
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($value, $i, 1));
        if ($j == $keyLen) $j = 0;
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $crypttext .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
    }
    return str_rot13($crypttext);
}

function wah_decode($value) {
    if (!$value) return false;
    $ci = &get_instance();
    $key = hash('sha256', $ci->config->item('encryption_key'), true);
    $strLen = strlen($value);
    $keyLen = strlen($key);
    $j = 0;
    $decrypttext = '';
    for ($i = 0; $i < $strLen; $i += 2) {
        $ordStr = hexdec(base_convert(strrev(substr(str_rot13($value), $i, 2)), 36, 16));
        if ($j == $keyLen) $j = 0;
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $decrypttext .= chr($ordStr - $ordKey);
    }

    $decodedstr = explode('|', base64_decode($decrypttext));
    // if (time() > $decodedstr[1]) show_error('Your token is no longer valid.');
    return base64_decode($decodedstr[1]);
}

function wah_encrypt($data, $key = null, $cipher = 'bf-ofb') {
    $ci = &get_instance();
    $key = (!$key ? $ci->config->item('encryption_key') : $key);
    $plaintext = $data;
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv, $tag = false);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
    return $ciphertext;
}

function wah_decrypt($data, $key = null, $cipher = 'bf-ofb') {
    $ci = &get_instance();
    $key = (!$key ? $ci->config->item('encryption_key') : $key);
    $c = base64_decode($data);
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv, $tag = false);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    return (hash_equals($hmac, $calcmac) ? $original_plaintext : false);
}

function rupiah($value) {
	return "Rp" . number_format($value, 0, ',', '.');
}

function input_get($value) {
    $ci = &get_instance();
    return htmlspecialchars(htmlentities(strip_tags($ci->input->get($value))), ENT_QUOTES, 'UTF-8');
}

function input_post($value) {
    $ci = &get_instance();
    return htmlspecialchars(htmlentities(strip_tags($ci->input->post($value))), ENT_QUOTES, 'UTF-8');
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function hariIndo($hariInggris) {
    switch ($hariInggris) {
      case 'Sunday':
        return 'Minggu';
      case 'Monday':
        return 'Senin';
      case 'Tuesday':
        return 'Selasa';
      case 'Wednesday':
        return 'Rabu';
      case 'Thursday':
        return 'Kamis';
      case 'Friday':
        return 'Jumat';
      case 'Saturday':
        return 'Sabtu';
      default:
        return '-';
    }
}