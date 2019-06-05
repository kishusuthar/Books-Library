<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BookController extends CI_Controller {
	
	public function __construct() {
		  parent::__construct(); 
		  $this->load->model('BooksModel'); 
		  $this->load->model('UsersModel'); 
		  $this->load->model('BookAssignmentModel'); 
	}

	// Handle method for issue a book
	public function handleIssueBook( $id ) {
		$bookAssignment	= new BookAssignmentModel;
		$data = $bookAssignment->fetchBookAssignmentByBookId( $id );

		if( !empty( $data ) ) {
			$bookAssignment->update( $data[0]->id );
		} else {
			$bookAssignment->insert( $id );
		}

		$this->load->helper('url');
		redirect( $_SERVER['HTTP_REFERER'], 'location'); 
	}

	// Handle method for return a book
	public function handleReturnBook( $id ) {
		$bookAssignment	= new BookAssignmentModel;
		$data = $bookAssignment->fetchBookAssignmentByBookId( $id );

		$bookAssignment->update( $data[0]->id );

		$this->load->helper('url');
		redirect( $_SERVER['HTTP_REFERER'], 'location');
	}

	// Function to display all books on UI
	public function listBooks() {
		$books			= new BooksModel;
		$users			= new UsersModel;
		$data['books']	= $books->fetchBooks();
		$data['users']	= $users->fetchActiveUsers();
		$data['search']	= $this->input->post( 'search' );

		$this->load->view( 'list_books', $data );
	}
}
