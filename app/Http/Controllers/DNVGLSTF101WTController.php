<?php

namespace App\Http\Controllers;

use App\DNVGLSTF101WT;
use Illuminate\Http\Request;

class DNVGLSTF101WTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {

      $inputFabricationType = array(
        0 => 'Seamless',
        1 => 'UO, TRB, ERB, HWF',
        2 => 'UOE');

      $inputMaterialType = array(
        0 => 'CMn & 13Cr',
        1 => '22Cr & 25Cr');

      $inputSupplementaryType = array(
        0 => 'Normal',
        1 => 'Supplementary Requirement - U');


      # Pipeline input data
      $def_plOD = [0.273];      #1
      $def_plDmax = [0.275];    #2
      $def_plDmin = [0.270];    #3
      $def_plFAB = [0];         #4
      $def_plMAT = [0];         #5
      $def_plSUP = [0];         #6
      $def_plSMYS = [358];      #7 MPa
      $def_plSMTS = [455];      #8 MPa
      $def_pltfab = [0.003];    #9


      $req_plOD = [$request->plOD0];
      $req_plDmax = [$request->plDmax0];
      $req_plDmin = [$request->plDmin0];
      $req_plFAB = [$request->plFAB0];
      $req_plMAT = [$request->plMAT0];
      $req_plSUP = [$request->plSUP0];
      $req_plSMYS = [$request->plSMYS0];
      $req_plSMTS = [$request->plSMTS0];
      $req_pltfab = [$request->pltfab0];

      $PLs = array(
        ['plOD', $def_plOD, $req_plOD, 'Outer diameter (D), m', 'm', '', false],
        ['plDmax', $def_plDmax, $req_plDmax, 'Maximum Outer diameter (Dmax), m', 'm,', '', false],
        ['plDmin', $def_plDmin, $req_plDmin, 'Minimum Outer diameter (Dmin), m', 'm', '', false],
        ['plFAB', $def_plFAB, $req_plFAB, 'Linepipe fabrication, -', '-', $inputFabricationType, false],
        ['plMAT', $def_plMAT, $req_plMAT, 'Linepipe material, -', '-,', $inputMaterialType, false],
        ['plSUP', $def_plSUP, $req_plSUP, 'Supplymentary requirement, -', '-', $inputSupplementaryType, false],
        ['plSMYS', $def_plSMYS, $req_plSMYS, 'Specified Minimum Yield Strength (SMYS), MPa', 'MPa', '', false],
        ['plSMTS', $def_plSMTS, $req_plSMTS, 'Specified Minimum Tensile Strength (SMTS), MPa', 'MPa', '',false],
        ['pltfab', $def_pltfab, $req_pltfab, 'Fabrication tolerance (tfab), m', 'm', '',false],
      );

      $def_optcor = [0.001];    #10
      $def_opTden = [1025];     #11
      $def_opPt = [11];         #12
      $def_opCden = [200];      #13
      $def_opPd = [10];         #14
      $def_opTopt = [60];       #15
      $def_opPe = [1];          #16
      $def_ophl = [10];         #17
      $def_ophref = [100];      #18

      $req_optcor = [$request->optcor0];
      $req_opTden = [$request->opTden0];
      $req_opPt = [$request->opPt0];
      $req_opCden = [$request->opCden0];
      $req_opPd = [$request->opPd0];
      $req_opTopt = [$request->opTopt0];
      $req_opPe = [$request->opPe0];
      $req_ophl = [$request->ophl0];
      $req_ophref = [$request->ophref0];

      $OPs = array(
        ['optcor', $def_optcor, $req_optcor, 'Corrosion allowance (tcor), m', 'm', '',false],
        ['opTden', $def_opTden, $req_opTden, 'System-test content density, kg/Cu.m.', 'kg/Cu.m.', '', false],
        ['opPt', $def_opPt, $req_opPt, 'System-test pressure (Pt), MPa', 'MPa', '', false],
        ['opCden', $def_opCden, $req_opCden, 'Operating content density, kg/Cu.m.', 'kg/Cu.m.', '', false],
        ['opPd', $def_opPd, $req_opPd, 'Design pressure (Pd), MPa', 'MPa', '', false],
        ['opTopt', $def_opTopt, $req_opTopt, 'Operating temperature (Topt), degC', 'degC', '',false],
        ['opPe', $def_opPe, $req_opPe, 'Design external pressure (Pe), MPa', 'MPa', '', false],
        ['ophl', $def_ophl, $req_ophl, 'Elevation of local pressure point (hl), m', 'm', '', false],
        ['ophref', $def_ophref, $req_ophref, 'Elevation of local pressure point (href), m', 'm', '', false],
        );

      $inputs = array($PLs, $OPs);

      // dd($inputs);

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
      $req_plDmax = [$request->plDmax0];
      $req_plDmin = [$request->plDmin0];
      $req_plFAB = [$request->plFAB0];
      $req_plMAT = [$request->plMAT0];
      $req_plSUP = [$request->plSUP0];
      $req_plSMYS = [$request->plSMYS0];
      $req_plSMTS = [$request->plSMTS0];
      $req_pltfab = [$request->pltfab0];

      $PLs = array(
        ['plOD', $def_plOD, $req_plOD, 'Outer diameter (D), m', 'm', '', false],
        ['plDmax', $def_plDmax, $req_plDmax, 'Maximum Outer diameter (Dmax), m', 'm,', '', false],
        ['plDmin', $def_plDmin, $req_plDmin, 'Minimum Outer diameter (Dmin), m', 'm', '', false],
        ['plFAB', $def_plFAB, $req_plFAB, 'Linepipe fabrication, -', '-', $inputFabricationType, false],
        ['plMAT', $def_plMAT, $req_plMAT, 'Linepipe material, -', '-,', $inputMaterialType, false],
        ['plSUP', $def_plSUP, $req_plSUP, 'Supplymentary requirement, -', '-', $inputSupplementaryType, false],
        ['plSMYS', $def_plSMYS, $req_plSMYS, 'Specified Minimum Yield Strength (SMYS), MPa', 'MPa', '', false],
        ['plSMTS', $def_plSMTS, $req_plSMTS, 'Specified Minimum Tensile Strength (SMTS), MPa', 'MPa', '',false],
        ['pltfab', $def_pltfab, $req_pltfab, 'Fabrication tolerance (tfab), m', 'm', '',false],
      );

      $req_optcor = [$request->optcor0];
      $req_opTden = [$request->opTden0];
      $req_opPt = [$request->opPt0];
      $req_opCden = [$request->opCden0];
      $req_opPd = [$request->opPd0];
      $req_opTopt = [$request->opTopt0];
      $req_opPe = [$request->opPe0];
      $req_ophl = [$request->ophl0];
      $req_ophref = [$request->ophref0];

      $OPs = array(
        ['optcor', $def_optcor, $req_optcor, 'Corrosion allowance (tcor), m', 'm', '',false],
        ['opTden', $def_opTden, $req_opTden, 'System-test content density, kg/Cu.m.', 'kg/Cu.m.', '', false],
        ['opPt', $def_opPt, $req_opPt, 'System-test pressure (Pt), MPa', 'MPa', '', false],
        ['opCden', $def_opCden, $req_opCden, 'Operating content density, kg/Cu.m.', 'kg/Cu.m.', '', false],
        ['opPd', $def_opPd, $req_opPd, 'Design pressure (Pd), MPa', 'MPa', '', false],
        ['opTopt', $def_opTopt, $req_opTopt, 'Operating temperature (Topt), degC', 'degC', '',false],
        ['opPe', $def_opPe, $req_opPe, 'Design external pressure (Pe), MPa', 'MPa', '', false],
        ['ophl', $def_ophl, $req_ophl, 'Elevation of local pressure point (hl), m', 'm', '', false],
        ['ophref', $def_ophref, $req_ophref, 'Elevation of local pressure point (href), m', 'm', '', false],
        );

      $args = array('');
      $iMax = count($args);
      $iMax = 1;
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

      // dd($args);

      $execPath = 'C:\Users\JQcomputerDorCom\AppData\Local\Programs\Python\Python37-32\python';
      $filePath = 'D:\laravel\sttools\app\Http\python\DNVGLSTF101\DNVGLSTF101WT.py';
      // $execPath = 'python3';
      // $filePath = '/var/www/html/app/Http/python/DNVGLSTF101WT.py'
      $command = ($execPath . ' ' . $filePath . ' '. $args[0]);
      //echo($command);
      $output = shell_exec($command);

      // dd($output);
      $outputJson = json_decode($output, true);

        return view('pages.DNVGLSTF101WT.index', compact('request','PLs', 'OPs','outputJson'));
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
        * @param  \App\DNVGLSTF101  $dNVGLRPF109
        * @return \Illuminate\Http\Response
        */
        public function show(DNVGLRPF109 $dNVGLRPF109)
        {
        //
        }

        /**
        * Show the form for editing the specified resource.
        *
        * @param  \App\DNVGLSTF101  $dNVGLRPF109
        * @return \Illuminate\Http\Response
        */
        public function edit(DNVGLSTF101 $dNVGLSTF101)
        {
        //
        }

        /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  \App\DNVGLSTF101  $dNVGLRPF109
        * @return \Illuminate\Http\Response
        */
        public function update(Request $request,DNVGLSTF101 $dNVGLSTF101)
        {
        //
        }

        /**
        * Remove the specified resource from storage.
        *
        * @param  \App\DNVGLSTF101  $dNVGLRPF109
        * @return \Illuminate\Http\Response
        */
        public function destroy(DNVGLSTF101 $dNVGLSTF101)
        {
        //
        }
        }
