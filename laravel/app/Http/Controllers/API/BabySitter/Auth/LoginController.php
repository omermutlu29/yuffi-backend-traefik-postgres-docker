<?php


namespace App\Http\Controllers\API\BabySitter\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Resources\BabySitterResource;
use App\Models\BabySitter;
use App\Models\BabySitterSmsCode;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
    public function __construct()
    {
        //$this->middleware('auth:baby_sitter', ['except' => ['login_one', 'login_two']]);
    }

    public function loginOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'kvkk' => 'required',
        ]);
        //return ($request->all());
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $babySitters = BabySitter::where('phone', $request->phone)->get();
        if (count($babySitters) > 0) {
            $babySitter = $babySitters[0];
            if ($babySitter->black_list == 1) {
                return $this->sendError('Uygulamaya erişiminiz engellenmiştir. Lütfen bizimle iletişime geçin.', 401);
            }
        } else {
            $babySitter = new BabySitter();
            $babySitter->phone = $request->phone;//Enesten Gelecek
            $babySitter->network = $request->ip();//Enesten Gelecek
            $babySitter->kvkk = $request->kvkk;//Enesten Gelecek
            $babySitter->google_st = $request->google_st;//Enesten Gelecek
            $babySitter->save();
        }
        $code = $this->generateCode($babySitter);
        if ($code) {
            $result = true;//$this->smsSend($code, $babySitter->phone);
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

        $babySitters = BabySitter::where('phone', $request->phone)->get();
        if (count($babySitters) > 0) {
            $babySitter = $babySitters[0];
        } else {
            return $this->sendError('Yanlış numara!', $validator->errors());
        }
        $smsCode = $request->code;

        if ($babySitter != null && $smsCode != null) {
            $smsCodes = BabySitterSmsCode::where('baby_sitter_id', $babySitter->id)->where('code', $smsCode)->get();
            if (count($smsCodes) > 0) {
                $success['accepted'] = true;
                $success['baby_sitter'] = BabySitterResource::make($babySitter);
                $success['token'] = $babySitter->createToken('baby_sitter')->accessToken;
                return $this->sendResponse($success, 'Başarılı bir şekilde giriş yapıldı!');
            } else {
                return $this->sendError('Yanlış kod!', $validator->errors());
            }
        } else {
            return $this->sendError('Eksik Veri Gönderimi', null, 400);
        }
    }

    /**
     * @param User $user
     * @return bool|int
     */
    protected function generateCode(BabySitter $babySitter)
    {
        $babySitter->sms_codes()->delete();
        $sms = 1111;
        $smsCode = new BabySitterSmsCode();
        $smsCode->code = $sms;
        $smsCode->baby_sitter_id = $babySitter->id;
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
            $log->user_id = BabySitter::where('phone', $phone)->first()->id;
            $log->save();
            return false;
        } else {
            return true;
        }

    }
}
