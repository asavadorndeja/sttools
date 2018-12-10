@extends('layouts.app')

@section('content')

<div class="Container">

  <div class="container">
    <div class="col-md-12">
      <h1>Pipeline wall thickness calculation (ASME B31.8)</h1>
    </div>
  </div>

  <div class="container">

      <div class="col-md-12">
        <div class="btn-group btn-sm" role="group">

        </div>
      </div>
    </div>
  </div>

<!-- <hr> -->
<div class="container">

<form method="get" class="form-group" action="{{ action('ASME318Controller@index') }}">

  <div class="row">

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Pipeline data
        </div>
        <div class="panel-body">
          <figure class="panel-image">
            <img src="{{URL::asset('/image/ASMEB318.png')}}" alt="Input " class="img-responsive center-block"></div>
          </figure>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Design data
        </div>
        <div class="panel-body">

            <div class="row">
                <div class='col-md-5'>
                  <label>Outer diameter, D</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Outer diameter" name="D" type="text" id="D" value={{ $request->D }}>
                </div>
                <div class='col-md-3'>
                  <label>m</label>
                </div>
            </div>

            <div class="row top-buffer">
                <div class='col-md-5'>
                  <label>Design pressure, P<sub>in</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Design pressure (internal)" name="Pin" type="text" id="Pin" value={{ $request->Pin }}>
                </div>
                <div class='col-md-3'>
                  <label>Pa</label>
                </div>
            </div>

            <div class="row top-buffer">
                <div class='col-md-5'>
                  <label>Design temperature, T<sub>in</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Design temperature (internal)" name="Tin" type="text" id="Tin" value={{ $request->Tin }}>
                </div>
                <div class='col-md-3'>
                  <label>degC</label>
                </div>
            </div>


            <div class="row top-buffer">
              <div class='col-md-5'>
                <label>Water depth, WD</label>
              </div>
              <div class='col-md-4'>
                <input required class="form-control" placeholder="Water depth" name="WD" type="text" id="WD" value={{ $request->WD }}>
              </div>
              <div class='col-md-3'>
                <label>m</label>
              </div>
            </div>

            <div class="row top-buffer">
                <div class='col-md-5'>
                  <label>Seawater denstity, &rho;<sub>water</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Seawater density" name="rhoWater" type="text" id="rhoWater" value={{ $request->rhoWater }}>
                </div>
                <div class='col-md-3'>
                  <label>kg/m<sup>3</label>
                </div>
            </div>

          </div>
        </div>

      </div>

    </div>


    <div class="panel panel-primary">
      <div class="panel-heading">
          Analysis options
      </div>
      <div class="panel-body">
        <div class="form-group">

          <!-- <div class='col-md-2'>
            <label>Select the line pipe</label>
          </div> -->

          <div class="col-md-5">
            <select name="linePipe" id="linePipe" class="form-control input-md">
                @foreach($request->E as $E)
                  @if($request->linePipe == $E['des'])
                    <option selected value="{{ $E['des']  }}">{{ $E['des']  }}</option>
                  @else
                  <option value="{{ $E['des'] }}">{{ $E['des'] }}</option>
                  @endif
                @endforeach
              </select>
          </div>

          <div class="col-4">
            <button type="Submit" value="Submit" class="btn btn-primary">Analyse</button>
          </div>
        </div>
      </div>
    </div>


  <div class="panel panel-primary">

      <div class="panel-heading">
        Minimum requied wall thickness (mm)
      </div>

      <div class="panel-body">

        <div class="container">

          <?php $first = true; ?>

          <table>
            @foreach($output as $key1 => $val1)

              @if($first)

              <tr>
                <th width="10%">Pipe grade</th>

                  @foreach($val1 as $key2 => $value2)
                    <th width="18%">{{$key2}}</th>
                  @endforeach
              </tr>


                <?php $first = false; ?>
              @endif

              <tr>

              <td>{{$key1}}</td>
                @foreach($val1 as $key2 => $val2)
                  <td>{{$val2}}</td>
                @endforeach
              </tr>
            @endforeach
          </table>



      </div>
    </div>
  </div>

@endsection
