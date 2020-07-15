<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Jobs\SendContactMail;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ContactController extends Controller {
	public function index() {
		return view('contact.index');
	}
	public function postForm(Request $request) {
		$input = $request->all();

		$resutlt = DB::table('contacts')->insert($input);
		if (!$resutlt) {
			return 'error';
		} else {
			$customer_id = DB::table('contacts')->max('id');
			$input['reciever_email'] = 'thieuhao2525@gmail.com';
			$input['subject'] = 'Khách hàng mới đăng kí nhận tin';
			$input['view'] = 'email.new_contact';
			$input['route'] = route('contact.assgin', $customer_id);
			// $input['customer_id'] = $resutlt->id;
			$time = Carbon::now()->addMinutes(1);
			SendContactMail::dispatch($input)
				->delay($time);

			return 'ok';
		}

	}
	public function assgin($customer_id) {
		$customer_info = DB::table('contacts')->where('id', $customer_id)->first();
		if (!$customer_info) {
			return 'error';
		}
		$data['staffs'] = DB::table('staff')->get();
		$data['id'] = $customer_id;

		$data['customer_info'] = $customer_info;
		return view('contact.assign', $data);
	}
	public function assignPost(Request $request) {

		$input = $request->all();

		DB::beginTransaction();

		try {
			//get information to update sheet
			$staff_info = DB::table('staff')->where('id', $input['staff'])->first();
			//update assign_to database
			$customer_info = DB::table('contacts')->where('id', $request->id)->first();
			DB::table('contacts')->where('id', $request->id)->update(['assign_to' => $input['id']]);

			$input['customer_info'] = $customer_info;

			$input['reciever_email'] = $staff_info->email;
			$input['subject'] = 'Khách hàng mới đã được chỉ định';
			$input['view'] = 'email.assign_contact';
			$input['route'] = route('contact.finish', $customer_info->_token);
			// $input['customer_id'] = $resutlt->id;
			$time = Carbon::now()->addMinutes(1);
			SendContactMail::dispatch($input)
				->delay($time);

			DB::commit();
			return 'asssign successfully!';

		} catch (\Exception $e) {
			DB::rollBack();
			return 'asssign falied';
		}

	}
	public function finish($token) {
		return $token;
	}
}
