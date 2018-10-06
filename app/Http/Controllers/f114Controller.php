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

        $argss = '';

        $vars = array(
          ['D', $def_D],
          ['Wins', $def_Wins],
          ['Whdt', $def_Whdt],
          ['Wopt', $def_Wopt],
          ['I', $def_I],
          ['T0', $def_T0],
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

        // dd($request);

        $request->request->add(['Depth' => $def_Depth]);
        $request->request->add(['gamma' => $def_gamma]);
        $request->request->add(['su' => $def_su]);
        $request->request->add(['su_re' => $def_su_re]);
        $request->request->add(['phi' => $def_phi]);
        $request->request->add(['deltaPeak' => $def_deltaPeak]);
        $request->request->add(['deltaRes' => $def_deltaRes]);
        $request->request->add(['mShansep' => $def_mShansep]);

        // dd($request);

        $execPath = 'C:\Users\JQcomputerDorCom\AppData\Local\Programs\Python\Python37-32\python';
        $filePath = 'D:\laravel\sttools\app\Http\python\psi.py D:\laravel\sttools\app\Http\python\psi.py';

        // $args = $request->D;
        // $args = $args . ' ' . $request->Wins;
        // $args = $args . ' ' . $request->Whdt;
        // $args = $args . ' ' . $request->Wopt;
        // $args = $args . ' ' . $request->I;
        // $args = $args . ' ' . $request->T0;
        // $args = $args . ' ' . implode(',', $request->Depth);
        // $args = $args . ' ' . implode(',', $request->gamma);
        // $args = $args . ' ' . implode(',', $request->Su);
        // $args = $args . ' ' . implode(',', $request->Su_re);
        // $args = $args . ' ' . implode(',', $request->phi);
        // $args = $args . ' ' . implode(',', $request->deltaPeak);
        // $args = $args . ' ' . implode(',', $request->deltaRes);
        // $args = $args . ' ' . implode(',', $request->mShansep);


        // dd($argss);

        $command = ($execPath . ' ' . $filePath . ' '. $argss);

        // dd($command);

        // $command = escapeshellcmd('C:\Users\JQcomputerDorCom\AppData\Local\Programs\Python\Python37-32\python D:\laravel\psi\app\python\psi.py D:\laravel\psi\app\python\psi.py 1 1');
        $output = shell_exec($command);
        // echo $output;

        // dd($request->request);
        return view('pages.f114.index', compact('request', 'output'));
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
