<?php


namespace App\Http\Controllers\API\Parent\Filter;


use App\Models\Appointment;
use App\Models\BabySitter;
use App\Models\BabySitterAvailableDate;
use App\Models\BabySitterAvailableTime;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\Parent\Child\ChildController;
use App\Http\Controllers\API\Parent\Payment\PaymentController;
use App\Http\Resources\BabySitterResource;
use App\Http\Resources\FilterResource;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class BabySitterController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:parent');
    }

    public function show(BabySitter $babySitter)
    {
        $data = [];
        $data['general'] = BabySitterResource::make($babySitter);
        $data['comment_count'] = count($babySitter->comments);
        $data['comments'] = $babySitter->comments()->with('appointment.parent')->get();
        $data['avg_point'] = $babySitter->points()->average('point');
        $data['appointment_count'] = $babySitter->appointments()->count();
        $data['clothing'] = $babySitter->points()->where('point_type_id', 1)->average('point');
        $data['timing'] = $babySitter->points()->where('point_type_id', 2)->average('point');
        $data['contact'] = $babySitter->points()->where('point_type_id', 3)->average('point');
        $data['choose'] = route('baby-sitter.choose', $babySitter->id);
        return $this->sendResponse($data, 'Bilgiler başarı ile getirildi!');
    }

    public function choose(Request $request, BabySitter $babySitter)
    {
        $parent = Auth::user();
        $json = json_encode($request->all());
        $data = json_decode($json);

        if ($this->filter($data, $babySitter)) {
            $appointment = new Appointment();
            $appointment->baby_sitter_id = $babySitter->id;
            $appointment->parent_id = $parent->id;
            $appointment->hour = $data->search_param->hour;
            $appointment->price = $data->search_param->hour * $babySitter->price_per_hour;
            $appointment->date = $data->search_param->date;
            $appointment->start = $data->search_param->time;
            $appointment->finish = (date('H:i', strtotime("+" . $data->search_param->hour . " Hour " . $data->search_param->time)));
            $appointment->appointment_location_id = $data->search_param->location_id;
            $appointment->location = $data->search_param->location;
            $appointment->town_id = $data->search_param->town_id;
            $appointment->appointment_status_id = 1;
            $appointment->save();


            foreach ($parent->parent_children as $child) {
                $appointment->registered_children()->attach($child->id);
            }

            (new PushNotificationService())->push('Kabul etti','şöyle oldu böyle oldu',$appointment->baby_sitter->google_st);

            return $this->sendResponse($appointment, "Başarılı bir şekilde oluşturuldu");

            // TODO
            //  BabySitter'a bildirim gidecek


        } else {
            return $this->sendError('Hata!', 'Bakıcı belirttiğiniz zaman(lar) içerisinde müsait görünmemektedir!');
        }


    }

    private function filter($data, BabySitter $babySitter)
    {
        $query = BabySitter::query();
        $parent = Auth::user();
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
            $query->where('id', $babySitter->id);
            $babySitter = ($query->first());
            return $babySitter;
        } else {
            return false;
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
