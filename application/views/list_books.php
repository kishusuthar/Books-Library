<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Book Library</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<style type="text/css">
	body {
		margin: 10px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
	}

	h1 {
		color: #444;
		font-size: 19px;
		font-weight: normal;
		padding: 14px 15px 5px 0px;
	}

	table {
		border-spacing:1px;
	}

	th {
		background:gray;
		padding:0;
		border:none;
		color:white;
		
	}

	td {
		padding-left:5px;
		border-bottom: 1px solid #85929E;
	}

	tr {
		border-color:black;
		border-bottom: 5px solid;
		height:30px;
	}

	tr:hover {
		background-color:#F3F3F3;
	}

	.search-box {
		border-radius: 5px;
		height:20px;
		outline:none;
		margin:5px;
		width:200px;
	}

	#right {
		float:right;
	}

	#success-msg {
		width:79%;
		height:25px;
		margin-top:5px;
		float:left;
		background-color:#D1F7D8;
		border-radius:5px;
		text-align:center;
		font-weight:bold;
		padding-top:2px;
	}

	#error-msg {
		background-color:#F49B89;
		border-radius:5px;
		text-align:center;
		font-weight:bold;
		padding-top:2px;
	}

	.hide {
		display:none;
	}

	.info {
		text-align: center;
	}
	</style>
</head>
<body>

<div id="books_container">
	<h1>Book Library</h1>
	<div id="success-msg" class="hide"></div>
	<div id="right">
		<label>Search:</label>
		<input type="text" name="search" id="search-box" class="search-box" value="<?php echo $search ?>">
	</div>
	<table width="100%">
		<tr>
			<th width="20%">Name</th>
			<th width="25%">Description</th>
			<th width="18%">Author</th>
			<th width="7%">Status</th>
			<th width="25%">Actions</th>
		</tr>
		<?php foreach( $books as $book ) { ?> 
		<tr data-id="<?php echo $book['id']; ?>">
			<td><?php echo $book['title']; ?></td>
			<td><?php echo $book['description']; ?></td>
			<td><?php echo $book['author_name']; ?></td>
			<td><?php echo ( empty( $book['book_status'] ) ) ? 'Available' : $book['book_status']; ?></td>
			<td>
				<button class="btn-issue_book <?php echo ( !empty( $book['book_status_id'] ) && 1 == $book['book_status_id'] ) ? 'hide' : '' ?>">Issue</button>
				<button class="btn-return_book <?php echo ( empty( $book['book_status_id'] ) || 2 == $book['book_status_id'] ) ? 'hide' : '' ?>">Return</button>
			</td>
		</tr>
		<?php } ?>
		<?php if( empty( $books ) ) {?>
			<tr>
				<td colspan="5" class="info">No Record Found.</td>
			</tr>
		<?php } ?>	
	</table>

	<div id="dialog-form" title="Issue Book">
		<div class="hide" id="error-msg"></div>	
		<form >
			<label for="name">User:</label>
			<select class="search-box" name="user">
				<option value="">Choose One</option>
				<?php foreach( $users as $user ) { ?>
					<option value="<?php echo $user->id ?>"><?php echo $user->name ?></option>
				<?php } ?>
			</select>		
			<!-- Allow form submission with keyboard without duplicating the dialog button -->
			<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
		</form>
	</div>
</div>
<script type="text/javascript">
var bookId = '';
var dialog = '';

$( function() {
	function validateAndSubmit() {
		if( !$('select[name="user"]').val() ) {
			$('#error-msg').html( 'User is required.' ).removeClass('hide');
			return false;
		}

		$.ajax({
			url: "/Books-Library/IssueBook/" + bookId,
			data: { 'book_status_id': 1, 'user_id': $('select[name="user"]').val() },
			type: 'POST',
			success: function( result ) {
				dialog.dialog( "close" );
				window.location.reload();
				$('#success-msg').html( 'Book issued successfully.' ).removeClass( 'hide' );
			}
		});
	}

    dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 180,
      width: 280,
      modal: true,
      buttons: {
        Submit: validateAndSubmit,
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
	  close: function() {
		$('select[name="user"]').val('');
	  }
	});
});


$('#search-box').focus();
$('.btn-issue_book').on( 'click', function() {
	bookId = $(this).closest('tr').data('id');
	dialog.dialog( "open" ).data( 'abc', 1 );
});

$('.btn-return_book').on( 'click', function() {
	$.ajax({
		url: "/Books-Library/ReturnBook/" + $(this).closest('tr').data('id'),
		success: function( result ){
			window.location.reload();
			$('#success-msg').html( 'Book returned successfully.' ).removeClass( 'hide' );
		}
	});
});

$('#search-box').on( 'keyup', function( event ) {
	if( '' == $.trim( event.target.value ) ) {
		return false;
	}

	setTimeout(() => {
		$.ajax({
			url: '/Books-Library/',
			data: { 'search': event.target.value },
			type: 'POST',
			success: function( result ) {
				$("#books_container").html( result );
			}
		});
	}, 1000 );
});
</script>
</body>
</html>