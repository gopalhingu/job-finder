<?php

	$CanPagesController = 'App\Http\Controllers\Candidate\PagesController';
	$CanCandCont = 'App\Http\Controllers\Candidate\CandidatesController';
	$CanJobsCont = 'App\Http\Controllers\Candidate\JobsController';
	$EssentialsController = 'App\Http\Controllers\EssentialsController';

	//Auth routes
	Route::get('/', $CanPagesController.'@index')->name('candidate-home'.$sd_route);
	Route::get('login', $CanCandCont.'@loginView')->name('candidate-login-view'.$sd_route);
	Route::post('post-login', $CanCandCont.'@login')->name('candidate-login-post'.$sd_route);
	Route::get('logout', $CanCandCont.'@logout')->name('candidate-logout'.$sd_route);
	Route::get('register', $CanCandCont.'@registerView')->name('candidate-register-view'.$sd_route);
	Route::post('post-register', $CanCandCont.'@register')->name('candidate-register-post'.$sd_route);
	Route::get('forgot-password', $CanCandCont.'@showForgotPassword')->name('candidate-forgot-view'.$sd_route);
	Route::post('send-password-link', $CanCandCont.'@sendPasswordLink')->name('candidate-send-link'.$sd_route);
	Route::get('reset-password/{token}', $CanCandCont.'@resetPassword')->name('candidate-reset-password-view'.$sd_route);
	Route::post('reset-password', $CanCandCont.'@updatePasswordByForgot')->name('candidate-reset-password-post'.$sd_route);
	Route::get('activate-account/{token}', $CanCandCont.'@activateAccount')->name('candidate-activate-account'.$sd_route);

	//Job routes
	Route::get('jobs', $CanJobsCont.'@listing')->name('candidate-jobs'.$sd_route);
	Route::get('job/{job_id}', $CanJobsCont.'@detail')->name('candidate-job'.$sd_route);
	Route::get('mark-favorite/{job_id}', $CanJobsCont.'@markFavorite')->name('candidate-mark-favourite'.$sd_route);
	Route::get('unmark-favorite/{job_id}', $CanJobsCont.'@unmarkFavorite')->name('candidate-unmark-favourite'.$sd_route);
	Route::get('refer-job-view', $CanJobsCont.'@referJobView')->name('candidate-refer-job-view'.$sd_route);
	Route::post('refer-job', $CanJobsCont.'@referJob')->name('candidate-refer-job'.$sd_route);
	Route::post('apply-job', $CanJobsCont.'@applyJob')->name('candidate-apply-job'.$sd_route);

	//Blog routes
	Route::get('blogs', $CanPagesController.'@blogListing')->name('candidate-blogs'.$sd_route);
	Route::get('blog/{blog_id}', $CanPagesController.'@blogDetail')->name('candidate-blog'.$sd_route);

	Route::get('set-front-language/{id?}', $EssentialsController.'@setCandidateLanguage')->name('set-candidate-language'.$sd_route);
	Route::get('set-color-theme/{color?}', $EssentialsController.'@setColorTheme')->name('set-color-theme'.$sd_route);
