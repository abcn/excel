<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/18
 * Time: 下午11:03
 */

namespace App\Models\Carousel;


use App\Models\Carousel\Traits\Attribute\CarouselAttribute;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use CarouselAttribute;
}