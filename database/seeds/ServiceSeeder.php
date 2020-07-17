<?php

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$service_array = ['BRANDNAME', 'LBS', 'DSCD', 'RANDOM', 'KM MKT', 'Email', 'Zalo ads', 'Facebook Ads', 'Google ads',
			'VoiceOTP',
			'MIX (SMS only)',
			'Topup',
			'E-warranty',
			'Smart campaign - Multi Channel',
			'Viber',
			'2 way (SMS, Viber, Zalo, Facebook)',
			'Email',
			'Online ads (GDN, FB)',
			'Wifi Ads',
			'Production (landing page, website)'];

		$sar = [];

		foreach ($service_array as $service) {
			$sar[] = [
				'name' => $service,
			];
		}
		DB::table('services')->insert($sar);
	}
}