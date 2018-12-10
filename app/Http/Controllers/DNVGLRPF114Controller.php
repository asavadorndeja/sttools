<?php

namespace App\Http\Controllers;

use App\DNVGLRPF114;
use Illuminate\Http\Request;

class DNVGLRPF114Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {

        $analysisOptions =['Undrained.model.1', 'Undrained.model.2', 'Drained'];
        $analysisOptions =['Undrained.model.2', 'Drained'];


        //Pipeline data
        $def_plOD = [0.27305];
        $def_plWins = [844.22];
        $def_plWhdt = [2780.96];
        $def_plWopt = [1222.12];
        $def_plI = [0.00008822];
        $def_plT0 = [2.50e+05];

        $req_plOD = [$request->plOD0];
        $req_plWins = [$request->plWins0];
        $req_plWhdt = [$request->plWhdt0];
        $req_plWopt = [$request->plWopt0];
        $req_plI = [$request->plI0];
        $req_plT0 = [$request->plT00];

        $PLs = array(
          ['plOD', $def_plOD, $req_plOD, 'Outer diameter (D), m', 'm', '', False],
          ['plWins', $def_plWins, $req_plWins, 'Installation submerged weight, N/m', 'N/m', '', False],
          ['plWhdt', $def_plWhdt, $req_plWhdt, 'Hydrotest submerged weight, N/m', 'N/m', '', False],
          ['plWopt', $def_plWopt, $req_plWopt, 'Operating submerged weight, N/m', 'N/m', '', False],
          ['plI', $def_plI, $req_plI, 'Moment of inertia (I), m4', 'm4', '', False],
          ['plT0', $def_plT0, $req_plT0, 'Bottom tension (T0), N', 'N.', '', False],
        );

        //Soil model
        $def_smModel = ['Undrained.model.2'];

        $req_smModel = [$request->smModel0];

        $SMs = array(
          ['smModel', $def_smModel, $req_smModel, 'Soil model, -', '-', $analysisOptions, False],
        );


        // Soil data
        $def_soDepth = [[0,1,2,3,4]];
        $def_soGamma = [[5000,5000,5000,5000,5000]];
        $def_soSu = [[1000,2000,3000,4000,5000]];
        $def_soSu_re = [[400,800,1200,1600,2000]];
        $def_soPhi = [[30,31,32,33,34]];
        $def_soDeltaPeak = [[25,26,27,28,29]];
        $def_soDeltaRes = [[20,21,22,23,24]];
        $def_soMShansep = [[0.70,0.71,0.72,0.73,0.74]];

        $req_soDepth = [[$request->soDepth0,$request->soDepth1,$request->soDepth2,$request->soDepth3,$request->soDepth4]];
        $req_soGamma = [[$request->soGamma0,$request->soGamma1,$request->soGamma2,$request->soGamma3,$request->soGamma4]];
        $req_soSu = [[$request->soSu0,$request->soSu1,$request->soSu2,$request->soSu3,$request->soSu4]];
        $req_soSu_re = [[$request->soSu_re0,$request->soSu_re1,$request->soSu_re2,$request->soSu_re3,$request->soSu_re4]];
        $req_soPhi = [[$request->soPhi0,$request->soPhi1,$request->soPhi2,$request->soPhi3,$request->soPhi4]];
        $req_soDeltaPeak = [[$request->soDeltaPeak0,$request->soDeltaPeak1,$request->soDeltaPeak2,$request->soDeltaPeak3,$request->soDeltaPeak4]];
        $req_soDeltaRes = [[$request->soDeltaRes0,$request->soDeltaRes1,$request->soDeltaRes2,$request->soDeltaRes3,$request->soDeltaRes4]];
        $req_soMShansep = [[$request->soMShansep0,$request->soMShansep1,$request->soMShansep2,$request->soMShansep3,$request->soMShansep4]];

        $SOs = array(
          ['soDepth', $def_soDepth, $req_soDepth, 'Depth, m', 'm', '', False],
          ['soGamma', $def_soGamma, $req_soGamma, 'Submerged unit weight, N/m', 'N/m', '', False],
          ['soSu', $def_soSu, $req_soSu, 'Intact undrained shear strength, N/Cu.m.', 'N/Cu.m.', '', False],
          ['soSu_re', $def_soSu_re, $req_soSu_re, 'Remoulded undrained shear strength, N/Cu.m.', 'N/Cu.m.', '', False],
          ['soPhi', $def_soPhi, $req_soPhi, 'Frictio angle, degree', 'degree', '', False],
          ['soDeltaPeak', $def_soDeltaPeak, $req_soDeltaPeak, 'Peak interface friction angle, degree', 'degree', '', False],
          ['soDeltaRes', $def_soDeltaRes, $req_soDeltaRes, 'Residual interface friction angle, degree', 'degree', '', False],
          ['soMShansep', $def_soMShansep, $req_soMShansep, 'Coefficient m', '-', '', False],
        );

        $inputs = array($PLs, $SMs, $SOs);
        foreach ($inputs as $key => $val) {
          $iMax = count($val);
          for ($i=0; $i < $iMax ; $i++) {
            $var0 = $val[$i][0];
            $var1 = $val[$i][1];
            $var2 = $val[$i][2];

            // Check if input is individual or table
            if (sizeof($var1[0]) == 1){
              // Input as individual or nested individual
              $j = 0;
              foreach ($var2 as $key2 => $val2) {

                if (is_null($val2)) {
                  $request->request->add([$var0.$j => $var1[$j]]);
                }
                $j = $j+1;
              }
            }else{
              // Input as table
              foreach ($var2 as $key2 => $val2) {
                foreach ($val2 as $key3 => $val3) {
                  if (is_null($val3)) {
                    // echo($key3);
                    $request->request->add([$var0.$key3 => $var1[0][$key3]]);
                  }
                }
              }
            }
          }
        }


        // dd($request);

        $req_plOD = [(float)$request->plOD0];
        $req_plWins = [(float)$request->plWins0];
        $req_plWhdt = [(float)$request->plWhdt0];
        $req_plWopt = [(float)$request->plWopt0];
        $req_plI = [(float)$request->plI0];
        $req_plT0 = [(float)$request->plT00];

        $PLs = array(
          ['plOD', $def_plOD, $req_plOD, 'Outer diameter (D), m', 'm', '', False],
          ['plWins', $def_plWins, $req_plWins, 'Installation submerged weight, N/m', 'N/m', '', False],
          ['plWhdt', $def_plWhdt, $req_plWhdt, 'Hydrotest submerged weight, N/m', 'N/m', '', False],
          ['plWopt', $def_plWopt, $req_plWopt, 'Operating submerged weight, N/m', 'N/m', '', False],
          ['plI', $def_plI, $req_plI, 'Moment of inertia (I), m4', 'm4', '', False],
          ['plT0', $def_plT0, $req_plT0, 'Bottom tension (T0), N', 'N.', '', False],
        );

        $req_smModel = [$request->smModel0];

        $SMs = array(
          ['smModel', $def_smModel, $req_smModel, 'Soil model, -', '-', $analysisOptions, False],
        );

        $req_soDepth = [[(float)$request->soDepth0,(float)$request->soDepth1,(float)$request->soDepth2,(float)$request->soDepth3,(float)$request->soDepth4]];
        $req_soGamma = [[(float)$request->soGamma0,(float)$request->soGamma1,(float)$request->soGamma2,(float)$request->soGamma3,(float)$request->soGamma4]];
        $req_soSu = [[(float)$request->soSu0,(float)$request->soSu1,(float)$request->soSu2,(float)$request->soSu3,(float)$request->soSu4]];
        $req_soSu_re = [[(float)$request->soSu_re0,(float)$request->soSu_re1,(float)$request->soSu_re2,(float)$request->soSu_re3,(float)$request->soSu_re4]];
        $req_soPhi = [[(float)$request->soPhi0,(float)$request->soPhi1,(float)$request->soPhi2,(float)$request->soPhi3,(float)$request->soPhi4]];
        $req_soDeltaPeak = [[(float)$request->soDeltaPeak0,(float)$request->soDeltaPeak1,(float)$request->soDeltaPeak2,(float)$request->soDeltaPeak3,(float)$request->soDeltaPeak4]];
        $req_soDeltaRes = [[(float)$request->soDeltaRes0,(float)$request->soDeltaRes1,(float)$request->soDeltaRes2,(float)$request->soDeltaRes3,(float)$request->soDeltaRes4]];
        $req_soMShansep = [[(float)$request->soMShansep0,(float)$request->soMShansep1,(float)$request->soMShansep2,(float)$request->soMShansep3,(float)$request->soMShansep4]];

        $SOs = array(
          ['soDepth', $def_soDepth, $req_soDepth, 'Depth, m', 'm', '', False],
          ['soGamma', $def_soGamma, $req_soGamma, 'Submerged unit weight, N/m', 'N/m', '', False],
          ['soSu', $def_soSu, $req_soSu, 'Intact undrained shear strength, N/Cu.m.', 'N/Cu.m.', '', False],
          ['soSu_re', $def_soSu_re, $req_soSu_re, 'Remoulded undrained shear strength, N/Cu.m.', 'N/Cu.m.', '', False],
          ['soPhi', $def_soPhi, $req_soPhi, 'Friction angle, degree', 'degree', '', False],
          ['soDeltaPeak', $def_soDeltaPeak, $req_soDeltaPeak, 'Peak interface friction angle, degree', 'degree', '', False],
          ['soDeltaRes', $def_soDeltaRes, $req_soDeltaRes, 'Residual interface friction angle, degree', 'degree', '', False],
          ['soMShansep', $def_soMShansep, $req_soMShansep, 'Coefficient m', '-', '', False],
        );

        $inputAll = array(
          'Pipeline data' => [$PLs, 'list'],
          'Soil model' => [$SMs, 'list'],
          'Soil data' => [$SOs, 'table'],
        );



        $args = implode($req_plOD).' '.
          implode($req_plWins).' '.
          implode($req_plWhdt).' '.
          implode($req_plWopt).' '.
          implode($req_plI).' '.
          implode($req_plT0).' '.
          implode($req_smModel).' '.
          implode($req_soDepth[0], ',').' '.
          implode($req_soGamma[0], ',').' '.
          implode($req_soSu[0], ',').' '.
          implode($req_soSu_re[0], ',').' '.
          implode($req_soPhi[0], ',').' '.
          implode($req_soDeltaPeak[0], ',').' '.
          implode($req_soDeltaRes[0], ',').' '.
          implode($req_soMShansep[0], ',')
          ;


        // $execPath = 'C:\Users\JQcomputerDorCom\AppData\Local\Programs\Python\Python37-32\python';
        // $filePath = 'D:\laravel\sttools\app\Http\python\DNVGLRPF114\DNVGLRPF114.py';
        $execPath = 'python3';
        $filePath = '/var/www/html/app/Http/python/DNVGLRPF114.py'

        $command = ($execPath . ' ' . $filePath . ' '. $args);

        // dd($command);

        $output = shell_exec($command);
        $outputJson = json_decode($output, true);

        // dd($outputJson);



        return view('pages.DNVGLRPF114.index', compact('request', 'inputAll', 'outputJson', 'analysisOptions'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DNVGLRPF114  $dNVGLRPF114
     * @return \Illuminate\Http\Response
     */
    public function show(DNVGLRPF114 $dNVGLRPF114)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DNVGLRPF114  $dNVGLRPF114
     * @return \Illuminate\Http\Response
     */
    public function edit(DNVGLRPF114 $dNVGLRPF114)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DNVGLRPF114  $dNVGLRPF114
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DNVGLRPF114 $dNVGLRPF114)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DNVGLRPF114  $dNVGLRPF114
     * @return \Illuminate\Http\Response
     */
    public function destroy(DNVGLRPF114 $dNVGLRPF114)
    {
        //
    }
}
