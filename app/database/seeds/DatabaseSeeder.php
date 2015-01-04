<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UsersTableSeeder');
		$this->call('ThreadsTableSeeder');
		$this->call('RessTableSeeder');
	}

}

class UsersTableSeeder extends Seeder {

	public function run() {
		DB::table('users')->delete();

		User::create(array(
			'fbid' => 123456789012,
			'first_name' => 'watashiha',
			'last_name' => 'yamauchi',
			'name' => 'watashiha yamauchi',
			'gender' => 'male',
			'photo' => '',
		));
	}
}

class ThreadsTableSeeder extends Seeder {

	public function run() {
		DB::table('threads')->delete();

		Thread::create(array(
			'title' => 'スレッド1',
			'body' => '1月の予定を書く',
			'user_id' => 1,
		));
		Thread::create(array(
			'title' => 'スレッド2',
			'body' => '2月の予定を書く',
			'user_id' => 1,
		));
		Thread::create(array(
			'title' => 'スレッド3',
			'body' => '3月の予定を書く',
			'user_id' => 1,
		));
	}
}

class RessTableSeeder extends Seeder {

	public function run() {
		DB::table('ress')->delete();

		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 2,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 3,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 4,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 5,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 6,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 7,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 8,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 9,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 10,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 11,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 12,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 13,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 14,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 15,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));


		Res::create(array(
			'thread_id' => 2,
			'Res_no' => 2,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 2,
			'Res_no' => 3,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 2,
			'Res_no' => 4,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 2,
			'Res_no' => 5,
			'body' => '内容内容内容内容',
			'user_id' => 1,
		));

	}

}
