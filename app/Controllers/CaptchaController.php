<?php
namespace App\Controllers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CaptchaController extends BaseController{

    public function generatecaptcha(){
        $randomNum=rand(1111,9999);
        $layer=imagecreatetruecolor(90,60);
        $color=imagecolorallocate($layer,120,140,255);
        imagefill($layer,0,0,$color);
        $captcha_text_color=imagecolorallocate($layer,0,0,0);
        imagestring($layer,5,5,5,$randomNum,$captcha_text_color);
        ob_start();
        imagejpeg($layer);
        $image_data=ob_get_contents();
        ob_end_clean(); 
        imagedestroy($layer);


        $payload=["captcha_code"=> $randomNum];
        $jwt_token=JWT::encode($payload,getenv('JWT_SECRET'), getenv('JWT_HASH_ALGO'));
        return $this->response->setJSON(['jwt_token' => $jwt_token , 'captcha_image'=>base64_encode($image_data)]);


    }
    public function validateCaptcha() {
     
        $request_data = $this->request->getJSON();
        $input_code = $request_data->captcha_code;
        $header = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $header);

        $jwt_secret = getenv('JWT_SECRET');
        $jwt_algo = getenv('JWT_HASH_ALGO');

        try {
            $decoded_data = JWT::decode($token, new Key($jwt_secret, $jwt_algo));
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid or expired token'
            ]);
        }

        $original_code = $decoded_data->captcha_code;
        if ($input_code == $original_code) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Captcha matched'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Captcha does not match'
            ]);
        }
    }
}



