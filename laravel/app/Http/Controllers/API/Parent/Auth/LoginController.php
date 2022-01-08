<?php


namespace App\Http\Controllers\API\Parent\Auth;



use App\Http\Controllers\API\BaseController;

use App\Http\Resources\ParentResource;
use App\Models\Parents;
use App\Models\Log;
use App\Models\ParentSmsCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
    public function __construct()
    {

    }

    public function loginOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'kvkk' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $parents = Parents::where('phone', $request->phone)->get();
        if (count($parents) > 0) {
            $parent = $parents[0];
            if ($parent->black_list == 1) {
                return $this->sendError('Uygulamaya erişiminiz engellenmiştir! Lütfen bizimle iletişime geçin.', 'Uygulamaya erişiminiz engellenmiştir! Lütfen bizimle iletişime geçin.',401);
            }
        } else {
            $parent = new Parents();
            $parent->phone = $request->phone;//Enesten Gelecek
            $parent->network = $request->ip();//Enesten Gelecek
            $parent->kvkk = 1;//Enesten Gelecek
            $parent->google_st = $request->google_st;//Enesten Gelecek
            $parent->save();
        }
        $code = $this->generateCode($parent);
        if ($code) {
            $result = true;//$this->smsSend($code, $parent->phone);
            if ($result) {
                $success['result'] = 'Telefonunuza SMS Gönderildi';
                return $this->sendResponse($success, 'Telefonunuza SMS Gönderildi');
            } else {
                return $this->sendError('SMS Gönderilemedi', null, 400);
            }
        } else {
            return $this->sendError('Kod oluşturulamadı!', null, 400);
        }
    }




    public function loginTwo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'phone' => 'required',
        ]);

        $user = Parents::where('phone', $request->phone)->get();
        if (count($user) > 0) {
            $user = $user[0];
        } else {
            return $this->sendError('Yanlış numara!', $validator->errors());
        }
        $smsCode = $request->code;

        if ($user != null && $smsCode != null) {
            $smsCodes = ParentSmsCode::where('parent_id', $user->id)->where('code', $smsCode)->get();
            if (count($smsCodes) > 0) {
                $success['accepted'] = true;
                $success['user'] = ParentResource::make($user);
                $success['token'] = $user->createToken('dilara')->accessToken;
                return $this->sendResponse($success, 'Başarılı bir şekilde giriş yapıldı!');
            } else {
                return $this->sendError('Yanlış kod!', $validator->errors(), 401);
            }
        }
    }

    /**
     * @param User $user
     * @return bool|int
     */
    protected function generateCode($user)
    {
        $user->sms_codes()->delete();
        $sms = 1111;
        $smsCode = new ParentSmsCode();
        $smsCode->code = $sms;
        $smsCode->parent_id = $user->id;
        $result = $smsCode->save();
        if ($result) {
            return $sms;
        } else {
            return false;
        }

    }

    private function smsSend($code, $phone)
    {
        $baslik = '2129095285';
        $username = "2129095285"; //
        $password = urlencode("123ABCD123"); //

        $url = "https://api.netgsm.com.tr/sms/send/get/?usercode=" . $username . "&password=" . $password . "&gsmno=" . $phone . "&message=" . $code . "&msgheader=" . $baslik;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $http_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (substr($http_response, 0, 2) != "00") {
            $log = new Log();
            $log->operation = 'SMS Sending';
            $log->ip = \request()->ip();
            $log->result = 'failed';
            $log->raw_input = $url;
            $log->raw_output = $http_response;
            $log->user_id = Parents::where('phone', $phone)->first()->id;
            $log->save();
            return false;
        } else {
            return true;
        }

    }

    /**
     * @param Request $request
     */


}
