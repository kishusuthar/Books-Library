<?php

class BooksModel extends CI_Model{
    
    // function to fetch all book from database
    public function fetchBooks() {
        $this->db->select( 'b.id, b.title, b.description, b.author_name, ba.book_status_id, bs.name as book_status' );
        $this->db->distinct();
        $this->db->from( 'books b' );
        $this->db->join( 'book_assignment ba', 'b.id = ba.book_id', 'left' );
        $this->db->join( 'book_statuses bs', 'bs.id = ba.book_status_id', 'left' );

        if( !empty( $this->input->post("search") ) ){
          $this->db->like('b.title', $this->input->post("search"));
          $this->db->or_like('b.description', $this->input->post("search")); 
        }

        $query = $this->db->get();
        return $query->result_array();
    }
    
}
?>