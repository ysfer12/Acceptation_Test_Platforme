<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Show the pending status page.
     *
     * @return \Illuminate\View\View
     */
    public function pending()
    {
        return view('status.pending');
    }

    /**
     * Show the rejected status page.
     *
     * @return \Illuminate\View\View
     */
    public function rejected()
    {
        return view('status.rejected');
    }
}
