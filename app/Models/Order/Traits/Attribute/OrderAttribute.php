<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 上午9:54
 */

namespace App\Models\Order\Traits\Attribute;


trait OrderAttribute
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
            return '<a href="' . route('admin.order.edit', $this->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '">编辑</i></a> ';
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
                    return '<a href="order/'.$this->id.'/sub" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="查看分单">查看分单</i></a> ';
                }
                break;

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
        if (access()->allow('delete-orders')) {
            return '<a href="' . route('admin.order.destroy', $this->id) . '" data-method="delete" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '">' . trans('buttons.general.crud.delete') . '</i></a>';
        }

        return '';
    }

    /**
     * @return string
     * 导出分单
     */
    public function getExportButtonAttribute()
    {
        if(access()->allow('order-export')){
            return '<a href="'. route('admin.subOrder.export', $this->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="导出分单" id="export">导出分单</i></a> ';
        }
    }

    /**
     * @return string
     * 导出分单
     */
    public function getExportIDButtonAttribute()
    {
        if(access()->allow('order-export')){
            return '<a href="'. route('admin.orderID.export', $this->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="导出身份证" id="export">导出身份证</i></a> ';
        }
    }

    /**
     * @return string
     */
    public function getDeclearButtonAttribute()
    {
        switch ($this->declear_state) {
            case 0:
                if (access()->allow('reactivate-article')) {
                    return '<a href="' . route('admin.order.declear', [$this->id, 1]) . '" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="申报">申报</i></a> ';
                }

                break;

            case 1:
                if (access()->allow('deactivate-article')) {
                    return '<i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="已申报">已申报</i>';
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
     * 到港按钮
     */
    public function getPortButtonAttribute()
    {
        switch ($this->port_state) {
            case 1:
                if (access()->allow('reactivate-article')) {
                    return '<a href="' . route('admin.order.port', [$this->id, 1]) . '" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="未到港">未到港</i></a> ';
                }

                break;

            case 2:
                if (access()->allow('reactivate-article')) {
                    return '<a href="' . route('admin.order.clear', [$this->id, 2]) . '" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="清关中">清关中</i></a> ';
                }

                break;
            case 3:
                if (access()->allow('deactivate-article')) {
                    return '<i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="已到港">已到港</i>';
                }
                break;
            default:
                return '';
            // No break
        }

        return '';
    }
    //导出申报单
    public function getReportButtonAttribute()
    {
        if(access()->allow('order-report')){
            return '<a href="'. route('admin.orderID.report', $this->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="导出申报单" id="report">导出申报单</i></a> ';
        }
    }

    public function getActionButtonsAttribute()
    {
        return $this->getImportButtonAttribute() .
            $this->getEditButtonAttribute() .
        $this->getExportIDButtonAttribute().
        $this->getDeclearButtonAttribute() .
        $this->getPortButtonAttribute().
        $this->getExportButtonAttribute().
        $this->getDeleteButtonAttribute();
    }
}