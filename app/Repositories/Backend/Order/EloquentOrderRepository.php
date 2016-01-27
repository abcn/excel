<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 下午12:58
 */

namespace App\Repositories\Backend\Order;


use App\Exceptions\GeneralException;
use App\Models\Article\Article;
use App\Models\Article\ArticleContent;
use App\Models\Order\Order;

class EloquentOrderRepository implements OrderContract
{
    public function __construct()
    {

    }

    public function findOrThrowException($id)
    {
        $order = Order::find($id);

        if(!is_null($order)){
            return $order;
        }

        throw new GeneralException(trans('exceptions.backend.article.not_found'));
    }

    public function findWithContent($id)
    {
        $order = Order::with('content')->find($id);
        if(!is_null($order)){
            return $order;
        }

        throw new GeneralException(trans('exceptions.backend.article.not_found'));
    }

    public function getJoin()
    {
        $order = Order::leftJoin('article_types','type_id', '=', 'article_types.id')
            ->select('articles.*','article_types.name');
        if(!is_null($order)){
            return $order;
        }

        throw new GeneralException(trans('exceptions.backend.article.not_found'));
    }

    public function getArticlesPaginated($per_page, $active = 1, $order_by = 'id', $sort = 'asc')
    {
        $order = Order::paginate($per_page);
        if(!is_null($order)){
            return $order;
        }

        throw new GeneralException(trans('exceptions.backend.article.not_found'));
    }

    //TODO
    public function getAll($order_by = 'id', $sort = 'asc')
    {
        return Order::orderByDate()->paginate(15);
    }

    public function getDeletedArticlesPaginated($per_page)
    {
        // TODO: Implement getDeletedArticlesPaginated() method.
    }

    public function delete($id){

    }

    public function create($input)
    {
        // TODO: Implement create() method.
        $order = $this->createArticleStub($input);

        if($order->save()){
            //将文章详情存入
//            $content = new ArticleContent();
//            $content->article_id = $order->id;
//            $content->content = $input['content'];
//            $content->save();
//
//            return true;
        }

        return new GeneralException(trans('exceptions.backend.article.create_error'));
    }

    public function update($id, $input)
    {
        $article = $this->findOrThrowException($id);
        //检查是否更换图片
        if ($input->hasFile('image')) {
            //存储图片
            $photo = $input->file('image');
            $filename = $photo->getClientOriginalName();

            $photo->move(storage_path(), $filename);
            //图片
            $response = uploadImage($filename,'image/upload');
            $article->image = $response;
        }
        $article->title = $input->get('title');
        $article->abstract = $input->get('title');
        $article->author = $input->get('author');
        $article->type_id = $input->get('type');
        if ($article->save()){
            //更新 content
            $content = ArticleContent::findOrFail($id);
            $content->content = $input['content'];
            $content->save();
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.article.update_error'));
    }
    //软删除
    public function destory($id)
    {
        $article = $this->findOrThrowException($id);
        if($article->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.article.delete_error'));
    }

    public function restory($id)
    {
        // TODO: Implement restory() method.
    }

    public function mark($id, $status)
    {
        if (access()->id() == $id && $status == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_deactivate_self'));
        }

        $article         = $this->findOrThrowException($id);
        $article->active = $status;

        if ($article->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.mark_error'));
    }

    public function createArticleStub($input)
    {
        //存储图片
        $photo = $input->file('image');
        $filename = $photo->getClientOriginalName();

        $photo->move(storage_path(), $filename);
        //图片
        $response = uploadImage($filename,'image/upload');

        $article = new Article();
        $article->title     = $input['title'];
        $article->abstract  = $input['abstract'];
        $article->image     = $response;
        $article->author    = $input['author'];
        $article->type_id   = $input['type'];
        return $article;
    }

}