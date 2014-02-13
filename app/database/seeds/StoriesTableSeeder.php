<?php

class StoriesTableSeeder extends Seeder {

    public function run()
    {
        // Create a whole bunch of test stories.
        Story::create(array(
            'title' => 'Enter The Void',
            'theme' => 'Scifi',
            'user_id' => 1,
            'status' => 2,
            'current_writer' => 1,
            'invite_mode' => 1,
            'max_writers' => 5,
            'min_words_per_segment' => 5,
            'max_words_per_segment' => 10,
            'write_mode' => 1
        ))->save();
        Story::create(array(
            'title' => 'Across The Plains',
            'theme' => 'Adventure',
            'user_id' => 1,
            'status' => 1,
            'current_writer' => 1,
            'invite_mode' => 3,
            'max_writers' => 3,
            'min_words_per_segment' => 5,
            'max_words_per_segment' => 20,
            'write_mode' => 1
        ))->save();
        Story::create(array(
            'title' => 'How To Dutch',
            'theme' => 'Informative',
            'user_id' => 2,
            'status' => 1,
            'current_writer' => 2,
            'invite_mode' => 4,
            'max_writers' => 3,
            'min_words_per_segment' => 3,
            'max_words_per_segment' => 250,
            'write_mode' => 1
        ))->save();
        Story::create(array(
            'title' => 'Guts, Nothing But Problems',
            'theme' => 'Bio',
            'user_id' => 5,
            'status' => 1,
            'current_writer' => 5,
            'invite_mode' => 1,
            'max_writers' => 2,
            'min_words_per_segment' => 3,
            'max_words_per_segment' => 250,
            'write_mode' => 1
        ))->save();
        Story::create(array(
            'title' => 'Chaos Theory',
            'theme' => 'Experimental',
            'user_id' => 4,
            'status' => 1,
            'current_writer' => 4,
            'invite_mode' => 4,
            'max_writers' => 2,
            'min_words_per_segment' => 3,
            'max_words_per_segment' => 30,
            'write_mode' => 2
        ))->save();

        // Bind the creators to the stories as writers.
        Story::find(1)->writers()->attach(1, array('active' => 1, 'creator' => 1));
        Story::find(2)->writers()->attach(1, array('active' => 1, 'creator' => 1));
        Story::find(3)->writers()->attach(2, array('active' => 1, 'creator' => 1));
        Story::find(4)->writers()->attach(5, array('active' => 1, 'creator' => 1));
        Story::find(5)->writers()->attach(4, array('active' => 1, 'creator' => 1));

        // Bind additional writers.
        //Story::find(1)->writers()->attach(2);
        //Story::find(1)->writers()->attach(3);
        Story::find(1)->writers()->attach(4, array('active' => 1));
        //Story::find(1)->writers()->attach(5);
        //Story::find(1)->writers()->attach(6);

        Story::find(3)->writers()->attach(1, array('active' => 1));

        Story::find(4)->writers()->attach(1);

        // Add some segments to the testing story.
        Segment::create([
            'user_id' => 1,
            'story_id' => 1,
            'content' => 'Bob wanted to go to space, so when the opportunity'
        ])->save();
        Segment::create([
            'user_id' => 4,
            'story_id' => 1,
            'content' => 'came around, he wasted no time in getting started.'
        ])->save();
        Segment::create([
            'user_id' => 1,
            'story_id' => 1,
            'content' => 'It was hard work and took a long time but'
        ])->save();
        Segment::create([
            'user_id' => 4,
            'story_id' => 1,
            'content' => 'after many months Bob was finally ready to go to'
        ])->save();
        Segment::create([
            'user_id' => 1,
            'story_id' => 1,
            'content' => 'space to explore the stars along side his fellow space'
        ])->save();
        Segment::create([
            'user_id' => 1,
            'story_id' => 1,
            'content' => 'comrades in the persuit of knowledge.'
        ])->save();
    }

}