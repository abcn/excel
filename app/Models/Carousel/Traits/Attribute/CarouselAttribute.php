<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/19
 * Time: 上午12:24
 */

namespace App\Models\Carousel\Traits\Attribute;


trait CarouselAttribute
{

    public function getEditButtonAttribute()
    {
        if(access()->allow('carousel-edit')){
            return '<a href="' . route('admin.carousel.edit', $this->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a> ';
        }
    }

    public function getActionButtonsAttribute()
    {
        return $this->getEditButtonAttribute();
    }
}