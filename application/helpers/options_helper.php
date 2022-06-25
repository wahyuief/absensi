<?php

function get_option($option_name, $default_value = null) {
    $ci = &get_instance();
    if ($option = check_option($option_name)) {
        if (!empty($option)) {
            return $option;
        } else {
            return $default_value;
        }
    } else {
        set_option($option_name, $default_value);
    }

    return $default_value;
}

function set_option($option_name, $option_value) {
    $ci = &get_instance();
    if (check_option($option_name) === false) {
        return $ci->db->insert('options', [ 
            'option_name' => $option_name,
            'option_value' => $option_value 
        ]);
    } else {
        return $ci->db
                    ->where(['option_name' => $option_name])
                    ->update('options', ['option_value' => $option_value]);
    }

    return false;
}

function unset_option($option_name) {
    $ci = &get_instance();
    return $ci->db
                ->where(['option_name' => $option_name])
                ->delete('options');
}

function check_option($option_name) {
    $ci = &get_instance();
    $options = $ci->db->get('options')->result();
    $option = [];
    foreach ($options as $row) $option[$row->option_name] = $row;
    if (isset($option[$option_name])) return $option[$option_name]->option_value;
    return false;
}