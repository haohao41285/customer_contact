<?php

use Illuminate\Database\Seeder;

class MajorBusiness extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$major_arr = [
			'Nhà hàng/ Khách sạn',
			'Du lịch',
			'Điện tử',
			'Bất động sản',
			'Tài chính- Ngân hàng',
			'Bảo hiểm',
			'TMĐT',
			'Y tế',
			'Giáo dục',
			'Hành chính công',
			'Thời trang',
			'Mỹ phẩm',
			'Tuyển dụng',
			'Game',
			'MXH',
			'Khác',
		];

		$mar = [];

		foreach ($major_arr as $major) {
			$mar[] = [
				'name' => $major,
			];
		}

		DB::table('major_businesses')->insert($mar);
	}
}
