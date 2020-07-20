<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Jobs\SendContactMail;

class ContactController extends Controller {

	public function saveContact(Request $request){
		$input = $request->all();
		$input['_token'] = 'thieuhao41285';

		DB::beginTransaction();
		try {
			$result = DB::table('contacts')->insert($input);
			if (!$result) {
				return 'error';
			} else {
				$customer_id = DB::table('contacts')->max('id');
				$input['reciever_email'] = 'thieuhao2525@gmail.com';
				$input['subject'] = 'kHÁCH HÀNG MỚI ĐĂNG KÍ';
				$input['view'] = 'email.new_contact';
				$input['route'] = route('contact.assgin', $customer_id);
				// $input['customer_id'] = $resutlt->id;
				$time = Carbon::now()->addSeconds(5);
				SendContactMail::dispatch($input)
				->delay($time);
				$values = [
					[
						intval(date('m')), $customer_id, $request->name, '', 'PENDING', $request->company,
					],
				];
				self::appendRow($values);
				$id = $customer_id + 1;
				self::colorLine($id, $a = 0.5, $b = 0.5, $r = 0.5, $g = 1);

				DB::commit();
				return response()->json([$result]);
			}
		} catch (\Exception $e) {
			DB::rollBack();
			\Log::info($e);
			return response()->json(['status'=>'0','message'=>'error']);
		}
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
	public static function getClient() {
		$client = new \Google_Client();
		$client->setApplicationName('Contact App');
		$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
		$client->setAccessType('offline');
		$client->setAuthConfig(public_path('credentials.json'));
		$sheets = new \Google_Service_Sheets($client);
		return $sheets;
	}
	public static function colorLine($line, $a = 0.5, $b = 0.5, $r = 0.5, $g = 0.5) {
		$service = self::getClient();
		$spreadsheetId = '1VREBsR7k8HQtYEoLg2oXhvWwkb5eRhLlPe7GG5FtszU';

		// get sheetId of sheet with index 0
		$sheetId = $service->spreadsheets->get($spreadsheetId);
		$sheetId = $sheetId->sheets[0]->properties->sheetId;

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
