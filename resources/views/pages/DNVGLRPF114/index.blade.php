@extends('layouts.app')

@section('content')

<div class="Container">

  <div class="container">
    <div class="col-md-12">
      <h1>Pipeline-soil interaction analysis (DNVGL RP F114)</h1>
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

<form method="get" class="form-group" action="{{ action('DNVGLRPF114Controller@index') }}">

  <!-- {{ csrf_field() }} -->


  @include('layouts.anaInput', [
    'inpFigure'=>URL::asset('/image/DNVGLRPF114.png'),
    'inpAll' => $inputAll
  ])

  <!-- List style input -->




    <div class="panel panel-primary">
      <div class="panel-heading">
          Analysis results
      </div>
      <div class="panel-body">
        <!-- <div class='col-md-12'> -->

        <table style="width:100%">
          <tr>
            <th>Description</th>
            <th>Installation</th>
            <th>Hydrotest</th>
            <th>Operation</th>
          </tr>

          @foreach ($outputJson as $key => $val)
            @foreach ($val as $key1 => $val1)
              @if ($key1 == 'output')
                @foreach ($val1 as $key2 => $val2)
                  <tr>
                    <td>{{$key2}}</td>
                  @foreach ($val2 as $key3 => $val3)
                    <td>{{ number_format($val3,2) }}</td>
                  @endforeach
                </tr>
                @endforeach
              @endif
            @endforeach
          @endforeach
        </table>

      </div>
    </div>


</form>
</div>

@endsection
