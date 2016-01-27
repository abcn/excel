<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 下午4:16
 */

namespace App\Http\Controllers;


use Guzzle\Http\Client;

class BaseController extends Controller
{
    protected $guzzle_client;

    public function __construct(Client $guzzle_client)
    {

    }
}