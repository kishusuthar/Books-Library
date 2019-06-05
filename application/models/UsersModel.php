<?php

class UsersModel extends CI_Model{
    
    // function to fetch all active users
    public function fetchActiveUsers() {
        $this->db->where( 'is_disabled=0' );

        $query = $this->db->get('users');
        return $query->result();
    }

}
?>