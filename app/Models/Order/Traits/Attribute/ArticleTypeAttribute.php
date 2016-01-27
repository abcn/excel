<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 上午9:54
 */

namespace App\Models\Article\Traits\Attribute;


trait ArticleTypeAttribute
{

    public function getEditButtonAttribute()
    {
        if(access()->allow('article-edit')){
            return '<a href="' . route('admin.article.type.edit', $this->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
        }
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        if (access()->allow('delete-users')) {
            return '<a href="' . route('admin.article.type.destroy', $this->id) . '" data-method="delete" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></a>';
        }

        return '';
    }

    public function getActionButtonsAttribute()
    {
        return $this->getEditButtonAttribute() .
        $this->getDeleteButtonAttribute();
    }
}