<?php

namespace App\Http\Controllers;

use App\f114;
use Illuminate\Http\Request;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class F114Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //Set analysis default value
        $def_D = 0.27305;
        $def_Wins = 844.22;
        $def_Whdt = 2780.96;
        $def_Wopt = 1222.12;
        $def_I = 0.00008822;
        $def_T0 = 2.50e+05;
        $def_Depth = ([0,1,2,3,4]);
        $def_gamma = ([5000,5000,5000,5000,5000]);
        $def_su = ([1000,2000,3000,4000,5000]);
        $def_su_re = ([400,800,1200,1600,2000]);
        $def_phi = ([30,31,32,33,34]);
        $def_deltaPeak = ([25,26,27,28,29]);
        $def_deltaRes = ([20,21,22,23,24]);
        $def_mShansep = ([0.70,0.70,0.70,0.70,0.70]);
        $def_analysisOption = 'Undrained.model.2';

        $argss = '';

        $vars = array(
          ['D', $def_D],
          ['Wins', $def_Wins],
          ['Whdt', $def_Whdt],
          ['Wopt', $def_Wopt],
          ['I', $def_I],
          ['T0', $def_T0],
          ['analysisOption', $def_analysisOption]
        );

        foreach ($vars as $var) {
          $var1 = $var[0];
          $var2 = $var[1];

          if (is_null($request->$var1)){
            $request->request->add([$var1 => $var2]);
          }

          $argss = $argss.' '.$request->$var1;

        }

        $vars = array(
          ['z', $def_Depth],
          ['gamma', $def_gamma],
          ['su', $def_su],
          ['su_re', $def_su_re],
          ['phi', $def_phi],
          ['deltaPeak', $def_deltaPeak],
          ['deltaRes', $def_deltaRes],
          ['mShansep', $def_mShansep],
        );

        foreach ($vars as $var) {
          $var1 = $var[0];
          $var2 = $var[1];

          for ($i = 0; $i <= 4; $i++) {

              $var1a = $var1 . (string)$i;

              if (is_null($request->$var1a)){
                // echo "$var1a is null <br>";
                $request->request->add([$var1a => $var2[$i]]);
              }
            if ($i == 0 ) {
              $argss = $argss.' '.$request->$var1a;
            }else {
              $argss = $argss.','.$request->$var1a;
            }

          }

        }


        $request->request->add(['Depth' => [$request->z0, $request->z1, $request->z2, $request->z3, $request->z4 ]]);
        $request->request->add(['gamma' => [$request->gamma0, $request->gamma1, $request->gamma2, $request->gamma3, $request->gamma4 ]]);
        $request->request->add(['su' => [$request->su0, $request->su1, $request->su2, $request->su3, $request->su4 ]]);
        $request->request->add(['su_re' => [$request->su_re0, $request->su_re1, $request->su_re2, $request->su_re3, $request->su_re4 ]]);
        $request->request->add(['phi' => [$request->phi0, $request->phi1, $request->phi2, $request->phi3, $request->phi4 ]]);
        $request->request->add(['deltaPeak' => [$request->deltaPeak0, $request->deltaPeak1, $request->deltaPeak2, $request->deltaPeak3, $request->deltaPeak4 ]]);
        $request->request->add(['deltaRes' => [$request->deltaRes0, $request->deltaRes1, $request->deltaRes2, $request->deltaRes3, $request->deltaRes4 ]]);
        $request->request->add(['mShansep' => [$request->mShansep0, $request->mShansep1, $request->mShansep2, $request->mShansep3, $request->mShansep4 ]]);

        // dd($request);
        // echo($request->Depth);

        $execPath = 'C:\Users\JQcomputerDorCom\AppData\Local\Programs\Python\Python37-32\python';
        // $filePath = 'D:\laravel\sttools\app\Http\python\psi.py D:\laravel\sttools\app\Http\python\psi.py';
        $filePath = 'D:\laravel\sttools\app\Http\python\DNVGLRPF114\DNVGLRPF114.py';


        $command = ($execPath . ' ' . $filePath . ' '. $argss);

        // dd($command);

        // $command = escapeshellcmd('C:\Users\JQcomputerDorCom\AppData\Local\Programs\Python\Python37-32\python D:\laravel\psi\app\python\psi.py D:\laravel\psi\app\python\psi.py 1 1');
        $output = shell_exec($command);
        $outputJson = json_decode($output, true);

        // dd($request->request);
        $analysisOptions =['Undrained.model.1', 'Undrained.model.2', 'Drained'];
        // dd($outputJson);
        return view('pages.f114.index', compact('request', 'output', 'outputJson', 'analysisOptions'));
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
     * @param  \App\f114  $f114
     * @return \Illuminate\Http\Response
     */
    public function show(f114 $f114)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\f114  $f114
     * @return \Illuminate\Http\Response
     */
    public function edit(f114 $f114)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\f114  $f114
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, f114 $f114)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\f114  $f114
     * @return \Illuminate\Http\Response
     */
    public function destroy(f114 $f114)
    {
        //
    }
}
