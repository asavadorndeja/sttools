<?php

namespace App\Http\Controllers;

use App\DNVGLRPF105;
use Illuminate\Http\Request;

class DNVGLRPF105Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //
        # Pipeline input data
        $def_plOD = [0.27305];
        $def_plTsteel = [0.01275];
        $def_plTcoating = [0.001275];
        $def_plTconcrete = [0.0050];
        $def_plRhoSteel = [7850];
        $def_plRhoCoating = [920];
        $def_plRhoConcrete = [3040];
        $def_plNu = [0.3];
        $def_plE = [207E09];
        $def_plAlhpa = [11.7E06];
        $def_plKc = [0.25];
        $def_plFnc = [0];
        $def_plK = [1E-05];

        $req_plOD = [$request->plOD0];
        $req_plTsteel = [$request->plTsteel0];
        $req_plTcoating = [$request->plTcoating0];
        $req_plTconcrete = [$request->plTconcrete0];
        $req_plRhoSteel = [$request->plRhoSteel0];
        $req_plRhoCoating = [$request->plRhoCoating0];
        $req_plRhoConcrete = [$request->plRhoConcrete0];
        $req_plNu = [$request->plNu0];
        $req_plE = [$request->plE0];
        $req_plAlhpa = [$request->plAlhpa0];
        $req_plKc = [$request->plKc0];
        $req_plFnc = [$request->plFnc0];
        $req_plK = [$request->plK0];

        $PLs = array(
          ['plOD', $def_plOD, $req_plOD, 'Outer diameter (D), m', 'm', '', False],
          ['plTsteel', $def_plTsteel, $req_plTsteel, 'Steel thickness (tsteel)', 'm', '', False],
          ['plTcoating', $def_plTcoating, $req_plTcoating, 'Coating thickness (tcoating), m', 'm', '', False],
          ['plTconcrete', $def_plTconcrete, $req_plTconcrete, 'Concrete thickness (tconcrete), m', 'm', '', False],
          ['plRhoSteel', $def_plRhoSteel, $req_plRhoSteel, 'Steel density (Rho steel), kg/cu.m.', 'kg/cu.m.', '', False],
          ['plRhoCoating', $def_plRhoCoating, $req_plRhoCoating, 'Coating density (Rho coating), kg/cu.m.', 'kg/cu.m.', '', False],
          ['plRhoConcrete', $def_plRhoConcrete, $req_plRhoConcrete, 'Concrete denstiy (Rho concrete), kg/cu.m.', 'kg/cu.m.', '', False],
          ['plNu', $def_plNu, $req_plNu, 'Steel poisson ratio (nu), -', '-', '', False],
          ['plAlhpa', $def_plAlhpa, $req_plAlhpa, 'Steel thermal expansion coefficient, 1/degC', '1/degC', '', False],
          ['plE', $def_plE, $req_plE, 'Steel modulus, Pa', 'Pa', '', False],
          ['plKc', $def_plKc, $req_plKc, 'Concrete stiffness factor (kc), -', '-', '', False],
          ['plFnc', $def_plFnc, $req_plFnc, 'Concrete compressive strength (fcn), MPa', 'MPa', '', False],
          ['plK', $def_plK, $req_plK, 'Surface roughness (k), m', 'm', '', False],
        );


        # Span input data
        $def_spH = [60.0];
        $def_spL = [20.0];
        $def_spE= [1.0];
        $def_spD = [0.0];
        $def_spZeta = [90];
        $def_spHeff = [0];
        $def_spP = [6];
        $def_spDeltaT = [25];

        $req_spH = [$request->spH0];
        $req_spL = [$request->spL0];
        $req_spE= [$request->spE0];
        $req_spD = [$request->spD0];
        $req_spZeta = [$request->spZeta0];
        $req_spHeff = [$request->spHeff0];
        $req_spP = [$request->spP0];
        $req_spDeltaT = [$request->spDeltaT0];

        $SPs = array(
          ['spH', $def_spH, $req_spH, 'Water depth (h), m', 'm', '', False],
          ['spL', $def_spL, $req_spL, 'Span length (L), m', 'm', '', False],
          ['spE', $def_spE, $req_spE, 'Span gap (e), m', 'm', '', False],
          ['spD', $def_spD, $req_spD, 'Trench depth (D), m', 'm', '', False],
          ['spZeta', $def_spZeta, $req_spZeta, 'Pipeline direction (Zeta), degree', 'degree', '', False],
          ['spHeff', $def_spHeff, $req_spHeff, 'Resiudal tension (Heff), N', 'N', '', False],
          ['spP', $def_spP, $req_spP, 'Pressure (p), Pa', 'Pa', '', False],
          ['spDeltaT', $def_spDeltaT, $req_spDeltaT, 'Temperature different (DeltaT), degC', 'degC', '', False],
        );


        $inputs = array($PLs, $SPs);

        // Push input into the request
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

        // Reassign the null value
        $req_plOD = [$request->plOD0];
        $req_plTsteel = [$request->plTsteel0];
        $req_plTcoating = [$request->plTcoating0];
        $req_plTconcrete = [$request->plTconcrete0];
        $req_plRhoSteel = [$request->plRhoSteel0];
        $req_plRhoCoating = [$request->plRhoCoating0];
        $req_plRhoConcrete = [$request->plRhoConcrete0];
        $req_plNu = [$request->plNu0];
        $req_plE = [$request->plE0];
        $req_plAlhpa = [$request->plAlhpa0];
        $req_plKc = [$request->plKc0];
        $req_plFnc = [$request->plFnc0];
        $req_plK = [$request->plK0];


        $PLs = array(
          ['plOD', $def_plOD, $req_plOD, 'Outer diameter (D), m', 'm', '', False],
          ['plTsteel', $def_plTsteel, $req_plTsteel, 'Steel thickness (tsteel)', 'm', '', False],
          ['plTcoating', $def_plTcoating, $req_plTcoating, 'Coating thickness (tcoating), m', 'm', '', False],
          ['plTconcrete', $def_plTconcrete, $req_plTconcrete, 'Concrete thickness (tconcrete), m', 'm', '', False],
          ['plRhoSteel', $def_plRhoSteel, $req_plRhoSteel, 'Steel density (Rho steel), kg/cu.m.', 'kg/cu.m.', '', False],
          ['plRhoCoating', $def_plRhoCoating, $req_plRhoCoating, 'Coating density (Rho coating), kg/cu.m.', 'kg/cu.m.', '', False],
          ['plRhoConcrete', $def_plRhoConcrete, $req_plRhoConcrete, 'Concrete denstiy (Rho concrete), kg/cu.m.', 'kg/cu.m.', '', False],
          ['plNu', $def_plNu, $req_plNu, 'Steel poisson ratio (nu), -', '-', '', False],
          ['plAlhpa', $def_plAlhpa, $req_plAlhpa, 'Steel thermal expansion coefficient, 1/degC', '1/degC', '', False],
          ['plE', $def_plE, $req_plE, 'Steel modulus, Pa', 'Pa', '', False],
          ['plKc', $def_plKc, $req_plKc, 'Concrete stiffness factor (kc), -', '-', '', False],
          ['plFnc', $def_plFnc, $req_plFnc, 'Concrete compressive strength (fcn), MPa', 'MPa', '', False],
          ['plK', $def_plK, $req_plK, 'Surface roughness (k), m', 'm', '', False],
        );

        $req_spH = [$request->spH0];
        $req_spL = [$request->spL0];
        $req_spE= [$request->spE0];
        $req_spD = [$request->spD0];
        $req_spZeta = [$request->spZeta0];
        $req_spHeff = [$request->spHeff0];
        $req_spP = [$request->spP0];
        $req_spDeltaT = [$request->spDeltaT0];

        $SPs = array(
          ['spH', $def_spH, $req_spH, 'Water depth (h), m', 'm', '', False],
          ['spL', $def_spL, $req_spL, 'Span length (L), m', 'm', '', False],
          ['spE', $def_spE, $req_spE, 'Span gap (e), m', 'm', '', False],
          ['spD', $def_spD, $req_spD, 'Trench depth (D), m', 'm', '', False],
          ['spZeta', $def_spZeta, $req_spZeta, 'Pipeline direction (Zeta), degree', 'degree', '', False],
          ['spHeff', $def_spHeff, $req_spHeff, 'Resiudal tension (Heff), N', 'N', '', False],
          ['spP', $def_spP, $req_spP, 'Pressure (p), Pa', 'Pa', '', False],
          ['spDeltaT', $def_spDeltaT, $req_spDeltaT, 'Temperature different (DeltaT), degC', 'degC', '', False],
        );


        // Prepare argument for python execution

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

        $inputAll = array(
          'Pipeline data' => $PLs,
          'Span data' => $SPs,
        );

        // dd($inputKey);

        return view('pages.DNVGLRPF105.index', compact('request','inputAll','PLs'));

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
     * @param  \App\DNVGLRPF105  $dNVGLRPF105
     * @return \Illuminate\Http\Response
     */
    public function show(DNVGLRPF105 $dNVGLRPF105)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DNVGLRPF105  $dNVGLRPF105
     * @return \Illuminate\Http\Response
     */
    public function edit(DNVGLRPF105 $dNVGLRPF105)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DNVGLRPF105  $dNVGLRPF105
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DNVGLRPF105 $dNVGLRPF105)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DNVGLRPF105  $dNVGLRPF105
     * @return \Illuminate\Http\Response
     */
    public function destroy(DNVGLRPF105 $dNVGLRPF105)
    {
        //
    }
}
