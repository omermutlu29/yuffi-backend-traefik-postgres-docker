<?php


namespace App\Services\ProfileService;


use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IProfileService;
use App\Interfaces\PaymentInterfaces\ISubMerchantService;
use App\Models\BabySitter;
use Illuminate\Http\Request;


class ProfileService implements IProfileService
{
    private IUserRepository $userRepository;
    private ISubMerchantService $subMerchantService;

    public function __construct(IUserRepository $userRepository, ISubMerchantService $subMerchantService)
    {
        $this->subMerchantService = $subMerchantService;
        $this->userRepository = $userRepository;
    }

    public function updateBasicInformation(BabySitter $babySitter, Request $request)
    {
        $result = ['status' => false, 'message' => ''];
        try {
            $criminalRecordPath = $request->file('criminal_record')->store('public/criminal-records');
            $profilePhotoPath = $request->file('photo')->store('public/profile-photo');
            $request['criminal_record'] = $criminalRecordPath;
            $request['photo'] = $profilePhotoPath;
            $this->userRepository->update($babySitter->id, $request->all());
            if ($babySitter->sub_merchant != null) {
                $serviceResult = $this->subMerchantService->updateIyzicoSubMerchant($babySitter->attributesToArray());
                if ($serviceResult->getStatus() == "failure") {
                    $result['message'] = $serviceResult->getErrorMessage();
                }
            } else {
                $serviceResult = $this->subMerchantService->insertIyzicoSubMerchant($babySitter->attributesToArray());
                if ($serviceResult->getStatus() == "failure") {
                    $result['message'] = $serviceResult->getErrorMessage();
                }
            }
            $data = ['sub_merchant' => $serviceResult->getSubMerchantKey()];
            $this->userRepository->update($babySitter->id, $data);
            $result['status'] = true;
            $result['message'] = 'İşlem tamamlandı';
            return $result;
        } catch (\Exception $exception) {
            throw $exception;
        }


    }
}
