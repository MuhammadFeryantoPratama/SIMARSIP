<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        return view('subscriptions.index');
    }
}
