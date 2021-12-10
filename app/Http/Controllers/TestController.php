<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nette\Utils\Random;

class TestController extends Controller
{
    public function test() {
        dd(Random::alphanumericString(9));

    }
}
