<?php

namespace App\Http\Controllers;

use App\Models\TallyBalken;
use Illuminate\Http\Request;
use App\Models\TallyLog;

class TallyController extends Controller
{
     public function assign(Request $request)
    {
        $request->validate([
            'no_register' => 'required|string',
            'kiln_dries_id' => 'required|integer',
        ]);

        $no_register = $request->no_register;
        $kiln_dries_id = $request->kiln_dries_id;

        $tally = TallyBalken::where('no_register', $no_register)->first();

        if (!$tally) {
            $tally = TallyLog::where('no_register', $no_register)->first();
        }

        if ($tally) {
            $tally->kiln_dries_id = $kiln_dries_id;
            $tally->save();

            return response()->json([
                'success' => true,
                'message' => "Data dengan No Register {$no_register} berhasil dipindahkan.",
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "No Register {$no_register} tidak ditemukan di Tally Balken atau Log.",
            ]);
        }
    }
}
