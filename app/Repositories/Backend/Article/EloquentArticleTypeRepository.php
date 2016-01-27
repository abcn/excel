<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 下午12:58
 */

namespace App\Repositories\Backend\Article;


use App\Exceptions\GeneralException;
use App\Models\Article\Article;
use App\Models\Article\ArticleType;

class EloquentArticleTypeRepository implements ArticleTypeContract
{
    public function findOrThrowException($id)
    {
        $type = ArticleType::find($id);

        if(!is_null($type)){
            return $type;
        }

        throw new GeneralException(trans('exceptions.backend.article.not_found'));
    }

    public function getAll()
    {
        return ArticleType::all();
    }

    public function getById($id)
    {

    }

    public function create($input)
    {
        // TODO: Implement create() method.
        $articleType = $this->createArticleStub($input);

        if($articleType->save()){
            //
            return true;
        }
        throw new GeneralException(trans('exceptions.backend.article.create_error'));
    }

    public function update($id, $input)
    {
        $articleType = $this->findOrThrowException($id);
        $articleType->name = $input->get('name');
        if($articleType->save()){
            return true;
        }
        throw new GeneralException(trans('exceptions.backend.article.update_error'));
    }

    public function createArticleStub($input)
    {
        $articleType = new ArticleType();
        $articleType->name     = $input['name'];
        return $articleType;
    }

}