<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Jobs\SendContactMail;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Google_Client; 
use Google_Service_Drive;
use Google_Service_Sheets;

class ContactController extends Controller {
	// define('STDIN',fopen("php://stdin","r"));
	CONST STDIN = 'fopen("php://stdin","r")';

	public function index() {
		return view('contact.index');
	}
	public function postForm(Request $request) {
		$input = $request->all();

		$resutlt = DB::table('contacts')->insert($input);
		if (!$resutlt) {
			return 'error';
		} else {
			// Get the API client and construct the service object.
			$client = $this->getClient();
			return $client;
			$customer_id = DB::table('contacts')->max('id');
			$input['reciever_email'] = 'thieuhao2525@gmail.com';
			$input['subject'] = 'kHÁCH HÀNG MỚI ĐĂNG KÍ';
			$input['view'] = 'email.new_contact';
			$input['route'] = route('contact.assgin', $customer_id);
			// $input['customer_id'] = $resutlt->id;
			$time = Carbon::now()->addSeconds(5);
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
			$input['subject'] = 'THÔNG BÁO KHÁCH HÀNG MỚI ĐƯỢC CHỈ ĐỊNH';
			$input['view'] = 'email.assign_contact';
			$input['route'] = route('contact.finish', $customer_info->_token);
			// $input['customer_id'] = $resutlt->id;
			$time = Carbon::now()->addSeconds(5);
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
	
	public static function getClient()
	{
		$client = new \Google_Client();
		$client->setApplicationName('Contact App');
		$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
		$client->setAccessType('offline');

		/*
		 * The JSON auth file can be provided to the Google Client in two ways, one is as a string which is assumed to be the
		 * path to the json file. This is a nice way to keep the creds out of the environment.
		 *
		 * The second option is as an array. For this example I'll pull the JSON from an environment variable, decode it, and
		 * pass along.
		 */
		
	    $client->setAuthConfig(public_path('credentials.json'));

		/*
		 * With the Google_Client we can get a Google_Service_Sheets service object to interact with sheets
		 */
		$sheets = new \Google_Service_Sheets($client);
		// dd($sheets);
		/*
		 * To read data from a sheet we need the spreadsheet ID and the range of data we want to retrieve.
		 * Range is defined using A1 notation, see https://developers.google.com/sheets/api/guides/concepts#a1_notation
		 */
		$data = [];

		// The first row contains the column titles, so lets start pulling data from row 2
		$currentRow = 2;

		// The range of A2:H will get columns A through H and all rows starting from row 2
		$spreadsheetId = '1D-xwdkxUo2cqPO-30LciucsadX-G4AhJPswNntq7XW8';
		$range = 'A2:H';
		// $rows = $sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']);
		// dd($rows);
		$response = $sheets->spreadsheets_values->get($spreadsheetId, $range);
		// dd($response);
		return $response;

		if (isset($rows['values'])) {
		    foreach ($rows['values'] as $row) {
		        /*
		         * If first column is empty, consider it an empty row and skip (this is just for example)
		         */
		        if (empty($row[0])) {
		            break;
		        }

		        $data[] = [
		            'col-a' => $row[0],
		            'col-b' => $row[1],
		            'col-c' => $row[2],
		            'col-d' => $row[3],
		            'col-e' => $row[4],
		            'col-f' => $row[5],
		            'col-g' => $row[6],
		            'col-h' => $row[7],
		        ];

		        /*
		         * Now for each row we've seen, lets update the I column with the current date
		         */
		        $updateRange = 'I'.$currentRow;
		        $updateBody = new \Google_Service_Sheets_ValueRange([
		            'range' => $updateRange,
		            'majorDimension' => 'ROWS',
		            'values' => ['values' => date('c')],
		        ]);
		        $sheets->spreadsheets_values->update(
		            $spreadsheetId,
		            $updateRange,
		            $updateBody,
		            ['valueInputOption' => 'USER_ENTERED']
		        );

		        $currentRow++;
		    }
		}
		print_r($data);
	}

}
