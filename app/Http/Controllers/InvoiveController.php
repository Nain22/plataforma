<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\View\View;

class InvoiveController extends Controller
{
    public function admin () {
    	$invoices = new Collection;
    	if (auth()->user()->stripe_id) {
            $invoices = auth()->user()->invoices();
        }
        ini_set('memory_limit', '-1');
        return view('invoices.admin', compact('invoices'));
    }

    public function download ($id) {
        ini_set('memory_limit', '-1');
        return request()->user()->downloadInvoice($id , [
            "vendor" => "Mi empresa" ,
            "product" => __("Suscripción")
        ]);
    }
}
