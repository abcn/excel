<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/23
 * Time: 上午10:31
 */

namespace App\Http\Controllers\Backend\Article;


use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Article\Type\CreateArticleTypeRequest;
use App\Http\Requests\Backend\Article\Type\StoreArticleTypeRequest;
use App\Http\Requests\Backend\Article\Type\UpdateArticleTypeRequest;
use App\Repositories\Backend\Article\ArticleTypeContract;

/**
 * Class ArticleTypeController
 * @package App\Http\Controllers\Backend\Article
 * 资讯类型
 */
class ArticleTypeController extends Controller
{
    protected $articleTypes;
    public function __construct(ArticleTypeContract $articleTypes)
    {
        $this->articleTypes = $articleTypes;
    }

    //
    public function index()
    {
        //获取所有文章类型
        $types = $this->articleTypes->getAll();
        return view('backend.article.type.index',compact('types'));
    }

    public function create()
    {
        return view('backend.article.type.create');
    }

    public function store(StoreArticleTypeRequest $request)
    {
        $this->articleTypes->create($request);
        return redirect()->route('admin.article.type.index')->withFlashSuccess(trans('alerts.backend.article.type.created'));
    }

    public function edit($id)
    {
        $type = $this->articleTypes->findOrThrowException($id);
        return view('backend.article.type.edit',compact('type'));
    }

    public function update($id, UpdateArticleTypeRequest $request)
    {
        $this->articleTypes->update($id,$request);
        return redirect()->route('admin.article.type.index')->withFlashSuccess(trans('alerts.backend.article.type.updated'));
    }
}