<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageManipulator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Function to display error messages throughout application
     *
     * @param array $data
     * @return html/string
     */
    public function ajaxErrorMessage($data)
    {
        if (isEmpRoute() || isAdminRoute()) {
            return view('admin.partials.messages-3', $data)->render();
        } else {
    	   return view('admin.partials.messages', $data)->render();
        }
    }

    /**
     * Function to resize and save images by set of parameters
     *
     * @param string $dir
     * @param string $name
     * @param string $ext
     * @param integer $width
     * @param string $height
     * @return void
     */
    public function resizeByWidthOrHeight($dir, $key, $name, $ext, $width = 0, $height = 0)
    {
        $file = $dir . $name . '.' . $ext;
        $newFile = $dir . $key . '.' . $ext;
        $im = new ImageManipulator($file);
        $im->resize($width, $height);
        $im->save($newFile);
    }   

    /**
     * Global function to send email
     *
     * @return void
     */
    public function sendEmail($message = '', $toEmail = '', $subject = '', $employer = 0)
    {
        $default = setting('smtp_enable') == 'yes' ? 'smtp' : 'sendmail';
        $from_email = settingEmp('from_email');
        $from_email = $from_email ? $from_email : env('MAIL_FROM_ADDRESS', 'hello@example.com');
        $from_name = settingEmp('site_name');
        $from_name = $from_name ? $from_name : env('MAIL_FROM_NAME', 'Example');

        \Config::set('mail.default', $default);
        \Config::set('mail.from.address', $from_email);
        \Config::set('mail.from.name', $from_name);

        if ($default == 'smtp') {
            $transport = 'smtp';
            $host = setting('smtp_host', $employer);
            $host = $host ? $host : env('MAIL_HOST', '');
            $port = setting('smtp_port', $employer);
            $port = $port ? $port : env('MAIL_PORT', '');
            $encryption = setting('smtp_protocol', $employer);
            $encryption = $encryption ? $encryption : env('MAIL_ENCRYPTION', '');
            $username = setting('smtp_username', $employer);
            $username = $username ? $username : env('MAIL_USERNAME', '');
            $password = setting('smtp_password', $employer);
            $password = $password ? $password : env('MAIL_PASSWORD', '');

            \Config::set('mail.mailers.smtp.transport', $transport);
            \Config::set('mail.mailers.smtp.host', $host);
            \Config::set('mail.mailers.smtp.port', $port);
            \Config::set('mail.mailers.smtp.encryption', $encryption);
            \Config::set('mail.mailers.smtp.username', $username);
            \Config::set('mail.mailers.smtp.password', $password);
        }

        try {
            \Mail::send([], [], function($email) use ($toEmail, $subject, $message) {
                $email->to($toEmail)->subject($subject)->setBody($message, 'text/html');
            });
        } catch (\Exception $e) {
            $error = array(
                'title' => 'Mail Send Error',
                'type' => 'mail_send_error',
                'description' => $e->getMessage(),
                'created_at' => date('y-m-d G:i:s')
            );
            DB::table('error_logs')->insert($error);
        }
    }

    /**
     * Global function to restrict actions if in demo mode
     *
     * @return void
     */
    public function checkIfDemo($type = '', $strict = false)
    {
        $noDemo = $strict ? env('CFSAAS_DEMO_STRICT') : env('CFSAAS_DEMO');
        if ($noDemo == 'true') {
            $message = 'Action restricted in demo mode';
            if ($type == 'reload') {
                die($message);
            } elseif ($type == 'front') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $message))
                )));
            } else {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $message))
                )));
            }
        }
    }

    /**
     * Global function to upload files
     *
     * @return void
     */
    public function uploadPublicFile($request, $uf, $path, $validation_rules, $validation_messages, $modifiedName = '')
    {
        $validator = \Validator::make($request->all(), $validation_rules, $validation_messages);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => $validator->messages()->first()))
            )));
        }

        if ($request->hasFile($uf)) {
            $file = $request->file($uf); //uf = uploaded_file
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            if ($modifiedName) {
                $name = $modifiedName.'.'.$file->getClientOriginalExtension();
            } else {
                $name = slugify($name).'-'.slugify().'.'.$file->getClientOriginalExtension();
            }

            if (function_exists('symlink')) {
                $file->storeAs('uploads/'.$path, $name);
            } else {
                $file->move(public_path().'/uploads/'.$path, $name);
            }
            
            return array('success' => 'true', 'message' => $path.$name, 'name' => $name);
        }
    }

    /**
     * Global function to delete files
     *
     * @return void
     */
    public function deleteOldFile($path)
    {
        if ($path) {
            $storagePath  = \Storage::disk(config('constants.upload_dirs.main'))->getDriver()->getAdapter()->getPathPrefix();
            $file = $storagePath.'/'.($path);
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Function to get google client for google login
     *
     * @return Google_Client
     */
    public function getGoogleClient($type = '')
    {
        // init configuration
        $clientID = setting('google_client_id');
        $clientSecret = setting('google_client_secret');
        $redirectUri = setting('google_redirect_uri');
        $state = base64UrlEncode('{"employer":"'.empSlug().'","type":"'.$type.'"}');

        // create Client Request to access Google API
        $client = new \Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");
        $client->setState($state);

        return $client;
    }
    
}
