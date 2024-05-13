<?php

if(!function_exists('objToArr')) {
function objToArr($obj) {
    return json_decode(json_encode($obj), true);
}
}

if(!function_exists('dd2')) {
function dd2($data) {
    if (is_array($data)) {
        $data2 = array();
        foreach ($data as $key => $value) {
            $data2[] = $value;
        }
        $data3 = $data2;
    } else {
        $data3 = $data;
    }
    echo "<pre>";
    print_r($data3);
    exit;
}
}

if(!function_exists('appId')) {
function appId() {
    return 'f89c848fa';
}
}

if(!function_exists('makePassword')) {
function makePassword($password)
{
    return md5($password).appId();
}
}

if(!function_exists('keyedArray')) {
function keyedArray($array) {
    $return = array();
    foreach ($array as $v) {
        $return[$v] = '';
    }
    return $return;
}
}

if(!function_exists('sel')) {
function sel($column, $value, $text = '') {
    if (is_array($value)) {
        echo in_array($column, $value) ? 'selected' : '';
    } else {
        echo strtolower($column) == strtolower($value) ? ($text ? $text : "selected") : '';
    }
}
}

if(!function_exists('sel2')) {
function sel2($job_filter_id, $job_filter_ids, $job_filter_value_id, $job_filter_value_ids) {
    if (in_array($job_filter_id, explode(',', $job_filter_ids))) {
        if (in_array($job_filter_value_id, explode(',', $job_filter_value_ids))) {
            return 'selected';
        }
    }
}
}

if(!function_exists('sel3')) {
function sel3($job_filter_id, $job_filter_value_id, $filtersSel) {
    $selected_filter_ids = array_keys($filtersSel);
    foreach ($filtersSel as $filter_id => $job_filter_value_ids) {
        if (in_array($job_filter_id, $selected_filter_ids)) {
            if (in_array($job_filter_value_id, $filtersSel[$job_filter_id])) {
                return 'checked="checked"';
            }
        }
    }
}
}

if(!function_exists('selMenu')) {
function selMenu($column, $value) {
    $column = strtolower($column);
    if (is_array($value)) {
        echo in_array($column, $value) ? 'active' : '';
    } else {
        echo $column == strtolower($value) ? 'class="active"' : '';
    }
}
}

if(!function_exists('selCb')) {
function selCb($column, $value) {
    return strtolower($column) == strtolower($value) ? 'checked' : '';
}
}

if(!function_exists('acActive')) {
function acActive($val1, $val2) {
    return $val1 == $val2 ? 'active' : '';  
}
}

if(!function_exists('acActiveCan')) {
function acActiveCan($val1, $val2) {
    return $val1 == $val2 ? 'class="active"' : '';  
}
}

if(!function_exists('treeActive')) {
function treeActive($val, $array) {
    return in_array($val, $array) ? 'menu-open' : '';
}
}

if(!function_exists('yesOrNo')) {
function yesOrNo($value, $print = false) {
    if ($print) {
        echo $value == 1 ? __('message.yes') : __('message.no');
    } else {
        return $value == 1 ? __('message.yes') : __('message.no');
    }
}
}

if(!function_exists('makeSlug')) {
function makeSlug($string) {
    return preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($string)));
}
}

if(!function_exists('trimString')) {
function trimString($str, $length = 20, $removeImage = true, $dots = '...') {
    if ($str != '') {
        if ($removeImage == true) {
            $str = preg_replace("/<img[^>]+\>/i", "", $str);
        }
        $str = preg_replace('/<h1[^>]*>([\s\S]*?)<\/h1[^>]*>/', '', $str);
        $str = preg_replace('/<h2[^>]*>([\s\S]*?)<\/h2[^>]*>/', '', $str);
        return (strlen($str) > $length) ? substr($str, 0, $length - strlen($dots)) . $dots : $str;
    } else {
        return '---';
    }
}
}

if(!function_exists('sectionTitle')) {
function sectionTitle($str) {
    if ($str != '') {
        return ucwords($str);
    } else {
        return '---';
    }
}
}

if(!function_exists('hyphenIfNull')) {
function hyphenIfNull($str) {
    if ($str == '') {
        return '---';
    }
}
}

if(!function_exists('mainFrontMenu')) {
function mainFrontMenu($alignment = 'left') {
    return App\Models\Admin\Menu::getAllForMenu($alignment);
}
}

if(!function_exists('adminUnreadMessagesCount')) {
function adminUnreadMessagesCount() {
    $CI = get_instance();
    return $CI->MessageModel->adminUnreadMessagesCount();
}
}

if(!function_exists('adminNotificationsWidget')) {
function adminNotificationsWidget() {
    $notifications = new App\Http\Controllers\Admin\NotificationsController();
    echo $notifications->listWidget();
}
}

if(!function_exists('ratingValue')) {
function ratingValue($column, $value) {
    return $column <= $value ? 'on' : 'off';
}
}

if(!function_exists('homeLink')) {
function homeLink($item) {
    if ($item == 'home_features' || $item == 'home_pricing' ||  $item == 'home_news' ||  
        $item == 'home_portfolio' || $item == 'home_testimonials' ||  $item == 'home_contact') {
        return 'page-scroll';
    } else if ($item == 'register_button') {
        return 'btn header-btn-register main-navbar-btn navbar-main-btn';
    } else if ($item == 'login_button') {
        return 'btn header-btn-login main-navbar-btn navbar-main-btn global-login-btn';
    }
}
}

if(!function_exists('candidateSession')) {
function candidateSession($field = '') {
    $candidate = getSession('candidate');
    if (isset($candidate['candidate_id']) && $field == '') {
        return $candidate['candidate_id'];
    } else if (isset($candidate[$field])) {
        return $candidate[$field];    
    }
    return false;
}
}

if(!function_exists('employerSession')) {
function employerSession($field = '') {
    $employer = getSession('employer');
    if (isset($employer['employer_id']) && $field == '') {
        return $employer['employer_id'];
    } else if (isset($employer[$field])) {
        return $employer[$field];
    }
    return false;
}
}

if(!function_exists('companySession')) {
function companySession($field = '') {
    $company = getSession('company');
    if (isset($company['company_id']) && $field == '') {
        return $company['company_id'];
    } else if (isset($company[$field])) {
        return $company[$field];
    }
    return false;
}
}

if(!function_exists('adminSession')) {
function adminSession($field = '') {
    $admin = getSession('admin');
    if (isset($admin['user_id']) && $field == '') {
        return $admin['user_id'];
    } else if (isset($admin[$field])) {
        return $admin[$field];
    }
}
}

if(!function_exists('allowedTo')) {
function allowedTo($permission = '', $redirect = '') {
    if (adminSession('user_type') == 'admin') { //Enable everything for 
        return true;
    }
    $permissions = App\Helpers\PermissionsHelper::Instance(adminSession());
    $permissions = $permissions ? $permissions : array();
    if (is_array($permission)) {
        foreach ($permission as $value) {
            if (in_array($value, $permissions)) {
                return true;
            }
        }
    } else {
        return in_array($permission, $permissions);    
    }
}
}

if(!function_exists('empAllowedTo')) {
function empAllowedTo($permission = '', $redirect = '') {
    if (employerSession('type') == 'main' || empMembership(employerId(), 'role_permissions') == 0) {
        return true;
    }
    $permissions = App\Helpers\EmpPermissionsHelper::Instance(employerSession());
    if (is_array($permission)) {
        foreach ($permission as $value) {
            if (in_array($value, $permissions)) {
                return true;
            }
        }
    } else {
        return in_array($permission, $permissions);    
    }
}
}

if(!function_exists('getTextFromFile')) {
function getTextFromFile($file) {
    $file = storage_path('/app/'.config('constants.upload_dirs.main').'/dummy-data/'.$file);
    $fh = fopen($file, 'r');
    $pageText = fread($fh, 25000);
    return $pageText;
}
}

if(!function_exists('createDirectoryIfNotExists')) {
function createDirectoryIfNotExists($path) {
    if (!file_exists(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
}
}

if(!function_exists('imageDimensions')) {
function imageDimensions() {
    return array(
        array('1620', '800'),
        array('1070', '604'),
        array('828', '468'),
        array('366', '219'),
        array('360', '220'),
        array('330', '180'),
        array('320', '200'),
        array('180', '160'),
    );
}
}

if(!function_exists('userImageDimensions')) {
function userImageDimensions() {
    return array(
        array('60', '60'),
        array('12', '120'),
    );
}
}

if(!function_exists('membershipgReceiptThumb')) {
function membershipgReceiptThumb($image) {
    $data['error'] = url('/g-assets').'/essentials/images/general-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;
}
}

if(!function_exists('packageThumb')) {
function packageThumb($image) {
    $data['error'] = url('/g-assets').'/essentials/images/general-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;
}
}

if(!function_exists('generalThumb')) {
function generalThumb($image) {
    $data['error'] = url('/g-assets').'/essentials/images/avatar-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;
}
}

if(!function_exists('newsThumb')) {
function newsThumb($image) {
    $data['error'] = url('/g-assets').'/essentials/images/news-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;
}
}

if(!function_exists('userThumb')) {
function userThumb($image) {
    $data['error'] = url('/g-assets').'/essentials/images/avatar-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;
}
}

if(!function_exists('candidateThumb')) {
function candidateThumb($image) {
    if (strpos($image, 'http') !== false) {
        return $image;
    }
    $data['error'] = url('/g-assets').'/essentials/images/avatar-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;
}
}

if(!function_exists('candidateThumbForPdf')) {
function candidateThumbForPdf($image) {
    $data['error'] = url('/g-assets').'/essentials/images/avatar-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;

}
}

if(!function_exists('candidateShow')) {
function candidateShow($candidate, $column) {
    if ($candidate['show_hide_personal_info']) {
        if ($candidate[$column] == 'yes') {
            return true;
        }
        return false;
    }
    return true;
}
}

if(!function_exists('resumeThumb')) {
function resumeThumb($file) {
    return $file ? route('uploads-view', $file) : '';
    return $file ? env('APP_URL').'/uploads'. $file : '';
}
}

if(!function_exists('questionThumb')) {
function questionThumb($image) {
    $data['error'] = url('/g-assets').'/essentials/images/general-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;
}
}

if(!function_exists('employerThumb')) {
function employerThumb($image, $forLogo = false) {
    if (strpos($image, 'http') !== false) {
        return $image;
    }
    $not_found_image = $forLogo ? 'company-not-found.png' : 'avatar-not-found.png';
    $data['error'] = url('/g-assets').'/essentials/images/'.$not_found_image;
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;
}
}

if(!function_exists('companyThumb')) {
    function companyThumb($image, $forLogo = false) {
        if (strpos($image, 'http') !== false) {
            return $image;
        }
        $not_found_image = $forLogo ? 'company-not-found.png' : 'avatar-not-found.png';
        $data['error'] = url('/g-assets').'/essentials/images/'.$not_found_image;
        $data['image'] = $image ? route('uploads-view', $image) : '';
        return $data;
    }
    }

if (!function_exists('candidateOrEmployerThumb')) {
function candidateOrEmployerThumb($type = 'candidate') {
    $image = [];
    if(candidateSession() && $type == 'candidate') {
        $image = candidateThumb(candidateSession('image'));
    } 
    else if(employerSession()) {
        $image = employerThumb(employerSession('image'));
    }
    else if(companySession()) {
        $image = companyThumb(companySession('image'));
    }
    return $image;
}
}

if(!function_exists('departmentThumb')) {
function departmentThumb($image) {
    $data['error'] = url('/g-assets').'/essentials/images/department-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;    
}
}

if(!function_exists('blogThumb')) {
function blogThumb($image) {
    $data['error'] = url('/g-assets').'/essentials/images/department-not-found.png';
    $data['image'] = $image ? route('uploads-view', $image) : '';
    return $data;    
}
}

if (!function_exists('base64UrlEncode')) {
function base64UrlEncode($inputStr) {
    return strtr(base64_encode($inputStr), '+/=', '-_,');
}
}

if (!function_exists('base64UrlDecode')) {
function base64UrlDecode($inputStr) {
    return base64_decode(strtr($inputStr, '-_,', '+/='));
}
}

if(!function_exists('encode')) {
function encode($id, $addSalt = true) {
    if ($id == '') {
        return '';
    }
    if ($addSalt) {
        $id = $id.'((--))'.rand(1,100);
    }
    return encodeDecodeFunction($id, 'e');
}
}

if(!function_exists('decode')) {
function decode($id, $removeSalt = true) {
    if ($removeSalt) {
        $decoded = encodeDecodeFunction($id, 'd');
        $exploded = explode('((--))', $decoded);
        return $exploded[0];
    } else {
        return encodeDecodeFunction($id, 'd');
    }
}
}

if(!function_exists('decodeArray')) {
function decodeArray($array, $removeSalt = true) {
    $array = objToArr($array);
    $decoded = array();
    foreach ($array as $key => $value) {
        $key = decode($key, $removeSalt);
        if (is_array($value)) {
            $decoded[$key] = decodeArray($value, $removeSalt);
        } else {
            if ($value) {
                $decoded[] = decode($value, $removeSalt);
            }
        }
    }
    return $decoded;
}
}

if(!function_exists('encodeDecodeFunction')) {
function encodeDecodeFunction( $string, $action = 'e' ) {
    $secret_key = appId();
    $secret_iv = 'my_simple_secret_iv';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }

    return $output;
}
}

if(!function_exists('timeFormat')) {
function timeFormat($time = '') {
    $format = 'd M, Y h:i A';
    $time = $time != '' ? $time : date('Y-m-d G:i:s');
    return date($format, strtotime($time));
}
}

if(!function_exists('dateFormat')) {
function dateFormat($time = '') {
    $format = 'd M, Y';
    $time = $time != '' ? $time : date('Y-m-d G:i:s');
    return date($format, strtotime($time));
}
}

if(!function_exists('validDate')) {
function validDate($date, $format = 'd M, Y') {
    if (strtotime($date) == false || $date == '' || $date == '0000-00-00 00:00:00' || $date == null) {
        return false;
    }
    return date($format, strtotime($date));
}
}

if(!function_exists('dateOnly')) {
function dateOnly($date) {
    return date('Y-m-d', strtotime($date));
}
}

if(!function_exists('divisibleArray')) {
function divisibleArray($number) {
    if ($number == '3') {
        return array(3,6,9,12,15,18,21,24,27,30);
    } else {
        return array(4,8,12,16,20,24,28,32,36,40);
    }
}
}

if(!function_exists('token')) {
function token() {
    return base64_encode(date('Y-m-d G:i:s')) . appId();
}
}

if (!function_exists('curRand')) {
function curRand() {
    return strtotime(date('Y-m-d G:i:s'));
}
}

if(!function_exists('activeItem')) {
function activeItem($type, $slug)
{
    $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $exploded = explode('/', $path);
    $match = '';
    if (isset($exploded[1]) && isset($exploded[2])) {
        if ($exploded[1] == $type && $exploded[2] == $slug) {
            $match = 'active';
        }
    } elseif (isset($exploded[1]) && !isset($exploded[2]) && $exploded[1] == $slug) {
        $match = 'active';
    }
    echo $match;
}
}

if(!function_exists('getIds')) {
function getIds($array, $key, $string = false) {
    $ids = array();
    foreach ($array as $a) {
        $ids[] = $a[$key];
    }
    return $string ? implode(',', $ids) : $ids;
}
}

if(!function_exists('footer')) {
function footer($column = 'First Column') {
    $controllerInstance = & get_instance();
    return $controllerInstance->footer($column);
}
}

if(!function_exists('defaultColorTheme')) {
function defaultColorTheme() {
    $by_user = getSession('default_color_set_by_user');
    $default_setting = setting('default_front_color_theme');
    return $by_user ? $by_user : $default_setting;
}
}

if(!function_exists('footerColWidth')) {
function footerColWidth() {
    $count = 0;
    if (setting('footer_column_1')) {
        $count++;
    }
    if (setting('footer_column_2')) {
        $count++;
    }
    if (setting('footer_column_3')) {
        $count++;
    }
    if (setting('footer_column_4')) {
        $count++;
    }
    if ($count == 4) {
        return 3;
    } elseif ($count == 3) {
        return 4;
    } elseif ($count == 2) {
        return 6;
    } else {
        return 12;
    }
}
}

if(!function_exists('checkFooterColumns')) {
function checkFooterColumns($data) {
    $count = 0;
    foreach ($data as $k => $d) {
        if (!empty($d)) {
            $count = $count + 1;
        }
    }
    if ($count == 1 || $count == 2) {
        $count = 6;
    } elseif ($count == 3) {
        $count = 4;
    } elseif ($count == 4) {
        $count = 3;
    }
    return $count;
}
}

if(!function_exists('get_client_ip')) {
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
}

if(!function_exists('getClientIpAddress')) {
function getClientIpAddress() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
}

if(!function_exists('hideApiFields')) {
function hideApiFields($type) {
    if (SS_DEMO) {
        $array = array(
            'paypal-email',
            'stripe-key',
            'stripe-secret',
            'google-client-id',
            'google-client-secret',
            'facebook-app-id',
            'facebook-app-secret',
            'sendgrid-username',
            'sendgrid-password',
            'share-script',
            'share-tag'
        );
        if (in_array($type, $array)) {
            return 'password';
        }
    }
    return 'text';
}
}

if(!function_exists('arrangeSections')) {
function arrangeSections($data)
{
    $return = array();
    $keys = array();
    foreach ($data as $key => $value) {
        $keys[] = $key;
    }
    if (is_array(array_values($data)[1])) {
        for ($i=0; $i < count(array_values($data)[1]) ; $i++) { 
            foreach ($keys as $key) {
                $return[$i][$key] = isset($data[$key][$i]) ? $data[$key][$i] : '';
            }
        }        
    }
    return $return;
}
}

if(!function_exists('sortForCSV')) {
function sortForCSV($data)
{
    $return = array();
    $keys = array_keys($data[0]);
    for ($i=0; $i < count($data) ; $i++) { 
        foreach ($keys as $key) {
            $return[$i][] = $data[$i][$key];
        }
    }
    $return = array_merge(array($keys), $return);
    return $return;
}
}

if(!function_exists('jobsCheckboxSel')) {
function jobsCheckboxSel($data, $val) {
    echo in_array($val, $data) ? 'checked ' : '';
}
}

if(!function_exists('jobStatus')) {
function jobStatus($status, $level) {
    $res = '';
    if ($status == 'hired') {
        $res = 'complete';
    } elseif ($status == 'interviewed' && ($level == 1 || $level == 2 || $level == 3)) {
        $res = 'complete';
    } elseif ($status == 'shortlisted' && ($level == 1 || $level == 2)) {
        $res = 'complete';
    } elseif ($status == 'applied' && $level == 1) {
        $res = 'complete';
    } else {
        $res = 'disabled';
    }
    echo $res;
}
}

if(!function_exists('quizTime')) {
function quizTime($from, $to) {
    //Current Time
    $now = date('Y-m-d G:i:s');

    //Max time
    $minutes_to_add = $to;
    $time = new DateTime($from);
    $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
    $max = $time->format('Y-m-d G:i:s');

    //Difference
    $diff = strtotime($max) - strtotime($now);

    return array(
        'now' => $now,
        'max' => $max,
        'diff' => $diff,
        'clock' => gmdate("H:i:s", $diff)
    );
}
}

if(!function_exists('textToImage')) {
function textToImage($txt, $user) {
    $images = '';
    $txt = wordwrap($txt,40,"--(|)--");
    $txts = explode('--(|)--', $txt);
    $rand = strtotime(date('Y-m-d G:i:s'));
    foreach ($txts as $k => $txt) {
        $img = imagecreate(400, 35);
        $textbgcolor = imagecolorallocate($img, 255, 255, 255);
        $textcolor = imagecolorallocate($img, 0, 0, 0);
        $txt = $txt;
        imagestring($img, 10, 10, 10, $txt, $textcolor);
        ob_start();
        imagepng($img);
        $base64 = base64_encode(ob_get_clean());
        $name = ($k+1).'-'.$user.'-question.jpeg';
        $question_path = config('constants.upload_dirs.questions').$name;
        $file = storage_path('/app/'.config('constants.upload_dirs.main').'/'.$question_path);
        $image = base64_to_jpeg($base64, $file);
        $images .= '<img src="'.route('uploads-view', $question_path).'?token='.$rand.'" width="100%"/><br />';
    }
    return $images;
}
}

if(!function_exists('base64_to_jpeg')) {
function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen( $output_file, 'wb' ); 
    fwrite($ifp, base64_decode($base64_string));
    fclose($ifp);
    return $output_file; 
}
}

if(!function_exists('getMonthsBetweenDates')) {
function getMonthsBetweenDates($date1, $date2) {
    $ts1 = strtotime($date1);
    $ts2 = strtotime($date2);
    $year1 = date('Y', $ts1);
    $year2 = date('Y', $ts2);
    $month1 = date('m', $ts1);
    $month2 = date('m', $ts2);
    $diff = (($year2 - $year1) * 12) + ($month2 - $month1);    
    return $diff;
}
}

if(!function_exists('getNextFiveYears')) {
function getNextFiveYears($optionList = true) {
    $years = array();
    $options = '';
    for ($i=0; $i <= 5; $i++) { 
        $i2 = '+'.$i.' year';
        $year = date('Y', strtotime($i2));
        $years[] = $year;
        $options .= '<option value="'.$year.'">'.$year.'</option>';
    }
    return $optionList ? $options : $years;
}
}

if(!function_exists('getExprienceInMonths')) {
function getExprienceInMonths($data) {
    $experience = 0;
    foreach ($data as $key => $value) {
        $experience = $experience + getMonthsBetweenDates($value['from'], $value['to']) + 1;
    }
    return $experience;
}
}

if(!function_exists('checkQuizCorrect')) {
function checkQuizCorrect($answer, $original, $type) {
    if ($type == 'radio') {
      return $answer == $original ? 'answer' : '';
    } else {
      if (is_array($answer)) {
        foreach ($answer as $value) {
          if ($value == $original) {
            return 'answer';
          }
        }
      }
    }
}
}

if(!function_exists('columnCount')) {
function columnCount($columns) {
    $count = count($columns);
    if ($count == 4) {
        return 3;
    } else if ($count == 3) {
        return 4;
    } else if ($count == 2) {
        return 6;
    } else if ($count == 1) {
        return 12;
    }
}
}

if(!function_exists('footerColumns')) {
function footerColumns() {
    $count = 0;
    $data = array(
        'col_1' => settingEmpSlug('footer_col_1'),
        'col_2' => settingEmpSlug('footer_col_2'),
        'col_3' => settingEmpSlug('footer_col_3'),
        'col_4' => settingEmpSlug('footer_col_4'),
    );
    if ($data['col_1']) {
        $count++;
    }
    if ($data['col_2']) {
        $count++;
    }
    if ($data['col_3']) {
        $count++;
    }
    if ($data['col_4']) {
        $count++;
    }
    if ($count == 4) {
        $count = 3;
    } elseif ($count == 3) {
        $count = 4;
    } elseif ($count == 2) {
        $count = 6;
    } elseif ($count == 1) {
        $count = 12;
    }
    $footer['columns'] = $data;
    $footer['column_count'] = $count;
    return $footer;
}
}

if(!function_exists('arrayToString')) {
function arrayToString($array, $slug = '', $type = '') {
    $addSlug = env('ADD_SLUG_IN_TRANSLATIONS') == 'true' ? true : false;
    $lang = '<?php '.PHP_EOL.PHP_EOL;
    foreach ($array as $key => $value) {
        $value = $slug && $addSlug ? $value.' ('.$slug.')' : $value;
        if ($type == 'validation') {
            $lang .= '$langValidation["'.$key.'"] = "'.htmlspecialchars($value).'";'.PHP_EOL;
        } else {
            $lang .= '$lang["'.$key.'"] = "'.htmlspecialchars($value).'";'.PHP_EOL;
        }
    }
    if ($type == 'validation') {
        $lang .= PHP_EOL.'return $langValidation;';
    } else {
        $lang .= PHP_EOL.'return $lang;';
    }
    return $lang;
}
}

if(!function_exists('arrayToStringJs')) {
function arrayToStringJs($array) {
    $jsVars = array(
        "candidates",
        "click_to_activate",
        "click_to_deactivate",
        "are_u_sure",
        "please_select_some_records_first",
        "edit_blog_category",
        "create_blog_category",
        "candidate_interview",
        "edit_company",
        "create_company",
        "edit_to_do_item",
        "create_to_do_item",
        "edit_department",
        "create_department",
        "edit_interview",
        "create_interview",
        "clone_interview",
        "edit_interview_category",
        "create_interview_category",
        "edit_interview_question",
        "create_interview_question",
        "create_language",
        "edit_question",
        "create_question",
        "change_to_multi_correct",
        "change_to_single_correct",
        "edit_question_category",
        "create_question_category",
        "edit_quiz",
        "create_quiz",
        "clone_quiz",
        "edit_quiz_category",
        "create_quiz_category",
        "edit_quiz_question",
        "create_quiz_question",
        "edit_traite",
        "create_traite",
        "edit_user",
        "create_user",
        "edit_team",
        "create_team",
        "edit_candidate",
        "create_candidate",
        "edit_employer",
        "create_employer",
        "edit_role",
        "create_role",
        "edit_package",
        "create_package",
        "edit_candidate_package",
        "create_candidate_package",
        "edit_news",
        "create_news",
        "edit_news_category",
        "create_news_category",
        "edit_faqs",
        "create_faqs",
        "edit_faqs_category",
        "create_faqs_category",
        "edit_membership",
        "create_membership",
        "edit_candidate_membership",
        "create_candidate_membership",
        "edit_candidate_membership",
        "create_candidate_membership",
        "edit_testimonial",
        "create_testimonial",
        "edit_page",
        "create_page",
        "edit",
        "create",
        "renew_membership",
        "edit_language",
        "mark_favorite",
        "unmark_favorite",
        "refer_this_job",
        "inactive",
        "active",
        "no",
        "yes",
        "login",
        "register",
        "forgot_password",
        "update",
        "selected",
        "non_selected",
        "only_1_candidate_allowed",
        "only_3_candidates_allowed",
        "only_5_candidates_allowed",
        "only_10_candidates_allowed",
        "associated_data_msg",
    );
    $lang = 'var lang = []; '.PHP_EOL.PHP_EOL;
    foreach ($array as $key => $value) {
        if (in_array($key, $jsVars)) {
            $lang .= 'lang["'.$key.'"] = "'.htmlspecialchars($value).'";'.PHP_EOL;
        }
    }
    return $lang;
}
}

if(!function_exists('esc_output')) {
function esc_output($string, $type = 'attr') {
    if ($type == 'raw') {
        return $string;
    }
    return html_escape($string);
}
}

if(!function_exists('remoteRequest')) {
function remoteRequest($url = '') {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
}

if(!function_exists('createFile')) {
function createFile($file, $data) {
    try {
        $file = MAIN_ROOT.'/'.$file;
        $file = fopen($file, "w");
        fwrite($file, $data);
        fclose($file);
        return 'success';
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
}

if(!function_exists('writeToFile')) {
function writeToFile($filePath, $content) {
    $myfile = fopen($filePath, "w") or die("Unable to open file!");
    fwrite($myfile, $content);
    fclose($myfile);
}
}

if(!function_exists('cf_print')) {
function cf_print($string) {
    //These are system generated safe strings.
    echo $string;
}
}

if(!function_exists('combinationsOfArray')) {
function combinationsOfArray($chars, $size, $combinations = array()) {
    if (empty($combinations)) {
        $combinations = $chars;
    }
    if ($size == 1) {
        return $combinations;
    }
    $new_combinations = array();
    foreach ($combinations as $combination) {
        foreach ($chars as $char) {
            $new_combinations[] = $combination .','. $char;
        }
    }
    return combinationsOfArray($chars, $size - 1, $new_combinations);
}
}

if(!function_exists('permutationsOfArray')) {
function permutationsOfArray($InArray, $InProcessedArray = array()) {
    $ReturnArray = array();
    foreach ($InArray as $Key=>$value) {
        $CopyArray = $InProcessedArray;
        $CopyArray[$Key] = $value;
        $TempArray = array_diff_key($InArray, $CopyArray);
        if (count($TempArray) == 0) {
            $ReturnArray[] = implode(',',$CopyArray);
        } else {
            $ReturnArray = array_merge($ReturnArray, permutationsOfArray($TempArray, $CopyArray));
        }
    }
    return $ReturnArray;
}
}

if(!function_exists('removeUselessLineBreaks')) {
function removeUselessLineBreaks($string) {
    $string = htmlentities($string, null, 'utf-8');
    $string = str_replace("&nbsp;", " ", $string);
    $string = html_entity_decode($string);
    return $string;
}
}

if(!function_exists('setSession')) {
function setSession($name, $value) {
    Session::put(array($name => $value));;
}
}

if(!function_exists('getSession')) {
function getSession($name) {
    return Session::get($name);
}
}

if(!function_exists('removeSession')) {
function removeSession($name) {
    Session::forget($name);
}
}

if (!function_exists('slugify')) {
function slugify($text = '') {
    if ($text == '') {
        return strtotime(date('Y-m-d G:i:s'));
    }

    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}
}

if (!function_exists('html_escape'))
{
function html_escape($var, $double_encode = TRUE, $charset = 'UTF-8')
{
    if (empty($var)) {
        return $var;
    }
    if (is_array($var)) {
        foreach (array_keys($var) as $key) {
            $var[$key] = html_escape($var[$key], $double_encode);
        }
        return $var;
    }
    return htmlspecialchars($var, ENT_QUOTES, $charset, $double_encode);
}
}

if (!function_exists('packageItem'))
{
function packageItem($title, $quantity, $trueType = '')
{
    if ($trueType && $quantity === '1') {
        return $title.' : '.__('message.yes');
    } elseif ($trueType && $quantity === '0') {
        return $title.' : '.__('message.no');
    }

    if ($quantity === -1) {
        return __('message.unlimited').' '.$title;
    } elseif ($quantity === 0) {
        return __('message.no').' '.$title;
    } else {
        return $quantity.' '.$title;
    }
}
}

if (!function_exists('packageItemBulletOld'))
{
function packageItemBulletOld($quantity, $trueType = '')
{
    if ($trueType && $quantity === 1) {
        return 'pricing-package-check fas fa-check-circle';
    } elseif ($trueType && $quantity === 0) {
        return 'fas fa-times-circle';
    }

    if ($quantity === -1) {
        return 'pricing-package-check fas fa-check-circle';
    } elseif ($quantity === 0) {
        return 'fas fa-times-circle';
    } else {
        return 'pricing-package-check fas fa-check-circle';
    }
}
}

if (!function_exists('packageItemBullet'))
{
function packageItemBullet($quantity, $trueType = '')
{
    if ($trueType && $quantity === '1') {
        return 'section-pricing-item-list-check fa fa-check';
    } elseif ($trueType && $quantity === '0') {
        return 'section-pricing-item-list-x fa fa-x';
    }

    if ($quantity === -1) {
        return 'section-pricing-item-list-check fa fa-check';
    } elseif ($quantity === 0) {
        return 'section-pricing-item-list-x fa fa-x';
    } else {
        return 'section-pricing-item-list-check fa fa-check';
    }
}
}


if (!function_exists('packageItemBulletEmp'))
{
function packageItemBulletEmp($quantity, $trueType = '')
{
    if ($trueType && $quantity === 1) {
        return 'fas fa-check-circle bg-green';
    } elseif ($trueType && $quantity === 0) {
        return 'fas fa-times-circle bg-red';
    }

    if ($quantity === -1) {
        return 'fas fa-check-circle bg-green';
    } elseif ($quantity === 0) {
        return 'fas fa-times-circle bg-red';
    } else {
        return 'fas fa-check-circle bg-green';
    }
}
}

if (!function_exists('notificationItemIcon'))
{
function notificationItemIcon($type)
{
    if ($type == 'message') {
        return 'fas fa-envelope-square';
    } elseif ($type == 'employer_signup') {
        return 'fas fa-user-tie';
    } elseif ($type == 'candidate_signup') {
        return 'fas fa-user-graduate';
    } elseif ($type == 'membership') {
        return 'fas fa-id-card-alt';
    }
}
}

if (!function_exists('timeAgoByTimeStamp'))
{
function timeAgoByTimeStamp($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) {
        $string = array_slice($string, 0, 1);
    }
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
}

if (!function_exists('dateInDays'))
{
function dateInDays($date) {
    $now = time();
    $date = strtotime($date);
    $datediff = $date - $now;
    return round($datediff / (60 * 60 * 24));
}
}

if (!function_exists('reservedWord'))
{
function reservedWord($word)
{
    $words = array(
        '',
        'register',
        'employer-register-free',
        'register-paid',
        'candidate-register',
        'companies',
        'news',
        'pages',
        'contact-form-submit',
        'schema',
        'data',
        'refresh-memberships',
        'ckeditor',
        'login',
        'post-login',
        'login-post',
        'logout',
        'jobs',
        'job',
        'mark-favorite',
        'unmark-favorite',
        'refer-job-view',
        'refer-job',
        'register',
        'post-register',
        'forgot-password',
        'send-password-link',
        'reset-password',
        'activate-account',
        'google-redirect',
        'linkedin-redirect',
        'apply-job',
        'blogs',
        'blog',
        'profile-update',
        'password-update',
        'create-resume',
        'account',
        'employer',
        'admin',
        'install',
        'paypal-payment-ipn'
    );
    if (in_array($word, $words)) {
        return true;
    }
}
}

if (!function_exists('packageExpiry'))
{
function packageExpiry($type)
{
    if ($type == 'free') {
        $days = setting('employer_free_registeration_days') ? "+".setting('employer_free_registeration_days')." day" : "+7 day";
    } else if ($type == 'monthly') {
        $days = "+31 day";
    } else if ($type == 'yearly') {
        $days = "+365 day";
    }
    return date('Y-m-d G:i:s', strtotime($days));
}
}

if (!function_exists('setSessionValues'))
{
function setSessionValues($data)
{
    foreach ($data as $name => $value) {
        setSession($name, $value);
    }
}
}

if (!function_exists('getSessionValues')) {
function getSessionValues($name, $default = '') {
    $value = getSession($name);
    return $value ? $value : $default;
}
}

if (!function_exists('printLastQuery')) {
function printLastQuery() {
    \DB::enableQueryLog();
    dd(\DB::getQueryLog());
}
}

if (!function_exists('emptyTableColumns'))
{
function emptyTableColumns($table, $extraColumns = array())
{
    $columns = \DB::getSchemaBuilder()->getColumnListing($table);
    $columns2 = array();
    foreach ($columns as $value) {
        $columns2[$value] = '';
    }
    if ($extraColumns) {
        foreach ($extraColumns as $value) {
            $columns2[$value] = '';
        }
    }
    return $columns2;
}
} 

if (!function_exists('issetVal'))
{
function issetVal($array, $index, $default = '')
{
    if (isset($array[$index]) && $array[$index] != '') {
        return $array[$index];
    } else {
        return $default ? $default : '';
    }
}
}

if (!function_exists('templateInput'))
{
function templateInput($index = '')
{
    $r = isset($_POST[$index]) ? esc_output($_POST[$index], 'raw') : esc_output($_POST, 'raw');
    return $r;
}
}

if (!function_exists('checkReservedWords'))
{
function checkReservedWords($words, $string)
{
    $errors = [];
    if (is_array($words)) {
        foreach ($words as $word) {
            if (strpos($string, $word) === false) {
                return true;
            }
        }
    } else {
        if (strpos($string, $words) === false) {
            return true;
        }
    }
}
}

if (!function_exists('replaceTagsInTemplate'))
{
function replaceTagsInTemplate($template, $tags, $values)
{
    return str_replace($tags, $values, $template);
}
}

if (!function_exists('sanitizeHtmlTemplates'))
{
function sanitizeHtmlTemplates($template)
{
    if (is_array($template)) {
        $array = array();
        foreach ($template as $key => $temp) {
            $config = \HTMLPurifier_Config::createDefault();
            $config = \HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', 'h1[class|style|id],h2[class|style|id],h3[class|style|id],h4[class|style|id],h5[class|style|id],h6[class|style|id],p,ol[class|style|id],ul[class|style|id],li[class|style|id],blockquote,table[class|style|id],tbody[class|style|id],tr[class|style|id],td[class|style|id],a[href|id|class],figure[class|style],img[src],span[class|style],div[class|id|lang|dir],img[class|style|src|alt|width|height],strong,u,i');
            $config->set('HTML.DefinitionID', 'enduser-customize.html tutorial');
            $config->set('HTML.DefinitionRev', 1);
            if ($def = $config->maybeGetRawHTMLDefinition()) {
                $def->addElement('figcaption', 'Block', 'Flow', 'Common');
                $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
            }
            $sanitizer = new \HTMLPurifier($config);
            $sanitized_html = $sanitizer->purify($temp);
            $array[$key] = $sanitized_html;
        }
        return $array;
    } else {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'h1[class|style|id],h2[class|style|id],h3[class|style|id],h4[class|style|id],h5[class|style|id],h6[class|style|id],p[class|style|id],ol[class|style|id],ul[class|style|id],li[class|style|id],blockquote,table[class|style|id],tbody[class|style|id],tr[class|style|id],td[class|style|id],a[href|id|class],figure[class|style],img[src],span[class|style],div[class|id|lang|dir],img[class|style|src|alt|width|height],strong,u,i');
        $config->set('HTML.DefinitionID', 'enduser-customize.html tutorial');
        $config->set('HTML.DefinitionRev', 1);
        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $def->addElement('figcaption', 'Block', 'Flow', 'Common');
            $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
        }
        $sanitizer = new \HTMLPurifier($config);
        $sanitized_html = $sanitizer->purify($template);
        return $sanitized_html;
    }
}
}

if (!function_exists('replaceTagsInTemplate2'))
{
function replaceTagsInTemplate2($template, $tagsWithValues)
{
    $tags = array_keys($tagsWithValues);
    $values = array_values($tagsWithValues);
    return str_replace($tags, $values, $template);
}
}

if (!function_exists('validateArrayMessage'))
{
function validateArrayMessage($messages, $tag = '')
{
    $tag = $tag == '' ? __('message.section') : $tag;
    $keyFinds = array('.0','.1','.2','.3','.4','.5','.6','.7','.8','.9',);
    $keyReplaces = array('','','','','','','','','','',);
    $newMessages = array();
    foreach ($messages as $key => $values) {
        $key = str_replace($keyFinds, $keyReplaces, $key);
        foreach ($values as $k => $v) {
            $v = replaceIndexWithSectionInValidationMessage($v, $tag);
            $newMessages[$key][] = str_replace($keyFinds, $keyReplaces, $v); 
        }
    }
    return $newMessages;
}
}

if (!function_exists('replaceIndexWithSectionInValidationMessage'))
{
function replaceIndexWithSectionInValidationMessage($message, $tag)
{
    $messageAltered = '';
    if (strpos($message, '.0') !== false) {
        $messageAltered = str_replace('.0', '', $message).' ('.$tag.' 1)';
    } elseif (strpos($message, '.1') !== false) {
        $messageAltered = str_replace('.1', '', $message).' ('.$tag.' 2)';
    } elseif (strpos($message, '.2') !== false) {
        $messageAltered = str_replace('.2', '', $message).' ('.$tag.' 3)';
    } elseif (strpos($message, '.3') !== false) {
        $messageAltered = str_replace('.3', '', $message).' ('.$tag.' 4)';
    } elseif (strpos($message, '.4') !== false) {
        $messageAltered = str_replace('.4', '', $message).' ('.$tag.' 5)';
    } elseif (strpos($message, '.5') !== false) {
        $messageAltered = str_replace('.5', '', $message).' ('.$tag.' 6)';
    } elseif (strpos($message, '.6') !== false) {
        $messageAltered = str_replace('.6', '', $message).' ('.$tag.' 7)';
    } elseif (strpos($message, '.7') !== false) {
        $messageAltered = str_replace('.7', '', $message).' ('.$tag.' 8)';
    } elseif (strpos($message, '.8') !== false) {
        $messageAltered = str_replace('.8', '', $message).' ('.$tag.' 9)';
    } elseif (strpos($message, '.9') !== false) {
        $messageAltered = str_replace('.9', '', $message).' ('.$tag.' 10)';
    } else {
        $messageAltered = $message;
    }
    return str_replace('_', ' ', $messageAltered);
}
}

/**********************************************/
/**********************************************/
/************Employer Functions****************/
/**********************************************/
/**********************************************/

if (!function_exists('employerPath')) {
function employerPath($bySlug = false, $adminSlug = '') {
    if ($adminSlug) { //Since admin can create departments as well, so adjustment for that
        return config('constants.upload_dirs.employers').$adminSlug;
    } elseif ($bySlug) {
        $slug_emp = getSession('slug_emp');
        return config('constants.upload_dirs.employers').issetVal($slug_emp, 'slug');
    } else {
        return config('constants.upload_dirs.employers').employerId('slug');
    }
}
}

if (!function_exists('companyPath')) {
    function companyPath($bySlug = false, $adminSlug = '') {
        if ($adminSlug) { //Since admin can create departments as well, so adjustment for that
            return config('constants.upload_dirs.companys').$adminSlug;
        } elseif ($bySlug) {
            $slug_emp = getSession('slug_emp');
            return config('constants.upload_dirs.companys').issetVal($slug_emp, 'slug');
        } else {
            return config('constants.upload_dirs.companys').companyId('slug');
        }
    }
    }

if (!function_exists('employerId')) {
function employerId($column = '') {
    $current = employerSession('type');
    if ($current == 'main') {
        return $column ? employerSession($column) : employerSession();
    } else {
        $child = App\Models\Admin\Employer::getEmployer('employers.employer_id', employerSession());
        if ($column) {
            $parent = App\Models\Admin\Employer::getEmployer('employers.employer_id', $child['parent_id']);
            return $parent[$column];
        }
        return $child['parent_id'];
    }
}
}

if (!function_exists('companyId')) {
function companyId($column = '') {
    $current = companySession('type');
    if ($current == 'main') {
        return $column ? companySession($column) : companySession();
    } else {
        $child = App\Models\Admin\Company::getCompany('company.company_id', companySession());
        if ($column) {
            $parent = App\Models\Admin\Company::getCompany('company.company_id', $child['parent_id']);
            return $parent[$column];
        }
        return $child['parent_id'];
    }
}
}

if (!function_exists('employerIdBySlug')) {
function employerIdBySlug($column = '') {
    $slugEmp = getSession('slug_emp');
    if ($column) {
        return issetVal($slugEmp, $column);
    } else {
        return issetVal($slugEmp, 'employer_id');
    }
}
}

if(!function_exists('empSlug')) {
function empSlug() {
    $slug = \Route::input('slug');    
    $subslug = \Route::input('subdomain_slug');
    return $slug ? $slug : $subslug;
}
}

if(!function_exists('isEmpRoute')) {
function isEmpRoute() {
    $route = \Request::url();
    if (strpos($route, 'employer') !== false) {
        return true;
    }
    return false;
}
}

if(!function_exists('isCompany')) {
    function isCompany() {
        $route = \Request::url();
        if (strpos($route, 'company') !== false) {
            return true;
        }
        return false;
    }
    }

if(!function_exists('isAdminRoute')) {
function isAdminRoute() {
    $route = \Request::url();
    if (strpos($route, 'admin') !== false) {
        return true;
    }
    return false;
}
}

if(!function_exists('setting')) {
function setting($index = '', $sorted = true) {
    $setting = App\Helpers\SettingsHelper::Instance($index, $sorted);
    return settingWithOrWithoutUrl($setting, $index, 'admin');
}
}

if(!function_exists('settingEmp')) {
function settingEmp($index = '', $original = false)
{
    if (membershipBrandingOrMailEnabled($index) || $original) {
        //Getting from employer settings
        $setting = App\Helpers\EmployerSettingsHelper::Instance($index);
        $setting = settingWithOrWithoutUrl($setting, $index, 'employer');
    } else {
        //Getting from admin settings as an override
        $setting = App\Helpers\SettingsHelper::Instance($index);
        $setting = settingWithOrWithoutUrl($setting, $index, 'admin');
    }
    return $setting;
}
}

if(!function_exists('settingEmpSlug')) {
function settingEmpSlug($index = '') {
    if (membershipBrandingOrMailEnabled($index, true)) {
        //Getting from employer settings
        $setting = App\Helpers\EmployerSettingsHelper::Instance($index, 'slug');
        $setting = settingWithOrWithoutUrl($setting, $index, 'employer', true);
    } else {
        //Getting from admin settings as an override
        $setting = App\Helpers\SettingsHelper::Instance($index);
        $setting = settingWithOrWithoutUrl($setting, $index, 'admin');
    }
    return $setting;
}
}

if(!function_exists('settingEmpById')) {
function settingEmpById($id) {
    $setting = App\Helpers\EmployerSettingsHelper::Instance($index, 'slug');
    return $setting;
}
}

if(!function_exists('settingWithUrl')) {
function settingWithOrWithoutUrl($setting, $index, $type, $bySlug = false) {
    if (in_array($index, array('site_logo', 'site_banner', 'site_favicon', 'testimonials_banner'))) {
        //With url settings
        if ($type == 'employer') {
            return route('uploads-view', $setting);
        } else {
            return route('uploads-view', $setting);
        }
    }
    //Without url setting
    return $setting;
}
}

if(!function_exists('empUrl')) {
function empUrl() {
    $type = env('CFSAAS_ROUTE_SLUG', 'slug');
    $url = env('APP_URL');
    $admin_setting = setting('enable_separate_employer_site');
    if ($admin_setting == 'yes' || $admin_setting == 'only_for_employers_with_separate_site') {
        if ($type == 'subdomain_slug') {
            $subslug = \Route::input('subdomain_slug');
            $url = str_replace('//', '//'.$subslug.'.', $url);
            $url = str_replace('www.', '', $url);
            return $url;
        } else {
            $slug = \Route::input('slug');    
            $url = $url.'/'.$slug.'/';
            $url = preg_replace('/([^:])(\/{2,})/', '$1/', $url);;
            return $url;
        }
    } else {
        return $url;
    }
}
}

if(!function_exists('empUrlBySlug')) {
function empUrlBySlug($slug) {
    $type = env('CFSAAS_ROUTE_SLUG', 'slug');
    $url = env('APP_URL');
    if ($type == 'subdomain_slug') {
        $url = str_replace('//', '//'.$slug.'.', $url);
        $url = str_replace('www.', '', $url);
        return $url;
    } else {
        $url = $url.'/'.$slug.'/';
        $url = preg_replace('/([^:])(\/{2,})/', '$1/', $url); //Replacing double quotes with one
        return $url;
    }
}
}

if(!function_exists('frontJobLink')) {
function frontJobLink($employer_slug, $separate_site, $selfCall = '') {
    $separate_site_setting = $selfCall ? $selfCall : setting('enable_separate_employer_site');
    if ($separate_site_setting == 'no') {
        $url = env('APP_URL');
        return $url.'job/';
    } elseif ($separate_site_setting == 'yes') {
        return empUrlBySlug($employer_slug).'job/';
    } else {
        $self_call = $separate_site == 1 ? 'yes' : 'no';
        return frontJobLink($employer_slug, $separate_site, $self_call);
    }
}
}

if(!function_exists('frontEmpUrl')) {
function frontEmpUrl($employer_slug, $separate_site, $selfCall = '') {
    $separate_site_setting = $selfCall ? $selfCall : setting('enable_separate_employer_site');
    if ($separate_site_setting == 'no') {
        $url = route('employer-company-detail', $employer_slug);
        return $url;
    } elseif ($separate_site_setting == 'yes') {
        return empUrlBySlug($employer_slug);
    } else {
        $self_call = $separate_site == 1 ? 'yes' : 'no';
        return frontEmpUrl($employer_slug, $separate_site, $self_call);
    }
}
}

if(!function_exists('frontCmpUrl')) {
    function frontCmpUrl($employer_slug, $separate_site, $selfCall = '') {
        $separate_site_setting = $selfCall ? $selfCall : setting('enable_separate_employer_site');
        $url = route('company-detail', $employer_slug);
        return $url;
        if ($separate_site_setting == 'no') {
            $url = route('company-detail', $employer_slug);
            return $url;
        } elseif ($separate_site_setting == 'yes') {
            return empUrlBySlug($employer_slug);
        } else {
            $self_call = $separate_site == 1 ? 'yes' : 'no';
            return frontCmpUrl($employer_slug, $separate_site, $self_call);
        }
    }
    }

if(!function_exists('empUrlByValue')) {
function empUrlByValue($value) {
    $type = env('CFSAAS_ROUTE_SLUG', 'slug');
    $url = env('APP_URL');
    if ($type == 'subdomain_slug') {
        $url = str_replace('//', '//'.$value.'.', $url);
        return $url;
    } else {
        $url = $url.'/'.$value.'/';
        $url = preg_replace('/([^:])(\/{2,})/', '$1/', $url); //Replacing double quotes with one
        return $url;
    }
}
}

if(!function_exists('routeWithSlug')) {
function routeWithSlug($name, $otherParams = array()) {
    $subslug = \Route::input('subdomain_slug');
    if ($subslug) {
        $params = array_merge(array('subdomain_slug' => $subslug), $otherParams);
        return route($name.'-sd', $params);
    }
    $slugEmp = getSession('slug_emp');
    $params = array_merge(array('slug' => issetVal($slugEmp, 'slug')), $otherParams);
    return route($name, $params);
}
}

if(!function_exists('empSlugBranding')) {
function empSlugBranding() {
    $slugEmp = getSession('slug_emp');
    $slug = issetVal($slugEmp, 'slug');
    $branding = empMembership($slug, 'branding');
    return $branding == 1 ? true : false;
}
}

if(!function_exists('empMembership')) {
function empMembership($employer, $index = '') {
    return App\Helpers\MembershipHelper::Instance($employer, $index);
}
}

if(!function_exists('membershipBrandingOrMailEnabled')) {
function membershipBrandingOrMailEnabled($index = '', $bySlug = false) {
    //Branding settings
    $brandingSettings = array('site_name', 'site_logo', 'site_banner', 'site_favicon', 'site_keywords', 'site_description', 'banner_text', 'before_blogs_text', 'after_blogs_text', 'before_how_text', 'after_how_text', 'footer_col_1', 'footer_col_2', 'footer_col_3', 'footer_col_4',);

    //Custom email settings
    $emailSettings = array('candidate_job_app','employer_job_app','employer_interview_assign','candidate_interview_assign','candidate_quiz_assign', 'team_creation');

    if (!in_array($index, $brandingSettings) && !in_array($index, $emailSettings)) {
        return true;
    }

    //Setting branding and custom emails settings from
    if ($bySlug) {
        $slugEmp = getSession('slug_emp');
        $slug = issetVal($slugEmp, 'slug');
        $branding = empMembership($slug, 'branding');
        $custom_emails = empMembership($slug, 'custom_emails');
    } else {
        $branding = empMembership(employerId(), 'branding');
        $custom_emails = empMembership(employerId(), 'custom_emails');
    }

    if (in_array($index, $brandingSettings) && $branding == '1') {
        return true;
    }
    if (in_array($index, $emailSettings) && $custom_emails == '1') {
        return true;
    }
    return false;
}
}

if(!function_exists('stripeCurrencies')) {
function stripeCurrencies($asSelectOptions = false, $selected = '') {
    $currencies = array('usd', 'aed', 'afn', 'all', 'amd', 'ang', 'aoa', 'ars', 'aud', 'awg', 'azn', 'bam', 'bbd', 'bdt', 'bgn', 'bhd', 'bif', 'bmd', 'bnd', 'bob', 'brl', 'bsd', 'bwp', 'byn', 'bzd', 'cad', 'cdf', 'chf', 'clp', 'cny', 'cop', 'crc', 'cve', 'czk', 'djf', 'dkk', 'dop', 'dzd', 'egp', 'etb', 'eur', 'fjd', 'fkp', 'gbp', 'gel', 'gip', 'gmd', 'gnf', 'gtq', 'gyd', 'hkd', 'hnl', 'hrk', 'htg', 'huf', 'idr', 'ils', 'inr', 'isk', 'jmd', 'jod', 'jpy', 'kes', 'kgs', 'khr', 'kmf', 'krw', 'kwd', 'kyd', 'kzt', 'lak', 'lbp', 'lkr', 'lrd', 'lsl', 'mad', 'mdl', 'mga', 'mkd', 'mmk', 'mnt', 'mop', 'mro', 'mur', 'mvr', 'mwk', 'mxn', 'myr', 'mzn', 'nad', 'ngn', 'nio', 'nok', 'npr', 'nzd', 'omr', 'pab', 'pen', 'pgk', 'php', 'pkr', 'pln', 'pyg', 'qar', 'ron', 'rsd', 'rub', 'rwf', 'sar', 'sbd', 'scr', 'sek', 'sgd', 'shp', 'sll', 'sos', 'srd', 'std', 'szl', 'thb', 'tjs', 'tnd', 'top', 'try', 'ttd', 'twd', 'tzs', 'uah', 'ugx', 'uyu', 'uzs', 'vnd', 'vuv', 'wst', 'xaf', 'xcd', 'xof', 'xpf', 'yer', 'zar', 'zmw', 'eek', 'lvl', 'svc', 'vef', 'ltl',);
    if ($asSelectOptions) {
        $options = '';
        foreach ($currencies as $cur) {
            $sel = $selected == $cur ? 'selected' : '';
            $options .= '<option value="'.$cur.'" '.$sel.'>'.$cur.'</option>';
        }
        return $options;
    }
    return $currencies;
}
}

if (!function_exists('languagePath')) {
function languagePath($path) {
    return base_path().'/resources/lang/'.$path;
}
}

if (!function_exists('activeLanguages')) {
function activeLanguages($type = 'front') {
    $controller = new App\Http\Controllers\Admin\LanguagesController;
    return $controller->languageSelect($type);
}
}

if(!function_exists('frontLanguage')) {
function frontLanguage() {
    $default = App\Models\Admin\Language::getDefault(true);
    $selected_flag = getSession('front_selected_language_flag');
    $selected_title = getSession('front_selected_language_title');
    $selected_slug = getSession('front_selected_language_slug');
    $selected_direction = getSession('front_selected_language_direction');
    if ($selected_flag) {
        return array(
            'flag' => $selected_flag, 
            'title' => $selected_title,
            'slug' => $selected_slug,
            'direction' => $selected_direction
        );
    } else {
        return array(
            'flag' => $default['flag'],
            'title' => $default['title'],
            'slug' => $default['slug'],
            'direction' => $default['direction']
        );
    }
}
}

if(!function_exists('employerLanguage')) {
function employerLanguage() {
    $default = App\Models\Admin\Language::getDefault(true);
    $selected_flag = getSession('employer_selected_language_flag');
    $selected_title = getSession('employer_selected_language_title');
    $selected_slug = getSession('employer_selected_language_slug');
    $selected_direction = getSession('employer_selected_language_direction');
    if ($selected_flag) {
        return array(
            'flag' => $selected_flag, 
            'title' => $selected_title,
            'slug' => $selected_slug,
            'direction' => $selected_direction
        );
    } else {
        return array(
            'flag' => $default['flag'],
            'title' => $default['title'],
            'slug' => $default['slug'],
            'direction' => $default['direction']
        );
    }
}
}

if(!function_exists('companyLanguage')) {
function companyLanguage() {
    $default = App\Models\Admin\Language::getDefault(true);
    $selected_flag = getSession('company_selected_language_flag');
    $selected_title = getSession('company_selected_language_title');
    $selected_slug = getSession('company_selected_language_slug');
    $selected_direction = getSession('company_selected_language_direction');
    if ($selected_flag) {
        return array(
            'flag' => $selected_flag, 
            'title' => $selected_title,
            'slug' => $selected_slug,
            'direction' => $selected_direction
        );
    } else {
        return array(
            'flag' => $default['flag'],
            'title' => $default['title'],
            'slug' => $default['slug'],
            'direction' => $default['direction']
        );
    }
}
}

if(!function_exists('flagCodes')) {
function flagCodes() {
    return array('ad','ae','af','ag','ai','al','am','ao','aq','ar','as','at','au','aw','ax','az','ba','bb','bd','be','bf','bg','bh','bi','bj','bl','bm','bn','bo','bq','br','bs','bt','bv','bw','by','bz','ca','cc','cd','cf','cg','ch','ci','ck','cl','cm','cn','co','cr','cu','cv','cw','cx','cy','cz','de','dj','dk','dm','do','dz','ec','ee','eg','eh','er','es','et','fi','fj','fk','fm','fo','fr','ga','gb','gd','ge','gf','gg','gh','gi','gl','gm','gn','gp','gq','gr','gs','gt','gu','gw','gy','hk','hm','hn','hr','ht','hu','id','ie','il','im','in','io','iq','ir','is','it','je','jm','jo','jp','ke','kg','kh','ki','km','kn','kp','kr','kw','ky','kz','la','lb','lc','li','lk','lr','ls','lt','lu','lv','ly','ma','mc','md','me','mf','mg','mh','mk','ml','mm','mn','mo','mp','mq','mr','ms','mt','mu','mv','mw','mx','my','mz','na','nc','ne','nf','ng','ni','nl','no','np','nr','nu','nz','om','pa','pe','pf','pg','ph','pk','pl','pm','pn','pr','ps','pt','pw','py','qa','re','ro','rs','ru','rw','sa','sb','sc','sd','se','sg','sh','si','sj','sk','sl','sm','sn','so','sr','ss','st','sv','sx','sy','sz','tc','td','tf','tg','th','tj','tk','tl','tm','tn','to','tr','tt','tv','tw','tz','ua','ug','um','us','uy','uz','va','vc','ve','vg','vi','vn','vu','wf','ws','ye','yt','za','zm','zw');
}
}

if(!function_exists('datesOfMonth')) {
function datesOfMonth($year = '', $month = '') {
    $list = array();
    $month = $month ? $month : date('m');
    $year = $year ? $year : date('Y');

    for($d = 1; $d <= 31; $d++) {
        $time = mktime(12, 0, 0, $month, $d, $year);          
        if (date('m', $time) == $month) {
            $list[] = date('Y-m-d', $time);
        }
    }
    return $list;
}
}

if(!function_exists('monthsOfYear')) {
function monthsOfYear($year = '') {
    $list = array();
    $year = $year ? $year : date('Y');
    $months = array('01','02','03','04','05','06','07','08','09','10','11','12');
    foreach ($months as $m) {
        $list[] = $year.'-'.$m;
    }
    return $list;
}
}

if (!function_exists('sessionVariables')) {
function sessionVariables($key = '', $value = '') {
    if ($value == '') {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
    } else {
        $_SESSION[$key] = $value;
    }
}
}

if (!function_exists('allVariablesTrue')) {
function allVariablesTrue() {
    $variables = array('php_version', 'pdo', 'gd', 'openssl', 'curl', 'uploads_writeable',);
    $result = true;
    $all = $_SESSION;
    foreach ($variables as $a) {
        if (isset($_SESSION[$a]) && $_SESSION[$a] == 'false') {
            $result = false;
        }
    }
    return $result;
}
}

if (!function_exists('base_url')) {
function base_url($instal = false, $page = '') {
    $url = getRequestScheme().'://'.$_SERVER['HTTP_HOST'].serverFolder($instal);
    return $url;
}    
}

if (!function_exists('url_with_port')) {
function url_with_port()
{
    $protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
    $server = $_SERVER['SERVER_NAME'];
    $port = $_SERVER['SERVER_PORT'] ? ':'.$_SERVER['SERVER_PORT'] : '';
    return $protocol.$server.$port;
}
}

if (!function_exists('getRequestScheme')) {
function getRequestScheme() {
    if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)) {
        return 'https';
    } else {
        return 'http';
    }
}
}

if (!function_exists('serverFolderOld')) {
function serverFolderOld($instal) {
    $script_name = issetVal($_SERVER, 'SCRIPT_NAME');
    if ($instal) {
        $folder = $script_name;
        $folder = strpos($folder, '/index.php') !== false ? str_replace('/index.php', '', $folder) : $folder;
    } else {
        $count = $instal ? pageCountForSubstr() : -17;
        $folder = substr($script_name, 0, $count);
    }
    return $folder;
}
}

if (!function_exists('serverFolder')) {
function serverFolder($instal) {
    $count = $instal ? pageCountForSubstr() : -17;
    $folder = substr($_SERVER['SCRIPT_NAME'], 0, $count);
    $folder = str_replace('/index.php', '', $folder);
    return $folder;
}
}

if (!function_exists('pageCountForSubstr')) {
function pageCountForSubstr() {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    if (strpos($scriptName, 'requirements') !== false) {
        return -24;
    } elseif (strpos($scriptName, 'database-connection') !== false) {
        return -32;
    } elseif (strpos($scriptName, 'database') !== false) {
        return -21;
    } elseif (strpos($scriptName, 'credentials') !== false) {
        return -24;
    } elseif (strpos($scriptName, 'create-user') !== false) {
        return -24;
    }
}
}

if (!function_exists('dbprfx')) {
function dbprfx() {
    return env('DB_PREFIX');
}
}

if (!function_exists('viewPrfx')) {
function viewPrfx($slash = false) {
    $prefix = env('CFSAAS_FRONT_PRFX') ? env('CFSAAS_FRONT_PRFX') : 'beta';
    if ($slash === 'only') {
        return $prefix;
    }
    return $slash ? '/'.$prefix.'/' : '.'.$prefix.'.';
}
}

if (!function_exists('dyanmicSubdomainUrl')) {
function dyanmicSubdomainUrl() {
    $url = env('APP_URL');
    $url = rtrim($url, '/');
    $url = str_replace('www.', '', $url);
    return preg_replace("(^https?://)", "", $url);
}
}

if (!function_exists('paginationOverview')) {
function paginationOverview($total, $limit, $page) {
    $offset = ($page == 1 ? 0 : ($page-1)) * $limit;
    $offset = $offset < 0 ? 0 : $offset;    
    $total_pages = $total != 0 ? ceil($total/$limit) : 0;
    $pagination = ($offset == 0 ? 1 : ($offset+1));
    $pagination .= ' - ';
    $pagination .= $total_pages == $page ? $total : ($limit*$page);
    $pagination .= ' '.__('message.of').' ';
    $pagination .= $total;
    return $pagination;
}
}

if (!function_exists('makeCombined')) {
function makeCombined($filters, $return_type = 'string') {
    $return = '';
    $returnArray = array();
    asort($filters);
    foreach ($filters as $id => $values) {
        if ($values) {
            asort($values);
            foreach ($values as $value) {
                $return .= $id.'-'.$value.',';
                $returnArray[] = $id.'-'.$value;
            }
        }
    }
    if ($return_type == 'string') {
        return $return ? rtrim($return, ',') : '';
    } else {
        return $returnArray;
    }
}
}

if (!function_exists('canHideAdminDepartments')) {
function canHideAdminDepartments($from) {
    if (setting('departments_creation') == 'only_admin') { //Checking universal setting from admin first
        return false;
    } else {
        $setting = 'display_admin_created_departments';
        $setting = $from == 'candidate_area' ? settingEmpSlug($setting) : settingEmp($setting);
        if ($setting == 'no') {
            return true;
        }
    }
    return false;

}
}

if (!function_exists('canHideAdminJobFilters')) {
function canHideAdminJobFilters($from) {
    if (setting('job_filters_creation') == 'only_admin') { //Checking universal setting from admin first
        return false;
    } else {
        $setting = 'display_admin_created_job_filters';
        $setting = $from == 'candidate_area' ? settingEmpSlug($setting) : settingEmp($setting);
        if ($setting == 'no') {
            return true;
        }
    }
    return false;
}
}

if (!function_exists('separateSiteAvailable')) {
function separateSiteAvailable($employer_setting) {
    $admin_setting = setting('enable_separate_employer_site');
    if ($admin_setting == 'yes') {
        return true;
    }
    if ($admin_setting == 'only_for_employers_with_separate_site' && $employer_setting == 1) {
        return true;
    }
    return false;
}
}

if (!function_exists('resizeByWidthAndCropByHeight')) {
function resizeByWidthAndCropByHeight($dir, $name, $ext, $width, $height = 'original') {
    $file = $dir . $name . '.' . $ext;
    $newFile = $dir . $name . '-' . $width . '-' . $height . '.' . $ext;

    //First resize with maintained aspect ratio
    $im = new App\Helpers\ImageManipulator($file);
    $im->resample($width, 0);
    $im->save($newFile);

    if ($height != 'original') {
        //Second Crop vertically with the above resized width
        $im = new App\Helpers\ImageManipulator($newFile);
        $centreX = round($im->getWidth() / 2);
        $centreY = round($im->getHeight() / 2);
        $width = ($width / 2);
        $height = $height == 'original' ? ($im->getHeight() / 2) : ($height / 2);

        $x1 = $centreX - ($width);
        $y1 = $centreY - ($height);

        $x2 = $centreX + ($width);
        $y2 = $centreY + ($height);

        $im->crop($x1, $y1, $x2, $y2);
        $im->save($newFile);
    }
}
}

if (!function_exists('deleteDirectory')) {
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return unlink($dir);
}
}

if(!function_exists('deleteDirWithContents')) {
function deleteDirWithContents($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") {
                    deleteDirWithContents($dir."/".$object); 
                } else {
                    unlink($dir."/".$object);
                }
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
}

if(!function_exists('adminFileUploadLimit')) {
function adminFileUploadLimit() {
    $limit_admin = setting('admin_file_upload_limit');
    $limit_general = setting('general_file_upload_limit');
    return $limit_admin ? $limit_admin : ($limit_general ? $limit_general : '1024');
}
}

if(!function_exists('generalFileUploadLimit')) {
function generalFileUploadLimit() {
    $limit_admin = setting('admin_file_upload_limit');
    $limit_general = setting('general_file_upload_limit');
    return $limit_general ? $limit_general : ($limit_admin ? $limit_admin : '1024');
}
}
