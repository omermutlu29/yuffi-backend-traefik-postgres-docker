<?php


namespace App\Http\Controllers\API\Parent\Favorite;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Parent\BabySitter\FavoriteRequest;

class FavoriteController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:parent');
    }

    public function addToFavorites(FavoriteRequest $request)
    {
        $result = auth()->user()->favorite_baby_sitters()->attach([$request->baby_sitter_id]);
        if (!$result) {
            throw new \Exception('Eklenemedi', 400);
        }
        return $this->sendResponse(auth()->user()->favorite_baby_sitters, 'Bakıcı favroilere eklendi!');
    }

    public function deleteFromFavorites(FavoriteRequest $request)
    {
        $result = auth()->user()->favorite_baby_sitters()->detach([$request->baby_sitter_id]);
        if (!$result) {
            throw new \Exception('Silinemedi', 400);
        }
        return $this->sendResponse(auth()->user()->favorite_baby_sitters, 'Bakıcı favorilerden silindi!');

    }

}
