@extends('essentials.layout')
@section('content')
<section id="contain">
    <div class="install-block-lp">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 install-block-in-lp">
                    <div class="install-box-lp">
                        <div class="install-box-in-lp">
                            <form id="database_form" class="database-form">
                                <div class="install-title-lp requirement-heading">Database Credentials</div>
                                <div id="message_container"></div>
                                <div class="requirement-label">
                                    <div class="form-group">
                                        <label for="host">DB Host</label>
                                        <input type="text" name="db_host" class="form-control" placeholder="e.g. localhost or 127.0.0.1" />
                                    </div>
                                    <div class="form-group">
                                        <label for="host">Database Name</label>
                                        <input type="text" name="db_name" class="form-control" placeholder="e.g. storyspot_db" />
                                    </div>
                                    <div class="form-group">
                                        <label for="host">Database User</label>
                                        <input type="text" name="db_user" class="form-control" placeholder="e.g. storyspot_admin" />
                                    </div>
                                    <div class="form-group">
                                        <label for="host">Database Password</label>
                                        <input type="password" name="db_password" class="form-control" placeholder="e.g. jVbtgp9Q7R" />
                                    </div>
                                    <div class="form-group">
                                        <label for="host">Database Prefix</label>
                                        <input type="text" name="db_prefix" class="form-control" placeholder="cf_" />
                                    </div>
                                    <div class="form-group">
                                        <label for="host">Database Type/Driver</label>
                                        <select class="form-control" name="db_type">
                                            <option value="mysql">MYSQL</option>
                                            <option value="sqlite">SQLite</option>
                                            <option value="pgsql">PostgreSQL</option>
                                            <option value="sqlsrv">SQL Server</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="install-btn-lp">
                                    <button type="submit" class="btn-common" id="database_form_button">Create Database & Proceed to admin credentials</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</section>
@endsection
@section('page-scripts')
<script src="{{ base_url(true) }}/g-assets/essentials/js/general.js"></script>
@endsection