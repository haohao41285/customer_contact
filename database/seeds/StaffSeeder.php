<?php

use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$staff_arr = [];
		for ($i = 1; $i < 10; $i++) {
			$staff_arr[] = [
				'name' => 'thieu' . $i,
				'email' => 'thieuhao2525@gmail.com',
			];
		}

		DB::table('staff')->insert($staff_arr);

	}
}
