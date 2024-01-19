<?php

function pre($data)
{
    echo "<pre>";
    print_r($data);
    exit;
}

function check_login()
{
    $ci = &get_instance();
    if (!$ci->session->userdata('admin_login')) {
        redirect(site_url('login'), 'refresh');
    }
}