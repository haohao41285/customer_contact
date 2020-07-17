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
		<h4 class="text-center">Contact Status Reporting</h4>
			<div class="py-2">
				<!-- <fieldset style="border:1px grey solid;border-radius:10px;min-width:auto;padding:5px;margin:auto"> -->
	    			<legend>Customer Information:</legend>
					<p>Name: <b>{{ $contact_info->name }}</b></p>
					<p>Email: <b>{{ $contact_info->email }}</b></p>
					<p>Phone: <b>{{ $contact_info->phone }}</b></p>
				<!-- </fieldset> -->
			</div>
			<div class="py-2">
				<form action="{{ route('contact.finish.post') }}" method="POST">
					@csrf
					<input type="hidden" name="id" value="{{ $contact_info->id}}" />
					<input type="hidden" name="token" value="{{$contact_info->_token}}" />
					<div class="form-group">
					    <label for="status">Status</label>
					    <select id="status" class="form-control form-control-sm" name="status">
					    	<option value="1">Successfully</option>
					    	<option value="2">Failed</option>
					    </select>
				   </div>
					<div class="form-group">
					    <label for="company">Note</label>
					    <textarea class="form-control form-control-sm" name="note" rows="2"></textarea>
				   </div>
				   <div class="form-group">
					    <label for="name">Nhu Cầu Dịch Vụ</label>
					     <select id="status" class="form-control form-control-sm" name="">
					    	<option value="SINGLE-SV">SINGLE-SV</option>
					    	<option value="MULTI-SV">MULTI-SV</option>
					    </select>
				   </div>
				   <div class="form-group">
					    <label for="services">Tên Dịch Vụ</label>
					     <select id="services" class="form-control form-control-sm" name="services">
					    	<option value="SINGLE-SV">SINGLE-SV</option>
					    	<option value="MULTI-SV">MULTI-SV</option>
					    </select>
				   </div>
				   <div class="form-group">
					    <label for="business_area">lĩnh Vực Kinh Doanh</label>
					     <select id="business_area" class="form-control form-control-sm" name="business_area">
					    	<option value="SINGLE-SV">SINGLE-SV</option>
					    	<option value="MULTI-SV">MULTI-SV</option>
					    </select>
				   </div>
				   <div class="form-group">
					    <label for="website">Website</label>
					     <select id="website" class="form-control form-control-sm" name="website">
					    	<option value="Hotline">Hotline</option>
					    	<option value="Website (email info, contact)">Website (email info, contact)</option>
					    	<option value="Fanpage">Fanpage</option>
					    	<option value="Event">Event</option>
					    	<option value="KH cũ giới thiệu">KH cũ giới thiệu</option>
					    	<option value="Internal">Internal</option>
					    	<option value="Campaign Promotion">Campaign Promotion</option>
					    	<option value="Website Agency">Website Agency</option>
					    </select>
				   </div>
				    <button type="submit" class="btn btn-primary btn-sm float-right">Submit</button>
				</form>
			</div>
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