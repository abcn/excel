<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 上午9:54
 */

namespace App\Models\Order\Traits\Attribute;


trait SubOrderAttribute
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
            return '<a href="' . route('admin.article.edit', $this->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '">编辑</i></a> ';
        }
    }

    public function getImportButtonAttribute()
    {
        switch ($this->import_state) {
            case 0:
                if(access()->allow('order-import')){
                    return '<a href="javascript:void(0);" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" onclick="showModal('.$this->id.')" data-placement="top" title="导入">导入</i></a> ';
                }

                break;
            case 1:
                if(access()->allow('order-sub')){
                    return '<a href="order/sub" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" onclick="showModal('.$this->id.')" data-placement="top" title="查看">查看</i></a> ';
                }
                break;

        }

    }

    /**
     * @return string
     */
    public function getPassAttribute()
    {
        switch ($this->clearance_state) {
            case 0:
                if (access()->allow('subOrder-check')) {
                    return '<a href="javascript:void(0);" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="查验" onclick="check('.$this->id.')">查验</i></a> ';
                }
                break;

            case 1:
                if (access()->allow('subOrder-pass')) {
                    return '<a href="javascript:void(0);" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="放行" onclick="pass('.$this->id.')">放行</i></a> ';
                }
                break;

                break;
            case 1:
                return '';

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
    public function getSendAttribute()
    {
        switch ($this->send_state) {
            case 0:
                if (access()->allow('subOrder-send')) {
                    return '<a href="javascript:void(0);" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="派送" onclick="send('.$this->id.')">派送</i></a> ';
                }
                break;

            case 1:
                if (access()->allow('subOrder-arrival')) {
                    return '<a href="javascript:void(0);" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="签收" onclick="arrival('.$this->id.')">签收</i></a> ';
                }
                break;

                break;
            case 1:
                return '';

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

    /**
     * @return string
     * 查看订单商品
     */
    public function getSubOrderProductAttribute()
    {
        if(access()->allow('subOrder-detail')){
            return '<a href="'.route('admin.subOrder.detail', $this->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="查看详情">查看详情</i></a> ';
        }
    }

    public function getActionButtonsAttribute()
    {
        return $this->getSubOrderProductAttribute() .
        $this->getPassAttribute() .
        $this->getSendAttribute().
        $this->getDeleteButtonAttribute();
    }
}