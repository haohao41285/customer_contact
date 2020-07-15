<!DOCTYPE html>
<html>
<head>
	<title>Contact Form</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
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
		<div class="">
			<h4 class="text-center">Assign Contact</h4>
			<fieldset style="border:1px grey solid;border-radius:10px;min-width:auto;padding:5px;margin:auto">
    			<legend>Customer Information:</legend>
				<p>Name: <b>{{ $customer_info->name }}</b></p>
				<p>Email: <b>{{ $customer_info->email }}</b></p>
				<p>Phone: <b>{{ $customer_info->phone }}</b></p>
			</fieldset>
			<form action="{{ route('contact.assign.post') }}" method="POST">
				@csrf
				<input type="hidden" value="{{ $id }}" name="id" />
				<div class="form-group">
				    <h5 class="mt-3"><b>Assign to</b></h5>
				    <select id="assign_to" class="form-control" name="staff">
				    	@foreach($staffs as $staff)
				    	<option value="{{ $staff->id }}">{{ $staff->name."-".$staff->email."-".$staff->position }}</option>
				    	@endforeach
				    </select>
			   </div>
			    <button type="submit" class="btn btn-primary btn-sm float-right">Assign</button>
			</form>
		</div>
	</div>
</body>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<script>
		$('#assign_to').select2();
	</script>
</html>