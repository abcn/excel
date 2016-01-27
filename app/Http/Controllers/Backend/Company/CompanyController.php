<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/23
 * Time: 下午11:06
 */

namespace App\Http\Controllers\Backend\Company;


use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        //获取公司列表

        return view('backend.company.index',compact());
    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

    public function delete()
    {

    }
}