<?php

class UsersTableSeeder extends Seeder {

    public function run()
    {
        // Create a whole bunch of test users.
        User::create(array(
            'username' => 'Christopher',
            'email' => 'christopher@email.com',
            'password' => '1234',
            'password_confirmation' => '1234'
        ))->save();
        User::create(array(
            'username' => 'Rien',
            'email' => 'rien@email.com',
            'password' => '1234',
            'password_confirmation' => '1234'
        ))->save();
        User::create(array(
            'username' => 'Dan',
            'email' => 'dan@email.com',
            'password' => '1234',
            'password_confirmation' => '1234'
        ))->save();
        User::create(array(
            'username' => 'Amarah',
            'email' => 'amarah@email.com',
            'password' => '1234',
            'password_confirmation' => '1234'
        ))->save();
        User::create(array(
            'username' => 'Urwin',
            'email' => 'urwin@email.com',
            'password' => '1234',
            'password_confirmation' => '1234'
        ))->save();
        User::create(array(
            'username' => 'Mike',
            'email' => 'mike@email.com',
            'password' => '1234',
            'password_confirmation' => '1234'
        ))->save();
    }

}