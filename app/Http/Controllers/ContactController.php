<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Google_Client;
use Google_Service_Sheets;
use Illuminate\Http\Request;

class ContactController extends Controller {

	public function index() {
		return view('contact.index');
	}
	public function postForm(Request $request) {
		$input = $request->all();

		DB::beginTransaction();
		try {
			$resutlt = DB::table('contacts')->insert($input);
			if (!$resutlt) {
				return 'error';
			} else {
				$customer_id = DB::table('contacts')->max('id');
				$input['reciever_email'] = 'thieuhao2525@gmail.com';
				$input['subject'] = 'kHÁCH HÀNG MỚI ĐĂNG KÍ';
				$input['view'] = 'email.new_contact';
				$input['route'] = route('contact.assgin', $customer_id);
				// $input['customer_id'] = $resutlt->id;
				$time = Carbon::now()->addSeconds(5);
				// SendContactMail::dispatch($input)
				// ->delay($time);
				DB::commit();
			}
		} catch (\Exception $e) {
			DB::rollBack();
			\Log::info($e);
			return 'error';
		}

		$values = [
			[
				intval(date('m')), $customer_id, $request->name, '', '<input type="checkbox" >', $request->company,
			],
		];
		self::appendRow($values);

		return 'insert successfully';

	}
	public function assgin($customer_id) {
		$customer_info = DB::table('contacts')->where('id', $customer_id)->first();
		if (!$customer_info) {
			return 'empty';
		}
		$data['staffs'] = DB::table('staff')->get();
		$data['id'] = $customer_id;

		$data['customer_info'] = $customer_info;
		return view('contact.assign', $data);
	}
	public function assignPost(Request $request) {

		$input = $request->all();

		DB::beginTransaction();
		//get information to update sheet
		$staff_info = DB::table('staff')->where('id', $input['staff'])->first();

		try {
			//update assign_to database
			$customer_info = DB::table('contacts')->where('id', $request->id)->first();
			DB::table('contacts')->where('id', $request->id)->update(['assign_to' => $input['id']]);

			$input['customer_info'] = $customer_info;

			$input['reciever_email'] = $staff_info->email;
			$input['subject'] = 'THÔNG BÁO KHÁCH HÀNG MỚI ĐƯỢC CHỈ ĐỊNH';
			$input['view'] = 'email.assign_contact';
			$input['route'] = route('contact.finish', $customer_info->_token);
			// $input['customer_id'] = $resutlt->id;
			$time = Carbon::now()->addSeconds(5);
			// SendContactMail::dispatch($input)
			// ->delay($time);
			DB::commit();

		} catch (\Exception $e) {
			\Log::info($e);
			DB::rollBack();
			return 'asssign falied';
		}
		$values = [
			[$staff_info->name, now()],
		];
		$id = intval($request->id) + 1;

		self::updateRow($values, $id);
		// return $id;
		self::colorLine($id);
		return 'asssign successfully!';

	}
	public function finish($token) {
		return $token;
	}

	public static function getClient() {
		$client = new \Google_Client();
		$client->setApplicationName('Contact App');
		$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
		$client->setAccessType('offline');
		$client->setAuthConfig(public_path('credentials.json'));
		$sheets = new \Google_Service_Sheets($client);
		return $sheets;
	}
	public static function appendRow($values) {
		$sheets = self::getClient();
		$range = "A2:X";
		$body = new \Google_Service_Sheets_ValueRange([
			'values' => $values,
		]);
		$params = [
			'valueInputOption' => 'RAW',
		];
		$result = $sheets->spreadsheets_values->append('1VREBsR7k8HQtYEoLg2oXhvWwkb5eRhLlPe7GG5FtszU', $range, $body, $params);
		printf("%d cells appended.", $result->getUpdates()->getUpdatedCells());
	}

	public static function updateRow($values, $id) {
		$sheets = self::getClient();
		$range = "H" . $id . ":" . "I" . $id;
		$spreadsheetId = '1VREBsR7k8HQtYEoLg2oXhvWwkb5eRhLlPe7GG5FtszU';
		$body = new \Google_Service_Sheets_ValueRange([
			'values' => $values,
		]);
		$params = [
			'valueInputOption' => 'RAW',
		];
		$result = $sheets->spreadsheets_values->update(
			$spreadsheetId,
			$range,
			$body,
			$params
		);
	}

	public static function colorLine($line) {
		$service = self::getClient();
		$spreadsheetId = '1VREBsR7k8HQtYEoLg2oXhvWwkb5eRhLlPe7GG5FtszU';

		// get sheetId of sheet with index 0
		$sheetId = $service->spreadsheets->get($spreadsheetId);
		$sheetId = $sheetId->sheets[0]->properties->sheetId;

		// set colour to a medium gray
		$b = $a = $r = 0.5;
		$g = 1;

		// define range
		$myRange = [
			'sheetId' => $sheetId, // IMPORTANT: sheetId IS NOT the sheets index but its actual ID
			'startRowIndex' => $line - 1,
			'endRowIndex' => $line,
			//'startColumnIndex' => 0, // can be omitted because default is 0
			'endColumnIndex' => 24,
		];
		// return $myRange;

		// define the formatting, change background colour and bold text
		$format = [
			'backgroundColor' => [
				'red' => $r,
				'green' => $g,
				'blue' => $b,
				'alpha' => $a,
			],
			// 'textFormat' => [
			// 	'bold' => true,
			// ],
		];

		// build request
		$requests = [
			new \Google_Service_Sheets_Request([
				'repeatCell' => [
					'fields' => 'userEnteredFormat.backgroundColor, userEnteredFormat.textFormat.bold',
					'range' => $myRange,
					'cell' => [
						'userEnteredFormat' => $format,
					],
				],
			]),
		];

		// add request to batchUpdate
		$batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
			'requests' => $requests,
		]);

		// run batchUpdate
		$result = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
	}

}
