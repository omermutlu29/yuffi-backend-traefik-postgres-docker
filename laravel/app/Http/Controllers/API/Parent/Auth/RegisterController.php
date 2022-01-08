<?php


namespace App\Http\Controllers\API\Parent\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Resources\ParentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:parent');
    }

    /**
     * @param Request $request
     * @return BabySitterResource|\Illuminate\Http\Response
     * Bakicinin temel bilgileri alınacak. Kaydedilecek.
     */
    public function updateInformation(Request $request)
    {
        try {
            $parent = Auth::user();
            /*
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'surname'=>'required',
                'tc'=>'required',
                'birthday'=>'required',
                'service_contract'=>'required',
                'gender_id'=>'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            } */
            $parent->fill($request->all());
            $parent->save();
            //return ParentResource::make($parent);
            return $this->getProfile();
        }catch(\Exception $e){
            return $this->sendError($e->getLine(),$e->getMessage());
        }
    }

    public function getProfile(){
        $parent=Auth::user();
        if ($parent!=null){
        $success['parent']=$parent;
        $success['children']=$parent->parent_children;
        return $this->sendResponse($success,'Veri Başarı ile Getirildi!');
        }
    }

}
