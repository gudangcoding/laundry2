<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaController extends Controller
{
    public function nota(Request $request, $id)
    {

        $salesOrder = Order::with(
            [
                'customer',
                'OrderDetail',
                'OrderDetail.produk'
            ]
        )
            ->findOrFail($id);


        $pdf = PDF::loadView('pdf', compact('salesOrder'))
            ->setPaper('letter', 'potrait')
            ->setOptions(['dpi' => 300]);
        return $pdf->stream();
    }
}
