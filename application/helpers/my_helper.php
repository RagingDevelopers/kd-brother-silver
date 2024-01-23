<?php

function ci()
{
    $CI = &get_instance();
    return $CI;
}
function Formatedate($date)
{
    return date('d-M-Y H:i:s', strtotime($date));
}

function pre($data, $exit = false)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    if ($exit == true)
        exit;
}

function imageUpload($path, $image)
{
    $config['upload_path'] = "$path";
    $config['allowed_types'] = 'gif|jpg|png';
    $config['max_size'] = '100';
    // $config['max_width']  = '1024';
    // $config['max_height']  = '768';
    ci()->load->library('upload', $config);
    if (!ci()->upload->do_upload($image)) {
        return false;
    } else {
        return ci()->upload->data();
    }
}
function access_level_admin()
{
    $CI = ci();
    if ($CI->session->has_userdata('login')) {
        $message = ['class' => 'danger', 'message' => 'Your Session hase been Expired!! '];
        flash_message($message);
        redirect(base_url('admin/login'));
    }
}
function check_login()
{
    $CI = $CI = ci();
    if (!$CI->session->userdata('admin_login')) {
        flash_message('danger', 'Your Session hase been Expired!!', 'login');
    }
}

function flash_message($class, $message = null, $url = null)
{
    $CI = ci();
    if (is_array($class)) {
        $FlashMessage = ['class' => $class['class'], 'message' => $class['message']];
    } else {
        $FlashMessage = ['class' => $class, 'message' => $message];
    }
    $CI->session->set_flashdata('flash', $FlashMessage);

    if (!is_null($url))
        return redirect(base_url($url));
}

function uri($url)
{
    $CI = ci();
    if ($CI->uri->uri_string() == $url) {
        return "active";
    }
    return "";
}

function IsActive($url)
{
    $CI =& get_instance();
    $segment = $CI->uri->segment(1);
    if ($segment == $url) {
        return "active";
    }

    return "";
}
