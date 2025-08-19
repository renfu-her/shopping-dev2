<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('pages.orders.index');
    }

    public function show($order)
    {
        return view('pages.orders.show', compact('order'));
    }
}
