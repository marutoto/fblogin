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
			'title' => 'thread no.1',
			'body' => 'bodybodybodybodybody1',
			'user_id' => 1,
		));
		Thread::create(array(
			'title' => 'thread no.2',
			'body' => 'bodybodybodybodybody1',
			'user_id' => 1,
		));
		Thread::create(array(
			'title' => 'thread no.3',
			'body' => 'bodybodybodybodybody1',
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
			'body' => 'bodybodybodybodybody2',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 3,
			'body' => 'bodybodybodybodybody3',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 4,
			'body' => 'bodybodybodybodybody4',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 5,
			'body' => 'bodybodybodybodybody5',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 6,
			'body' => 'bodybodybodybodybody6',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 7,
			'body' => 'bodybodybodybodybody7',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 8,
			'body' => 'bodybodybodybodybody8',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 9,
			'body' => 'bodybodybodybodybody9',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 10,
			'body' => 'bodybodybodybodybody10',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 11,
			'body' => 'bodybodybodybodybody11',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 12,
			'body' => 'bodybodybodybodybody12',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 13,
			'body' => 'bodybodybodybodybody13',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 14,
			'body' => 'bodybodybodybodybody14',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 1,
			'Res_no' => 15,
			'body' => 'bodybodybodybodybody15',
			'user_id' => 1,
		));


		Res::create(array(
			'thread_id' => 2,
			'Res_no' => 2,
			'body' => 'bodybodybodybodybody2',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 2,
			'Res_no' => 3,
			'body' => 'bodybodybodybodybody3',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 2,
			'Res_no' => 4,
			'body' => 'bodybodybodybodybody4',
			'user_id' => 1,
		));
		Res::create(array(
			'thread_id' => 2,
			'Res_no' => 5,
			'body' => 'bodybodybodybodybody5',
			'user_id' => 1,
		));

	}

}
