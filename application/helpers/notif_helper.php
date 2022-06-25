<?php

function notif_sent($sender_id, $receiver_id, $title, $message) {
    $ci = &get_instance();
    if ($receiver_id === $sender_id) return false;
    $data = array(
        'sender_id' => $sender_id,
        'receiver_id' => $receiver_id,
        'title' => $title,
        'message' => $message
    );
    $ci->db->set($data);
    $ci->db->insert('notification');
    return $ci->db->insert_id();
}

function notif_read($id, $receiver_id) {
    $ci = &get_instance();
    $query = $ci->db->get_where('notification', array('id' => $id, 'receiver_id' => $receiver_id));
    $update = false;
    if ($query->num_rows() > 0) {
        $ci->db->set('read_on', date('Y-m-d H:i:s'));
        $ci->db->where('id', $id);
        $ci->db->where('receiver_id', $receiver_id);
        $update = $ci->db->update('notification');
    }
    return $update;
}

function notif_list($receiver_id, $limit = null, $offset = null, $like = false, $where = false) {
    $ci = &get_instance();
    $ci->db->select('sender.fullname as sender_name, receiver.fullname as receiver_name, notification.*');
    if ($like) $ci->db->like('title', $like);
    if ($where) $ci->db->where($where);
    $ci->db->where('notification.receiver_id', $receiver_id);
    if ($limit && $offset) $ci->db->limit($limit, $offset);
    $ci->db->order_by('notification.id', 'DESC');
    $ci->db->join('users as sender', 'notification.sender_id = sender.id');
    $ci->db->join('users as receiver', 'notification.receiver_id = receiver.id');
    $query = $ci->db->get('notification');
    return $query;
}

function notif_delete($id) {
    $ci = &get_instance();
    $query = $ci->db->get_where('notification', array('id' => $id));
    $delete = false;
    if ($query->num_rows() > 0) {
        $ci->db->where('id', $id);
        $delete = $ci->db->delete('notification');
    }
    return $delete;
}