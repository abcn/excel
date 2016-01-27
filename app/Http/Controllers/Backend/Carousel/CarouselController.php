<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/18
 * Time: 下午9:35
 */

namespace App\Http\Controllers\Backend\Carousel;


use App\Models\Carousel\Carousel;
use Illuminate\Routing\Controller;

class CarouselController extends Controller
{
    protected $carouse;

    public function __construct(Carousel $carousel)
    {
        $this->carouse = $carousel;
    }

    public function index()
    {
        $carousels = $this->carouse->paginate(15);
        return view('backend.carousel.index',compact('carousels'));
    }

    public function edit()
    {

    }
}