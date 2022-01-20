<?php


namespace App\Http\Controllers\API\Parent\Filter;


use App\Models\BabySitter;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\BabySitterResource;
use App\Http\Resources\FilterResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\Parent\Child\ChildController;

class FilterController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:parent');
    }

    public function create()
    {

    }

    /**
     * @param Request $request
     * Çocuklarda özel durum var mı ?
     * Bakıcının o gün boşluğu var mı ?
     * Çocuk Cinsiyeti
     * Çocuk Sayısı
     * Depositi Ödedi mi ?
     * İlçeye Hizmet veren bakıcılar
     * Bakıcının Cinsiyeti
     * Lokasyon : İş yeri, Ev vb. @AppointmentLocation
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function filter(Request $request)
    {
        try {
            $query = BabySitter::query();
            $parent = Auth::user();
            $json = json_encode($request->all());
            $data = json_decode($json);
            if (isset($data->personal_info)) {
                $parent->name = $data->personal_info->name;
                $parent->surname = $data->personal_info->surname;
                $parent->save();
            }

            if (count($parent->parent_children) == 0) {
                if (isset($data->children) && count($data->children) > 0) {

                    $children = (new ChildController())->store($request);
                    if (!($children && count($children) > 0)) {
                        return $this->sendError('Gerekli parametreleri girmediniz!', 'Hata');
                    }
                }
            }

            if (isset($data->search_param) &&
                isset($data->search_param->town_id) &&
                isset($data->search_param->date) &&
                isset($data->search_param->time) &&
                isset($data->search_param->hour)) {

                for ($i = 0; $i < $data->search_param->hour; $i++) {//Saatleri array'e attık
                    $time = (date('H:i', strtotime("+" . $i . " Hour " . $data->search_param->time)));
                    $times[] = $time;
                }

                //Sahip olduğu çocuklara bakacağız
                $child_gender_status = $this->getChildGenderStatus($parent);
                if ($child_gender_status != null) {
                    $query->whereRaw('( child_gender_id = ' . $child_gender_status . '  OR child_gender_id = 3 )');
                } else {
                    $query->whereRaw('( child_gender_id = 3 )');
                }


                //Sahip olduğu çocuklarda özel durum var mı ?
                $disable = $this->areThereDisableChild($parent);
                if ($disable) {

                    $query->where('disabled_status', 1);
                }

                //Eğer Cinsiyet Tercihi varsa
                if (isset($data->search_param->gender_id) && $data->search_param->gender_id !== 3) { //3, farketmez demek dolayısıyla 3 ü case etmiyorz
                    $query->where('gender_id', $data->search_param->gender_id);
                }


                $query->whereRaw('( child_count >= ' . count($parent->parent_children) . '  OR child_count = null OR child_count = 0 )')->get();//Çocuk Sayısı
                $query->where('deposit', 30);//Depozitosu var mı
                $query->whereHas('available_towns', function ($q) use ($data) {
                    $q->where('town_id', (int)$data->search_param->town_id);
                });//İlçesi işaretli olanlar

                $query->whereHas('baby_sitter_available_dates', function ($q) use ($data, $times) {//O gün yer var mı ?
                    $q->where('date', $data->search_param->date);
                    foreach ($times as $time) {
                        $q->whereHas('times', function ($q1) use ($time) {//O günün saatlerinde yer var mı varsa meşgul mü değil mi ?
                            $q1->where('start', $time)->where('time_status_id', 1);
                        });
                    }
                });


                $query->whereHas('accepted_locations', function ($q) use ($data) {
                    $q->where('location_id', $data->search_param->location_id);
                });

                $babySitters = ($query->get());
                if (count($babySitters) > 0) {
                    return $this->sendResponse(FilterResource::collection($babySitters), 'Bakıcılar Getirildi!');
                } else {
                    return $this->sendResponse([], 'Belirttiğiniz kriterlerde bakıcı bulunmamaktadır!');

                }

            } else {
                return $this->sendError('Gerekli parametreleri girmediniz!', 'Hata');
            }


        } catch
        (\Exception $exception) {
            return $this->sendError($exception, $exception->getMessage());
        }
    }


    public function getChildGenderStatus($parent)
    {
        $child_gender_male = false;
        $child_gender_female = false;

        foreach ($parent->parent_children as $child) {
            if ($child->gender_id == 1) {
                $child_gender_male = true;
            } else {
                $child_gender_female = true;
            }
        }
        $child_gender_status = 0;
        if ($child_gender_female && $child_gender_male) {
            return null;
        } elseif ($child_gender_male && !$child_gender_female) {
            return 1;
        } elseif ($child_gender_female && !$child_gender_male) {
            return 2;
        }
    }

    public function areThereDisableChild($parent)
    {
        foreach ($parent->parent_children as $child) {
            if ($child->disable == 1) {
                return true;
            }
        }
        return false;
    }

}
