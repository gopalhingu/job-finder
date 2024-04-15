<?php

	$CanCandCont = 'App\Http\Controllers\Candidate\CandidatesController';
	$CanJobsCont = 'App\Http\Controllers\Candidate\JobsController';
	$CanQuizesCont = 'App\Http\Controllers\Candidate\QuizesController';
	$CanResCont = 'App\Http\Controllers\Candidate\ResumesController';

	//Account area Candidate Resumes Routes (inner)
	Route::get('account', $CanResCont.'@listing')->name('can-acc-main'.$sd_route);
	Route::post('create-resume', $CanResCont.'@create')->name('can-acc-create-resume'.$sd_route);
	Route::get('account/resume/{resume_id?}', $CanResCont.'@detailView')->name('can-acc-resume-view'.$sd_route);
	Route::post('account/resume-save-general', $CanResCont.'@updateGeneral')->name('can-acc-res-save-gen'.$sd_route);
	Route::post('account/resume-save-experience', $CanResCont.'@updateExperience')->name('can-acc-res-save-exp'.$sd_route);
	Route::post('account/resume-save-qualification', $CanResCont.'@updateQualification')->name('can-acc-res-save-qua'.$sd_route);
	Route::post('account/resume-save-language', $CanResCont.'@updateLanguage')->name('can-acc-res-save-lan'.$sd_route);
	Route::post('account/resume-save-skill', $CanResCont.'@updateSkill')->name('can-acc-res-save-skill'.$sd_route);
	Route::post('account/resume-save-achievement', $CanResCont.'@updateAchievement')->name('can-acc-res-save-ach'.$sd_route);
	Route::post('account/resume-save-reference', $CanResCont.'@updateReference')->name('can-acc-res-save-ref'.$sd_route);
	Route::get('account/resume-add-section/{resume_id}/{type}', $CanResCont.'@addSection')->name('can-acc-res-acc-sec'.$sd_route);
	Route::get('account/resume-remove-section/{section_id}/{type}', $CanResCont.'@removeSection')->name('can-acc-res-remo-sec'.$sd_route);
	Route::post('account/resume-update-doc', $CanResCont.'@updateDocResume')->name('can-acc-res-update-doc'.$sd_route);

	//Candidate Routes (inner)
	Route::get('account/profile', $CanCandCont.'@updateProfileView')->name('can-acc-profile-view'.$sd_route);
	Route::post('profile-update', $CanCandCont.'@updateProfile')->name('can-acc-profile-update'.$sd_route);
	Route::get('account/password', $CanCandCont.'@updatePasswordView')->name('can-acc-pass-view'.$sd_route);
	Route::post('password-update', $CanCandCont.'@updatePassword')->name('can-acc-pass-update'.$sd_route);

	//Account ares job routes
	Route::get('account/job-applications', $CanJobsCont.'@jobApplicationsView')->name('can-acc-job-apps'.$sd_route);
	Route::get('account/job-favorites', $CanJobsCont.'@jobFavoritesView')->name('can-acc-job-favs'.$sd_route);
	Route::get('account/job-referred', $CanJobsCont.'@jobReferredView')->name('can-acc-job-referred'.$sd_route);

	//Account Area Quizes routes
	Route::get('account/quizes', $CanQuizesCont.'@listView')->name('can-acc-quizes'.$sd_route);
	Route::get('account/quiz/{quiz_id}', $CanQuizesCont.'@attemptView')->name('can-acc-quiz-single'.$sd_route);
	Route::post('account/quiz-attempt', $CanQuizesCont.'@attempt')->name('can-acc-quiz-attempt'.$sd_route);

