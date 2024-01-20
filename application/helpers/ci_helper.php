<?php

function view($view, $page_data)
{
    $page_data['page_name'] = $view;
    return ci()->load->view('common', $page_data);
}

function ession($session)
{
    return ci()->session->userdata($session);
}

function setSession($type, $session_data)
{
    return ci()->session->set_userdata($type, $session_data);
}

function modal($modal, $name = "")
{
    return ci()->load->model($modal, $name);
}

function helper($helper)
{
    return ci()->load->helper($helper);
}

function library($library)
{
    return ci()->load->library($library);
}
