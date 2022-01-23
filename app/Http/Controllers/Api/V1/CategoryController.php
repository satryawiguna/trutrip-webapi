<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Transformers\CategoryTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rules\Password;

class CategoryController extends Controller
{
    public function actionCategories()
    {
        if (!Auth::user()->can('view', [User::class])) {
            return $this->responseUnauthorized();
        }

        $categories = (new Category())->orderBy('id', 'DESC')
            ->get();

        return fractal($categories, new CategoryTransformer())->toArray();
    }

    public function actionCategoriesListSearch(Request $request)
    {
        if (!Auth::user()->can('view', [User::class])) {
            return $this->responseUnauthorized();
        }

        $search = $request->input('search');

        $category = new Category();

        if ($search) {
            $category = $category->where([
                ['name','LIKE','%'. $search .'%']
            ]);
        }

        $categories = $category->orderBy('id', 'DESC')
            ->get();

        return fractal($categories, new CategoryTransformer())->toArray();
    }

    public function actionCategoriesPageSearch(Request $request)
    {
        if (!Auth::user()->can('view', [User::class])) {
            return $this->responseUnauthorized();
        }

        $search = $request->input('search');
        $perPage = $request->input('per_page') ?: 5;

        $category = new Category();

        if ($search) {
            $category = $category->where([
                ['name','LIKE','%'. $search .'%']
            ]);
        }

        $category = $category->orderBy('id', 'DESC')
            ->paginate($perPage);

        return fractal($category, new CategoryTransformer())->toArray();
    }

    public function actionCategory($id)
    {
        if (!Auth::user()->can('view', [User::class])) {
            return $this->responseUnauthorized();
        }

        $category = Category::find($id);

        if (!$category)
            return $this->responseUnprocessable(new MessageBag(['Category is not found']));

        return fractal($category, new CategoryTransformer())->toArray();
    }

    public function actionCategoryStore(Request $request)
    {
        if (!Auth::user()->can('create', [User::class])) {
            return $this->responseUnauthorized();
        }

        $validatedCategoryStore = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($validatedCategoryStore->fails()) {
            return $this->responseUnprocessable($validatedCategoryStore->errors());
        }

        try {
            $category = new Category([
                'name' => $request->input('name')
            ]);
            $category->save();

            return fractal($category, new CategoryTransformer())->toArray();
        } catch (Exception $e) {
            return $this->responseServerError($e->getMessage());
        }
    }

    public function actionCategoryUpdate($id, Request $request)
    {
        if (!Auth::user()->can('update', [User::class])) {
            return $this->responseUnauthorized();
        }

        $validatedCategoryUpdate = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($validatedCategoryUpdate->fails()) {
            return $this->responseUnprocessable($validatedCategoryUpdate->errors());
        }

        $category = Category::find($id);

        if (!$category)
            return $this->responseUnprocessable(new MessageBag(['Category is not found']));

        try {
            $category->update([
                "name" => $request->input('name')
            ]);

            return fractal($category, new CategoryTransformer())->toArray();
        } catch (Exception $e) {
            return $this->responseServerError($e->getMessage());
        }
    }

    public function actionCategoryDelete($id)
    {
        if (!Auth::user()->can('delete', [User::class])) {
            return $this->responseUnauthorized();
        }

        $category = Category::find($id);

        if (!$category)
            return $this->responseUnprocessable(new MessageBag(['Category is not found']));

        $category->delete();

        return $this->responseSuccess('Category deleted');
    }
}
