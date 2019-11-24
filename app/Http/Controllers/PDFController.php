<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EstruturaOrganizacional;
use PDF;

class PDFController extends Controller
{
	public function geraPDF($config)
    {    	
        $pdf = PDF::loadView($config['view'], ['data' => $config['data']]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream($config['name']);
    }
}
