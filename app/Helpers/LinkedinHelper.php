<?php

namespace App\Helpers;

class LinkedinHelper
{
    private $ci;
    private $uri;
    private $sec;

    public function __construct()
    {
        $this->ci = setting('linkedin_id');
        $this->sec = setting('linkedin_secret');
        $this->uri = setting('linkedin_redirect_uri');
    }

    public function getLink($type = '')
    {
        $loginLink = 'https://www.linkedin.com/oauth/v2/authorization?response_type=code';
        $loginLink .= '&client_id='.$this->ci;
        $loginLink .= '&redirect_uri='.$this->uri;
        $loginLink .= '&state='.base64UrlEncode('{"employer":"'.empSlug().'","type":"'.$type.'"}');
        $loginLink .= '&scope=r_liteprofile r_emailaddress';
        return $loginLink;
    }

    public function getAccessToken($code)
    {
        $url = 'https://www.linkedin.com/oauth/v2/accessToken'; 
        $url .= '?grant_type=authorization_code';
        $url .= '&code='.$code;
        $url .= '&redirect_uri='.$this->uri;
        $url .= '&client_id='.$this->ci;
        $url .= '&client_secret='.$this->sec;
        $result = json_decode(remoteRequest($url));
        return isset($result->access_token) ? $result->access_token : '';
    }

    public function getLinkedinRefinedData($request, $accessToken)
    {
        $emailData = $this->fetchLinkedinData($accessToken, 'email');
        $idNameImageData = $this->fetchLinkedinData($accessToken);
        $email = isset($emailData['elements'][0]['handle~']['emailAddress']) ? $emailData['elements'][0]['handle~']['emailAddress'] : '';
        $firstName = isset($idNameImageData['firstName']['localized']['en_US']) ? $idNameImageData['firstName']['localized']['en_US'] : '';
        $lastName = isset($idNameImageData['lastName']['localized']['en_US']) ? $idNameImageData['lastName']['localized']['en_US'] : '';
        $image = isset($idNameImageData['profilePicture']['displayImage~']['elements'][0]['identifiers'][0]['identifier']) ?  $idNameImageData['profilePicture']['displayImage~']['elements'][0]['identifiers'][0]['identifier'] : '';
        $id = isset($idNameImageData['id']) ? $idNameImageData['id'] : '';
        return array(
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'image' => $image,
            'id' => $id,
            'state' => $request->get('state')
        );
    }

    private function fetchLinkedinData($access_token, $type = '') {
        if ($type == 'email') {
            $l = 'https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))';
        } else {
            $l = 'https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams))';
        }
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $l,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
            'authorization: Bearer '.$access_token,
            'cache-control: no-cache',
            'connection: Keep-Alive'
            ),
        );
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return 'failed';
        } else {
            $response = json_decode($response, true);
            return $response;
        }
    }    
}