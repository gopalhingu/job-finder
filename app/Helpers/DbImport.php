<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DbImport
{
    public static function run()
    {
        Self::importUsersData();
        Self::importEmployersData();
        Self::importCandidatesData();
        Self::importAdminRolesData();
        Self::importPackages();
        Self::importNewsCategories();
        Self::importNews();
        Self::importFaqsCategories();
        Self::importFaqs();
        Self::importTestimonials();
        Self::importResumeData();
        Self::importDepartments();
        Self::importTraites();
        Self::importJobFilters();
        Self::importSearchLogs();
    }

    private static function importUsersData()
    {
        $password = \Hash::make('60703310');
        $date = date('Y-m-d G:i:s');
        $data = array(
            array(
                'first_name' => 'Admin',
                'last_name' => 'User',
                'username' => 'admin',
                'email' => 'admin@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'admin',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Liam',
                'last_name' => 'Logan',
                'username' => 'liam',
                'email' => 'liam@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'William',
                'last_name' => 'Amith',
                'username' => 'william',
                'email' => 'william@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Oliver',
                'last_name' => 'Wood',
                'username' => 'oliver',
                'email' => 'oliver@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Brad',
                'last_name' => 'Pitt',
                'username' => 'brad',
                'email' => 'brad@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Neil',
                'last_name' => 'Armstrong',
                'username' => 'neil',
                'email' => 'neil@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Anthony',
                'last_name' => 'Hopkins',
                'username' => 'anthony',
                'email' => 'anthony@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Fredrick',
                'last_name' => 'John',
                'username' => 'john',
                'email' => 'john@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Virat',
                'last_name' => 'Anand',
                'username' => 'virat',
                'email' => 'anand@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Ali',
                'last_name' => 'Moeen',
                'username' => 'ali',
                'email' => 'ali.moeen@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Victoria',
                'last_name' => 'Joseph',
                'username' => 'team',
                'email' => 'victoria@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
            array(
                'first_name' => 'Mahima',
                'last_name' => 'Khan',
                'username' => 'khan',
                'email' => 'khan@cf.com',
                'image' => '',
                'phone' => '',
                'password' => $password,
                'status' => 1,
                'user_type' => 'team',
                'created_at' => $date,
            ),
        );
        foreach ($data as $d) {
        	$result = DB::table('users')->where('username', $d['username'])->orWhere('email', $d['email'])->first();
            if (!$result) {
                DB::table('users')->insert($d);
            }
        }
    }

    private static function importEmployersData()
    {
        $short_description = '
            Mauris sapien risus, pharetra sit amet libero ac, consectetur imperdiet arcu. Fusce lectus nunc, pulvinar ut orci eu, dictum laoreet tortor. Ut nibh est, aliquet eleifend interdum eget, vestibulum viverra massa. Aenean eu cursus eros. Nunc ultrices, metus id congue suscipit, lorem est tincidunt est, sed aliquam velit enim interdum ex. Ut ut accumsan purus, nec aliquet mauris. Nullam sollicitudin purus dolor, nec aliquet velit cursus ut. Praesent est lacus, porttitor vel blandit nec, gravida non metus. Suspendisse faucibus purus maximus dapibus commodo. Nulla aliquet ultrices eleifend. Nullam id sodales mi. Nunc euismod dui sodales nibh sollicitudin auctor. Fusce ultricies tempor ligula interdum malesuada
        ';
        $password = \Hash::make('60703310');
        $data = array(
            array(
                'first_name' => 'Phillip',
                'last_name' => 'Morris',
                'company' => 'Flame Media',
                'slug' => 'flame-media',
                'employername' => 'flame-media',
                'email' => 'flamemedia@cf.com',
                'logo' => config('constants.upload_dirs.employers').'flame-media.png',
                'phone1' => '1234567890',
                'password' => $password,
                'short_description' => $short_description,
                'status' => 1,
                'type' => 'main',
                'country' => 'Australia',
                'city' => 'Sydney',
                'url' => 'http://www.example.com',
                'no_of_employees' => '10-50',
                'industry' => 'Information Technology',
                'founded_in' => '2005',
                'twitter_link' => 'https://www.twitter.com',
                'facebook_link' => 'https://www.facebook.com',
                'instagram_link' => 'https://www.instagram.com',
                'google_link' => 'https://www.google.com',
                'linkedin_link' => 'https://www.linkedin.com',
                'youtube_link' => 'https://www.youtube.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Adam',
                'last_name' => 'Smith',
                'company' => 'Informatics Corportation',
                'slug' => 'informatics-corporation',
                'employername' => 'informatics-corporation',
                'email' => 'informatics-corporation@cf.com',
                'logo' => config('constants.upload_dirs.employers').'informatics-corporation.png',
                'phone1' => '1234567890',
                'password' => $password,
                'short_description' => $short_description,
                'status' => 1,
                'type' => 'main',
                'country' => 'Russia',
                'city' => 'Moscow',
                'url' => 'http://www.example.com',
                'no_of_employees' => '10-50',
                'industry' => 'Data Science',
                'founded_in' => '2005',
                'twitter_link' => 'https://www.twitter.com',
                'facebook_link' => 'https://www.facebook.com',
                'instagram_link' => 'https://www.instagram.com',
                'google_link' => 'https://www.google.com',
                'linkedin_link' => 'https://www.linkedin.com',
                'youtube_link' => 'https://www.youtube.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Jubin',
                'last_name' => 'Ahuja',
                'company' => 'Simplex Cube',
                'slug' => 'simplex-cube',
                'employername' => 'simplex-cube',
                'email' => 'simplex-cube@cf.com',
                'logo' => config('constants.upload_dirs.employers').'simplex-cube.png',
                'phone1' => '1234567890',
                'password' => $password,
                'short_description' => $short_description,
                'status' => 1,
                'type' => 'main',
                'country' => 'Norway',
                'city' => 'Oslo',
                'url' => 'http://www.example.com',
                'no_of_employees' => '10-50',
                'industry' => 'Media',
                'founded_in' => '2005',
                'twitter_link' => 'https://www.twitter.com',
                'facebook_link' => 'https://www.facebook.com',
                'instagram_link' => 'https://www.instagram.com',
                'google_link' => 'https://www.google.com',
                'linkedin_link' => 'https://www.linkedin.com',
                'youtube_link' => 'https://www.youtube.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'James',
                'last_name' => 'Brown',
                'company' => 'Analytica Square',
                'slug' => 'analytica-square',
                'employername' => 'analytica-square',
                'email' => 'analytica-square@cf.com',
                'logo' => config('constants.upload_dirs.employers').'analytica-square.png',
                'phone1' => '1234567890',
                'password' => $password,
                'short_description' => $short_description,
                'status' => 1,
                'type' => 'main',
                'country' => 'Indonesia',
                'city' => 'Jakarta',
                'url' => 'http://www.example.com',
                'no_of_employees' => '10-50',
                'industry' => 'Textiles',
                'founded_in' => '2005',
                'twitter_link' => 'https://www.twitter.com',
                'facebook_link' => 'https://www.facebook.com',
                'instagram_link' => 'https://www.instagram.com',
                'google_link' => 'https://www.google.com',
                'linkedin_link' => 'https://www.linkedin.com',
                'youtube_link' => 'https://www.youtube.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'John',
                'last_name' => 'Garner',
                'company' => 'Rocket Igniter',
                'slug' => 'rocket-igniter',
                'employername' => 'rocket-igniter',
                'email' => 'rocket-igniter@cf.com',
                'logo' => config('constants.upload_dirs.employers').'rocket-igniter.png',
                'phone1' => '1234567890',
                'password' => $password,
                'short_description' => $short_description,
                'status' => 1,
                'type' => 'main',
                'country' => 'India',
                'city' => 'New Delhi',
                'url' => 'http://www.example.com',
                'no_of_employees' => '10-50',
                'industry' => 'Information Technology',
                'founded_in' => '2005',
                'twitter_link' => 'https://www.twitter.com',
                'facebook_link' => 'https://www.facebook.com',
                'instagram_link' => 'https://www.instagram.com',
                'google_link' => 'https://www.google.com',
                'linkedin_link' => 'https://www.linkedin.com',
                'youtube_link' => 'https://www.youtube.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Jaden',
                'last_name' => 'Smith',
                'company' => 'Royal Neon',
                'slug' => 'royal-neon',
                'employername' => 'royal-neon',
                'email' => 'royal-neon@cf.com',
                'logo' => config('constants.upload_dirs.employers').'royal-neon.png',
                'phone1' => '1234567890',
                'password' => $password,
                'short_description' => $short_description,
                'status' => 1,
                'type' => 'main',
                'country' => 'USA',
                'city' => 'New York',
                'url' => 'http://www.example.com',
                'no_of_employees' => '10-50',
                'industry' => 'Travel & Tours',
                'founded_in' => '2005',
                'twitter_link' => 'https://www.twitter.com',
                'facebook_link' => 'https://www.facebook.com',
                'instagram_link' => 'https://www.instagram.com',
                'google_link' => 'https://www.google.com',
                'linkedin_link' => 'https://www.linkedin.com',
                'youtube_link' => 'https://www.youtube.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Jim',
                'last_name' => 'Cooper',
                'company' => 'Hadron Box',
                'slug' => 'hadron-box',
                'employername' => 'hadron-box',
                'email' => 'hadron-box@cf.com',
                'logo' => config('constants.upload_dirs.employers').'hadron-box.png',
                'phone1' => '1234567890',
                'password' => $password,
                'short_description' => $short_description,
                'status' => 1,
                'type' => 'main',
                'country' => 'Italy',
                'city' => 'Rome',
                'url' => 'http://www.example.com',
                'no_of_employees' => '10-50',
                'industry' => 'Information Technology',
                'founded_in' => '2005',
                'twitter_link' => 'https://www.twitter.com',
                'facebook_link' => 'https://www.facebook.com',
                'instagram_link' => 'https://www.instagram.com',
                'google_link' => 'https://www.google.com',
                'linkedin_link' => 'https://www.linkedin.com',
                'youtube_link' => 'https://www.youtube.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Sylivia',
                'last_name' => 'Gold',
                'company' => 'Fashion Archive',
                'slug' => 'fashion-archive',
                'employername' => 'fashion-archive',
                'email' => 'fashion-archive@cf.com',
                'logo' => config('constants.upload_dirs.employers').'fashion-archive.png',
                'phone1' => '1234567890',
                'password' => $password,
                'short_description' => $short_description,
                'status' => 1,
                'type' => 'main',
                'country' => 'France',
                'city' => 'Paris',
                'url' => 'http://www.example.com',
                'no_of_employees' => '10-50',
                'industry' => 'Fashion Design',
                'founded_in' => '2005',
                'twitter_link' => 'https://www.twitter.com',
                'facebook_link' => 'https://www.facebook.com',
                'instagram_link' => 'https://www.instagram.com',
                'google_link' => 'https://www.google.com',
                'linkedin_link' => 'https://www.linkedin.com',
                'youtube_link' => 'https://www.youtube.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
        );
        foreach ($data as $d) {
            $result = DB::table('employers')->where('slug', $d['slug'])->first();
            if (!$result) {
                DB::table('employers')->insert($d);
                $employer_id = DB::getPdo()->lastInsertId();
                \App\Models\Admin\Employer::importEmployerSettings($employer_id);
            }
        }
    }

    private static function importCandidatesData()
    {
        $bio = '
            Mauris sapien risus, pharetra sit amet libero ac, consectetur imperdiet arcu. Fusce lectus nunc, pulvinar ut orci eu, dictum laoreet tortor. Ut nibh est, aliquet eleifend interdum eget, vestibulum viverra massa. Aenean eu cursus eros. Nunc ultrices, metus id congue suscipit, lorem est tincidunt est, sed aliquam velit enim interdum ex. Ut ut accumsan purus, nec aliquet mauris. Nullam sollicitudin purus dolor, nec aliquet velit cursus ut. Praesent est lacus, porttitor vel blandit nec, gravida non metus. Suspendisse faucibus purus maximus dapibus commodo. Nulla aliquet ultrices eleifend. Nullam id sodales mi. Nunc euismod dui sodales nibh sollicitudin auctor. Fusce ultricies tempor ligula interdum malesuada
        ';        
        $password = \Hash::make('60703310');
        $data = array(
            array(
                'first_name' => 'Josh',
                'last_name' => 'Kent',
                'slug' => 'josh-kent',
                'email' => 'josh@cf.com',
                'image' => config('constants.upload_dirs.candidates').'8.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'Australia',                
                'city' => 'Sydney',
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Candidate',
                'last_name' => 'User',
                'slug' => 'candidate-user',
                'email' => 'candidate@cf.com',
                'image' => config('constants.upload_dirs.candidates').'2.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'Russia',                
                'city' => 'Moscow',                
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'William',
                'last_name' => 'Shakespare',
                'slug' => 'william-shakespare',
                'email' => 'william@cf.com',
                'image' => config('constants.upload_dirs.candidates').'3.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'Norway',                
                'city' => 'Oslo',                
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Kristen',
                'last_name' => 'Wood',
                'slug' => 'kristen-wood',
                'email' => 'oliver@cf.com',
                'image' => config('constants.upload_dirs.candidates').'4.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'Indonesia',                
                'city' => 'Jakarta',                
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Brad',
                'last_name' => 'Pitt',
                'slug' => 'brad-pitt',
                'email' => 'brad@cf.com',
                'image' => config('constants.upload_dirs.candidates').'5.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'India',                
                'city' => 'New Delhi',
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Neil',
                'last_name' => 'Armstrong',
                'slug' => 'neil-armstrong',
                'email' => 'neil@cf.com',
                'image' => config('constants.upload_dirs.candidates').'6.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'USA',                
                'city' => 'New York',
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Anthony',
                'last_name' => 'Hopkins',
                'slug' => 'anthony-hopkins',
                'email' => 'anthony@cf.com',
                'image' => config('constants.upload_dirs.candidates').'7.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'Italy',                
                'city' => 'Rome',                
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Fredrick',
                'last_name' => 'John',
                'slug' => 'fredrick-john',
                'email' => 'john@cf.com',
                'image' => config('constants.upload_dirs.candidates').'8.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'France',                
                'city' => 'Paris',                
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Virat',
                'last_name' => 'Anand',
                'slug' => 'virat-anand',
                'email' => 'anand@cf.com',
                'image' => config('constants.upload_dirs.candidates').'9.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'USA',                
                'city' => 'New York',                
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Ali',
                'last_name' => 'Moeen',
                'slug' => 'ali-moeen',
                'email' => 'ali.moeen@cf.com',
                'image' => config('constants.upload_dirs.candidates').'3.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'Australia',                
                'city' => 'Sydney',                
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Victoria',
                'last_name' => 'Joseph',
                'slug' => 'victoria-joseph',
                'email' => 'victoria@cf.com',
                'image' => config('constants.upload_dirs.candidates').'5.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'Indonesia',                
                'city' => 'Jakarta',                                
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'first_name' => 'Mahima',
                'last_name' => 'Khan',
                'slug' => 'mahima-khan',
                'email' => 'khan@cf.com',
                'image' => config('constants.upload_dirs.candidates').'7.png',
                'phone1' => '',
                'password' => $password,
                'country' => 'India',                
                'city' => 'New Delhi',                
                'status' => 1,
                'bio' => $bio,
                'twitter_link' => 'https://www.example.com',
                'facebook_link' => 'https://www.example.com',
                'instagram_link' => 'https://www.example.com',
                'google_link' => 'https://www.example.com',
                'linkedin_link' => 'https://www.example.com',
                'youtube_link' => 'https://www.example.com',
                'created_at' => date('Y-m-d G:i:s'),
            ),
        );
        foreach ($data as $d) {
            $result = DB::table('candidates')->where('email', $d['email'])->first();
            if (!$result) {
                DB::table('candidates')->insert($d);
            }
        }        
    }

    private static function importAdminRolesData()
    {
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));
        $data = array(
            array('title' => 'Super Admin',),
            array('title' => 'News Manager',),
            array('title' => 'Site Controller',),
        );
        foreach ($data as $d) {
            $condition = array('type' => 'admin', 'employer_id' => 0, 'title' => $d['title']);
            $result = DB::table('roles')->where($condition)->first();
            if (!$result) {
                DB::table('roles')->insert(array_merge($dates, $condition));
            }
        }
    }

    private static function importPackages()
    {
        $data = array(
            array(
                'title' => 'Silver',
                'currency' => '$',
                'currency_for_api' => 'usd',
                'monthly_price' => '25',
                'yearly_price' => '250',
                'active_jobs' => '10',
                'active_users' => '3',
                'active_custom_filters' => '5',
                'active_quizes' => '10',
                'active_interviews' => '10',
                'active_traites' => '10',
                'branding' => '1',
                'role_permissions' => '0',
                'custom_emails' => '0',
                'is_free' => '0',
                'is_top_sale' => '0',
                'image' => 'general/silver.png',
                'status' => '1',
                'created_at' => date('Y-m-d G:i:s'),
                'updated_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'title' => 'Bronze',
                'currency' => '$',
                'currency_for_api' => 'usd',
                'monthly_price' => '50',
                'yearly_price' => '500',
                'active_jobs' => '100',
                'active_users' => '10',
                'active_custom_filters' => '10',
                'active_quizes' => '25',
                'active_interviews' => '25',
                'active_traites' => '25',
                'branding' => '1',
                'role_permissions' => '1',
                'custom_emails' => '0',
                'is_free' => '0',
                'is_top_sale' => '1',
                'image' => 'general/bronze.png',
                'status' => '1',
                'created_at' => date('Y-m-d G:i:s'),
                'updated_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'title' => 'Gold',
                'currency' => '$',
                'currency_for_api' => 'usd',
                'monthly_price' => '100',
                'yearly_price' => '1000',
                'active_jobs' => '0',
                'active_users' => '0',
                'active_custom_filters' => '25',
                'active_quizes' => '100',
                'active_interviews' => '100',
                'active_traites' => '100',
                'branding' => '1',
                'role_permissions' => '1',
                'custom_emails' => '1',
                'is_free' => '0',
                'is_top_sale' => '0',
                'image' => 'general/gold.png',
                'status' => '1',
                'created_at' => date('Y-m-d G:i:s'),
                'updated_at' => date('Y-m-d G:i:s'),
            ),
            array(
                'title' => 'Free',
                'currency' => '$',
                'currency_for_api' => 'usd',
                'monthly_price' => '0',
                'yearly_price' => '0',
                'active_jobs' => '5',
                'active_users' => '1',
                'active_custom_filters' => '1',
                'active_quizes' => '1',
                'active_interviews' => '1',
                'active_traites' => '1',
                'branding' => '0',
                'role_permissions' => '0',
                'custom_emails' => '0',
                'is_free' => '1',
                'is_top_sale' => '0',
                'image' => '',
                'status' => '1',
                'created_at' => date('Y-m-d G:i:s'),
                'updated_at' => date('Y-m-d G:i:s'),
            ),
        );
        foreach ($data as $d) {
            $result = DB::table('packages')->where(array('title' => $d['title']))->first();
            if (!$result) {
                DB::table('packages')->insert($d);
            }
        }
    }

    private static function importFaqsCategories()
    {
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));
        $data = array(
            array('title' => 'Pricing', 'status' => '1',),
            array('title' => 'General', 'status' => '1',),
        );
        foreach ($data as $d) {
            $result = DB::table('faqs_categories')->where(array('title' => $d['title']))->first();
            if (!$result) {
                DB::table('faqs_categories')->insert(array_merge($d, $dates));
            }
        }
    }

    private static function importFaqs()
    {
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));

        $data = array(
            array(
                'category_id' => 1,
                'question' => 'What happens when the memberships expire?',
                'answer' => 'Your login and all the information will remain intact but you can not create any more content, nor if the limit for any item is zero then it will not appear.',
                'status' => '1',
            ),
            array(
                'category_id' => 1,
                'question' => 'What is the difference between Portal and Multitenancy?',
                'answer' => 'These are two approaches, you can use the script. With the portal approach, all the jobs appears on the main site, candidates will also apply on the same site. While with the multitenancy approach, every employer will have their separate site/interface where only their jobs and other content will appear. Employers can also select to have their own branding information if allowed in their membership.',
                'status' => '1',
            ),
            array(
                'category_id' => 1,
                'question' => 'Do I get any discount on selecting the annual plan?',
                'answer' => 'Just like any other SaaS system, our annual plans price is less than the actual accumulated amount for all months.',
                'status' => '1',
            ),
        );
        foreach ($data as $d) {
            $result = DB::table('faqs')->where(array('question' => $d['question']))->first();
            if (!$result) {
                DB::table('faqs')->insert(array_merge($d, $dates));
            }
        }
    }

    private static function importNewsCategories()
    {
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));
        $data = array(
            array('title' => 'Recruitment', 'status' => '1',),
            array('title' => 'Travel & Tours', 'status' => '1',),
            array('title' => 'Food', 'status' => '1',),
            array('title' => 'Career Growth', 'status' => '1',),
        );
        foreach ($data as $d) {
            $result = DB::table('news_categories')->where(array('title' => $d['title']))->first();
            if (!$result) {
                DB::table('news_categories')->insert(array_merge($d, $dates));
            }
        }
    }

    private static function importNews()
    {
        $summary1 = 'A common test of processing speed is the “digit symbol substitution test”, in which a range of symbols are paired with a set of numbers in a code.';
        $summary2 = 'Even if disruption of higher education wasn’t their goal, these companies have been slowly re-inventing the optimal environment for technical learning.';
        $summary3 = 'Ultimately, people want to do business with people. With today’s plethora of digital communication and social media platforms, it’s easier and more impactful than ever before for an individual leader.';
        $summary = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget pharetra ex. Phasellus imperdiet sapien nec massa faucibus, in tempor dui dapibus.';
        $description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget pharetra ex. Phasellus imperdiet sapien nec massa faucibus, in tempor dui dapibus. Aliquam id velit augue. Nullam laoreet, sapien sit amet mollis congue, lorem magna ultrices nulla, in vehicula sapien nibh vel mi. Quisque ut ante felis. Praesent at consectetur metus, ut rutrum nunc. Nam a ante in ex tristique aliquet. Praesent eu dapibus justo, sit amet vulputate nisi. Ut ultricies, risus in tristique fringilla, urna quam dictum nulla, vitae consequat augue justo eu turpis. Morbi ac scelerisque ante. Nunc sit amet tortor ac velit molestie rutrum.';
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));

        $data = array(
            array(
                'category_id' => 1,
                'title' => 'Senior Employees Responsibilities',
                'slug' => 'senior-employees-responsibilities',
                'summary' => $summary1,
                'description' => $description,
                'image' => 'news/01.jpg',
                'keywords' => 'Senior Employees Responsibilities',
                'status' => '1',
            ),
            array(
                'category_id' => 1,
                'title' => 'Recruitment Processes',
                'slug' => 'recruitment-processes',
                'summary' => $summary2,
                'description' => $description,
                'image' => 'news/02.jpg',
                'keywords' => 'Recruitment Processes',
                'status' => '1',
            ),
            array(
                'category_id' => 1,
                'title' => 'Identity Building',
                'slug' => 'identity-building',
                'summary' => $summary3,
                'description' => $description,
                'image' => 'news/03.jpg',
                'keywords' => 'Identity Building',
                'status' => '1',
            ),
            array(
                'category_id' => 1,
                'title' => 'Qualification Criterias',
                'slug' => 'qualification-criterias',
                'summary' => $summary1,
                'description' => $description,
                'image' => 'news/04.jpg',
                'keywords' => 'Qualification Criterias',
                'status' => '1',
            ),
            array(
                'category_id' => 1,
                'title' => 'Be Ahead',
                'slug' => 'be-ahead',
                'summary' => $summary2,
                'description' => $description,
                'image' => 'news/05.jpg',
                'keywords' => 'Be Ahead',
                'status' => '1',
            ),
        );
        foreach ($data as $d) {
            $result = DB::table('news')->where(array('slug' => $d['slug']))->first();
            if (!$result) {
                DB::table('news')->insert(array_merge($d, $dates));
            }
        }
    }

    private static function importTestimonials()
    {
        $description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget pharetra ex. Phasellus imperdiet sapien nec massa faucibus, in tempor dui dapibus.';
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));

        $data = array(
            array('employer_id' => 1, 'description' => $description, 'status' => '1', 'rating' => 4),
            array('employer_id' => 2, 'description' => $description, 'status' => '1', 'rating' => 5),
            array('employer_id' => 3, 'description' => $description, 'status' => '1', 'rating' => 4),
            array('employer_id' => 4, 'description' => $description, 'status' => '1', 'rating' => 5),
            array('employer_id' => 5, 'description' => $description, 'status' => '1', 'rating' => 4),
        );
        foreach ($data as $d) {
            $result = DB::table('testimonials')->where(array('employer_id' => $d['employer_id']))->first();
            if (!$result) {
                DB::table('testimonials')->insert(array_merge($d, $dates));
            }
        }
    }    

    private static function importResumeData()
    {
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));
        $objective = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.';
        $des = $objective;
        $data = array(
            array(
                'candidate_id' => '1',
                'title' => 'My Resume 1',
                'designation' => 'Marketing Manager',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Intern', 'company' => 'ABC Company', 'from' => '2015-01-01', 'to' => '2018-12-30', 'description' => $des),
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2019-01-01', 'to' => '2019-03-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2019-04-01', 'to' => '2020-02-15', 'description' => $des, 'is_current' => 1),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Masters','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => $des),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'French', 'proficiency' => '1'),
                    array('title' => 'German', 'proficiency' => '3'),
                    array('title' => 'German2', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'PHP', 'proficiency' => '5'),
                    array('title' => 'MySql', 'proficiency' => '1'),
                    array('title' => 'HTML', 'proficiency' => '3'),
                    array('title' => 'CSS', 'proficiency' => '3'),
                ),
            ),
            array(
                'candidate_id' => '2',
                'title' => 'My Resume 2',
                'designation' => 'Marketing Executive',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Intern', 'company' => 'ABC Company', 'from' => '2015-01-01', 'to' => '2016-12-30', 'description' => $des),
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2016-01-01', 'to' => '2017-12-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2018-01-01', 'to' => '2019-12-15', 'description' => $des),
                    array('title' => 'Sr. Manager', 'company' => 'XYZ Corp 2.', 'from' => '2019-04-01', 'to' => '2020-10-15', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Masters','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                    array('title' => 'P.H.D.','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'French', 'proficiency' => '1'),
                ),
                'skills' => array(
                    array('title' => 'CANVA', 'proficiency' => '5'),
                    array('title' => 'Presentation', 'proficiency' => '1'),
                    array('title' => 'WIGS', 'proficiency' => '3'),
                    array('title' => 'CSS', 'proficiency' => '3'),
                ),
            ),
            array(
                'candidate_id' => '3',
                'title' => 'My Resume 3',
                'designation' => 'Public Relations Manager',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Intern', 'company' => 'ABC Company', 'from' => '2015-01-01', 'to' => '2018-12-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2019-04-01', 'to' => '2020-02-15', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'English 2', 'proficiency' => '5'),
                    array('title' => 'French', 'proficiency' => '1'),
                    array('title' => 'French 2', 'proficiency' => '1'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'Quick Books', 'proficiency' => '5'),
                    array('title' => 'Peach Tree', 'proficiency' => '1'),
                    array('title' => 'Excel', 'proficiency' => '3'),
                    array('title' => 'Kanban', 'proficiency' => '3'),
                ),
            ),
            array(
                'candidate_id' => '4',
                'title' => 'My Resume 4',
                'designation' => 'Business Developer',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Intern', 'company' => 'ABC Company', 'from' => '2015-01-01', 'to' => '2018-12-30', 'description' => $des),
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2019-01-01', 'to' => '2019-03-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2019-04-01', 'to' => '2020-02-15', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Masters','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                    array('title' => 'P.H.D.','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),                
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'PHP', 'proficiency' => '5'),
                    array('title' => 'MySql', 'proficiency' => '1'),
                    array('title' => 'HTML', 'proficiency' => '3'),
                    array('title' => 'CSS', 'proficiency' => '3'),
                ),
            ),
            array(
                'candidate_id' => '5',
                'title' => 'My Resume 5',
                'designation' => 'Manager Market Operations',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Intern', 'company' => 'ABC Company', 'from' => '2017-06-01', 'to' => '2018-12-30', 'description' => $des),
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2019-02-01', 'to' => '2019-08-30', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Masters','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                    array('title' => 'Certification','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'French', 'proficiency' => '1'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'CCNA', 'proficiency' => '5'),
                    array('title' => 'Protocols', 'proficiency' => '1'),
                    array('title' => 'Databases', 'proficiency' => '3'),
                ),                
            ),
            array(
                'candidate_id' => '7',
                'title' => 'My Resume 7',
                'designation' => 'Business Developer',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Intern', 'company' => 'ABC Company', 'from' => '2016-01-01', 'to' => '2018-12-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2019-05-01', 'to' => '2019-10-30', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'French', 'proficiency' => '1'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'Dealerships', 'proficiency' => '1'),
                    array('title' => 'Meetings', 'proficiency' => '5'),
                    array('title' => 'Campaigns', 'proficiency' => '3'),
                ),
            ),
            array(
                'candidate_id' => '8',
                'title' => 'My Resume 8',
                'designation' => 'Marketeer',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2011-01-01', 'to' => '2016-03-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2017-04-01', 'to' => '2020-02-15', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'French', 'proficiency' => '1'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'Kanban', 'proficiency' => '1'),
                    array('title' => 'Canva', 'proficiency' => '5'),
                    array('title' => 'Campaigns', 'proficiency' => '3'),
                ),
            ),
            array(
                'candidate_id' => '9',
                'title' => 'My Resume 9',
                'designation' => 'Business Developer',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Intern', 'company' => 'ABC Company', 'from' => '2015-01-01', 'to' => '2018-12-30', 'description' => $des),
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2019-01-01', 'to' => '2019-03-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2019-04-01', 'to' => '2020-02-15', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Masters','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'French', 'proficiency' => '1'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'Charts', 'proficiency' => '1'),
                    array('title' => 'Distribution', 'proficiency' => '5'),
                    array('title' => 'Campaigns', 'proficiency' => '3'),
                    array('title' => 'Analytics', 'proficiency' => '3'),
                ),
            ),
            array(
                'candidate_id' => '10',
                'title' => 'My Resume 10',
                'designation' => 'Marketing Manager',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Intern', 'company' => 'ABC Company', 'from' => '2011-01-01', 'to' => '2013-12-30', 'description' => $des),
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2014-01-01', 'to' => '2016-03-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2017-04-01', 'to' => '2018-02-15', 'description' => $des),
                    array('title' => 'Sr. Manager', 'company' => 'XYZ Corp.', 'from' => '2019-04-01', 'to' => '2020-02-15', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Masters','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                    array('title' => 'Doctorate','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'French', 'proficiency' => '1'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'Advertising', 'proficiency' => '1'),
                    array('title' => 'Social Media', 'proficiency' => '5'),
                    array('title' => 'Campaigns', 'proficiency' => '3'),
                ),                
            ),
            array(
                'candidate_id' => '11',
                'title' => 'My Resume 11',
                'designation' => 'Area Sales Manager',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Intern', 'company' => 'ABC Company', 'from' => '2015-01-01', 'to' => '2018-12-30', 'description' => $des),
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2019-01-01', 'to' => '2019-03-30', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Masters','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '5'),
                    array('title' => 'French', 'proficiency' => '1'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'Dealerships', 'proficiency' => '1'),
                    array('title' => 'Meetings', 'proficiency' => '5'),
                    array('title' => 'Campaigns', 'proficiency' => '3'),
                ),                
            ),
            array(
                'candidate_id' => '12',
                'title' => 'My Resume 12',
                'designation' => 'Marketing Supervisor',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2019-01-01', 'to' => '2019-03-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2019-04-01', 'to' => '2020-02-15', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Masters','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                    array('title' => 'M Phil.','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '1'),
                    array('title' => 'French', 'proficiency' => '5'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'Advertising', 'proficiency' => '1'),
                    array('title' => 'Social Media', 'proficiency' => '5'),
                    array('title' => 'Campaigns', 'proficiency' => '3'),
                ),                                
            ),
            array(
                'candidate_id' => '6',
                'title' => 'My Resume 14',
                'designation' => 'System Software Architect',
                'objective' => $objective,
                'status' => 1,
                'experiences' => array(
                    array('title' => 'Executive', 'company' => 'EFG Inc.', 'from' => '2019-01-01', 'to' => '2019-03-30', 'description' => $des),
                    array('title' => 'Manager', 'company' => 'XYZ Corp.', 'from' => '2019-04-01', 'to' => '2020-02-15', 'description' => $des),
                ),
                'qualifications' => array(
                    array('title' => 'Graduation','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2011-01-01','to'=>'2015-12-30'),
                    array('title' => 'Masters','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                    array('title' => 'M Phil.','institution' => 'ABC College','marks' => '3.5','out_of' => '4.0','from' => '2016-01-01','to'=>'2018-12-30'),
                ),
                'achievements' => array(
                    array('title' => 'Certificate','link' => 'http://www.example.com','type' => 'certificate','date' => '2018-06-15','description' => 'Dummy Description'),
                ),
                'references' => array(
                    array('title' => 'Mr. Person','Relation' => 'Immediate Boss','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person@examplecf.com'),
                    array('title' => 'Mr. Person 2','Relation' => 'Colleague','company' => 'ABC Corp.','phone' => '1234567890','email' => 'mr.person.2@examplecf.com'),
                ),
                'languages' => array(
                    array('title' => 'English', 'proficiency' => '1'),
                    array('title' => 'French', 'proficiency' => '5'),
                    array('title' => 'German', 'proficiency' => '3'),
                ),
                'skills' => array(
                    array('title' => 'ERDS', 'proficiency' => '5'),
                    array('title' => 'Protocols', 'proficiency' => '1'),
                    array('title' => 'Use Case Diagrams', 'proficiency' => '3'),
                ),
            ),
        );

        foreach ($data as $d) {
            $condition = array('title' => $d['title'], 'candidate_id' => $d['candidate_id']);
            $result = DB::table('resumes')->where($condition)->first();
            if (!$result) {

                //Separting dependents
                $experiences = $d['experiences'];
                $qualifications = $d['qualifications'];
                $achievements = $d['achievements'];
                $references = $d['references'];
                $languages = $d['languages'];
                $skills = $d['skills'];
                unset($d['experiences'],$d['qualifications'],$d['achievements'],$d['references'],$d['skills']);

                $d['type'] = 'detailed';
                $d['experience'] = getExprienceInMonths($experiences);
                $d['experiences'] = count($experiences);
                $d['qualifications'] = count($qualifications);
                $d['achievements'] = count($achievements);
                $d['references'] = count($references);
                $d['languages'] = count($languages);
                $d['skills'] = count($skills);
                DB::table('resumes')->insert(array_merge($d, $dates));
                $resume_id = DB::getPdo()->lastInsertId();

                //Inserting experiences
                foreach ($experiences as $e) {
                    DB::table('resume_experiences')->insert(array_merge($e, $dates, array('resume_id' => $resume_id)));
                }

                //Inserting qualifications
                foreach ($qualifications as $q) {
                    DB::table('resume_qualifications')->insert(array_merge($q, $dates, array('resume_id' => $resume_id)));
                }

                //Inserting achievements
                foreach ($achievements as $a) {
                    DB::table('resume_achievements')->insert(array_merge($a, $dates, array('resume_id' => $resume_id)));
                }

                //Inserting references
                foreach ($references as $r) {
                    DB::table('resume_references')->insert(array_merge($r, $dates, array('resume_id' => $resume_id)));
                }

                //Inserting languages
                foreach ($languages as $l) {
                    DB::table('resume_languages')->insert(array_merge($l, $dates, array('resume_id' => $resume_id)));
                }

                //Inserting skills
                foreach ($skills as $s) {
                    DB::table('resume_skills')->insert(array_merge($s, $dates, array('resume_id' => $resume_id)));
                }
            }
        }
    }

    private static function importDepartments()
    {
        $dataDir = storage_path('/app/'.config('constants.upload_dirs.main').'/dummy-data/');

        //Creating employer directory if not exists
        $employerDir = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.employers').'/admin/');

        $emti = '';
        $date = date('Y-m-d G:i:s');
        $additional = array('created_at' => $date, 'updated_at' => $date, 'employer_id' => 0);
        $jd = getTextFromFile('job.txt');
        $did = array();
        $bcid = '';

        $data = array(
            array('title' => 'Accounting'.$emti, 'image' => 'accounting.png', 'status' => 1,),
            array('title' => 'Administration'.$emti, 'image' => 'administration.png', 'status' => 1,),
            array('title' => 'Information Technology'.$emti, 'image' => 'information-tech.png', 'status' => 1,),
            array('title' => 'Human Resource'.$emti, 'image' => 'human-resource.png', 'status' => 1,),
            array('title' => 'Finance'.$emti, 'image' => 'finance.png', 'status' => 1,),
            array('title' => 'Marketing'.$emti, 'image' => 'marketing.png', 'status' => 1,),
            array('title' => 'Advertising'.$emti, 'image' => 'advertising.png', 'status' => 1,),
            array('title' => 'Sales'.$emti, 'image' => 'sales.png', 'status' => 1,),
            array('title' => 'Business Development'.$emti, 'image' => 'business-dev.png', 'status' => 1,),
            array('title' => 'Customer Service'.$emti, 'image' => 'customer-service.png', 'status' => 1,),
            array('title' => 'Procurement'.$emti, 'image' => 'procurement.png', 'status' => 1,),
            array('title' => 'Maintenance'.$emti, 'image' => 'maintenance.png', 'status' => 1,),
        );
        foreach ($data as $d) {
            $condition = array('employer_id' => 0, 'title' => $d['title']);
            $result = DB::table('departments')->where($condition)->first();
            if (!$result) {
                $original_image = $d['image'];
                $d['image'] = config('constants.upload_dirs.employers').'admin/'.config('constants.upload_dirs.departments').$d['image'];
                DB::table('departments')->insert(array_merge($d, $additional));
                $did[] = DB::getPdo()->lastInsertId();

                //Copying image from uploads/dummy-data to newly created employer folder.
                createDirectoryIfNotExists($employerDir.config('constants.upload_dirs.departments').$original_image);
                $cr = copy($dataDir.$original_image, $employerDir.config('constants.upload_dirs.departments').'/'.$original_image);
            }
        }
    }

    private static function importTraites()
    {
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));
        $data = array(
            array('title' => 'Punctuality',),
            array('title' => 'Attention To Detail',),
            array('title' => 'Problem Solver',),
            array('title' => 'Team Player',),
            array('title' => 'Leadership',),
        );
        foreach ($data as $d) {
            $condition = array('employer_id' => 0, 'title' => $d['title']);
            $result = DB::table('traites')->where($condition)->first();
            if (!$result) {
                DB::table('traites')->insert(array_merge($dates, $condition));
            }
        }
    }

    private static function importJobFilters()
    {
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));
        $data = array(
            array(
                'employer_id' => 0,
                'title' => 'Location',
                'order' => 1,
                'type' => 'dropdown',
                'icon' => 'fa-regular fa-bookmark',
                'values' => array('New Delhi', 'New York', 'Moscow', 'Rome', 'Jakarta', 'Paris', 'Beijing', 'Oslo')
            ),
            array(
                'employer_id' => 0,
                'title' => 'Experience Level',
                'order' => 1,
                'type' => 'radio',
                'icon' => 'fa-solid fa-person-running',
                'values' => array('6 Months', '1 Year', '3 Years', '5 Years', 'More than 5 Years')
            ),
            array(
                'employer_id' => 0,
                'title' => 'Salary Level',
                'order' => 1,
                'type' => 'radio',
                'icon' => 'fa-solid fa-money-bill',
                'values' => array('$100-500', '$500-5000', '$More than 5000'),
            ),
            array(
                'employer_id' => 0,
                'title' => 'Job Type',
                'order' => 1,
                'type' => 'radio',
                'icon' => 'fa-regular fa-bookmark',
                'values' => array('On Site', 'Remote', 'Freelance', 'Full Time'),
            ),
        );
        foreach ($data as $d) {
            $values = $d['values'];
            unset($d['values']);
            $condition = array('employer_id' => 0, 'title' => $d['title']);
            $result = DB::table('job_filters')->where($condition)->first();
            if (!$result) {
                DB::table('job_filters')->insert(array_merge($dates, $d));
                $job_filter_id = DB::getPdo()->lastInsertId();
                foreach ($values as $value) {
                    $jfv['title'] = $value;
                    $jfv['job_filter_id'] = $job_filter_id;
                    $jfv['employer_id'] = 0;
                    DB::table('job_filter_values')->insert($jfv);
                }
            }
        }
    }

    private static function importSearchLogs()
    {
        $data = array(
            array('title' => 'Software Engineer'),
            array('title' => 'Architect'),
            array('title' => 'Manager sales'),
            array('title' => 'Project Coordinator'),
        );
        foreach ($data as $d) {
            $condition = array('title' => $d['title']);
            $result = DB::table('search_logs')->where($condition)->first();
            if (!$result) {
                DB::table('search_logs')->insert(array('title' => $d['title'], 'created_at' => date('Y-m-d G:i:s')));
            }
        }
    }    
}