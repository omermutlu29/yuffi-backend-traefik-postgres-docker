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
        try {
            if (auth()->user()->favorite_baby_sitters()->where('baby_sitter_id', $request->baby_sitter_id)->count() == 0) {
                auth()->user()->favorite_baby_sitters()->attach([$request->baby_sitter_id]);
            }
            return $this->sendResponse(auth()->user()->favorite_baby_sitters, 'Bakıcı favroilere eklendi!');
        } catch (\Exception $exception) {
            $this->sendError('Hata', ['eklenemedi'], 400);
        }

    }

    public function deleteFromFavorites(FavoriteRequest $request)
    {
        try {
            if (auth()->user()->favorite_baby_sitters()->where('baby_sitter_id', $request->baby_sitter_id)->count() > 0) {
                auth()->user()->favorite_baby_sitters()->detach([$request->baby_sitter_id]);
            }
            return $this->sendResponse(auth()->user()->favorite_baby_sitters, 'Bakıcı favorilerden silindi!');
        } catch (\Exception $exception) {
            $this->sendError('Hata', ['eklenemedi'], 400);
        }
    }

}
