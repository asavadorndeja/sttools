<?php

namespace App\Http\Controllers;

use App\DNVGLRPF109;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;

class DNVGLRPF109Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //
        //dd($request);


          $inputSoilType = array(
            0 => 'Silt and clay',
            1 => 'Fine sand',
            2 => 'FBE with CWC',
            3 => 'Medium sand',
            4 => 'Coarse sand',
            5 => 'Gravel',
            6 => 'Pebble',
            7 => 'Cobble',
            8 => 'Boulder',
            );
        $argss = '';

        # Pipeline input data
        $def_plOD = [0.27305];          #1
        $def_plWS = [1000, 1200, 1100]; #2
        $def_plGammaSC = [1,1,1];       #3
        $def_plZp = [0.01,0.01,0.01];   #4
        $def_plCL = [0.9, 0.9, 0.9];    #5

        $req_plOD = [$request->plOD0];
        $req_plWS = [$request->plWS0, $request->plWS1, $request->plWS2];
        $req_plGammaSC = [$request->plGammaSC0, $request->plGammaSC1, $request->plGammaSC2];
        $req_plZp = [$request->plZp0, $request->plZp1, $request->plZp2];
        $req_plCL = [$request->plCL0, $request->plCL1, $request->plCL2];

        $PLs = array(
          ['plOD', $def_plOD, $req_plOD, 'Outer diameter (D), m', 'm', '', False],
          ['plWS', $def_plWS, $req_plWS, 'Submerged weight (Wsub), N/m', 'N/m,', '', True],
          ['plGammaSC', $def_plGammaSC, $req_plGammaSC, 'Safety class factor, -', '-', '', True],
          ['plZp', $def_plZp, $req_plZp, 'Pipe penetration (zp), m', 'm', '', True],
          ['plCL', $def_plCL, $req_plCL, 'Lift coefficeint (CL), -', '-', '',True],
        );

        # Seabed input data
        $def_sbSoilType = [1,1,1];            #6
        $def_sbf = [0.6, 0.6, 0.6];           #7
        $def_sbFR = [0, 0, 0];                #8
        $def_sbTrenchDepth = [0.0,0,0];       #9
        $def_sbTrenchAngle = [0.0,0,0];       #10

        $req_sbSoilType = [$request->sbSoilType0];
        $req_sbf = [$request->sbf0, $request->sbf1, $request->sbf2];
        $req_sbFR= [$request->sbFR0, $request->sbFR1, $request->sbFR2];
        $req_sbTrenchDepth = [$request->sbTrenchDepth0, $request->sbTrenchDepth1, $request->sbTrenchDepth2];
        $req_sbTrenchAngle = [$request->sbTrenchAngle0, $request->sbTrenchAngle1, $request->sbTrenchAngle2];

        $SBs = array(
          ['sbSoilType', $def_sbSoilType, $req_sbSoilType, 'Soil type, -', '-', $inputSoilType, False],
          ['sbf', $def_sbf, $req_sbf, 'Friction coefficient (f), -', '-', '', True],
          ['sbFR', $def_sbFR, $req_sbFR, 'Passive soil resistant (FR), N/m', 'N/m', '', True],
          ['sbTrenchDepth', $def_sbTrenchDepth, $req_sbTrenchDepth, 'Trench depth, m', 'm', '', True],
          ['sbTrenchAngle', $def_sbTrenchAngle, $req_sbTrenchAngle, 'Trench angel, deg', 'deg', '', True],
        );

        // Environmental data
        $def_enWD = [60, 60, 60];             #11
        $def_enHs = [8, 9, 10];               #12
        $def_enTP = [13, 14, 15];             #13
        $def_enJWP = [1.0, 1.0, 1.0];         #14
        $def_enUr = [0.3, 0.4, 0.54];         #15
        $def_enZr = [1, 1, 1];                #16
        $def_enSeaDen = [1025, 1025, 1025];   #17
        $def_enEnvDir = [90, 90, 90];         #18

        $req_enWD = [$request->enWD0, $request->enWD1, $request->enWD2];
        $req_enHs = [$request->enHs0, $request->enHs1, $request->enHs2];
        $req_enTP = [$request->enTP0, $request->enTP1, $request->enTP2];
        $req_enJWP = [$request->enJWP0, $request->enJWP1, $request->enJWP2];
        $req_enUr = [$request->enUr0, $request->enUr1, $request->enUr2];
        $req_enZr = [$request->enZr0];
        $req_enSeaDen = [$request->enSeaDen0];
        $req_enEnvDir = [$request->enEnvDir0,$request->enEnvDir1,$request->enEnvDir2];


        $ENs = array(
          ['enWD', $def_enWD, $req_enWD, 'Water depth, m', '-', '', False],
          ['enHs', $def_enHs, $req_enHs, 'Significant wave height (Hs), m', 'm', '', True],
          ['enTP', $def_enTP, $req_enTP, 'Peakperiod (Tp), sec', 'sec', '', True],
          ['enJWP', $def_enJWP, $req_enJWP, 'JONSWAP Wave parameter, -', '-', '', True],
          ['enUr', $def_enUr, $req_enUr, 'Current velocity (Ur), m/s', 'm/s', '', True],
          ['enZr', $def_enZr, $req_enZr, 'Reference heigh (Zr), m', 'm', '', False],
          ['enSeaDen', $def_enSeaDen, $req_enSeaDen, 'Seawater density, kg/cu.m.', 'kg/cu.m.', '', False],
          ['enEnvDir', $def_enEnvDir, $req_enEnvDir, 'Environmental direction', 'deg', 'deg', True],
        );

        $inputs = array($PLs, $SBs, $ENs);

        foreach ($inputs as $key => $val) {
          $iMax = count($val);
          for ($i=0; $i < $iMax ; $i++) {
            $var0 = $val[$i][0];
            $var1 = $val[$i][1];
            $var2 = $val[$i][2];

            $j = 0;
            foreach ($var2 as $key2 => $val2) {
              if (is_null($val2)){
                $request->request->add([$var0.$j => $var1[$j]]);
              }
              $j = $j+1;
            }


          }
        }

        $req_plOD = [$request->plOD0];
        $req_plWS = [$request->plWS0, $request->plWS1, $request->plWS2];
        $req_plGammaSC = [$request->plGammaSC0, $request->plGammaSC1, $request->plGammaSC2];
        $req_plZp = [$request->plZp0, $request->plZp1, $request->plZp2];
        $req_plCL = [$request->plCL0, $request->plCL1, $request->plCL2];

        $PLs = array(
          ['plOD', $def_plOD, $req_plOD, 'Outer diameter (D), m', 'm', '', False],
          ['plWS', $def_plWS, $req_plWS, 'Submerged weight (Wsub), N/m', 'N/m,', '', True],
          ['plGammaSC', $def_plGammaSC, $req_plGammaSC, 'Safety class factor, -', '-', '', True],
          ['plZp', $def_plZp, $req_plZp, 'Pipe penetration (zp), m', 'm', '', True],
          ['plCL', $def_plCL, $req_plCL, 'Lift coefficeint (CL), -', '-', '',True],
        );

        $req_sbSoilType = [$request->sbSoilType0];
        $req_sbf = [$request->sbf0, $request->sbf1, $request->sbf2];
        $req_sbFR= [$request->sbFR0, $request->sbFR1, $request->sbFR2];
        $req_sbTrenchDepth = [$request->sbTrenchDepth0, $request->sbTrenchDepth1, $request->sbTrenchDepth2];
        $req_sbTrenchAngle = [$request->sbTrenchAngle0, $request->sbTrenchAngle1, $request->sbTrenchAngle2];

        $SBs = array(
          ['sbSoilType', $def_sbSoilType, $req_sbSoilType, 'Soil type, -', '-', $inputSoilType, False],
          ['sbf', $def_sbf, $req_sbf, 'Friction coefficient (f), -', '-', '', True],
          ['sbFR', $def_sbFR, $req_sbFR, 'Passive soil resistant (FR), N/m', 'N/m', '', True],
          ['sbTrenchDepth', $def_sbTrenchDepth, $req_sbTrenchDepth, 'Trench depth, m', 'm', '', True],
          ['sbTrenchAngle', $def_sbTrenchAngle, $req_sbTrenchAngle, 'Trench angel, deg', 'deg', '', True],
        );

        $req_enWD = [$request->enWD0, $request->enWD1, $request->enWD2];
        $req_enHs = [$request->enHs0, $request->enHs1, $request->enHs2];
        $req_enTP = [$request->enTP0, $request->enTP1, $request->enTP2];
        $req_enJWP = [$request->enJWP0, $request->enJWP1, $request->enJWP2];
        $req_enUr = [$request->enUr0, $request->enUr1, $request->enUr2];
        $req_enZr = [$request->enZr0];
        $req_enSeaDen = [$request->enSeaDen0];
        $req_enEnvDir = [$request->enEnvDir0,$request->enEnvDir1,$request->enEnvDir2];


        $ENs = array(
          ['enWD', $def_enWD, $req_enWD, 'Water depth, m', '-', '', False],
          ['enHs', $def_enHs, $req_enHs, 'Significant wave height (Hs), m', 'm', '', True],
          ['enTP', $def_enTP, $req_enTP, 'Peakperiod (Tp), sec', 'sec', '', True],
          ['enJWP', $def_enJWP, $req_enJWP, 'JONSWAP Wave parameter, -', '-', '', True],
          ['enUr', $def_enUr, $req_enUr, 'Current velocity (Ur), m/s', 'm/s', '', True],
          ['enZr', $def_enZr, $req_enZr, 'Reference heigh (Zr), m', 'm', '', False],
          ['enSeaDen', $def_enSeaDen, $req_enSeaDen, 'Seawater density, kg/cu.m.', 'kg/cu.m.', '', False],
          ['enEnvDir', $def_enEnvDir, $req_enEnvDir, 'Environmental direction', 'deg', 'deg', True],
        );


        $args = array('','','');
        $iMax = count($args);
        $iMax = 3;
        for ($i=0; $i < $iMax ; $i++){
          foreach ($inputs as $key => $val) {
            foreach ($val as $key1 => $val1) {
              // dd($val);
              if ($val1[6] == false) {
                $name = $val1[0].'0';
                $args[$i] = $args[$i].' '.$request->$name;
              }else {
                $name = $val1[0].$i;
                $args[$i] = $args[$i].' '.$request->$name;
              }

            }
          }
        }


        foreach ($args as $key => $arg) {

          // $execPath = 'C:\Users\JQcomputerDorCom\AppData\Local\Programs\Python\Python37-32\python';
          // $filePath = 'D:\laravel\sttools\app\Http\python\DNVGLRPF109\DNVGLRPF109.py';
          $execPath = 'python3';
          $filePath = '/var/www/html/app/Http/python/DNVGLRPF109.py'
          $command = ($execPath . ' ' . $filePath . ' '. $arg);
          $output[$key] = shell_exec($command);
        }

        $outputJson = [json_decode($output[0], true)[1], json_decode($output[1], true)[1], json_decode($output[2], true)[1]];

        // dd($PLs);

        return view('pages.DNVGLRPF109.index', compact('request','PLs', 'SBs', 'ENs','outputJson'));
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
     * @param  \App\DNVGLRPF109  $dNVGLRPF109
     * @return \Illuminate\Http\Response
     */
    public function show(DNVGLRPF109 $dNVGLRPF109)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DNVGLRPF109  $dNVGLRPF109
     * @return \Illuminate\Http\Response
     */
    public function edit(DNVGLRPF109 $dNVGLRPF109)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DNVGLRPF109  $dNVGLRPF109
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DNVGLRPF109 $dNVGLRPF109)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DNVGLRPF109  $dNVGLRPF109
     * @return \Illuminate\Http\Response
     */
    public function destroy(DNVGLRPF109 $dNVGLRPF109)
    {
        //
    }
}
