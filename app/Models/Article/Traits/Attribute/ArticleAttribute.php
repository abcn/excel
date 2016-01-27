<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 上午9:54
 */

namespace App\Models\Article\Traits\Attribute;


trait ArticleAttribute
{
    /**
     * @return bool
     */
    public function isActive() {
        return $this->active == 1;
    }

    public function getEditButtonAttribute()
    {
        if(access()->allow('article-edit')){
            return '<a href="' . route('admin.article.edit', $this->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
        }
    }

    /**
     * @return string
     */
    public function getActiveButtonAttribute()
    {
        switch ($this->active) {
            case 0:
                if (access()->allow('reactivate-article')) {
                    return '<a href="' . route('admin.article.mark', [$this->id, 1]) . '" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.backend.article.activate') . '"></i></a> ';
                }

                break;

            case 1:
                if (access()->allow('deactivate-article')) {
                    return '<a href="' . route('admin.article.mark', [$this->id, 0]) . '" class="btn btn-xs btn-warning"><i class="fa fa-pause" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.backend.article.deactivate') . '"></i></a> ';
                }

                break;

            default:
                return '';
            // No break
        }

        return '';
    }


    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        if (access()->allow('delete-users')) {
            return '<a href="' . route('admin.article.destroy', $this->id) . '" data-method="delete" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></a>';
        }

        return '';
    }

    public function getActionButtonsAttribute()
    {
        return $this->getEditButtonAttribute() .
        $this->getActiveButtonAttribute() .
        $this->getDeleteButtonAttribute();
    }
}