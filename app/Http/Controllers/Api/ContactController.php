<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller {
	public function saveContact(Request $request) {
		return 'ok';
		return $request->all();
	}
}
