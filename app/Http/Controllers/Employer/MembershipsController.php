<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Membership;
use App\Models\Employer\Package;
use App\Models\Employer\Employer;
use App\Models\Admin\Notification;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

use Razorpay\Api\Api as RazorpayApi;

class MembershipsController extends Controller
{
    /**
     * View Function to display memberships list view page
     *
     * @return html/string
     */
    public function membershipsListView()
    {
        $data['page'] = __('message.memberships');
        $data['menu'] = 'memberships';
        return view('employer.memberships.list', $data);
    }

    /**
     * Function to get data for memberships jquery datatable
     *
     * @return json
     */
    public function membershipsList(Request $request)
    {
        echo json_encode(Membership::membershipsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @return html/string
     */
    public function renewForm()
    {
        $data['packages'] = Membership::getPackages();
        echo view('employer.memberships.renew', $data)->render();
    }

    /**
     * Ajax Function to make stripe payment by employer
     *
     * @return void
     */
    public function stripePayment(Request $request)
    {
        $this->checkIfDemo();

        //Doing validation
        $rules['card_number'] = 'required|digits_between:10,20';
        $rules['cvc'] = 'required|digits_between:2,5';
        $validator = Validator::make($request->all(), $rules, [
            'card_number.required' => __('validation.required'),
            'card_number.digits_between' => __('validation.digits_between'),
            'cvc.required' => __('validation.required'),
            'cvc.digits_between' => __('validation.digits_between'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Preparing data
        $selected = explode('-', decode($request->input('selected')));
        $employer = isset($selected[0]) ? $selected[0] : '';
        $package_id = isset($selected[1]) ? $selected[1] : '';
        $type = isset($selected[2]) ? $selected[2] : '';
        $package = Package::getPackage('packages.package_id', $package_id);
        $amount = $type == "monthly" ? $package['monthly_price'] : $package['yearly_price'];

        //Making payment to stripe with stripe helper and library
        $stripe = new \App\Helpers\StripeHelper();
        $stripeData = array(
            'item_name' => $package['title'],
            'amount' => $amount,
            'item_id' => $package_id,
            'item_number' => $package_id,
            'currency_code' => $package['currency_for_api'],
            'name' => employerId('first_name').' '.employerId('last_name'),
            'email' => employerId('email'),
            'card_number' => $request->input('card_number'),
            'month' => $request->input('month'),
            'year' => $request->input('year'),
            'cvc' => $request->input('cvc'),
            'token' => $request->input('token'),
        );
        $res = $stripe->chargeAmountFromCard($stripeData);

        //Recording membership on successfull payment
        if ($res['amount_refunded'] == 0 && empty($res['failure_code']) && $res['paid'] == 1 && $res['captured'] == 1 &&
            $res['status'] == 'succeeded') {
            $this->addMembershipToDb($res, $package['title'], $package_id, $type, $amount, employerId(), 'stripe');

            $successMessage = __('message.payment_successfull')." (". $res["balance_transaction"].")";
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => $successMessage)),
            ));
        }
    }

    /**
     * Ajax Function to make offline payment by employer
     *
     * @return void
     */
    public function offlinePayment(Request $request)
    {
        $this->checkIfDemo();

        $rules['message'] = ['required', new MinString(10), new MaxString(250)];
        $messages['message.required'] = __('validation.required');
        $messages['message.min'] = __('validation.min_string');
        $messages['message.max'] = __('validation.max_string');

        if (setting('enable_offline_payment_attachment') == 'required') {
            $rules['file'] = ['required', 'mimes:jpeg,png,jpg,pdf,doc,docx', new MaxFile(generalFileUploadLimit())];
            $messages['file.mimes'] = __('validation.mimes');
        } else {
            $rules['file'] = ['mimes:jpeg,png,jpg,pdf,doc,docx', new MaxFile(generalFileUploadLimit())];
            $messages['file.mimes'] = __('validation.mimes');            
        }

        //Doing validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $fileUpload = $this->uploadPublicFile(
            $request, 
            'file', 
            config('constants.upload_dirs.memberships'), 
            array('file' => ['mimes:jpeg,png,jpg,gif,pdf,doc,docx', new MaxFile(generalFileUploadLimit())]),
            array('file.mimes' => __('validation.mimes'))
        );

        if (issetVal($fileUpload, 'success') == 'false') {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => issetVal($fileUpload, 'message')))
            )));
        }

        //Preparing data from selected package
        $selected = explode('-', decode($request->input('selected')));
        $employer = isset($selected[0]) ? $selected[0] : '';
        $package_id = isset($selected[1]) ? $selected[1] : '';
        $type = isset($selected[2]) ? $selected[2] : '';
        $package = Package::getPackage('packages.package_id', $package_id);
        $amount = $type == "monthly" ? $package['monthly_price'] : $package['yearly_price'];
        $status = setting('enable_offline_payment_unverified_activation') == 'yes' ? 1 : 0;

        //Preparing data for dummy api
        $prepared_res["balance_transaction"] = slugify().'-ofl';
        $prepared_res["status"] = $status;
        $prepared_res["currency"] = $package['currency_for_api'];
        $prepared_res["file"] = issetVal($fileUpload, 'message');
        $prepared_res["message"] = $request->input('message');

        //Recording membership on successfull payment
        $this->addMembershipToDb($prepared_res, $package['title'], $package_id, $type, $amount, employerId(), 'offline');

        $successMessage = __('message.payment_successfull')." (". $prepared_res["balance_transaction"].")";
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => $successMessage)),
        ));
    }

    /**
     * Function to make paypal payment by employer
     *
     * @param Request $request
     * @param integer $selectedData
     * @return void
     */
    public function paypalPayment(Request $request, $selectedOriginal)
    {
        //Preparing data
        $selected = explode('-', decode($selectedOriginal));
        $employer = isset($selected[0]) ? $selected[0] : '';
        $package_id = isset($selected[1]) ? $selected[1] : '';
        $type = isset($selected[2]) ? $selected[2] : '';
        $package = Package::getPackage('packages.package_id', $package_id);
        $amount = $type == "monthly" ? $package['monthly_price'] : $package['yearly_price'];

        //Setting paypal prerequisites
        $data['cmd'] = '_xclick';
        $data['no_note'] = '1';
        $data['lc'] = 'US';
        $data['bn'] = 'PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest';
        $data['submit'] = 'Submit Payment';
        $data['currency_code'] = strtoupper($package['currency_for_api']);
        $data['payer_id'] = encode(employerId());
        $data['first_name'] = employerId('first_name');
        $data['last_name'] = employerId('last_name');
        $data['payer_email'] = employerId('email');
        $data['return'] = stripslashes(url('/employer').'/memberships?ps=1');
        $data['cancel_return'] = stripslashes(url('/employer').'/memberships');
        $data['notify_url'] = stripslashes(url('').'/paypal-payment-ipn?selected='.$selectedOriginal);
        $data['business'] = setting('paypal_email');
        $data['item_number'] = $package_id;
        $data['item_name'] = $package['title'];
        $data['amount'] = $amount;

        //Setting payapal url
        $paypalUrl = setting('paypal_environment') == 'testing' ? env('PAYPAL_SANDBOX_URL') : env('PAYPAL_URL');

        //Build the query string from the data and redirecting
        $queryString = http_build_query($data);
        header('location:' . $paypalUrl . '?' . $queryString);
        exit();
    }

    /**
     * Function which will be used as a receiver for paypal post hit for ipn
     *
     * @return void
     */
    public function paypalPaymentIpn(Request $request, $selected = null)
    {
        //Getting data from paypal
        $raw_data_from_paypal = file_get_contents('php://input');
        $paypal_data_exploded = explode('&', $raw_data_from_paypal);
        $payapal_data = array();
        foreach ($paypal_data_exploded as $keyval) {
          $keyval = explode ('=', $keyval);
          if (count($keyval) == 2)
             $payapal_data[$keyval[0]] = urldecode($keyval[1]);
        }

        $txn = $payapal_data['txn_id'];

        if ($txn) {

            $res = array(
                'balance_transaction' => $txn, 
                'status' => $payapal_data['payment_status'], 
                'currency' => $payapal_data['mc_currency'], 
                'response' => json_encode($payapal_data)
            );

            try {
                //Verifying payment is from paypal and not a possible existing duplicate
                if ($this->paypalTransactionVerification($payapal_data) && Membership::checkTransactionId($txn)) {

                    //Preparing data
                    $sel = $this->selectedBreakup($request->input('selected'));

                    //Recording to database
                    $this->addMembershipToDb(
                        $res,
                        $sel['package_title'],
                        $sel['package_id'],
                        $sel['type'],
                        $sel['amount'],
                        $sel['employer'],
                        'paypal'
                    );

                    //Giving message
                    $message = "Response.<br /><br />".json_encode($payapal_data);
                    $this->sendEmail($message, setting('admin_email'), __('message.payment_successfull'));
                }
            } catch (Exception $e) {
                $message = "Response.<br /><br />".json_encode($payapal_data);
                $this->sendEmail($message, setting('admin_email'), __('message.payment_error'));
            }
        }
    }

    /**
    * Function to verify a paypal transaction
    *
    * @param array $data
    * @return boolean
    */
    public function paypalTransactionVerification($data)
    {
        $paypalUrl = setting('paypal_environment') == 'testing' ? env('PAYPAL_SANDBOX_URL') : env('PAYPAL_URL');

        $req = 'cmd=_notify-validate';
        foreach ($data as $key => $value) {
            $value = urlencode(stripslashes($value));
            $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
            $req .= "&$key=$value";
        }

        $ch = curl_init($paypalUrl);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        $res = curl_exec($ch);

        if (!$res) {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: [$errno] $errstr");
        }

        $info = curl_getinfo($ch);

        // Check the http response
        $httpCode = $info['http_code'];
        if ($httpCode != 200) {
            throw new Exception("PayPal responded with http code $httpCode");
        }

        curl_close($ch);

        return $res === 'VERIFIED';
    }

    /**
     * Ajax Function to make paystack payment by employer
     *
     * @return void
     */
    public function paystackPayment(Request $request)
    {
        $this->checkIfDemo();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$request->input('response'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".setting('paystack_secret_key'),
                "Cache-Control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        if ($error) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $error
            )));
        } else {
            $decoded_response = objToArr(json_decode($response));
            if ($decoded_response['status'] == true) {
                //Preparing data
                $sel = $this->selectedBreakup($request->input('selected'));
                $decoded_response['balance_transaction'] = $decoded_response['data']['id'];
                $decoded_response['currency'] = $decoded_response['data']['currency'];
                $this->addMembershipToDb($decoded_response, $package['title'], $sel['package_id'], $sel['type'], $sel['amount'], employerId(), 'paystack');
                $successMessage = __('message.payment_successfull')." (". $decoded_response["balance_transaction"].")";
                echo json_encode(array(
                    'success' => 'true',
                    'messages' => $successMessage,
                ));
            }
        }
    }

    /**
    * Function for paystack webhook
    *
    * @return void
    */
    public function paystackWebhook(Request $request)
    {
        return "success";
    }

    /**
    * Function for paystack callback
    *
    * @return void
    */
    public function paystackCallback(Request $request)
    {
        return "success";
    }

    /**
    * Function to create a razorpay order
    *
    * @return void
    */
    public function razorpayOrder(Request $request)
    {
        $api = new RazorpayApi(setting('razorpay_key_id'), setting('razorpay_key_secret'));
        $data = json_decode($request->input('data'));
        $actual_amount = $data->amount;
        $currency = 'INR';
        $receipt = curRand().'_'.employerSession();
        $orderData = array(
            'receipt' => $receipt,
            'amount' => $actual_amount * 100,
            'currency' => $currency,
        );
        $order = $api->order->create($orderData);
        $order_id = $order['id'];
        $order_receipt = $order['receipt'];
        $order_amount = $order['amount'];
        $order_currency = $order['currency'];
        $order_created_at = $order['created_at'];
        $sel = $this->selectedBreakup($data->selected);
        \Session::put(array('razorpay_order_id' => $order_id));
        \Session::put(array('razorpay_selected' => $data->selected));
        return array(
            'order_id' => $order_id,
            'order_receipt' => $order_receipt,
            'order_amount' => $order_amount,
            'order_currency' => $order_currency,
            'order_created_at' => $order_created_at,
            'other_name' => $sel['package_title'].' ('.$sel['type'].') - '.setting('site_name'),
            'other_image' => setting('site_favicon'),
            'other_key' => setting('razorpay_key_id'),
            'other_prefill_name' => employerSession('first_name').' '.employerSession('last_name'),
            'other_prefill_email' => employerSession('email'),
            'other_prefill-phone' => employerSession('phone1'),
        );
    }

    /**
    * Function to verify razorpay payment
    *
    * @return void
    */
    public function razorpayVerify(Request $request)
    {
        $success = true;
        $error = "Payment Failed";
        $response = '';
        if (empty($request->input('razorpay_payment_id')) === false) {
            $api = new RazorpayApi(setting('razorpay_key_id'), setting('razorpay_key_secret'));
            try {
                $response = array(
                    'razorpay_order_id' => \Session::get('razorpay_order_id'),
                    'razorpay_payment_id' => $request->input('razorpay_payment_id'),
                    'razorpay_signature' => $request->input('razorpay_signature')
                );
                $api->utility->verifyPaymentSignature($response);
            } catch(SignatureVerificationError $e) {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        if ($success === true) {
            //Preparing data
            $sel = $this->selectedBreakup(\Session::get('razorpay_selected'));
            $package = $sel['package'];
            $response['balance_transaction'] = $response['razorpay_payment_id'];
            $response['currency'] = $package['currency_for_api'];
            $response['status'] = 1;
            $this->addMembershipToDb($response, $package['title'], $sel['package_id'], $sel['type'], $sel['amount'], employerId(), 'razorpay');
            $message = __('message.payment_successfull')." (". $response["balance_transaction"].")";
            echo json_encode(array('success' => 'true', 'messages' => $message,));
            return redirect(route('employer-memberships'));
        } else {
            $message = "<p>Your payment failed</p><p>{".$error."}</p>";
            die($message);
        }
    }

    /**
    * Function to store membership
    *
    * @param string $res
    * @param string $title
    * @param integer $package_id
    * @param string $package_type
    * @param double $amount
    * @param integer $employer_id
    * @param string $type
    * @return void
    */
    private function addMembershipToDb($res, $title, $package_id, $package_type, $amount, $employer_id, $type)
    {
        //Getting employer and package detail
        $employer_detail = Employer::getEmployer('employers.employer_id', encode($employer_id));
        $package_detail = Package::getPackage('packages.package_id', $package_id);

        //Removing unnecessary variables from package detail
        unset(
            $package_detail['package_id'],
            $package_detail['currency_for_api'],
            $package_detail['status'],
            $package_detail['is_free'],
            $package_detail['is_top_sale'],
            $package_detail['created_at'],
            $package_detail['updated_at']
        );

        //Recording payment record to our database
        $data['package_id'] = decode($package_id);
        $data['payment_type'] = $type;
        $data['package_type'] = $package_type;
        $data['details'] = json_encode($package_detail);
        $data['separate_site'] = $package_detail['separate_site'];
        $data['price_paid'] = $amount;
        $data['title'] = $title;
        $data['employer_id'] = $employer_detail['employer_id'];
        $data['payer_email'] = $employer_detail['email'];
        $data['receiver_email'] = $type == 'paypal' ? setting('paypal_email') : setting('admin_email');
        $data['created_at'] = date('Y-m-d G:i:s');
        $data['transaction_id'] = $res["balance_transaction"];
        $data['payment_status'] = $res["status"];
        $data['payment_currency'] = $res["currency"];
        $data['file'] = issetVal($res, "file");
        $data['message'] = issetVal($res, "message");
        $data['status'] = 1;
        $data['expiry'] = packageExpiry($package_type);
        $data['response'] = json_encode($res);

        //Adding to database
        Membership::addPayment($data);

        //Creating system notification for admin
        $employer = $employer_detail['first_name'].' '.$employer_detail['last_name'].' ('.$employer_detail['email'].')';
        Notification::do('membership', __('message.membership_renewed_by').' '.$employer);
    }

    private function selectedBreakup($selected) 
    {
        $selected = explode('-', decode($selected));
        $employer = isset($selected[0]) ? $selected[0] : '';
        $package_id = isset($selected[1]) ? $selected[1] : '';
        $type = isset($selected[2]) ? $selected[2] : '';
        $package = Package::getPackage('packages.package_id', $package_id);
        $package_title = $package['title'];
        $amount = $type == "monthly" ? $package['monthly_price'] : $package['yearly_price'];
        return array(
            'employer' => $employer,
            'package_id' => $package_id,
            'package_title' => $package_title,
            'package' => $package,
            'type' => $type,
            'amount' => $amount
        );
    }
}