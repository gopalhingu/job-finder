<?php

namespace App\Helpers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DbTables
{
    public static function run()
    {
        Self::createUsersTable();
        Self::createEmployersTable();
        Self::addColumnToEmployersTable();
        Self::addColumnToEmployersTable2();
        Self::createEmployerCandidatesTable();
        Self::createCandidatesTable();
        Self::addColumnToCandidatesTable();
        Self::createResumeTable();
        Self::addColumnToResumesTable();
        Self::createResumeExperienceTable();
        Self::createResumeLanguageTable();
        Self::createResumeQualificationTable();
        Self::createResumeAchievementsTable();
        Self::createResumeReferencesTable();
        Self::createResumeSkillsTable();
        Self::createRolesTable();
        Self::createMenusTable();
        Self::addColumnToMenusTable();
        Self::createPermissionsTable();
        Self::createRolePermissionsTable();
        Self::createUserRolesTable();
        Self::createEmployerRolesTable();
        Self::createPagesTable();
        Self::createTestimonialsTable();
        Self::addColumnToTestimonialsTable();
        Self::createSettingsTable();
        Self::createToDosTable();
        Self::createJobsTable();
        Self::addColumnToJobsTable();
        Self::addColumnAPIJobIDToJobsTable();
        Self::addColumnToJobsTableTwo();
        Self::createJobFiltersTable();
        Self::addColumnToJobFiltersTable();
        Self::createJobFilterValuesTable();
        Self::createJobFilterValueAssignmentsTable();
        Self::createJobsCustomFieldsTable();
        Self::createDepartmentsTable();
        Self::createTraitesTable();
        Self::createJobTraitesTable();
        Self::createJobTraiteAnswersTable();
        Self::createJobApplicationsTable();
        Self::createJobFavoritesTable();
        Self::createJobFollowTable();
        Self::createCandidateFavoritesTable();
        Self::createJobReferredTable();
        Self::createNewsCategoriesTable();
        Self::createNewsTable();
        Self::addColumnToNewsTable();
        Self::createFaqsCategoriesTable();
        Self::createFaqsTable();
        Self::createBlogCategoriesTable();
        Self::createBlogsTable();        
        Self::addColumnToBlogsTable();
        Self::createLanguagesTable();
        Self::addColumnToLanguagesTable();
        Self::createAppUpdateTable();
        Self::createQuestionCategoriesTable();
        Self::createQuestionsTable();
        Self::createQuestionAnswersTable();
        Self::createQuizCategoriesTable();
        Self::createQuizesTable();
        Self::createQuizQuestionsTable();
        Self::createQuizQuestionAnswersTable();
        Self::createJobQuizesTable();
        Self::createCandidateQuizesTable();
        Self::createInterviewCategoriesTable();
        Self::createInterviewsTable();
        Self::createInterviewQuestionsTable();
        Self::createCandidateInterviewsTable();
        Self::createPackagesTable();
        Self::addColumnToPackagesTable();
        Self::addColumnToPackagesTable2();
        Self::createMembershipsTable();
        Self::createCandidatePackagesTable();
        Self::createCandidateMembershipsTable();
        Self::addColumnToMembershipTable();
        Self::addColumnToMembershipTableTwo();
        Self::createMessagesTable();
        Self::createNotificationsTable();
        Self::createErrorLogsTable();
        Self::createSearchLogsTable();

        //Necessary Data
        Self::importAdminSettings();
        Self::importMenuItems();
        Self::importPages();
        Self::importAdminPermissions();        
        Self::importEmployerPermissions();        
        Self::importLanguagesData();
    }

    private static function createUsersTable()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('user_id')->unsigned();
                $table->string('first_name', 50)->nullable();
                $table->string('last_name', 50)->nullable();
                $table->string('username', 50)->unique();
                $table->string('email', 150)->unique();
                $table->string('image', 255)->nullable();
                $table->string('phone', 50)->nullable();
                $table->string('password', 150);
                $table->string('user_type', 20)->default('admin');
                $table->tinyInteger('status')->default('1');
                $table->string('token', 150)->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createEmployersTable()
    {
        if (!Schema::hasTable('employers')) {
            Schema::create('employers', function (Blueprint $table) {
                $table->bigIncrements('employer_id')->unsigned();
                $table->bigInteger('parent_id')->unsigned()->nullable();
                $table->string('company', 50)->nullable();
                $table->string('slug', 50)->nullable();
                $table->string('first_name', 50)->nullable();
                $table->string('last_name', 50)->nullable();
                $table->string('employername', 50)->unique();
                $table->string('email', 150)->unique();
                $table->string('password', 150);
                $table->string('image', 255)->nullable();
                $table->string('phone1', 50)->nullable();
                $table->string('phone2', 50)->nullable();
                $table->string('city', 150)->nullable();
                $table->string('state', 150)->nullable();
                $table->string('country', 150)->nullable();
                $table->string('address', 150)->nullable();
                $table->string('gender', 20)->nullable();
                $table->datetime('dob')->nullable();
                $table->text('short_description')->nullable();
                $table->string('type', 20)->default('main');
                $table->tinyInteger('status')->default('1');
                $table->string('token', 150)->nullable();
                $table->timestamps();
            });
        }
    }

    private static function addColumnToEmployersTable()
    {
        //added in version 1.3
        if (!Schema::hasColumn('employers', 'logo')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('logo', 255)->nullable()->after('image');
            });
        }
    }

    private static function addColumnToEmployersTable2()
    {
        //added in version 2.0
        if (!Schema::hasColumn('employers', 'url')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('url', 255)->nullable()->after('short_description');
            });
        }
        if (!Schema::hasColumn('employers', 'no_of_employees')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('no_of_employees', 10)->nullable()->after('url');
            });
        }
        if (!Schema::hasColumn('employers', 'industry')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('industry', 100)->nullable()->after('no_of_employees');
            });
        }
        if (!Schema::hasColumn('employers', 'founded_in')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('founded_in', 20)->nullable()->after('industry');
            });
        }
        if (!Schema::hasColumn('employers', 'twitter_link')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('twitter_link', 250)->nullable()->after('founded_in');
            });
        }
        if (!Schema::hasColumn('employers', 'facebook_link')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('facebook_link', 250)->nullable()->after('twitter_link');
            });
        }
        if (!Schema::hasColumn('employers', 'instagram_link')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('instagram_link', 250)->nullable()->after('facebook_link');
            });
        }
        if (!Schema::hasColumn('employers', 'google_link')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('google_link', 250)->nullable()->after('instagram_link');
            });
        }
        if (!Schema::hasColumn('employers', 'linkedin_link')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('linkedin_link', 250)->nullable()->after('google_link');
            });
        }
        if (!Schema::hasColumn('employers', 'youtube_link')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('youtube_link', 250)->nullable()->after('linkedin_link');
            });
        }
        if (!Schema::hasColumn('employers', 'account_type')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('account_type', 30)->default('site')->after('youtube_link');
            });
        }
        if (!Schema::hasColumn('employers', 'external_id')) {
            Schema::table('employers', function (Blueprint $table) {
                $table->string('external_id', 255)->nullable()->after('account_type');
            });
        }
    }

    private static function createEmployerCandidatesTable()
    {
        if (!Schema::hasTable('employer_candidates')) {
            Schema::create('employer_candidates', function (Blueprint $table) {
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->bigInteger('candidate_id')->unsigned()->nullable();
            });
        }
    }    

    private static function createCandidatesTable()
    {
        if (!Schema::hasTable('candidates')) {
            Schema::create('candidates', function (Blueprint $table) {
                $table->bigIncrements('candidate_id')->unsigned();
                $table->string('first_name', 50)->nullable();
                $table->string('last_name', 50)->nullable();
                $table->string('email', 150)->unique();
                $table->string('password', 150);
                $table->string('image', 255)->nullable();
                $table->string('phone1', 50)->nullable();
                $table->string('phone2', 50)->nullable();
                $table->string('city', 150)->nullable();
                $table->string('state', 150)->nullable();
                $table->string('country', 150)->nullable();
                $table->string('address', 150)->nullable();
                $table->string('gender', 20)->nullable();
                $table->datetime('dob')->nullable();
                $table->text('bio')->nullable();
                $table->tinyInteger('status')->default('1');
                $table->string('account_type', 30)->default('site')->nullable();
                $table->string('external_id', 255)->nullable();
                $table->string('token', 150)->nullable();
                $table->timestamps();
            });
        }
    }

    private static function addColumnToCandidatesTable()
    {
        //added in version 2.0
        if (!Schema::hasColumn('candidates', 'slug')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('slug', 100)->nullable()->after('last_name');
            });
        }
        //added in version 2.0
        if (!Schema::hasColumn('candidates', 'twitter_link')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('twitter_link', 250)->nullable()->after('bio');
            });
        }
        if (!Schema::hasColumn('candidates', 'facebook_link')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('facebook_link', 250)->nullable()->after('twitter_link');
            });
        }
        if (!Schema::hasColumn('candidates', 'instagram_link')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('instagram_link', 250)->nullable()->after('facebook_link');
            });
        }
        if (!Schema::hasColumn('candidates', 'google_link')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('google_link', 250)->nullable()->after('instagram_link');
            });
        }
        if (!Schema::hasColumn('candidates', 'linkedin_link')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('linkedin_link', 250)->nullable()->after('google_link');
            });
        }
        if (!Schema::hasColumn('candidates', 'youtube_link')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('youtube_link', 250)->nullable()->after('linkedin_link');
            });
        }
        //added in version 2.3
        if (!Schema::hasColumn('candidates', 'show_profile')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_profile', 4)->default('yes')->nullable()->after('youtube_link');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_bio')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_bio', 4)->default('yes')->nullable()->after('show_profile');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_location')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_location', 4)->default('yes')->nullable()->after('show_bio');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_email')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_email', 4)->default('yes')->nullable()->after('show_location');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_phone')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_phone', 4)->default('yes')->nullable()->after('show_email');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_social_links')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_social_links', 4)->default('yes')->nullable()->after('show_phone');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_experiences')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_experiences', 4)->default('yes')->nullable()->after('show_social_links');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_qualifications')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_qualifications', 4)->default('yes')->nullable()->after('show_experiences');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_achievements')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_achievements', 4)->default('yes')->nullable()->after('show_qualifications');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_skills')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_skills', 4)->default('yes')->nullable()->after('show_achievements');
            });
        }
        if (!Schema::hasColumn('candidates', 'show_languages')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('show_languages', 4)->default('yes')->nullable()->after('show_skills');
            });
        }
    }

    private static function createResumeTable()
    {
        if (!Schema::hasTable('resumes')) {
            Schema::create('resumes', function (Blueprint $table) {
                $table->bigIncrements('resume_id')->unsigned();
                $table->bigInteger('candidate_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('designation', 100)->nullable();
                $table->text('objective')->nullable();
                $table->string('type', 30)->default('detailed')->nullable();
                $table->string('file', 255)->nullable();
                $table->integer('experience')->default(0)->nullable();
                $table->integer('experiences')->default(0)->nullable();
                $table->integer('qualifications')->default(0)->nullable();
                $table->integer('languages')->default(0)->nullable();
                $table->integer('achievements')->default(0)->nullable();
                $table->integer('references')->default(0)->nullable();
                $table->tinyInteger('is_default')->default('1');
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }        
    }

    private static function addColumnToResumesTable()
    {
        //added in version 2.0
        if (!Schema::hasColumn('resumes', 'skills')) {
            Schema::table('resumes', function (Blueprint $table) {
                $table->integer('skills')->default(0)->after('references');
            });
        }
    }

    private static function createResumeExperienceTable()
    {
        if (!Schema::hasTable('resume_experiences')) {
            Schema::create('resume_experiences', function (Blueprint $table) {
                $table->bigIncrements('resume_experience_id')->unsigned();
                $table->bigInteger('resume_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('company', 100)->nullable();
                $table->datetime('from')->nullable();
                $table->datetime('to')->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('is_current')->default('0');
                $table->timestamps();
            });
        }        
    }

    private static function createResumeLanguageTable()
    {
        if (!Schema::hasTable('resume_languages')) {
            Schema::create('resume_languages', function (Blueprint $table) {
                $table->bigIncrements('resume_language_id')->unsigned();
                $table->bigInteger('resume_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('proficiency', 100)->nullable();
                $table->timestamps();
            });
        }        
    }

    private static function createResumeQualificationTable()
    {
        if (!Schema::hasTable('resume_qualifications')) {
            Schema::create('resume_qualifications', function (Blueprint $table) {
                $table->bigIncrements('resume_qualification_id')->unsigned();
                $table->bigInteger('resume_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('institution', 100)->nullable();
                $table->double('marks', 15, 8)->nullable();
                $table->string('out_of', 100)->nullable();
                $table->datetime('from')->nullable();
                $table->datetime('to')->nullable();
                $table->timestamps();
            });
        }        
    }

    private static function createResumeAchievementsTable()
    {
        if (!Schema::hasTable('resume_achievements')) {
            Schema::create('resume_achievements', function (Blueprint $table) {
                $table->bigIncrements('resume_achievement_id')->unsigned();
                $table->bigInteger('resume_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('link', 255)->nullable();
                $table->datetime('date')->nullable();
                $table->string('type', 100)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }        
    }

    private static function createResumeReferencesTable()
    {
        if (!Schema::hasTable('resume_references')) {
            Schema::create('resume_references', function (Blueprint $table) {
                $table->bigIncrements('resume_reference_id')->unsigned();
                $table->bigInteger('resume_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('relation', 100)->nullable();
                $table->string('company', 100)->nullable();
                $table->string('phone', 100)->nullable();
                $table->string('email', 100)->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createResumeSkillsTable()
    {
        if (!Schema::hasTable('resume_skills')) {
            Schema::create('resume_skills', function (Blueprint $table) {
                $table->bigIncrements('resume_skill_id')->unsigned();
                $table->bigInteger('resume_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('proficiency', 100)->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createRolesTable()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->bigIncrements('role_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('type', 20)->nullable()->default('admin');
                $table->timestamps();
            });
        }        
    }

    private static function createMenusTable()
    {
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->bigIncrements('menu_id')->unsigned();
                $table->string('menu_item_id')->nullable();
                $table->string('parent_id')->nullable();
                $table->string('title', 100)->nullable();
                $table->text('link')->nullable();
                $table->string('type', 20)->nullable()->default('admin');
                $table->tinyInteger('order')->default('1');
                $table->timestamps();
            });
        }        
    }

    private static function addColumnToMenusTable()
    {
        //added in version 2.0
        if (!Schema::hasColumn('menus', 'alignment')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('alignment', 10)->nullable()->default('left')->after('type');
            });
        }
    }

    private static function createPermissionsTable()
    {
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->bigIncrements('permission_id')->unsigned();
                $table->string('title', 100)->nullable();
                $table->string('slug', 100)->nullable();
                $table->string('category', 50)->nullable();
                $table->string('type', 20)->nullable()->default('employer');
            });
        }        
    }

    private static function createRolePermissionsTable()
    {
        if (!Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->bigInteger('role_id')->unsigned()->nullable();
                $table->bigInteger('permission_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
            });
        }        
    }

    private static function createUserRolesTable()
    {
        if (!Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->bigInteger('user_id')->unsigned()->nullable();
                $table->bigInteger('role_id')->unsigned()->nullable();
            });
        }        
    }

    private static function createEmployerRolesTable()
    {
        if (!Schema::hasTable('employer_roles')) {
            Schema::create('employer_roles', function (Blueprint $table) {
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->bigInteger('role_id')->unsigned()->nullable();
            });
        }        
    }

    private static function createPagesTable()
    {
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->bigIncrements('page_id')->unsigned();
                $table->string('title', 100)->nullable();
                $table->string('slug', 100)->nullable();
                $table->text('description')->nullable();
                $table->text('keywords')->nullable();
                $table->text('summary')->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }        
    }

    private static function createTestimonialsTable()
    {
        if (!Schema::hasTable('testimonials')) {
            Schema::create('testimonials', function (Blueprint $table) {
                $table->bigIncrements('testimonial_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }        
    }

    private static function addColumnToTestimonialsTable()
    {
        //added in version 2.0
        if (!Schema::hasColumn('testimonials', 'rating')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->integer('rating')->default(0)->after('description');
            });
        }
    }    

    private static function createSettingsTable()
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->bigIncrements('setting_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('category', 100)->nullable();
                $table->string('key', 150)->nullable();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }        
    }

    private static function createToDosTable()
    {
        if (!Schema::hasTable('to_dos')) {
            Schema::create('to_dos', function (Blueprint $table) {
                $table->bigIncrements('to_do_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 150)->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }        
    }

    private static function createJobsTable()
    {
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->bigIncrements('job_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->bigInteger('department_id')->unsigned()->nullable();
                $table->string('title', 255)->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('is_static_allowed')->default('1');
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }        
    }

    private static function addColumnToJobsTable()
    {
        //added in version 1.2
        if (!Schema::hasColumn('jobs', 'combined_filters')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->string('combined_filters', 255)->nullable()->after('description');
            });
        }
        if (!Schema::hasColumn('jobs', 'slug')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->string('slug', 255)->nullable()->after('description');
            });
        }
    }

    private static function addColumnAPIJobIDToJobsTable()
    {
        //added in version 1.2
        if (!Schema::hasColumn('jobs', 'api_job_id')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->string('api_job_id', 255)->nullable()->after('description');
            });
        }
        if (!Schema::hasColumn('jobs', 'slug')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->string('slug', 255)->nullable()->after('description');
            });
        }
    }

    private static function addColumnToJobsTableTwo()
    {
        //added in version 2.2
        if (!Schema::hasColumn('jobs', 'last_date')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->datetime('last_date')->nullable()->after('combined_filters');
            });
        }
        if (!Schema::hasColumn('jobs', 'hide_job_after_last_date')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->tinyInteger('hide_job_after_last_date')->default('0')->after('last_date');
            });
        }
    }

    private static function createJobFiltersTable()
    {
        if (!Schema::hasTable('job_filters')) {
            Schema::create('job_filters', function (Blueprint $table) {
                $table->bigIncrements('job_filter_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 255)->nullable();
                $table->tinyInteger('order')->default('1');
                $table->tinyInteger('admin_filter')->default('1');
                $table->tinyInteger('front_filter')->default('1');
                $table->tinyInteger('front_value')->default('1');
                $table->string('type', 100)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }

    private static function addColumnToJobFiltersTable()
    {
        //added in version 2.0
        if (!Schema::hasColumn('job_filters', 'icon')) {
            Schema::table('job_filters', function (Blueprint $table) {
                $table->string('icon', 255)->nullable()->after('type');
            });
        }
    }

    private static function createJobFilterValuesTable()
    {
        if (!Schema::hasTable('job_filter_values')) {
            Schema::create('job_filter_values', function (Blueprint $table) {
                $table->bigIncrements('job_filter_value_id')->unsigned();
                $table->bigInteger('job_filter_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
            });
        }        
    }

    private static function createJobFilterValueAssignmentsTable()
    {
        if (!Schema::hasTable('job_filter_value_assignments')) {
            Schema::create('job_filter_value_assignments', function (Blueprint $table) {
                $table->bigIncrements('job_filter_value_assignment_id')->unsigned();
                $table->bigInteger('job_filter_value_id')->unsigned()->nullable();
                $table->bigInteger('job_filter_id')->unsigned()->nullable();
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
            });
        }        
    }

    private static function createJobsCustomFieldsTable()
    {
        if (!Schema::hasTable('job_custom_fields')) {
            Schema::create('job_custom_fields', function (Blueprint $table) {
                $table->bigIncrements('custom_field_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->string('label', 255)->nullable();
                $table->string('value', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createDepartmentsTable()
    {
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->bigIncrements('department_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 255)->nullable();
                $table->string('image', 255)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }

    private static function createTraitesTable()
    {
        if (!Schema::hasTable('traites')) {
            Schema::create('traites', function (Blueprint $table) {
                $table->bigIncrements('traite_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 255)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }

    private static function createJobTraitesTable()
    {
        if (!Schema::hasTable('job_traites')) {
            Schema::create('job_traites', function (Blueprint $table) {
                $table->bigIncrements('job_traite_id')->unsigned();
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->bigInteger('traite_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createJobTraiteAnswersTable()
    {
        if (!Schema::hasTable('job_traite_answers')) {
            Schema::create('job_traite_answers', function (Blueprint $table) {
                $table->bigIncrements('job_traite_answer_id')->unsigned();
                $table->bigInteger('job_traite_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->bigInteger('candidate_id')->unsigned()->nullable();
                $table->bigInteger('job_application_id')->unsigned()->nullable();
                $table->string('job_traite_title', 255)->nullable();
                $table->tinyInteger('rating')->default('0');
                $table->timestamps();
            });
        }
    }

    private static function createJobApplicationsTable()
    {
        if (!Schema::hasTable('job_applications')) {
            Schema::create('job_applications', function (Blueprint $table) {
                $table->bigIncrements('job_application_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->bigInteger('candidate_id')->unsigned()->nullable();
                $table->bigInteger('resume_id')->unsigned()->nullable();
                $table->enum('status', array("applied","shortlisted","interviewed","hired","rejected"))->default('applied');
                $table->integer('traites_result')->unsigned()->nullable()->default(0);
                $table->integer('quizes_result')->unsigned()->nullable()->default(0);
                $table->integer('interviews_result')->unsigned()->nullable()->default(0);
                $table->integer('overall_result')->unsigned()->nullable()->default(0);
                $table->timestamps();
            });
        }
    }

    private static function createJobFavoritesTable()
    {
        if (!Schema::hasTable('job_favorites')) {
            Schema::create('job_favorites', function (Blueprint $table) {
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->bigInteger('candidate_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createJobFollowTable()
    {
        if (!Schema::hasTable('job_follow')) {
            Schema::create('job_follow', function (Blueprint $table) {
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createCandidateFavoritesTable()
    {
        if (!Schema::hasTable('candidate_favorites')) {
            Schema::create('candidate_favorites', function (Blueprint $table) {
                $table->bigInteger('candidate_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createJobReferredTable()
    {
        if (!Schema::hasTable('job_referred')) {
            Schema::create('job_referred', function (Blueprint $table) {
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->bigInteger('candidate_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('name', 50)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('phone', 50)->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createNewsCategoriesTable()
    {
        if (!Schema::hasTable('news_categories')) {
            Schema::create('news_categories', function (Blueprint $table) {
                $table->bigIncrements('category_id')->unsigned();
                $table->string('title', 255)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }

    private static function createNewsTable()
    {
        if (!Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->bigIncrements('news_id')->unsigned();
                $table->bigInteger('category_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('slug', 100)->nullable();
                $table->string('summary', 255)->nullable();
                $table->text('description')->nullable();
                $table->text('keywords')->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }

    private static function addColumnToNewsTable()
    {
        //added in version 2.0
        if (!Schema::hasColumn('news', 'image')) {
            Schema::table('news', function (Blueprint $table) {
                $table->string('image', 255)->nullable()->after('keywords');
            });
        }
    }    

    private static function createFaqsCategoriesTable()
    {
        if (!Schema::hasTable('faqs_categories')) {
            Schema::create('faqs_categories', function (Blueprint $table) {
                $table->bigIncrements('category_id')->unsigned();
                $table->string('title', 255)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }

    private static function createFaqsTable()
    {
        if (!Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->bigIncrements('faqs_id')->unsigned();
                $table->bigInteger('category_id')->unsigned()->nullable();
                $table->text('question')->nullable();
                $table->text('answer')->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }

    private static function createBlogCategoriesTable()
    {
        if (!Schema::hasTable('blog_categories')) {
            Schema::create('blog_categories', function (Blueprint $table) {
                $table->bigIncrements('blog_category_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 255)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }        
    }

    private static function createBlogsTable()
    {
        if (!Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->bigIncrements('blog_id')->unsigned();
                $table->bigInteger('blog_category_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 200)->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }    

    private static function addColumnToBlogsTable()
    {
        //added in version 2.0
        if (!Schema::hasColumn('blogs', 'image')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->string('image', 255)->nullable()->after('description');
            });
        }
    }    

    private static function createLanguagesTable()
    {
        if (!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->bigIncrements('language_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('slug', 100)->nullable();
                $table->tinyInteger('is_selected')->default('0');
                $table->tinyInteger('is_default')->default('0');
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }

    private static function addColumnToLanguagesTable()
    {
        //added in version 2.2
        if (!Schema::hasColumn('languages', 'is_main')) {
            Schema::table('languages', function (Blueprint $table) {
                $table->tinyInteger('is_main')->default(0)->after('slug');
            });
        }
        if (!Schema::hasColumn('languages', 'flag')) {
            Schema::table('languages', function (Blueprint $table) {
                $table->string('flag', 5)->nullable()->after('is_default');
            });
        }
        if (!Schema::hasColumn('languages', 'display')) {
            Schema::table('languages', function (Blueprint $table) {
                $table->string('display', 15)->nullable()->after('flag')->default('both');
            });
        }
        if (!Schema::hasColumn('languages', 'direction')) {
            Schema::table('languages', function (Blueprint $table) {
                $table->string('direction', 5)->nullable()->after('display')->default('ltr');
            });
        }
    }    

    private static function createAppUpdateTable()
    {
        if (!Schema::hasTable('updates')) {
            Schema::create('updates', function (Blueprint $table) {
                $table->bigIncrements('update_id')->unsigned();
                $table->string('version', 50)->nullable();
                $table->string('title', 150)->nullable();
                $table->text('details')->nullable();
                $table->text('files')->nullable();
                $table->datetime('released_at')->nullable();
                $table->tinyInteger('is_current')->default('1');
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
    }

    private static function createQuestionCategoriesTable()
    {
        if (!Schema::hasTable('question_categories')) {
            Schema::create('question_categories', function (Blueprint $table) {
                $table->bigIncrements('question_category_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }        
    }

    private static function createQuestionsTable()
    {
        if (!Schema::hasTable('questions')) {
            Schema::create('questions', function (Blueprint $table) {
                $table->bigIncrements('question_id')->unsigned();
                $table->bigInteger('question_category_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->text('title')->nullable();
                $table->string('image', 250)->nullable();
                $table->string('type', 20)->nullable();
                $table->string('nature', 20)->nullable()->default('quiz');
                $table->timestamps();
            });
        }
       
    }

    private static function createQuestionAnswersTable()
    {
        if (!Schema::hasTable('question_answers')) {
            Schema::create('question_answers', function (Blueprint $table) {
                $table->bigIncrements('question_answer_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->bigInteger('question_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->tinyInteger('is_correct')->default('1');
            });
        }
       
    }

    private static function createQuizCategoriesTable()
    {
        if (!Schema::hasTable('quiz_categories')) {
            Schema::create('quiz_categories', function (Blueprint $table) {
                $table->bigIncrements('quiz_category_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
       
    }

    private static function createQuizesTable()
    {
        if (!Schema::hasTable('quizes')) {
            Schema::create('quizes', function (Blueprint $table) {
                $table->bigIncrements('quiz_id')->unsigned();
                $table->bigInteger('quiz_category_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 150)->nullable();
                $table->text('description')->nullable();
                $table->integer('allowed_time');
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
       
    }

    private static function createQuizQuestionsTable()
    {
        if (!Schema::hasTable('quiz_questions')) {
            Schema::create('quiz_questions', function (Blueprint $table) {
                $table->bigIncrements('quiz_question_id')->unsigned();
                $table->bigInteger('quiz_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 250)->nullable();
                $table->string('image', 250)->nullable();
                $table->string('type', 20)->nullable();
                $table->tinyInteger('order');
                $table->timestamps();
            });
        }
       
    }

    private static function createQuizQuestionAnswersTable()
    {
        if (!Schema::hasTable('quiz_question_answers')) {
            Schema::create('quiz_question_answers', function (Blueprint $table) {
                $table->bigIncrements('quiz_question_answer_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->bigInteger('quiz_question_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->tinyInteger('is_correct')->default('1');
            });
        }
       
    }

    private static function createJobQuizesTable()
    {
        if (!Schema::hasTable('job_quizes')) {
            Schema::create('job_quizes', function (Blueprint $table) {
                $table->bigIncrements('job_quiz_id')->unsigned();
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->bigInteger('quiz_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('quiz_title', 150)->nullable();
                $table->integer('total_questions')->nullable();
                $table->integer('allowed_time')->nullable();
                $table->text('quiz_data')->nullable();
                $table->datetime('created_at')->nullable();
            });
        }
       
    }

    private static function createCandidateQuizesTable()
    {
        if (!Schema::hasTable('candidate_quizes')) {
            Schema::create('candidate_quizes', function (Blueprint $table) {
                $table->bigIncrements('candidate_quiz_id')->unsigned();
                $table->bigInteger('candidate_id')->unsigned()->nullable();
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->bigInteger('job_quiz_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('quiz_title', 150)->nullable();
                $table->text('quiz_data')->nullable();
                $table->text('answers_data')->nullable();
                $table->tinyInteger('total_questions');
                $table->tinyInteger('allowed_time');
                $table->tinyInteger('correct_answers');
                $table->tinyInteger('attempt')->default('0');
                $table->datetime('started_at')->nullable();                
                $table->timestamps();
            });
        }
       
    }

    private static function createInterviewCategoriesTable()
    {
        if (!Schema::hasTable('interview_categories')) {
            Schema::create('interview_categories', function (Blueprint $table) {
                $table->bigIncrements('interview_category_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
       
    }

    private static function createInterviewsTable()
    {
        if (!Schema::hasTable('interviews')) {
            Schema::create('interviews', function (Blueprint $table) {
                $table->bigIncrements('interview_id')->unsigned();
                $table->bigInteger('interview_category_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('status')->default('1');
                $table->timestamps();
            });
        }
       
    }

    private static function createInterviewQuestionsTable()
    {
        if (!Schema::hasTable('interview_questions')) {
            Schema::create('interview_questions', function (Blueprint $table) {
                $table->bigIncrements('interview_question_id')->unsigned();
                $table->bigInteger('interview_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->text('title')->nullable();
                $table->string('image', 250)->nullable();
                $table->tinyInteger('order');
                $table->timestamps();
            });
        }
       
    }

    private static function createCandidateInterviewsTable()
    {
        if (!Schema::hasTable('candidate_interviews')) {
            Schema::create('candidate_interviews', function (Blueprint $table) {
                $table->bigIncrements('candidate_interview_id')->unsigned();
                $table->bigInteger('candidate_id')->unsigned()->nullable();
                $table->bigInteger('job_id')->unsigned()->nullable();
                $table->bigInteger('interviewer_id')->unsigned()->nullable();
                $table->bigInteger('employer_id')->unsigned()->nullable();
                $table->string('interview_title', 250)->nullable();
                $table->text('interview_data')->nullable();
                $table->text('answers_data')->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('total_questions')->nullable();
                $table->tinyInteger('overall_rating')->nullable();
                $table->tinyInteger('status')->default('0');
                $table->datetime('interview_time')->nullable();
                $table->timestamps();
            });
        }       
    }

    private static function createPackagesTable()
    {
        if (!Schema::hasTable('packages')) {
            Schema::create('packages', function (Blueprint $table) {
                $table->bigIncrements('package_id')->unsigned();
                $table->string('title', 100)->nullable();
                $table->string('currency', 100)->nullable();
                $table->string('currency_for_api', 10)->nullable();
                $table->double('monthly_price', 15, 2)->nullable();
                $table->double('yearly_price', 15, 2)->nullable();
                $table->integer('active_jobs')->default('0')->nullable();
                $table->integer('active_users')->default('0')->nullable();
                $table->integer('active_custom_filters')->default('0')->nullable();
                $table->integer('active_traites')->default('0')->nullable();
                $table->integer('active_quizes')->default('0')->nullable();
                $table->integer('active_interviews')->default('0')->nullable();
                $table->tinyInteger('branding')->default('1');
                $table->tinyInteger('role_permissions')->default('1');
                $table->tinyInteger('custom_emails')->default('1');
                $table->tinyInteger('status')->default('1');
                $table->tinyInteger('is_free')->default('0');
                $table->tinyInteger('is_top_sale')->default('0');
                $table->timestamps();
            });
        }
    }

    private static function addColumnToPackagesTable()
    {
        //added in version 1.3
        if (!Schema::hasColumn('packages', 'separate_site')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->tinyInteger('separate_site')->default('1')->after('active_interviews');
            });
        }        
    }

    private static function addColumnToPackagesTable2()
    {
        //added in version 2.0
        if (!Schema::hasColumn('packages', 'image')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->string('image', 150)->nullable()->after('is_top_sale');
            });
        }        
    }

    private static function createMembershipsTable()
    {
        if (!Schema::hasTable('memberships')) {
            Schema::create('memberships', function (Blueprint $table) {
                $table->bigIncrements('membership_id')->unsigned();
                $table->bigInteger('employer_id')->unsigned();
                $table->bigInteger('package_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('payment_type', 50)->nullable();
                $table->string('package_type', 30)->nullable();
                $table->string('transaction_id', 150)->nullable();
                $table->string('payment_status', 50)->nullable();
                $table->string('payment_currency', 50)->nullable();
                $table->string('receiver_email', 150)->nullable();
                $table->string('payer_email', 150)->nullable();
                $table->double('price_paid', 15, 8)->nullable();
                $table->text('response')->nullable();
                $table->text('details')->nullable();
                $table->tinyInteger('status')->default('1');
                $table->datetime('expiry')->nullable();
                $table->timestamps();
            });
        }
    }

    private static function addColumnToMembershipTable()
    {
        //added in version 1.3
        if (!Schema::hasColumn('memberships', 'separate_site')) {
            Schema::table('memberships', function (Blueprint $table) {
                $table->tinyInteger('separate_site')->default('0')->after('details');
            });
        }
    }

    private static function addColumnToMembershipTableTwo()
    {
        //added in version 2.2
        if (!Schema::hasColumn('memberships', 'file')) {
            Schema::table('memberships', function (Blueprint $table) {
                $table->string('file', 255)->nullable()->after('separate_site');
            });
        }
        if (!Schema::hasColumn('memberships', 'message')) {
            Schema::table('memberships', function (Blueprint $table) {
                $table->string('message', 255)->nullable()->after('file');
            });
        }
    }

    private static function createCandidatePackagesTable()
    {
        if (!Schema::hasTable('candidate_packages')) {
            Schema::create('candidate_packages', function (Blueprint $table) {
                $table->bigIncrements('candidate_package_id')->unsigned();
                $table->string('title', 100)->nullable();
                $table->string('currency', 100)->nullable();
                $table->string('currency_for_api', 10)->nullable();
                $table->string('image', 150)->nullable();
                $table->double('monthly_price', 15, 2)->nullable();
                $table->double('yearly_price', 15, 2)->nullable();
                $table->integer('allowed_job_applications')->default('0')->nullable();
                $table->integer('allowed_resumes')->default('0')->nullable();
                $table->tinyInteger('show_hide_personal_info')->default('1');
                $table->tinyInteger('status')->default('1');
                $table->tinyInteger('is_free')->default('0');
                $table->tinyInteger('is_top_sale')->default('0');
                $table->timestamps();
            });
        }
    }

    private static function createCandidateMembershipsTable()
    {
        if (!Schema::hasTable('candidate_memberships')) {
            Schema::create('candidate_memberships', function (Blueprint $table) {
                $table->bigIncrements('candidate_membership_id')->unsigned();
                $table->bigInteger('candidate_id')->unsigned();
                $table->bigInteger('candidate_package_id')->unsigned()->nullable();
                $table->string('title', 100)->nullable();
                $table->string('payment_type', 50)->nullable();
                $table->string('package_type', 30)->nullable();
                $table->string('transaction_id', 150)->nullable();
                $table->string('payment_status', 50)->nullable();
                $table->string('payment_currency', 50)->nullable();
                $table->string('receiver_email', 150)->nullable();
                $table->string('payer_email', 150)->nullable();
                $table->double('price_paid', 15, 8)->nullable();
                $table->text('response')->nullable();
                $table->text('details')->nullable();
                $table->tinyInteger('show_hide_personal_info')->default('0');
                $table->string('file', 255)->nullable();
                $table->string('message', 255)->nullable();
                $table->tinyInteger('status')->default('1');
                $table->datetime('expiry')->nullable();
                $table->timestamps();
            });
        }
    }

    private static function createMessagesTable()
    {
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->bigIncrements('message_id')->unsigned();
                $table->string('name', 100)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('subject', 100)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }        
    }

    private static function createNotificationsTable()
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->bigIncrements('notification_id')->unsigned();
                $table->string('title', 255)->nullable();
                $table->text('description')->nullable();
                $table->string('type', 20)->nullable();
                $table->tinyInteger('is_read')->default('0');
                $table->timestamps();
            });
        }        
    }

    private static function createErrorLogsTable()
    {
        if (!Schema::hasTable('error_logs')) {
            Schema::create('error_logs', function (Blueprint $table) {
                $table->bigIncrements('error_log_id')->unsigned();
                $table->string('title', 255)->nullable();
                $table->text('description')->nullable();
                $table->string('type', 30)->nullable();
                $table->datetime('created_at')->nullable();
            });
        }        
    }

    private static function createSearchLogsTable()
    {
        if (!Schema::hasTable('search_logs')) {
            Schema::create('search_logs', function (Blueprint $table) {
                $table->bigIncrements('search_log_id')->unsigned();
                $table->string('title', 255)->nullable();
                $table->tinyInteger('count')->default('0');
                $table->datetime('created_at')->nullable();
            });
        }        
    }
    
    public static function importAdminSettings()
    {
        $app_url = base_url(true);
        $bannerText = '<h1 class="banner-title">Looking for Talent</h1>
        <p class="banner-text">Signup with our most advanced applicant tracking system with built in tools <br />to help you filter out the best candidates for your job needs.</p>';
        $bannerTextEmployer = '<h2>Looking for an exciting career path ?<br>Come, Join Us!</h2>';        
        $offlinePaymentText = 'Please pay on the following bank account and attach the reciept. Once we verify, we\'ll activate your membership.<br />Account Title : Candidate Finder SaaS</br >Account # 010123456789';        
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'candidatefinder.com';
        $host = str_replace('www.', '', $host);
        $fromEmail = 'hr@'.$host;
        $logo = route('uploads-view', 'identities/site-logo.png');
        $banner = route('uploads-view', 'identities/site-banner.png');
        $breadcrumb = route('uploads-view', 'identities/site-breadcrumb-image.png');
        $css_variables = '{"main-banner":"url('.$banner.')","body-bg":"#FBFBFB","main-menu-bg":"#FBFBFB","main-menu-font-color":"#484848","main-menu-font-highlight-color":"#286EFB","main-banner-bg":"#FBFBFB","main-banner-height":"500px","breadcrumb-image":"url('.$breadcrumb.')"}';
        $css_variables_candidate = '{"body-bg":"#FBFBFB","main-menu-bg":"#FBFBFB","main-menu-font-color":"#484848","main-menu-font-highlight-color":"#286EFB","main-banner-bg":"#FBFBFB","main-banner-height":"500px","breadcrumb-image":"url('.$breadcrumb.')","main-banner":"url('.$banner.')"}';

        $col1 = '
            <h3>'.setting('site_name').'</h3>
            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
        ';
        $col2 = '
            <div class="footer-head">
                <h4>Latest News</h4>
                <ul>
                    <li><a href="#">Lorem Ipsum News 1</a></li>
                    <li><a href="#">Lorem Ipsum News 2</a></li>
                    <li><a href="#">Lorem Ipsum News 3</a></li>
                    <li><a href="#">Lorem Ipsum News 4</a></li>
                    <li><a href="#">Lorem Ipsum News 5</a></li>
                </ul>
            </div>        
        ';
        $col3 = '
            <div class="footer-head">
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="#">Lorem Ipsum Link 1</a></li>
                    <li><a href="#">Lorem Ipsum Link 2</a></li>
                    <li><a href="#">Lorem Ipsum Link 3</a></li>
                    <li><a href="#">Lorem Ipsum Link 4</a></li>
                    <li><a href="#">Lorem Ipsum Link 5</a></li>
                </ul>
            </div>        
        ';
        $col4 = '
            <div class="footer-head">
                <h4>information</h4>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                </p>
                <div class="footer-contacts">
                    <p><span>Tel:</span> +123 456 789</p>
                    <p><span>Email:</span> contact@example.com</p>
                    <p><span>Working Hours:</span> 9am-5pm</p>
                </div>
            </div>        
        ';

        $data = array(
            array('employer_id' => 0, 'category' => 'General', 'key' => 'site_logo', 'value' => 'identities/site-logo.png',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'site_banner', 'value' => 'identities/site-banner.png',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'site_breadcrumb_image', 
                'value' => 'identities/site-breadcrumb-image.png',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'site_favicon', 'value' => 'identities/site-favicon.png',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'site_name', 'value' => 'Candidate Finder SAAS',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'admin_email', 'value' => 'admin@'.$host,),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'purchase_code', 'value' => 'test',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'site_keywords', 'value' => 'candidate finder',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'site_description', 'value' => 'candidate finder',),

            array('employer_id' => 0, 'category' => 'General', 'key' => 'employer_free_registeration_days', 'value' => '7'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_employer_registeration', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_employer_email_verification', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_employer_forgot_password', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_employer_free_registeration', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_employer_language_selector', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_only_employer_to_view_candidates', 'value' => 'no'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_employer_register_notification', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_employer_job_apply_notification', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'import_employer_dummy_data_on_signup', 'value' => 'no'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'import_employer_dummy_data_on_creation', 'value' => 'no'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_candidate_registeration', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_candidate_email_verification', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_candidate_forgot_password', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_candidate_register_notification', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_candidate_job_apply_notification', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_candidate_language_selector', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_candidate_packages', 'value' => 'no'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'default_settings_for_candidates', 'value' => 'display'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_multiple_resume', 'value' => 'no',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_candidate_dark_mode_button', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'jobs_per_page', 'value' => '25',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'news_per_page', 'value' => '25',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'news_detail_image_full_width', 'value' => 'no',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'companies_per_page', 'value' => '25',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'candidates_per_page', 'value' => '10',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'default_front_color_theme', 'value' => 'blue'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'display_front_color_theme_selector_panel', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'display_main_menu_bg_as_transparent', 'value' => 'no'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'display_info_in_login_modal', 'value' => 'yes'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'display_main_menu_as_full_width', 'value' => 'no'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'front_site_css', 'value' => ''),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'candidate_site_css', 'value' => ''),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'body_bg', 'value' => '#fbfbfb'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'main_menu_bg', 'value' => '#fbfbfb'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'main_menu_font_color', 'value' => '#484848'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'main_menu_font_highlight_color', 'value' => '#286EFB'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'main_banner_height', 'value' => '320px'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'main_banner_bg', 'value' => '#fbfbfb'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'breadcrumb_background', 'value' => '#edf8ff'),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'allow_all_jobs_page', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'allow_all_companies_page', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'front_header_scripts', 'value' => '',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'front_footer_scripts', 'value' => '',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'employer_header_scripts', 'value' => '',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'employer_footer_scripts', 'value' => '',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'candidate_header_scripts', 'value' => '',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'candidate_footer_scripts', 'value' => '',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'admin_file_upload_limit', 'value' => '512',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'general_file_upload_limit', 'value' => '512',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_editor_for_email_templates', 'value' => 'no',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'enable_separate_employer_site', 'value' => 'only_for_employers_with_separate_site',),
            array('employer_id' => 0, 'category' => 'General', 'key' => 'css_variables', 'value' => $css_variables,),            
            array('employer_id' => 0, 'category' => 'General', 'key' => 'css_variables_candidate', 'value' => $css_variables_candidate,),            
            array('employer_id' => 0, 'category' => 'Portal Vs MT', 'key' => 'departments_creation', 'value' => 'both_admin_and_employer',),
            array('employer_id' => 0, 'category' => 'Portal Vs MT', 'key' => 'job_filters_creation', 'value' => 'both_admin_and_employer',),
            array('employer_id' => 0, 'category' => 'Portal Vs MT', 'key' => 'front_login_type', 'value' => 'both',),
            array('employer_id' => 0, 'category' => 'Portal Vs MT', 'key' => 'display_jobs_front', 'value' => 'from_all_employers',),
            array('employer_id' => 0, 'category' => 'Portal Vs MT', 'key' => 'display_departments_front', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Portal Vs MT', 'key' => 'display_employers_front', 'value' => 'yes',),

            //General Images
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_modal', 'value' => 'general/modal.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_candidates', 'value' => 'general/candidates.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_employers', 'value' => 'general/employers.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_benefits', 'value' => 'general/benefits.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_filters', 'value' => 'general/features/filters.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_interviews', 'value' => 'general/features/interviews.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_jobboard', 'value' => 'general/features/jobboard.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_personalized', 'value' => 'general/features/personalized.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_quizes', 'value' => 'general/features/quizes.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_referjob', 'value' => 'general/features/referjob.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_reports', 'value' => 'general/features/reports.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_resume', 'value' => 'general/features/resume.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_rolepermissions', 'value' => 'general/features/rolepermissions.png',),
            array('employer_id' => 0, 'category' => 'General Images', 'key' => 'general_feature_selfassesment', 'value' => 'general/features/selfassesment.png',),

            //Email
            array('employer_id' => 0, 'category' => 'Email', 'key' => 'smtp_enable', 'value' => 'no',),
            array('employer_id' => 0, 'category' => 'Email', 'key' => 'smtp_host', 'value' => 'smtp.googlemail.com',),
            array('employer_id' => 0, 'category' => 'Email', 'key' => 'smtp_protocol', 'value' => 'ssl',),
            array('employer_id' => 0, 'category' => 'Email', 'key' => 'smtp_port', 'value' => '465',),
            array('employer_id' => 0, 'category' => 'Email', 'key' => 'smtp_username', 'value' => 'your_gmail@gmail.com',),
            array('employer_id' => 0, 'category' => 'Email', 'key' => 'smtp_password', 'value' => 'Abcd1234!',),
            array('employer_id' => 0, 'category' => 'Email', 'key' => 'from_email', 'value' => $fromEmail,),
            array('employer_id' => 0, 'category' => 'Email', 'key' => 'from_name', 'value' => 'Candidate Finder SaaS',),
            array('employer_id' => 0, 'category' => 'Email', 'key' => 'enable_site_message_email', 'value' => 'yes',),

            //Email Templates
            array('employer_id' => 0, 'category' => 'Email Templates', 'key' => 'candidate_reset_password', 
                  'value' => getTextFromFile('candidate-reset-password.txt'),),
            array('employer_id' => 0, 'category' => 'Email Templates', 'key' => 'candidate_signup', 
                  'value' => getTextFromFile('candidate-signup.txt'),),
            array('employer_id' => 0, 'category' => 'Email Templates', 'key' => 'candidate_verify_email', 
                  'value' => getTextFromFile('candidate-verify-email.txt'),),
            array('employer_id' => 0, 'category' => 'Email Templates', 'key' => 'employer_reset_password', 
                  'value' => getTextFromFile('employer-reset-password.txt'),),
            array('employer_id' => 0, 'category' => 'Email Templates', 'key' => 'employer_signup', 
                  'value' => getTextFromFile('employer-signup.txt'),),
            array('employer_id' => 0, 'category' => 'Email Templates', 'key' => 'employer_verify_email', 
                  'value' => getTextFromFile('employer-verify-email.txt'),),
            array('employer_id' => 0, 'category' => 'Email Templates', 'key' => 'employer_refer_job', 
                  'value' => getTextFromFile('employer-refer-job.txt'),),

            //Apis
            //Offline Payment Gateway
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'enable_offline_payment', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'enable_offline_payment_attachment', 'value' => 'required',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'enable_offline_payment_unverified_activation', 'value' => 'no',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'offline_payment_title', 'value' => 'Offline Payment',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'offline_payment_text', 'value' => $offlinePaymentText,),
            //Paypal
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'enable_paypal', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'paypal_environment', 'value' => 'testing',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'paypal_email', 'value' => 'mybusiness@paypal.com',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'paypal_client_id', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'paypal_client_secret', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'paypal_webhook', 'value' => $app_url.'/paypal-payment-ipn',),
            //Stripe
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'enable_stripe', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'stripe_publisher_key', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'stripe_secret_key', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'stripe_webhook', 'value' => $app_url.'/stripe-invoice',),
            //Paystack
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'enable_paystack', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'paystack_public_key', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'paystack_secret_key', 'value' => 'abcd1234',),
            //Razorpay
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'enable_razorpay', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'razorpay_key_id', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'razorpay_key_secret', 'value' => 'abcd1234',),
            //Google login
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'enable_google_login', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'google_client_id', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'google_client_secret', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'google_redirect_uri', 'value' => $app_url.'/google-redirect',),
            //Linkedin login
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'enable_linkedin_login', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'linkedin_id', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'linkedin_secret', 'value' => 'abcd1234',),
            array('employer_id' => 0, 'category' => 'Apis', 'key' => 'linkedin_redirect_uri', 'value' => $app_url.'/linkedin-redirect',),

            //Home
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_banner', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_banner_type', 'value' => 'side_image',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_banner_filters_display', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_banner_text', 'value' => $bannerText,),
            
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_display_order', 'value' => '{"home_highlights_section":"1","home_departments_section":"2","home_companies_section":"3","home_jobs_section":"4","home_candidates_section":"5","home_guide_section":"6","home_make_account_section":"7","home_pricing_section":"8","home_testimonials_section":"9","home_features_section":"10","home_news_section":"11"}'),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_highlights_section', 'value' => 'enabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_departments_section', 'value' => 'enabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_departments_section_sort_order', 'value' => 'departments.department_id DESC',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_departments_section_limit', 'value' => '12',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_companies_section', 'value' => 'enabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_companies_section_sort_order', 'value' => 'employers.employer_id DESC',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_companies_section_limit', 'value' => '8',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_jobs_section', 'value' => 'enabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_jobs_section_sort_order', 'value' => 'jobs.created_at DESC',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_jobs_section_limit', 'value' => '6',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_candidates_section', 'value' => 'enabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_candidates_section_sort_order', 'value' => 'candidates.created_at DESC',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_candidates_section_limit', 'value' => '6',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_guide_section', 'value' => 'enabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_make_account_section', 'value' => 'disabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_pricing_section', 'value' => 'disabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_testimonials_section', 'value' => 'enabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_testimonials_section_sort_order', 'value' => 'testimonials.testimonial_id DESC',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_testimonials_section_limit', 'value' => '6',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_features_section', 'value' => 'disabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_news_section', 'value' => 'enabled',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_news_section_sort_order', 'value' => 'news.news_id ASC',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'home_news_section_limit', 'value' => '3',),

            array('employer_id' => 0, 'category' => 'Home', 'key' => 'enable_feature_section', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'quiz_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'interview_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'assesment_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'filter_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'job_board_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'resume_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'referral_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'oauth_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'translation_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'setting_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'roles_feature', 'value' => 'yes',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'reports_feature', 'value' => 'yes',),

            array('employer_id' => 0, 'category' => 'Home', 'key' => 'contact_phone', 'value' => '+1 5589 55488 55',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'contact_email', 'value' => 'info@example.com',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'contact_address', 'value' => 'Sydney, Australia',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'contact_map', 'value' => 'https://maps.google.com/maps?q=2880%20Broadway,%20New%20York&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed',),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'contact_text', 'value' => $bannerText,),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'footer_column_1', 'value' => $col1,),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'footer_column_2', 'value' => $col2,),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'footer_column_3', 'value' => $col3,),
            array('employer_id' => 0, 'category' => 'Home', 'key' => 'footer_column_4', 'value' => $col4,),

            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'banner_text', 'value' => $bannerTextEmployer),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'before_blogs_text', 'value' => ''),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'after_blogs_text', 'value' => ''),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'before_how_text', 'value' => ''),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'after_how_text', 'value' => ''),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'footer_col_1', 'value' => $col1),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'footer_col_2', 'value' => $col2),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'footer_col_3', 'value' => $col3),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'footer_col_4', 'value' => $col4),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'candidate_job_app', 'value' => getTextFromFile('candidate-job-application.txt')),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'employer_job_app', 'value' => getTextFromFile('employer-job-application.txt')),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'employer_interview_assign', 'value' => getTextFromFile('employer-interview-assign.txt')),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'candidate_interview_assign', 'value' => getTextFromFile('candidate-interview-assign.txt')),
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'candidate_quiz_assign', 'value' => getTextFromFile('candidate-quiz-assign.txt')),            
            array('employer_id' => 0, 'category' => 'Employer', 'key' => 'team_creation', 'value' => getTextFromFile('team-creation.txt')),
        );

        foreach ($data as $d) {
            $result = DB::table('settings')->where(array('key' => $d['key'], 'employer_id' => 0))->first();
            if (!$result) {
                DB::table('settings')->insert($d);
            }
        }
    }
     
    public static function importPages()
    {
        $description = '
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi posuere, felis nec gravida sollicitudin, orci lorem hendrerit felis, et auctor dui orci semper elit. Sed varius interdum auctor. Proin venenatis velit quis tempor accumsan. Aliquam dapibus nisi eros, eu vehicula mauris ultricies vitae. Aliquam urna augue, pellentesque id diam vel, varius ullamcorper elit. Curabitur nunc purus, pellentesque sed pulvinar id, laoreet a augue. Cras ultricies lacus non ante tristique, id malesuada velit accumsan. Mauris mauris ex, eleifend id justo at, gravida pellentesque lorem. Vestibulum vehicula imperdiet ex eget scelerisque. Donec gravida scelerisque ex, sit amet laoreet nisi sagittis in. Donec aliquet vulputate justo, et dictum nunc blandit vitae. Fusce hendrerit porta nibh a rutrum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
         <p>Vestibulum dictum fermentum diam, eu consectetur sem ullamcorper id. Nam at nibh efficitur, ornare arcu a, maximus ex. Pellentesque ipsum mauris, pretium et lectus auctor, tincidunt maximus lacus. Proin eu vestibulum dui. Duis et mauris fermentum neque condimentum pharetra. Nam porttitor elit nec facilisis maximus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam feugiat neque mauris, vel porttitor neque vulputate nec.</p>
         <p>Aenean venenatis eu libero nec efficitur. Suspendisse pretium mi sed quam lacinia sodales. Maecenas ut viverra turpis. Suspendisse accumsan sit amet lacus quis venenatis. Donec sed nisl vitae tellus cursus accumsan. Mauris sagittis elementum ipsum id laoreet. Donec ac velit arcu. Nullam eu velit eu leo sagittis varius. Nullam cursus mauris non lacinia varius. Cras convallis tellus a purus ullamcorper interdum. Nulla facilisi. Suspendisse quis mi bibendum, varius diam vitae, mollis enim. Curabitur lacus nunc, porta in felis in, finibus auctor sapien. Nulla facilisi. Suspendisse ullamcorper pretium dolor vel pellentesque.</p>
         <p>Aenean tristique est non nibh bibendum imperdiet. Morbi ac mauris massa. Quisque elit eros, pretium a erat nec, egestas mattis lectus. Morbi vitae libero vehicula, tempus mauris ac, suscipit nunc. Nulla mi eros, egestas sed lectus nec, posuere maximus ipsum. Duis viverra, felis eget ultricies interdum, nibh mauris vehicula magna, eu rutrum lacus mi a neque. Donec velit quam, placerat id placerat nec, gravida ac nunc. Suspendisse non nulla libero. Phasellus aliquam vehicula odio et viverra. Duis auctor diam quis elit sollicitudin scelerisque. Morbi ac tincidunt augue. Donec ultricies tempor libero vel hendrerit. Nunc magna massa, hendrerit at venenatis at, fringilla at nisl.</p>
         <p>Mauris sapien risus, pharetra sit amet libero ac, consectetur imperdiet arcu. Fusce lectus nunc, pulvinar ut orci eu, dictum laoreet tortor. Ut nibh est, aliquet eleifend interdum eget, vestibulum viverra massa. Aenean eu cursus eros. Nunc ultrices, metus id congue suscipit, lorem est tincidunt est, sed aliquam velit enim interdum ex. Ut ut accumsan purus, nec aliquet mauris. Nullam sollicitudin purus dolor, nec aliquet velit cursus ut. Praesent est lacus, porttitor vel blandit nec, gravida non metus. Suspendisse faucibus purus maximus dapibus commodo. Nulla aliquet ultrices eleifend. Nullam id sodales mi. Nunc euismod dui sodales nibh sollicitudin auctor. Fusce ultricies tempor ligula interdum malesuada.</p>        
        ';
        $summary = '
            Mauris sapien risus, pharetra sit amet libero ac, consectetur imperdiet arcu. Fusce lectus nunc, pulvinar ut orci eu, dictum laoreet tortor. Ut nibh est, aliquet eleifend interdum eget, vestibulum viverra massa. Aenean eu cursus eros. Nunc ultrices, metus id congue suscipit, lorem est tincidunt est, sed aliquam velit enim interdum ex. Ut ut accumsan purus, nec aliquet mauris. Nullam sollicitudin purus dolor, nec aliquet velit cursus ut. Praesent est lacus, porttitor vel blandit nec, gravida non metus. Suspendisse faucibus purus maximus dapibus commodo. Nulla aliquet ultrices eleifend. Nullam id sodales mi. Nunc euismod dui sodales nibh sollicitudin auctor. Fusce ultricies tempor ligula interdum malesuada
        ';
        $data = array(
            array(
                'title' => 'About Us',
                'slug' => 'about-us',
                'description' => $description,
                'keywords' => 'about us',
                'summary' => $summary,
                'created_at' => date('Y-m-d G:i:s'),
                'updated_at' => date('Y-m-d G:i:s')
            ),
            array(
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'description' => $description,
                'keywords' => 'terms conditions',
                'summary' => $summary,
                'created_at' => date('Y-m-d G:i:s'),
                'updated_at' => date('Y-m-d G:i:s')
            ),
            array(
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'description' => $description,
                'keywords' => 'privacy policy',
                'summary' => $summary,
                'created_at' => date('Y-m-d G:i:s'),
                'updated_at' => date('Y-m-d G:i:s')
            ),
        );
        foreach ($data as $d) {
            $result = DB::table('pages')->where(array('slug' => $d['slug']))->first();
            if (!$result) {
                DB::table('pages')->insert($d);
            }
        }
    }   

    public static function importMenuItems()
    {
        $url = base_url(true);
        if (env('CFSAAS_SCRIPT_TYPE') == 'jobportal') {
            $data = array(
                array(
                    "menu_item_id" => "home_main",
                    "parent_id" => 0,
                    "title" => "message.home",
                    "link" => $url,
                    "type" => "home_main",
                    "alignment" => "left",
                    "order" => 1,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "all_jobs_page",
                    "parent_id" => 0,
                    "title" => "message.jobs",
                    "link" => $url."/jobs",
                    "type" => "all_jobs_page",
                    "alignment" => "left",
                    "order" => 2,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "all_candidates_page",
                    "parent_id" => 0,
                    "title" => "message.candidates",
                    "link" => $url."/candidates",
                    "type" => "all_candidates_page",
                    "alignment" => "left",
                    "order" => 3,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "all_companies_page",
                    "parent_id" => 0,
                    "title" => "message.companies",
                    "link" => $url."/companies",
                    "type" => "all_companies_page",
                    "alignment" => "left",
                    "order" => 4,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "static_external",
                    "parent_id" => 0,
                    "title" => "message.explore",
                    "link" => "#explore",
                    "type" => "static_external",
                    "alignment" => "left",
                    "order" => 5,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "features",
                    "parent_id" => 5,
                    "title" => "message.features",
                    "link" => $url."/features",
                    "type" => "features",
                    "alignment" => "left",
                    "order" => 6,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "pricing",
                    "parent_id" => 5,
                    "title" => "message.pricing",
                    "link" => $url."/pricing",
                    "type" => "pricing",
                    "alignment" => "left",
                    "order" => 7,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "select_page",
                    "parent_id" => 5,
                    "title" => "message.general",
                    "link" => "#general",
                    "type" => "select_page",
                    "alignment" => "left",
                    "order" => 8,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "select_page",
                    "parent_id" => 8,
                    "title" => "message.about_us",
                    "link" => $url.'/'."pages/about-us",
                    "type" => "select_page",
                    "alignment" => "left",
                    "order" => 9,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "select_page",
                    "parent_id" => 8,
                    "title" => "message.terms_conditions",
                    "link" => $url.'/'."pages/terms-conditions",
                    "type" => "select_page",
                    "alignment" => "left",
                    "order" => 10,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "select_page",
                    "parent_id" => 8,
                    "title" => "message.privacy_policy",
                    "link" => $url.'/'."pages/privacy-policy",
                    "type" => "select_page",
                    "alignment" => "left",
                    "order" => 11,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "all_news_page",
                    "parent_id" => 5,
                    "title" => "message.news",
                    "link" => $url."/news",
                    "type" => "all_news_page",
                    "alignment" => "left",
                    "order" => 12,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "contact",
                    "parent_id" => 0,
                    "title" => "message.contact",
                    "link" => $url."/contact",
                    "type" => "contact",
                    "alignment" => "left",
                    "order" => 13,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),            
                array(
                    "menu_item_id" => "language_button",
                    "parent_id" => 0,
                    "title" => "message.language",
                    "link" => "#language",
                    "type" => "language_button",
                    "alignment" => "right",
                    "order" => 14,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "dark_mode_button",
                    "parent_id" => 0,
                    "title" => "message.dark_mode_button",
                    "link" => "#dark-mode",
                    "type" => "dark_mode_button",
                    "alignment" => "right",
                    "order" => 15,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "login_button",
                    "parent_id" => 0,
                    "title" => "message.login",
                    "link" => "#login",
                    "type" => "login_button",
                    "alignment" => "right",
                    "order" => 16,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
            );
        } else {
            $data = array(
                array(
                    "menu_item_id" => "home_main",
                    "parent_id" => 0,
                    "title" => "message.home",
                    "link" => $url,
                    "type" => "home_main",
                    "alignment" => "left",
                    "order" => 1,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "all_companies_page",
                    "parent_id" => 0,
                    "title" => "message.companies",
                    "link" => $url."/companies",
                    "type" => "all_companies_page",
                    "alignment" => "left",
                    "order" => 2,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "features",
                    "parent_id" => 0,
                    "title" => "message.features",
                    "link" => $url."/features",
                    "type" => "features",
                    "alignment" => "left",
                    "order" => 3,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "pricing",
                    "parent_id" => 0,
                    "title" => "message.pricing",
                    "link" => $url."/pricing",
                    "type" => "pricing",
                    "alignment" => "left",
                    "order" => 4,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "all_news_page",
                    "parent_id" => 0,
                    "title" => "message.news",
                    "link" => $url."/news",
                    "type" => "all_news_page",
                    "alignment" => "left",
                    "order" => 5,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),                
                array(
                    "menu_item_id" => "select_page",
                    "parent_id" => 0,
                    "title" => "message.information",
                    "link" => "#information",
                    "type" => "select_page",
                    "alignment" => "left",
                    "order" => 6,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "select_page",
                    "parent_id" => 6,
                    "title" => "message.about_us",
                    "link" => $url.'/'."pages/about-us",
                    "type" => "select_page",
                    "alignment" => "left",
                    "order" => 7,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "select_page",
                    "parent_id" => 6,
                    "title" => "message.terms_conditions",
                    "link" => $url.'/'."pages/terms-conditions",
                    "type" => "select_page",
                    "alignment" => "left",
                    "order" => 8,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "select_page",
                    "parent_id" => 6,
                    "title" => "message.privacy_policy",
                    "link" => $url.'/'."pages/privacy-policy",
                    "type" => "select_page",
                    "alignment" => "left",
                    "order" => 9,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "contact",
                    "parent_id" => 0,
                    "title" => "message.contact",
                    "link" => $url."/contact",
                    "type" => "contact",
                    "alignment" => "left",
                    "order" => 10,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),            
                array(
                    "menu_item_id" => "login_button",
                    "parent_id" => 0,
                    "title" => "message.login",
                    "link" => "#login",
                    "type" => "login_button",
                    "alignment" => "right",
                    "order" => 12,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
                array(
                    "menu_item_id" => "dark_mode_button",
                    "parent_id" => 0,
                    "title" => "message.dark_mode_button",
                    "link" => "#dark-mode",
                    "type" => "dark_mode_button",
                    "alignment" => "right",
                    "order" => 13,
                    "created_at" => date('Y-m-d G:i:s'),
                    "updated_at" => date('Y-m-d G:i:s'),
                ),
            );            
        }

        foreach ($data as $d) {
            $result = DB::table('menus')->where(array('link' => $d['link']))->first();
            if (!$result) {
                DB::table('menus')->insert($d);
            }
        }
    }  

    public static function importAdminPermissions()
    {
        $data = array(
            //Dashboard
            array('category' => 'Dashboard', 'title' => 'View Dashboard Stats', 'slug' => 'view_dashboard_stats',),
            array('category' => 'Dashboard', 'title' => 'View Sales chart', 'slug' => 'view_sales_chart',),
            array('category' => 'Dashboard', 'title' => 'View Signups chart', 'slug' => 'view_signups_chart',),
            //Jobs
            array('category' => 'Jobs', 'title' => 'View Jobs', 'slug' => 'view_jobs',),
            array('category' => 'Jobs', 'title' => 'Create Jobs', 'slug' => 'create_jobs',),
            array('category' => 'Jobs', 'title' => 'Edit Jobs', 'slug' => 'edit_jobs',),
            array('category' => 'Jobs', 'title' => 'Delete Jobs', 'slug' => 'delete_jobs',),
            //Quizes
            array('category' => 'Quizes', 'title' => 'View Quizes', 'slug' => 'view_quizes',),
            array('category' => 'Quizes', 'title' => 'Add Quizes', 'slug' => 'create_quizes',),
            array('category' => 'Quizes', 'title' => 'Edit Quizes', 'slug' => 'edit_quizes',),
            array('category' => 'Quizes', 'title' => 'Delete Quizes', 'slug' => 'delete_quizes',),
            //Interviews
            array('category' => 'Interviews', 'title' => 'View Interviews', 'slug' => 'view_interviews',),
            array('category' => 'Interviews', 'title' => 'Add Interviews', 'slug' => 'create_interviews',),
            array('category' => 'Interviews', 'title' => 'Edit Interviews', 'slug' => 'edit_interviews',),
            array('category' => 'Interviews', 'title' => 'Delete Interviews', 'slug' => 'delete_interviews',),
            //Traites
            array('category' => 'Traites', 'title' => 'View Traites', 'slug' => 'view_traites',),
            array('category' => 'Traites', 'title' => 'Create Traites', 'slug' => 'create_traites',),
            array('category' => 'Traites', 'title' => 'Edit Traites', 'slug' => 'edit_traites',),
            array('category' => 'Traites', 'title' => 'Delete Traites', 'slug' => 'delete_traites',),
            //Packages
            array('category' => 'Packages', 'title' => 'View Packages', 'slug' => 'view_packages',),
            array('category' => 'Packages', 'title' => 'Create Packages', 'slug' => 'create_package',),
            array('category' => 'Packages', 'title' => 'Edit Packages', 'slug' => 'edit_package',),
            array('category' => 'Packages', 'title' => 'Delete Packages', 'slug' => 'delete_package',),
            //Memberships
            array('category' => 'Memberships', 'title' => 'View Memberships', 'slug' => 'view_memberships',),
            array('category' => 'Memberships', 'title' => 'Create Memberships', 'slug' => 'create_membership',),
            array('category' => 'Memberships', 'title' => 'Edit Memberships', 'slug' => 'edit_membership',),
            array('category' => 'Memberships', 'title' => 'Delete Memberships', 'slug' => 'delete_membership',),
            //Candidate Packages
            array('category' => 'Candidate Packages', 'title' => 'View Candidate Packages', 'slug' => 'view_candidate_packages',),
            array('category' => 'Candidate Packages', 'title' => 'Create Candidate Packages', 'slug' => 'create_candidate_package',),
            array('category' => 'Candidate Packages', 'title' => 'Edit Candidate Packages', 'slug' => 'edit_candidate_package',),
            array('category' => 'Candidate Packages', 'title' => 'Delete Candidate Packages', 'slug' => 'delete_candidate_package',),
            //Candidate Memberships
            array('category' => 'Candidate Memberships', 'title' => 'View Candidate Memberships', 'slug' => 'view_candidate_memberships',),
            array('category' => 'Candidate Memberships', 'title' => 'Create Candidate Memberships', 'slug' => 'create_candidate_membership',),
            array('category' => 'Candidate Memberships', 'title' => 'Edit Candidate Memberships', 'slug' => 'edit_candidate_membership',),
            array('category' => 'Candidate Memberships', 'title' => 'Delete Candidate Memberships', 'slug' => 'delete_candidate_membership',),
            //Testimonials
            array('category' => 'Testimonials', 'title' => 'View Testimonials', 'slug' => 'view_testimonials',),
            array('category' => 'Testimonials', 'title' => 'Create Testimonials', 'slug' => 'create_testimonial',),
            array('category' => 'Testimonials', 'title' => 'Edit Testimonials', 'slug' => 'edit_testimonial',),
            array('category' => 'Testimonials', 'title' => 'Delete Testimonials', 'slug' => 'delete_testimonial',),
            //Pages
            array('category' => 'Pages', 'title' => 'View Pages', 'slug' => 'view_pages',),
            array('category' => 'Pages', 'title' => 'Create Pages', 'slug' => 'create_page',),
            array('category' => 'Pages', 'title' => 'Edit Pages', 'slug' => 'edit_page',),
            array('category' => 'Pages', 'title' => 'Delete Pages', 'slug' => 'delete_page',),
            //Users
            array('category' => 'Users', 'title' => 'View Users Listing', 'slug' => 'view_user_listing',),
            array('category' => 'Users', 'title' => 'Add Users', 'slug' => 'create_user',),
            array('category' => 'Users', 'title' => 'Edit Users', 'slug' => 'edit_user',),
            array('category' => 'Users', 'title' => 'Delete Users', 'slug' => 'delete_user',),
            array('category' => 'Users', 'title' => 'View Roles', 'slug' => 'view_roles',),
            array('category' => 'Users', 'title' => 'Add Role', 'slug' => 'create_role',),
            array('category' => 'Users', 'title' => 'Edit Role', 'slug' => 'edit_role',),
            array('category' => 'Users', 'title' => 'Delete Role', 'slug' => 'delete_role',),
            //Candidates
            array('category' => 'Candidates', 'title' => 'View Candidate Listing', 'slug' => 'view_candidate_listing',),
            array('category' => 'Candidates', 'title' => 'Add Candidate', 'slug' => 'create_candidate',),
            array('category' => 'Candidates', 'title' => 'Edit Candidate', 'slug' => 'edit_candidate',),
            array('category' => 'Candidates', 'title' => 'Delete Candidate', 'slug' => 'delete_candidate',),
            array('category' => 'Candidates', 'title' => 'Login as Candidate', 'slug' => 'login_as_candidate',),
            //Employers
            array('category' => 'Employers', 'title' => 'View Employer Listing', 'slug' => 'view_employer_listing',),
            array('category' => 'Employers', 'title' => 'Add Employer', 'slug' => 'create_employer',),
            array('category' => 'Employers', 'title' => 'Edit Employer', 'slug' => 'edit_employer',),
            array('category' => 'Employers', 'title' => 'Delete Employer', 'slug' => 'delete_employer',),
            array('category' => 'Employers', 'title' => 'Login as Employer', 'slug' => 'login_as_employer',),
            //News
            array('category' => 'News', 'title' => 'View News Listing', 'slug' => 'view_news_listing',),
            array('category' => 'News', 'title' => 'Add News', 'slug' => 'create_news',),
            array('category' => 'News', 'title' => 'Edit News', 'slug' => 'edit_news',),
            array('category' => 'News', 'title' => 'Delete News', 'slug' => 'delete_news',),
            array('category' => 'News', 'title' => 'View News Categories', 'slug' => 'view_news_categories',),
            array('category' => 'News', 'title' => 'Add News Categories', 'slug' => 'create_news_categories',),
            array('category' => 'News', 'title' => 'Edit News Categories', 'slug' => 'edit_news_categories',),
            array('category' => 'News', 'title' => 'Delete News Categories', 'slug' => 'delete_news_categories',),
            //Faqs
            array('category' => 'News', 'title' => 'View Faqs Listing', 'slug' => 'view_faqs_listing',),
            array('category' => 'Faqs', 'title' => 'Add Faqs', 'slug' => 'create_faqs',),
            array('category' => 'Faqs', 'title' => 'Edit Faqs', 'slug' => 'edit_faqs',),
            array('category' => 'Faqs', 'title' => 'Delete Faqs', 'slug' => 'delete_faqs',),
            array('category' => 'Faqs', 'title' => 'View Faqs Categories', 'slug' => 'view_faqs_categories',),
            array('category' => 'Faqs', 'title' => 'Add Faqs Categories', 'slug' => 'create_faqs_categories',),
            array('category' => 'Faqs', 'title' => 'Edit Faqs Categories', 'slug' => 'edit_faqs_categories',),
            array('category' => 'Faqs', 'title' => 'Delete Faqs Categories', 'slug' => 'delete_faqs_categories',),
            //Departments
            array('category' => 'Departments (Admin)', 'title' => 'View Departments (admin)', 'slug' => 'admin_view_departments',),
            array('category' => 'Departments (Admin)', 'title' => 'Create Departments (admin)', 'slug' => 'admin_create_departments',),
            array('category' => 'Departments (Admin)', 'title' => 'Edit Departments (admin)', 'slug' => 'admin_edit_departments',),
            array('category' => 'Departments (Admin)', 'title' => 'Delete Departments (admin)', 'slug' => 'admin_delete_departments',),
            //Job Filters
            array('category' => 'Job Filters (Admin)', 'title' => 'View Job Filters', 'slug' => 'admin_view_job_filters',),
            array('category' => 'Job Filters (Admin)', 'title' => 'Create Job Filters', 'slug' => 'admin_create_job_filters',),
            array('category' => 'Job Filters (Admin)', 'title' => 'Edit Job Filters', 'slug' => 'admin_edit_job_filters',),
            array('category' => 'Job Filters (Admin)', 'title' => 'Delete Job Filters', 'slug' => 'admin_delete_job_filters',),            
            //CMS & Settings
            array('category' => 'CMS', 'title' => 'Home Page', 'slug' => 'home_page_settings',),
            array('category' => 'CMS', 'title' => 'Menu', 'slug' => 'menu_settings',),
            array('category' => 'CMS', 'title' => 'View Messages Listing', 'slug' => 'view_messages_listing',),
            array('category' => 'Settings', 'title' => 'Job Portal Vs SaaS', 'slug' => 'portal_vs_multitenancy',),
            array('category' => 'Settings', 'title' => 'General', 'slug' => 'general_settings',),
            array('category' => 'Settings', 'title' => 'General Images', 'slug' => 'general_images_settings',),
            array('category' => 'Settings', 'title' => 'Display', 'slug' => 'display_settings',),
            array('category' => 'Settings', 'title' => 'Emails', 'slug' => 'email_settings',),
            array('category' => 'Settings', 'title' => 'Email Templates', 'slug' => 'email_template_settings',),
            array('category' => 'Settings', 'title' => 'Apis', 'slug' => 'apis_settings',),
            array('category' => 'Settings', 'title' => 'Css', 'slug' => 'css_settings',),
            array('category' => 'Settings', 'title' => 'Languages', 'slug' => 'languages_settings',),
            array('category' => 'Settings', 'title' => 'Roles', 'slug' => 'roles_settings',),
            array('category' => 'Settings', 'title' => 'Refresh Memberships', 'slug' => 'refresh_memberships',),
            array('category' => 'Settings', 'title' => 'Employer Overrides', 'slug' => 'employer_override_settings',),
        );
        foreach ($data as $d) {
            $d['type'] = 'admin';
            $result = DB::table('permissions')->where(array('slug' => $d['slug'], 'type' => 'admin'))->first();
            if (!$result) {
                DB::table('permissions')->insert($d);
            }
        }
    }

    public static function importEmployerPermissions()
    {
        $data = array(
            //Dashboard
            array('category' => 'Dashboard', 'title' => 'View Dashboard Stats', 'slug' => 'view_dashboard_stats',),
            array('category' => 'Dashboard', 'title' => 'View Job chart', 'slug' => 'view_job_chart',),
            array('category' => 'Dashboard', 'title' => 'View Candidate chart', 'slug' => 'view_candidate_chart',),
            array('category' => 'Dashboard', 'title' => 'View Jobs Status', 'slug' => 'view_jobs_status',),
            array('category' => 'Dashboard', 'title' => 'To Do List', 'slug' => 'to_do_list',),
            //Job Board
            array('category' => 'Job Board', 'title' => 'View Job Board', 'slug' => 'view_job_board',),
            array('category' => 'Job Board', 'title' => 'Actions Job Board', 'slug' => 'actions_job_board',),
            //Interviews
            array('category' => 'Interviews', 'title' => 'View & Conduct Interviews', 'slug' => 'view_conduct_interviews',),
            //Jobs
            array('category' => 'Jobs', 'title' => 'View Jobs', 'slug' => 'view_jobs',),
            array('category' => 'Jobs', 'title' => 'Create Jobs', 'slug' => 'create_jobs',),
            array('category' => 'Jobs', 'title' => 'Edit Jobs', 'slug' => 'edit_jobs',),
            array('category' => 'Jobs', 'title' => 'Delete Jobs', 'slug' => 'delete_jobs',),
            //Job Filters
            array('category' => 'Job Filters', 'title' => 'View Job Filters', 'slug' => 'view_job_filters',),
            array('category' => 'Job Filters', 'title' => 'Create Job Filters', 'slug' => 'create_job_filters',),
            array('category' => 'Job Filters', 'title' => 'Edit Job Filters', 'slug' => 'edit_job_filters',),
            array('category' => 'Job Filters', 'title' => 'Delete Job Filters', 'slug' => 'delete_job_filters',),
            //Departments
            array('category' => 'Departments', 'title' => 'View Departments', 'slug' => 'view_departments',),
            array('category' => 'Departments', 'title' => 'Create Departments', 'slug' => 'create_departments',),
            array('category' => 'Departments', 'title' => 'Edit Departments', 'slug' => 'edit_departments',),
            array('category' => 'Departments', 'title' => 'Delete Departments', 'slug' => 'delete_departments',),
            //Quizes
            array('category' => 'Quizes', 'title' => 'View Quiz Questions', 'slug' => 'view_quiz_questions',),
            array('category' => 'Quizes', 'title' => 'Edit Quiz Questions', 'slug' => 'edit_quiz_questions',),
            array('category' => 'Quizes', 'title' => 'Delete Quiz Questions', 'slug' => 'delete_quiz_questions',),
            array('category' => 'Quizes', 'title' => 'View Quizes', 'slug' => 'view_quizes',),
            array('category' => 'Quizes', 'title' => 'Add Quizes', 'slug' => 'create_quizes',),
            array('category' => 'Quizes', 'title' => 'Edit Quizes', 'slug' => 'edit_quizes',),
            array('category' => 'Quizes', 'title' => 'Delete Quizes', 'slug' => 'delete_quizes',),
            array('category' => 'Quizes', 'title' => 'Clone Quizes', 'slug' => 'clone_quizes',),
            array('category' => 'Quizes', 'title' => 'Download Quizes', 'slug' => 'download_quizes',),
            //Interviews
            array('category' => 'Interviews', 'title' => 'View Interview Questions', 'slug' => 'view_interview_questions',),
            array('category' => 'Interviews', 'title' => 'Edit Interview Questions', 'slug' => 'edit_interview_questions',),
            array('category' => 'Interviews', 'title' => 'Delete Interview Questions', 'slug' => 'delete_interview_questions',),
            array('category' => 'Interviews', 'title' => 'View Interviews', 'slug' => 'view_interviews',),
            array('category' => 'Interviews', 'title' => 'Add Interviews', 'slug' => 'create_interviews',),
            array('category' => 'Interviews', 'title' => 'Edit Interviews', 'slug' => 'edit_interviews',),
            array('category' => 'Interviews', 'title' => 'Delete Interviews', 'slug' => 'delete_interviews',),
            array('category' => 'Interviews', 'title' => 'Clone Interviews', 'slug' => 'clone_interviews',),
            array('category' => 'Interviews', 'title' => 'Download Interviews', 'slug' => 'download_interviews',),
            array('category' => 'Interviews', 'title' => 'All Candidate Interviews', 'slug' => 'all_candidate_interviews',),
            //Traits
            array('category' => 'Traites', 'title' => 'View Traites', 'slug' => 'view_traites',),
            array('category' => 'Traites', 'title' => 'Create Traites', 'slug' => 'create_traites',),
            array('category' => 'Traites', 'title' => 'Edit Traites', 'slug' => 'edit_traites',),
            array('category' => 'Traites', 'title' => 'Delete Traites', 'slug' => 'delete_traites',),
            //Question Categories
            array('category' => 'Question Categories', 'title' => 'View Question Categories', 'slug' => 'view_question_categories',),
            array('category' => 'Question Categories', 'title' => 'Create Question Categories', 'slug' => 'create_question_categories',),
            array('category' => 'Question Categories', 'title' => 'Edit Question Categories', 'slug' => 'edit_question_categories',),
            array('category' => 'Question Categories', 'title' => 'Delete Question Categories', 'slug' => 'delete_question_categories',),
            //Quiz Categories
            array('category' => 'Quiz Categories', 'title' => 'View Quiz Categories', 'slug' => 'view_quiz_categories',),
            array('category' => 'Quiz Categories', 'title' => 'Create Quiz Categories', 'slug' => 'create_quiz_categories',),
            array('category' => 'Quiz Categories', 'title' => 'Edit Quiz Categories', 'slug' => 'edit_quiz_categories',),
            array('category' => 'Quiz Categories', 'title' => 'Delete Quiz Categories', 'slug' => 'delete_quiz_categories',),
            //Interview Categories
            array('category' => 'Interview Categories', 'title' => 'View Interview Categories', 'slug' => 'view_interview_categories',),
            array('category' => 'Interview Categories', 'title' => 'Create Interview Categories', 'slug' => 'create_interview_categories',),
            array('category' => 'Interview Categories', 'title' => 'Edit Interview Categories', 'slug' => 'edit_interview_categories',),
            array('category' => 'Interview Categories', 'title' => 'Delete Interview Categories', 'slug' => 'delete_interview_categories',),
            //Questions
            array('category' => 'Questions', 'title' => 'View Questions', 'slug' => 'view_questions',),
            array('category' => 'Questions', 'title' => 'Create Questions', 'slug' => 'create_questions',),
            array('category' => 'Questions', 'title' => 'Edit Questions', 'slug' => 'edit_questions',),
            array('category' => 'Questions', 'title' => 'Delete Questions', 'slug' => 'delete_questions',),
            //Team
            array('category' => 'Team', 'title' => 'View Team Listing', 'slug' => 'view_team_listing',),
            array('category' => 'Team', 'title' => 'Add Team Member', 'slug' => 'create_team_member',),
            array('category' => 'Team', 'title' => 'Edit Team Member', 'slug' => 'edit_team_member',),
            array('category' => 'Team', 'title' => 'Delete Team Member', 'slug' => 'delete_team_member',),
            array('category' => 'Team', 'title' => 'View Roles', 'slug' => 'view_roles',),
            array('category' => 'Team', 'title' => 'Add Role', 'slug' => 'create_role',),
            array('category' => 'Team', 'title' => 'Edit Role', 'slug' => 'edit_role',),
            array('category' => 'Team', 'title' => 'Delete Role', 'slug' => 'delete_role',),
            //Candidates
            array('category' => 'Candidates', 'title' => 'View Candidate Listing', 'slug' => 'view_candidate_listing',),
            array('category' => 'Candidates', 'title' => 'Email Candidates', 'slug' => 'email_candidates',),
            //Blog
            array('category' => 'Blog', 'title' => 'View Blog Listing', 'slug' => 'view_blog_listing',),
            array('category' => 'Blog', 'title' => 'Add Blog', 'slug' => 'create_blog',),
            array('category' => 'Blog', 'title' => 'Edit Blog', 'slug' => 'edit_blog',),
            array('category' => 'Blog', 'title' => 'Delete Blog', 'slug' => 'delete_blog',),
            array('category' => 'Blog', 'title' => 'View Blog Categories', 'slug' => 'view_blog_categories',),
            array('category' => 'Blog', 'title' => 'Add Blog Categories', 'slug' => 'create_blog_categories',),
            array('category' => 'Blog', 'title' => 'Edit Blog Categories', 'slug' => 'edit_blog_categories',),
            array('category' => 'Blog', 'title' => 'Delete Blog Categories', 'slug' => 'delete_blog_categories',),
            //Memberships
            array('category' => 'Memberships', 'title' => 'View Memberships', 'slug' => 'view_memberships',),
            array('category' => 'Memberships', 'title' => 'Create Memberships', 'slug' => 'create_memberships',),
            //Settings
            array('category' => 'Settings', 'title' => 'General', 'slug' => 'general',),
            array('category' => 'Settings', 'title' => 'Branding', 'slug' => 'branding',),
            array('category' => 'Settings', 'title' => 'Emails', 'slug' => 'emails',),
            array('category' => 'Settings', 'title' => 'Css', 'slug' => 'css',),
        );
        foreach ($data as $d) {
            $d['type'] = 'employer';
            $result = DB::table('permissions')->where(array('slug' => $d['slug'], 'type' => 'employer'))->first();
            if (!$result) {
                DB::table('permissions')->insert($d);
            }
        }        
    }

    private static function importLanguagesData()
    {
        $dates = array('created_at' => date('Y-m-d G:i:s'), 'updated_at' => date('Y-m-d G:i:s'));
        $data = array(
            array('title' => 'Origin', 'slug' => 'origin', 'status' => 1, 'is_selected' => 0, 'is_default' => 0, 'is_main' => 1),
            array('title' => 'English', 'slug' => 'english', 'status' => 1, 'is_selected' => 1, 'is_default' => 1, 'flag' => 'gb', 'display' => 'both', 'direction' => 'ltr'),
            array('title' => 'French', 'slug' => 'french', 'status' => 1, 'is_selected' => 1, 'is_default' => 0, 'flag' => 'fr', 'display' => 'both', 'direction' => 'ltr'),
            array('title' => 'Arabic', 'slug' => 'arabic', 'status' => 1, 'is_selected' => 1, 'is_default' => 0, 'flag' => 'sa', 'display' => 'both', 'direction' => 'rtl'),
        );
        foreach ($data as $d) {
            $result = DB::table('languages')->where(array('slug' => $d['slug']))->first();
            if (!$result) {
                DB::table('languages')->insert(array_merge($d, $dates));
            }
        }
    }    
}
    