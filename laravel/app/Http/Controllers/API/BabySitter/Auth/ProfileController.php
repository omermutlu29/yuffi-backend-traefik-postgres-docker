<?php


namespace App\Http\Controllers\API\BabySitter\Auth;


use App\Models\BabySitter;
use App\Models\BabySitterSmsCode;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\BabySitterResource;
use App\Models\Parents;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:baby_sitter');
        $this->middleware('bs_first_step')->except(['store_general_information', 'getProfile']);
    }

    /**
     * @param Request $request
     * @return BabySitterResource|\Illuminate\Http\Response
     * Bakicinin temel bilgileri alınacak. Kaydedilecek.
     */
    public function store_general_information(Request $request)
    {
        try {
            $baby_sitter = Auth::user();
            /*
             *   $validator = Validator::make($request->all(), [
                  'name' => 'required',
                  'surname' => 'required',
                  'tc' => 'required',
                  'gender_id' => 'required',
                  'birthday' => 'required',
                  'iban' => 'required',
                  'criminal_record' => 'required|file',
                  'address' => 'required',
                  'email' => 'required',
                  'photo' => 'required|file'
              ]);
              if ($validator->fails()) {
                  return $this->sendError('Validation Error.', $validator->errors());
              }
             */

            $baby_sitter->name = $request->name;
            $baby_sitter->surname = $request->surname;
            $baby_sitter->tc = $request->tc;
            $baby_sitter->gender_id = $request->gender_id;
            $baby_sitter->birthday = $request->birthday;
            $baby_sitter->address = $request->address;
            $baby_sitter->email = $request->email;
            $baby_sitter->photo = $request->photo;
            $baby_sitter->baby_sitter_status_id = 2;
            if ($this->checkIBAN($request->iban)) {
                $baby_sitter->iban = $request->iban;
            } else {
                return $this->sendError('Hata', 'Gönderilen iban hatalı formattadır');
            }

            //Buraya dosya yükleyeceğiz
            $baby_sitter->criminal_record = $request->criminal_record;

            $baby_sitter->save();
            if ($baby_sitter->sub_merchant != null) {
                $subMerchant = $this->update_iyzico_submerchant($baby_sitter);
                if ($subMerchant->getStatus() == "failure") {
                    return $this->sendError($subMerchant->getErrorMessagetProfilege(), null, 409);
                }
            } else {

                $subMerchant = $this->insert_iyzico_submerchant($baby_sitter);
                if ($subMerchant->getStatus() == "failure") {
                    return $this->sendError($subMerchant->getErrorMessage(), null, 409);
                }
                $baby_sitter->sub_merchant = $subMerchant->getSubMerchantKey();
                $baby_sitter->save();

            }
            // SOR
            $baby_sitter = BabySitter::with('baby_sitter_status:id,name', 'child_year:id,name', 'gender:id,name', 'child_gender:id,name','accepted_locations','avaliable_towns')->find(Auth::id());

            $success['baby_sitter'] = $baby_sitter;
            return $this->sendResponse($success, 'Veri Başarı ile Getirildi!');
        } catch (\Exception $e) {
            return $this->sendError($e->getLine(), $e->getMessage());
        }
    }

    public function update_iban(Request $request)
    {
        $baby_sitter = Auth::user();
        if ($request->iban) {
            if ($this->checkIBAN($request->iban)) {
                $baby_sitter->iban = $request->iban;
            } else {
                return $this->sendError('Hata', 'Gönderilen iban hatalı formattadır');
            }
        }
        $baby_sitter->save();
        $subMerchant = $this->update_iyzico_submerchant($baby_sitter);
        if ($subMerchant->getStatus() == "failure") {
            return $this->sendError($subMerchant->getErrorMessage(), null, 409);
        }
        return BabySitterResource::make($baby_sitter);
    }

    protected function update_iyzico_submerchant(BabySitter $babySitter)
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey('sandbox-lSbnjzUNb16LIlL7jS4GawM8jMNz5Am8');
        $options->setSecretKey('sandbox-h46lZ9TxaCxuIHudfZ2ulOWyapHfwXzh');
        $options->setBaseUrl('https://sandbox-api.iyzipay.com');
        $request = new \Iyzipay\Request\UpdateSubMerchantRequest();

        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($babySitter->tc);
        $request->setSubMerchantKey($babySitter->sub_merchant);
        $request->setIban($babySitter->iban);
        $request->setAddress($babySitter->address);
        $request->setContactName($babySitter->name);
        $request->setContactSurname($babySitter->surname);
        $request->setEmail($babySitter->email);
        $request->setGsmNumber('+90' . $babySitter->phone);
        $request->setName($babySitter->name . ' ' . $babySitter->surname);
        $request->setIdentityNumber($babySitter->tc);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);


        $subMerchant = \Iyzipay\Model\SubMerchant::update($request, $options);
        return $subMerchant;

    }

    protected function insert_iyzico_submerchant(BabySitter $baby_sitter)
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey('sandbox-lSbnjzUNb16LIlL7jS4GawM8jMNz5Am8');
        $options->setSecretKey('sandbox-h46lZ9TxaCxuIHudfZ2ulOWyapHfwXzh');
        $options->setBaseUrl('https://sandbox-api.iyzipay.com');
        $request = new \Iyzipay\Request\CreateSubMerchantRequest();


        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($baby_sitter->tc);
        $request->setSubMerchantExternalId($baby_sitter->tc);
        $request->setSubMerchantType(\Iyzipay\Model\SubMerchantType::PERSONAL);
        $request->setAddress($baby_sitter->address);
        $request->setContactName($baby_sitter->name);
        $request->setContactSurname($baby_sitter->surname);
        $request->setEmail($baby_sitter->email);
        $request->setGsmNumber('+90' . $baby_sitter->phone);
        $request->setName($baby_sitter->name . ' ' . $baby_sitter->surname);
        $request->setIban($baby_sitter->iban);
        $request->setIdentityNumber($baby_sitter->tc);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);


        $subMerchant = \Iyzipay\Model\SubMerchant::create($request, $options);
        return $subMerchant;


    }

    protected function checkIBAN($iban)
    {

        // Normalize input (remove spaces and make upcase)
        $iban = strtoupper(str_replace(' ', '', $iban));

        if (preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/', $iban)) {
            $country = substr($iban, 0, 2);
            $check = intval(substr($iban, 2, 2));
            $account = substr($iban, 4);

            // To numeric representation
            $search = range('A', 'Z');
            foreach (range(10, 35) as $tmp)
                $replace[] = strval($tmp);
            $numstr = str_replace($search, $replace, $account . $country . '00');

            // Calculate checksum
            $checksum = intval(substr($numstr, 0, 1));
            for ($pos = 1; $pos < strlen($numstr); $pos++) {
                $checksum *= 10;
                $checksum += intval(substr($numstr, $pos, 1));
                $checksum %= 97;
            }

            return ((98 - $checksum) == $check);
        } else
            return false;
    }

    public function getProfile()
    {
        $baby_sitter = BabySitter::with('baby_sitter_status:id,name', 'child_year:id,name', 'gender:id,name', 'child_gender:id,name','accepted_locations','avaliable_towns')->find(Auth::id());

        $success['baby_sitter'] = $baby_sitter;

        return $this->sendResponse($success, 'Veri Başarı ile Getirildi!');
    }


}
