<?php
namespace App\Http\Controllers\API\Parent\Child;

use App\Models\ChildYear;
use App\Models\Gender;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\ChildResource;
use App\Models\ParentChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:parent');
    }

    public static function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $parent = Auth::user();
        return ChildResource::collection($parent->parent_children()->with(['child_year', 'gender'])->get());
    }

    public function store(Request $request): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        Auth::user()->parent_children()->delete();

        try {
            $parent = Auth::user();
            $json = json_encode($request->all());
            $data = json_decode($json);
            $data = $data->children;

            $children = [];
            foreach ($data as $datum) {
                if (ChildYear::find($datum->child_year_id) && Gender::find($datum->gender_id)) {
                    $child = new ParentChild();
                    $child->child_year_id = $datum->child_year_id;
                    $child->gender_id = $datum->gender_id;
                    $child->disable = $datum->disable;
                    $children[] = $child;
                }
            }
            $parent->parent_children()->saveMany($children);
            return $this->index();
        } catch (\Exception $exception) {
            return $this->sendError('Bir Hata Oluştu', $exception->getMessage());
        }
    }

    public function update(Request $request, ParentChild $child): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $parent = Auth::user();
            $json = json_encode($request->all());
            $datum = json_decode($json);
            if ($child->parent_id != $parent->id) {
                return $this->sendError('Sadece kendi bilgilerinizi güncelleyebilirsiniz.', 'Hata');
            }
            if (ChildYear::find($datum->child_year_id) && Gender::find($datum->gender_id)) {
                $child->child_year_id = $datum->child_year_id;
                $child->gender_id = $datum->gender_id;
                $child->disable = $datum->disable;
                $child->save();
            }
            return $this->index();
        } catch (\Exception $exception) {
            return $this->sendError('Bir Hata Oluştu', $exception->getMessage());
        }
    }

    public function destroy(ParentChild $child): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    {
        if ($child->parent_id == Auth::user()->id) {
            $child->delete();
        } else {
            return $this->sendError('Sadece kendi ürünlerinizi silebilirsiniz.', 'Hata');
        }
        return response()->json(['data' => $this->index(), 'success' => true]);
    }
}
