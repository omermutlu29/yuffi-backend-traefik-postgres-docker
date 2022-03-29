<?php


namespace App\Http\Controllers\API\Parent\Favorite;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Parent\BabySitter\FavoriteRequest;
use Illuminate\Support\Facades\Log;

class FavoriteController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:parent');
    }

    public function addToFavorites(FavoriteRequest $request)
    {
        try {
            $result = auth()->user()->favroite_baby_sitters()->attach([$request->baby_sitter_id]);
            if (!$result) {
                throw new \Exception('Eklenemedi', 400);
            }
            return $this->sendResponse(auth()->user()->favorite_baby_sitters, 'Bakıcı favroilere eklendi!');
        } catch (\Exception $exception) {
            return $this->sendError('Hata', ['Bir sorun ile karşılaşıldı'], 400);
        }
    }

    public function deleteFromFavorites(FavoriteRequest $request)
    {
        try {
            $result = auth()->user()->favroite_baby_sitters()->detach([$request->baby_sitter_id]);
            if (!$result) {
                throw new \Exception('Silinemedi', 400);
            }
            return $this->sendResponse(auth()->user()->favorite_baby_sitters, 'Bakıcı favorilerden silindi!');
        } catch (\Exception $exception) {
            Log::info($exception);
            return $this->sendError('Hata', ['Bir sorun ile karşılaşıldı'], 400);
        }
    }

}
