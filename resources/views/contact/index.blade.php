<!DOCTYPE html>
<html>
<head>
	<title>Contact Form</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<style>
		/* Chrome, Safari, Edge, Opera */
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
	  -webkit-appearance: none;
	  margin: 0;
	}

	/* Firefox */
	input[type=number] {
	  -moz-appearance: textfield;
	}
	</style>
</head>
<body>
	<div class="container">
		<div class="col-md-4 offset-md-4 p-5 mt-5 border border-secondary rounded">
			<h4 class="text-center">Contact Form</h4>
			<form action="{{ route('contact.post') }}" method="POST">
				@csrf
				<div class="form-group">
				    <label for="name">Name</label>
				    <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Name">
			   </div>
				<div class="form-group">
				    <label for="company">Company</label>
				    <input type="text" class="form-control" name="company" id="company" placeholder="Enter Your Company">
			   </div>
			   <div class="form-group">
				    <label for="email">Email</label>
				    <input type="email" class="form-control" name="email" id="email"  placeholder="Enter Email">
				</div>
			    <div class="form-group">
				    <label for="phone">Phone</label>
				    <input type="number" onkeypress="return isNumberKey(event)" name="phone" class="form-control" id="phone" placeholder="Enter Phone" required>
			    </div>
			    <button type="submit" class="btn btn-primary btn-sm float-right">Submit</button>
			</form>
		</div>
	</div>
</body>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script>
		function isNumberKey(evt){
		    var charCode = (evt.which) ? evt.which : event.keyCode
		    if (charCode > 31 && (charCode < 48 || charCode > 57))
		      return false;
		     return true;
		}
	</script>
</html>