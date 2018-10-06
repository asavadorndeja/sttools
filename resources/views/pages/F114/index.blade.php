@extends('layouts.app')

@section('content')

<div class="Container">

  <div class="container">
    <div class="col-md-12">
      <h1>DNVGL RP F114 Pipeline Soil Interaction Analysis</h1>
    </div>
  </div>

  <div class="container">

      <div class="col-md-12">
        <div class="btn-group btn-sm" role="group">

        </div>
      </div>
    </div>
  </div>
</div>

<hr>

  <div class="container">

<form method="get" class="form-group" action="{{ action('f114Controller@index') }}">

  <div class="row">

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Pipeline data
        </div>
        <div class="panel-body">
            <img src="{{URL::asset('/image/F114.png')}}" alt="Input "></div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Pipeline data
        </div>
        <div class="panel-body">

            <div class="row">
                <div class='col-md-4'>
                  <label>Outer diameter, D</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Outer diameter" name="D" type="text" value={{ $request->D }} id="D">
                </div>
                <div class='col-md-4'>
                  <label>m</label>
                </div>
            </div>

            <div class="row top-buffer">
                <div class='col-md-4'>
                  <label>Installation Wt</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Submerged weight (installation)" name="Winst" type="text" value={{ $request->Wins }} id="Winst">
                </div>
                <div class='col-md-4'>
                  <label>N/m</label>
                </div>
            </div>

            <div class="row top-buffer">
                <div class='col-md-4'>
                  <label>Hydrotest Wt</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Submerged weight (hydrotest)" name="Whdt" type="text" value={{ $request->Whdt }} id="Whdt">
                </div>
                <div class='col-md-4'>
                  <label>N/m</label>
                </div>
            </div>

            <div class="row top-buffer">
                <div class='col-md-4'>
                  <label>Operation Wt</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Submerged weight (operation)" name="Wope" type="text" value={{ $request->Wopt }} id="Wope">
                </div>
                <div class='col-md-4'>
                  <label>N/m</label>
                </div>
            </div>

            <div class="row top-buffer">
                <div class='col-md-4'>
                  <label>Moment of inetia</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Moment of inertia" name="I" type="text" value={{ $request->I }} id="I">
                </div>
                <div class='col-md-4'>
                  <label>m<sup>4</sup></label>
                </div>
            </div>

            <div class="row top-buffer">
                <div class='col-md-4'>
                  <label>Bottom tension</label>
                </div>
                <div class='col-md-4'>
                  <input required class="form-control" placeholder="Moment of inertia" name="T0" type="text" value={{ $request->T0 }} id="T0">
                </div>
                <div class='col-md-4'>
                  <label>N</label>
                </div>
            </div>

      </div>
    </div>

  </div>

</div>


    <div class="panel panel-primary">

        <div class="panel-heading">
          Geotechnical data
        </div>

        <div class="panel-body">

          <div class="container">

          <div class="row">

            <div class='col-md-1'>
              <label>Index</label>
            </div>

              <div class='col-md-1'>
                <label>Depth</label>
              </div>

              <div class='col-md-1'>
                <label><font face="Symbol">g</font>'</label>
              </div>

              <div class='col-md-1'>
                <label>S<sub>u</sub></label>
              </div>

              <div class='col-md-1'>
                <label>S<sub>u re</sub></label>
              </div>

              <div class='col-md-1'>
                <label><font face="Symbol">f</font></label>
              </div>

              <div class='col-md-1'>
                <label><font face="Symbol">d</font><sub>peak</sub></label>
              </div>

              <div class='col-md-1'>
                <label><font face="Symbol">d</font><sub>residual</sub></label>
              </div>

              <div class='col-md-1'>
                <label>m</label>
              </div>

          </div>

          <div class="row">

            <div class='col-md-1'>
              <label></label>
            </div>

              <div class='col-md-1'>
                <label>(m)</label>
              </div>

              <div class='col-md-1'>
                <label>N/m<sup>3</sup></label>
              </div>

              <div class='col-md-1'>
                <label>Pa</label>
              </div>

              <div class='col-md-1'>
                <label>Pa</label>
              </div>

              <div class='col-md-1'>
                <label>deg.</label>
              </div>

              <div class='col-md-1'>
                <label>deg.</label>
              </div>

              <div class='col-md-1'>
                <label>deg.</label>
              </div>

              <div class='col-md-1'>
                <label>-</label>
              </div>

          </div>


            @for ($i = 0; $i < 5; $i++)
              <div class="row top-buffer">

                <div class='col-md-1'>
                    {{ $i }}
                </div>

                <div class='col-md-1'>
                    <input class="form-control" name="z{{ $i }}" type="text" value={{ $i }} id="z{{ $i }}">
                </div>

                <div class='col-md-1'>
                    <input class="form-control" name="gamma{{ $i }}" type="text" value={{ $request->gamma[$i]}} id="gamma{{ $i }}">
                </div>

                <div class='col-md-1'>
                    <input class="form-control" name="su{{ $i }}" type="text" value={{ $request->su[$i]}} id="su{{ $i }}">
                </div>

                <div class='col-md-1'>
                    <input class="form-control" name="su_re{{ $i }}" type="text" value={{ $request->su_re[$i]}} id="su_re{{ $i }}">
                </div>

                <div class='col-md-1'>
                    <input class="form-control" name="phi{{ $i }}" type="text" value={{ $request->phi[$i]}} id="phi{{ $i }}">
                </div>

                <div class='col-md-1'>
                    <input class="form-control" name="deltaPeak{{ $i }}" type="text" value={{ $request->deltaPeak[$i]}} id="deltaPeak{{ $i }}">
                </div>

                <div class='col-md-1'>
                    <input class="form-control" name="deltaRes{{ $i }}" type="text" value={{ $request->deltaRes[$i]}} id="deltaRes{{ $i }}">
                </div>

                <div class='col-md-1'>
                    <input class="form-control" name="deltaRes{{ $i }}" type="text" value={{ $request->mShansep[$i]}} id="mShansep{{ $i }}">
                </div>

              </div>

            @endfor

        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="col-4">
        <button type="Submit" value="Submit" class="btn btn-primary">Analysis</button>
      </div>
    </div>

  </form>
</div>

<hr>

<div class="container">

  <div class="panel panel-primary">
    <!-- <div class="panel-heading">
        Pipeline-Soil Interaction Input
    </div>
    <div class="panel-body">
      <div class='col-md-12'>
          <textarea name="input" rows="8" cols="80">
              {{$request->D}}
          </textarea>
      </div>
    </div>
  </div> -->

  <div class="panel panel-primary">
    <div class="panel-heading">
        Pipeline-soil interaction analysis output
    </div>
    <div class="panel-body">
      <div class='col-md-12'>
        <textarea name="name" rows="10" cols="150">
          {{ $output }}
        </textarea>
          <!-- <input type="Text" class="form-control" id="output" name="output" value="{{ $output }}"> -->
      </div>
    </div>
  </div>

</div>

@endsection
