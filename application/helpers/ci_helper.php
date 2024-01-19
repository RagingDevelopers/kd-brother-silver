<?php
global $ci;

$ci = &get_instance();

function view($page_data){
    return $ci->load->view('admin/common', $page_data);  
}

function getSession($session){
    return $ci->session->userdata($session);
}

function setSession($type,$session_data){
   return $ci->session->set_userdata($type, $session_data);  
}

?>