<?php

namespace App\Http\Controllers;

use App\ASME318;
use Illuminate\Http\Request;

class ASME318Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {

      $def_D = 0.27305;
      $def_Pin = 10e06;
      $def_Tin = 150;
      $def_WD = 100;
      // $def_CA = 0.003;
      $def_rhoWater = 1025;
      $def_Euse = 1;
      $def_linePipe = 'API 5L Seamless' ;

      $F = array(
        0 => array('des' => 'Location Class 1, Devision 1', 'val' => 0.80),
        1 => array('des' => 'Location Class 1, Devision 2', 'val' => 0.72),
        2 => array('des' => 'Location Class 2', 'val' => 0.60),
        3 => array('des' => 'Location Class 3', 'val' => 0.50),
        4 => array('des' => 'Location Class 4', 'val' => 0.40),
      );

      $E = array(
        0 => array('des' => 'API 5L Electric Welded', 'val' => 1.00),
        1 => array('des' => 'API 5L Seamless', 'val' => 1.00),
        2 => array('des' => 'API 5L Submerged-Arc Welded (Longitudinal Seam or Helical Seam)', 'val' => 1.00),
        3 => array('des' => 'API 5L Furnace-Butt Welded, Continuous Weld', 'val' => 0.60),
      );


      $T = array(
        0 => array('des' => -100, 'val' => 1.000),
        1 => array('des' => 121, 'val' => 1.000 ),
        2 => array('des' => 149, 'val' => 0.967 ),
        3 => array('des' => 177, 'val' => 0.933 ),
        4 => array('des' => 204, 'val' => 0.900 ),
        5 => array('des' => 232, 'val' => 0.867),
        6 => array('des' => 1000, 'val' => 0.867),
      );

      $SMYS = array(
        0 => array('des' => 'Gr. A25', 'val' => 172e06 ),
        1 => array('des' => 'Gr. A', 'val' => 207e06 ),
        2 => array('des' => 'Gr. B', 'val' => 241e06 ),
        3 => array('des' => 'Gr. X42', 'val' => 290e06 ),
        4 => array('des' => 'Gr. X46', 'val' => 317e06 ),
        5 => array('des' => 'Gr. X52', 'val' => 359e06 ),
        6 => array('des' => 'Gr. X56', 'val' => 386e06 ),
        7 => array('des' => 'Gr. X60', 'val' => 414e06 ),
        8 => array('des' => 'Gr. X65', 'val' => 448e06 ),
        9 => array('des' => 'Gr. X70', 'val' => 483e06 ),
        10 => array('des' => 'Gr. X80', 'val' => 552e06 ),
      );


      $vars = array(
        ['D', $def_D],
        ['Pin', $def_Pin],
        ['Tin', $def_Tin],
        ['WD', $def_WD],
        // ['CA', $def_CA],
        ['rhoWater', $def_rhoWater],
        ['linePipe', $def_linePipe],
        ['Euse', $def_Euse],
        ['F', $F],
        ['E', $E],
        ['T', $T],
        ['SMYS', $SMYS],
      );

      // dd($E[0]['des']);

      foreach ($vars as $var) {
        $var1 = $var[0];
        $var2 = $var[1];

        if (is_null($request->$var1)){
          $request->request->add([$var1 => $var2]);
        }
      }

      // dd($request);

      // return view('pages.ASME318.index', compact('request', 'output', 'analysisOptions'));

      $output = $this->ASME318WT($request);
      // dd($output);
      // dd($output['Location Class 1, Devision 1']['Gr. A']);

      return view('pages.ASME318.index', compact('request', 'output'));

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
     * @param  \App\ASME318  $aSME318
     * @return \Illuminate\Http\Response
     */
    public function show(ASME318 $aSME318)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ASME318  $aSME318
     * @return \Illuminate\Http\Response
     */
    public function edit(ASME318 $aSME318)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ASME318  $aSME318
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ASME318 $aSME318)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ASME318  $aSME318
     * @return \Illuminate\Http\Response
     */
    public function destroy(ASME318 $aSME318)
    {
        //
    }

    public function ASME318WT($request)
    {
      // dd($reqeust);// D = $args[0];

      $D = $request->D;
      $Pin = $request->Pin;
      $Tin = $request->Tin;
      $WD = $request->WD;
      $CA = $request->CA;
      $rhoWater = $request->rhoWater;
      $E = $request->E;
      $T = $request->T;
      $linePipe = $request->linePipe;

      // dd($request);


      $Pext = $WD*9.81*$rhoWater;
      $Pdesign = $Pin-$Pext;

      // dd($T);

      $index = 0;
      // dd($T[1]['des']);

      do {

        $x1 = $T[$index]['des'];
        $y1 = $T[$index]['val'];

        $x2 = $T[$index + 1]['des'];
        $y2 = $T[$index + 1]['val'];

        if (($Tin > $x1) and ($Tin < $x2)) {
          $m = ($y2-$y1)/($x2-$x1);
          $Tuse = $m*($Tin - $x1) + $y1;
        }

        $index = $index + 1;

      } while ($Tin > $x2);

      foreach ($E as $key => $val) {
        // echo($val['des']);
        // echo($linePipe);
        //
        if ($linePipe == $val['des']) {
          $Euse = $val['val'];
        }
      }



      foreach ($request->SMYS as $SMYS) {
        $tt[$SMYS['des']] = ($Pdesign*$D)/($SMYS['val']*0.80*$Euse*$Tuse);
        foreach($request->F as $F){

          if ($Pdesign > 0) {
            $t[$SMYS['des']][$F['des']] = number_format(($Pdesign*$D)/($SMYS['val']*$F['val']*$Euse*$Tuse)*1000,2);
          }else {
            $t[$SMYS['des']][$F['des']] = 'N.A.';
          }


        }
      }

      // dd($t);

      return $t;
    }
}
