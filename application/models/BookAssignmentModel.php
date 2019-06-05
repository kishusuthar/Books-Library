<?php

class BookAssignmentModel extends CI_Model{

    // function to fetch book assignment by book id
    public function fetchBookAssignmentByBookId( $id ) {
        $this->db->where( 'book_id', $id );

        $query =  $this->db->get( 'book_assignment' );
        return $query->result();
    }

    // Insert book assignment
    public function insert( $bookId ) {
        return $this->db->insert( 'book_assignment', [ 'book_id' => $bookId, 'book_status_id' =>  $this->input->post("book_status_id"), 'user_id' =>  $this->input->post("user_id") ] );     
    }

    // Update book assignment
    public function update( $id ) {
        $this->db->where( 'id', $id );
        return $this->db->update( 'book_assignment', [ 'book_status_id' =>  $this->input->post("book_status_id") ] );     
    }

}
?>