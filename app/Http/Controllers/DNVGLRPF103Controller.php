<?php

namespace App\Http\Controllers;

use App\DNVGLRPF103;
use Illuminate\Http\Request;

class DNVGLRPF103Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //
        $def_D = 0.27305;
        $def_lPL = 12;
        $def_lFJ = 0.30;
        $def_tAmb = 20;
        $def_tAno = 50;
        $def_tf = 25;
        $def_rhoSea = 0.35;
        $def_aGap = 50E-03;
        $def_aThk = 50E-03;
        $def_aDen = 2700;
        $def_aMat = 0;
        $def_coatLP = 2;
        $def_coatFJ = 7;
        $def_spaceMin = 5;
        $def_spaceMax = 15;
        $def_burial = 1;

        $inputCoatingLP = array(
          0 => 'Glass fibre reinforced asphalt enamel with CWC',
          1 => 'FBE w/o CWC',
          2 => 'FBE with CWC',
          3 => '3-layer FBE/PE w/o CWC',
          4 => '3-layer FBE/PE with CWC',
          5 => '3-layer FBE/PP w/o CWC',
          6 => '3-layer FBE/PP with CWC',
          7 => 'FBE/PP thermally insulating coating without CWC',
          8 => 'FBE/PU thermally insulating coating without CWC',
          9 => 'Polychloroprene',
          );

        $inputCoatingFJ = array(
          0 => 'none // 4E(1) moulded PU on top bare steel (with primer',
          1 => '1D Adhesive Tape or 2A(1)/2A-(2) HSS (PE/PP backing) with mastic adhesive // 4E(2) moulded PU on top 1D or 2A(1)/2A(2)',
          2 => '2B(1) HSS (backing + adhesive in PE with LE primer) // none',
          3 => '2B(1) HSS (backing + adhesive in PE with LE primer) // 4E(2) moulded PU on top 2B(1)',
          4 => '2C (1) HSS (backing + adhesive in PP, LE primer) // none',
          5 => '2C (1) HSS (backing + adhesive in PP, LE primer) // 4E(2) moulded PU on top 2B(1)',
          6 => '3A FBE // none',
          7 => '3A FBE // 4E(2) moulded PU on top',
          8 => '2B(2) FBE with PE HSS // none',
          9 => '2B(2) FBE with PE HSS // 4E(2) moulded PU on top FBE + PE HSS',
          10 => '5D(1) and 5E FBE with PE applied as flame spraying or tape, respectively // none',
          11 => '2C(2) FBE with PP HSS // none',
          12 => '5A/B/C(1) FBE, PP adhesive and PP (wrapped, flame sprayed or moulded) // none',
          13 => 'NA // 5C(1) Moulded PE on top FBE with PE adhesive',
          14 => 'NA // 5C(2) Moulded PP on top FBE with PP adhesive',
          15 => '8A polychloroprene // none',
          );

        $inputSpacing = array(
          5 => 5,
          6 => 6,
          7 => 7,
          8 => 8,
          9 => 9,
          10 => 10,
          11 => 11,
          12 => 12,
          13 => 13,
          14 => 14,
          15 => 15,
          16 => 16,
          17 => 17,
          18 => 18,
          19 => 19,
          20 => 20,
          21 => 21,
          22 => 22,
          23 => 23,
          24 => 24,
          25 => 25,
        );

        $inputBurial = array(
          1 => 'non-burial',
          2 => 'burial',
        );

        $inputAnode = array(
          0 => 'Al-Zn-In',
          1 => 'Zn',
        );

        $vars = array(
          ['D', $def_D, $request->D,'Outer diameter D', 'm', ''],
          ['lPL', $def_lPL, $request->lPL, 'Linepipe length, LPL', 'm', ''],
          ['lFJ', $def_lFJ, $request->lFJ, 'Field joint length, LFJ', 'm', ''],
          ['tAmb', $def_tAmb, $request->tAmb, 'Ambient temperature, tAmb', 'degC', ''],
          ['tAno', $def_tAno, $request->tAno, 'Anode temperature, tAno', 'degC', ''],
          ['tf', $def_tf, $request->tf, 'Design life', 'years', ''],
          ['rhoSea', $def_rhoSea, $request->rhoSea, 'Seawater resistivity', 'ohm-m', ''],
          ['aGap', $def_aGap, $request->aGap, 'Anode gap, aGap', 'm', ''],
          ['aThk', $def_aThk, $request->aThk, 'Anode thickness, aThk', 'm', ''],
          ['aDen', $def_aDen, $request->aDen, 'Anode density, aDen', 'kg/Cu.m.', ''],
          ['aMat', $def_aMat, $request->aMat, 'Anode material', '', $inputAnode],
          ['coatLP', $def_coatLP, $request->coatLP, 'Linepipe coating', '', $inputCoatingLP],
          ['coatFJ', $def_coatFJ, $request->coatFJ, 'Field joint coating', '', $inputCoatingFJ],
          ['spaceMin', $def_spaceMin, $request->spaceMin, 'Minimum joint spacing','', $inputSpacing],
          ['spaceMax', $def_spaceMax, $request->spaceMax, 'Maximum joint spacing','', $inputSpacing],
          ['burial', $def_burial, $request->burial, 'Burial condition, ', '', $inputBurial]
        );

        $iMax = count($vars);
        $argss = '';

        for ($i=0; $i < $iMax ; $i++) {
          $var0 = $vars[$i][0];
          $var1 = $vars[$i][1];

          if (is_null($request->$var0)){
            $request->request->add([$var0 => $var1]);
            $vars[$i][2] = $var1;
          }

          $argss = $argss.' '.$vars[$i][2];

        }

        // dd($argss);


        // $execPath = 'C:\Users\JQcomputerDorCom\AppData\Local\Programs\Python\Python37-32\python';
        // $filePath = 'D:\laravel\sttools\app\Http\python\DNVGLRPF103\DNVGLRPF103.py';
        $execPath = 'python3';
        $filePath = '/var/www/html/app/Http/python/DNVGLRPF103.py'
        $command = ($execPath . ' ' . $filePath . ' '. $argss);
        // $output = json_decode(shell_exec($command),true);
        $output = shell_exec($command);
        // dd($output);
        // $result = explode(exec($command, $output, $ret_code));

        $outputJson = json_decode($output, true);
        // dd($outputJson);
        // dd($test[0]['input']);
        // dd(json_decode($output));

        // exec($command, $output, $ret_code);
        // $resultData = json_decode($output);
        //
        // dd($resultData);
        // $last_line = system($command, $retval);

        // dd($retval);
        // dd($outputJson);

        // dd($vars);

        return view('pages.DNVGLRPF103.index', compact('request', 'vars', 'output', 'outputJson'));

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
     * @param  \App\DNVGLRPF103  $dNVGLRPF103
     * @return \Illuminate\Http\Response
     */
    public function show(DNVGLRPF103 $dNVGLRPF103)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DNVGLRPF103  $dNVGLRPF103
     * @return \Illuminate\Http\Response
     */
    public function edit(DNVGLRPF103 $dNVGLRPF103)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DNVGLRPF103  $dNVGLRPF103
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DNVGLRPF103 $dNVGLRPF103)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DNVGLRPF103  $dNVGLRPF103
     * @return \Illuminate\Http\Response
     */
    public function destroy(DNVGLRPF103 $dNVGLRPF103)
    {
        //
    }
}
