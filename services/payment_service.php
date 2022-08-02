<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/vendor/phpseclib/phpseclib/phpseclib/Crypt/RSA.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/libs/vendor/phpseclib/phpseclib/phpseclib/Math/BigInteger.php';

class PaymentService
{
    private $partnerCode = "MOMOSCN920220707";
    private $publicKey = "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAkBhLl6ycdtOx/brm8FSiguQ0822Nj+x+mre1YS+BIAYWn2cnCH7R7u7i9K1cw4rfDzdXDiUyMvhwh86fwGJ3lY+B+sn5V0QkXJHp3zAbZkVsFb5COt+P94Vv2riVaE6uDOMucahRkWFR4L8GOW2f8mPk01cWj+LatqrcMRgaXd/n6l497vgW4HKfmhSvGct0UbUGauJVcrYyQXjYADWvacoD3aeKznkp28XmUHp4sc0wOMnSVSbn58NWvD4TTN6UBHP2EUMP56WmrObc8syqnxvDYDMg/IJMiEO/W0mCh0BKyKVFa0FtF5Ly4pKEI4wuIiC0Fsjd+Edj1Kbd+Zu13RSiOBPYNyjtqNLKEpEzLJeU1TjUgLg3zTbIDH/uDYUV2YrliGbj7Cp+5vATUabf4gA5CQ4/75ik2wPASa2HCrsTPs8VWQxwDr6fntcXPsCzKArWIcDlDcDb20LFnJbHzw1PR+t7b5TTiJwEEfE2mwE5sryG+rk8Jo8xXYZ0lxHr22Qj9zYrD4qvS0IUSwqo8inkLHrZC6K/R2X/EgkjurqJy1ze73bEl32X3YVGLBOjoTcdSsEubMaDdrMEn2/cMXHQZQMYF5bJ6Yo8v5H5Hsbmuy/sJ292gEW52EMPE8j5zvW1nhEeAse3kodepLwAV4tp5t30CJCX/GHrjF+n58UCAwEAAQ==";
    private $secretKey = "A3LvVs5BBiEl5SmcEgeW4hg0s6rcfCJp";
    public function payMomo($customerNumber,$appData,$amount, $partnerRefId)
    {
        $jsonArr = array(
            "partnerCode" => $this ->partnerCode,
            "partnerRefId" => $partnerRefId,
            "partnerTransId" => "",
            "amount" => $amount,
            "partnerName" => "CSC",
        );
        $hash = $this->encryptRSA($jsonArr, $this -> publicKey);
        $request = array(
            "partnerCode" => $this ->partnerCode,
            "customerNumber" => $customerNumber,
            "partnerRefId" => $partnerRefId,
            "appData" => $appData,
            "hash" => $hash,
            "description" => "Thanh toán dịch vụ CSC",
            "version" => 2,
            "payType" => 3
        );

        $data_string = json_encode($request);
        $curl = curl_init("https://test-payment.momo.vn/pay/app");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
          
    }

    public function confirmMomo($customerNumber, $partnerRefId, $transid, $requestType) {
        $requestId = "RequestCSCMomoPay" . time();
    

        $rawHash = "partnerCode=" . $this -> partnerCode . 
                   "&partnerRefId=" . $partnerRefId . 
                   "&requestType=" . $requestType . 
                   "&requestId=" . $requestId . 
                   "&momoTransId=" . $transid;


        $signature = hash_hmac("sha256", $rawHash, $this -> secretKey);
        $request = array(
            "partnerCode" => $this ->partnerCode,
            "partnerRefId" => $partnerRefId,
            "requestType" => $requestType,
            "requestId" => $requestId,
            "momoTransId" => $transid,
            "signature" => $signature,
            "customerNumber" => $customerNumber
        );

        $data_string = json_encode($request);
        $curl = curl_init("https://test-payment.momo.vn/pay/confirm");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }


    public function encryptRSA(array $rawData, $publicKey)
    {
        $rawJson = json_encode($rawData, JSON_UNESCAPED_UNICODE);
        $rsa = new Crypt_RSA();
        $rsa->loadKey($publicKey);
        $rsa->setEncryptionMode(2);
        $cipher = $rsa->encrypt($rawJson);
        return base64_encode($cipher);
    }
}


