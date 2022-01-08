<?php


namespace App\Http\Controllers\API\BabySitter\Preferences;


use App\Models\BabySitterAvaliableDate;
use App\Models\BabySitterAvaliableTime;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\CalendarResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends BaseController
{

    public function __construct()
    {
        $this->middleware(['auth:baby_sitter', 'bs_first_step', 'bs_second_step', 'deposit']);
    }

    public function store(Request $request)
    {
        $baby_sitter = Auth::user();
        $json = json_encode($request->all());
        $data = json_decode($json);
        $nowTime = Carbon::now('+3:00')->toTimeString();
        $today = Carbon::now()->toDateString();
        if (count($data) > 0) {
            foreach ($data as $datum) {

                if (count($datum->hours) > 0) {
                    if ($today > $datum->date) return $this->sendError('Hata Mesajı', 'Geçmişe ait bir güne veri ekleyemezsiniz');
                    $babySitterAvaliableDate = BabySitterAvaliableDate::firstOrCreate(['date' => $datum->date, 'baby_sitter_id' => $baby_sitter->id]);
                    foreach ($datum->hours as $hour) {
                        if (($today == $datum->date && $nowTime < $hour->start)) {
                            return $this->sendError('Hata Mesajı', $nowTime . ' saatinden ileri bir saat seçmelisiniz.');
                        }
                        $babySitterAvaliableTime = BabySitterAvaliableTime::firstOrCreate(['avaliable_date_id' => $babySitterAvaliableDate->id, 'start' => $hour->start, 'finish' => $hour->end, 'time_status_id' => 1]);
                        $babySitterAvaliableTime->save();
                    }
                }
            }
        }
        return $this->sendResponse($this->get(), 'Kaydetme işlemi başarılı!');
    }

    public function get()
    {
        $date = (date('Y-m-d', time()));
        $nextDay = date('Y-m-d', strtotime("+15 days"));
        $baby_sitter = Auth::user();
        return CalendarResource::collection($baby_sitter
            ->baby_sitter_avaliable_dates()
            ->where('date', '>=', $date)
            ->where('date', '<=', $nextDay)
            ->with(['times', 'times.time_status'])
            ->get());
    }

    public function update(Request $request, BabySitterAvaliableTime $babySitterAvaliableTime)
    {
        if ($babySitterAvaliableTime->baby_sitter_avaliable_date->baby_sitter->id == Auth::user()->id) {
            $babySitterAvaliableTime->is_active = $request->is_active;
            $babySitterAvaliableTime->save();
            return $this->get();
        }
    }

    public function delete(BabySitterAvaliableTime $babySitterAvaliableTime)
    {
        $babySitterAvaliableTime->delete();
        return $this->get();
    }


}
