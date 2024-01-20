<?php

function view($view, $page_data)
{
    $page_data['page_name'] = $view;
    return ci()->load->view('common', $page_data);
}

function getSession($session)
{
    return ci()->session->userdata($session);
}

function setSession($type, $session_data)
{
    return ci()->session->set_userdata($type, $session_data);
}
