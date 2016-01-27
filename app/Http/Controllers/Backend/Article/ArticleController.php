<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 上午9:17
 */

namespace App\Http\Controllers\Backend\Article;


use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Article\DeleteArticleRequest;
use App\Http\Requests\Backend\Article\EditArticleRequest;
use App\Http\Requests\Backend\Article\MarkArticleRequest;
use App\Http\Requests\Backend\Article\StoreArticleRequest;
use App\Http\Requests\Backend\Article\UpdateArticleRequest;
use App\Repositories\Backend\Article\ArticleContract;
use App\Repositories\Backend\Article\ArticleTypeContract;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class ArticleController extends Controller
{
    protected $articles;

    protected $article_type;

    public function __construct(ArticleContract $articles,ArticleTypeContract $article_type)
    {
        $this->articles = $articles;

        $this->article_type = $article_type;
    }
    //显示文章列表
    public function index()
    {
        return view('backend.article.index');
    }
    //添加新文章
    public function create()
    {
        //获取文章类型
        $articleTypes = $this->article_type->getAll();


        return view('backend.article.create',compact('articleTypes'));
    }
    //保存新文章
    public function store(StoreArticleRequest $request)
    {

        //保存新文章
        $this->articles->create($request);
        return redirect()->route('admin.article.index')->withFlashSuccess(trans('alerts.backend.article.created'));
    }

    public function edit($id, EditArticleRequest $request)
    {
        $article = $this->articles->findWithContent($id);
        //获取文章类型
        $articleTypes = $this->article_type->getAll();

        return view('backend.article.edit',compact('article','articleTypes'));
    }

    public function update($id, UpdateArticleRequest $request)
    {
        $this->articles->update($id,$request);

        return redirect()->route('admin.article.index')->withFlashSuccess(trans('alerts.backend.article.updated'));
    }


    public function destroy($id,DeleteArticleRequest $request)
    {
        $this->articles->destory($id);
        return redirect()->back()->withFlashSuccess(trans('alerts.backend.article.deleted'));
    }

    public function deleted()
    {

    }

    //审核文章
    /**
     * @param  $id
     * @param  $status
     * @param  MarkUserRequest $request
     * @return mixed
     */
    public function mark($id, $status, MarkArticleRequest $request)
    {
        $this->articles->mark($id, $status);
        return redirect()->back()->withFlashSuccess(trans('alerts.backend.article.updated'));
    }

    public function data()
    {
        //获取数据
        $articles = $this->articles->getJoin();
        //设置table
        $datatables = Datatables::of($articles)
            ->editColumn('created_at', function ($articles) {
                return $articles->created_at ? with(new Carbon($articles->created_at))->format('m/d/Y') : '';
            })
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            //添加按钮
            ->addColumn('action', function ($articles) {
                return $articles->action_buttons;
            });
        //查询新闻标题
        if ($title = $datatables->request->get('title')) {
            $datatables->where('title', 'like', "$title%");
        }

        //查询资讯类型
        if ($name = $datatables->request->get('type')) {
            $datatables->where('article_types.name', 'like', "$name%");
        }

        return $datatables->make(true);
    }
}