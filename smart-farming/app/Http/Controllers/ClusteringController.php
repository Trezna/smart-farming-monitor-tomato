<?php

namespace App\Http\Controllers;

use App\Models\ClusteringHasil;

class ClusteringController extends Controller
{
    public function index()
    {
        $clusters        = ClusteringHasil::orderBy('cluster')->get();
        $silhouetteScore = 0.2868;
        $totalData       = 32579;

        // Cross-tab distribusi Line vs Cluster dari notebook
        $crossTab = [
            ['line' => 'Line 1', 'cluster_0' => 5195, 'cluster_1' => 5529, 'cluster_2' => 155,  'total' => 10879],
            ['line' => 'Line 2', 'cluster_0' => 4297, 'cluster_1' => 3963, 'cluster_2' => 2602, 'total' => 10862],
            ['line' => 'Line 3', 'cluster_0' => 4173, 'cluster_1' => 4544, 'cluster_2' => 2121, 'total' => 10838],
        ];

        // Silhouette scores per K dari notebook
        $silhouettePerK = [
            2 => 0.2615,
            3 => 0.2868,
            4 => 0.2660,
            5 => 0.2704,
            6 => 0.2287,
            7 => 0.2164,
            8 => 0.2184,
            9 => 0.2216,
            10 => 0.2125,
        ];

        return view('clustering.index', compact(
            'clusters',
            'silhouetteScore',
            'totalData',
            'crossTab',
            'silhouettePerK'
        ));
    }
}
