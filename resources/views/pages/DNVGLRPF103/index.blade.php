@extends('layouts.app')

@section('content')

<div class="Container">

  <div class="container">
    <div class="col-md-12">
      <h1>Anode dimension calculation (DNVGL RP F103)</h1>
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

<form method="get" class="form-group" action="{{ action('DNVGLRPF103Controller@index') }}">

  <div class="row">

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Dimensions
        </div>
        <div class="panel-body">
          <figure class="panel-image">
            <img src="{{URL::asset('/image/DNVGLRPF103.png')}}" alt="Input " class="img-responsive center-block"></div>
          </figure>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Design data
        </div>

        <div class="panel-body">

          <?php
            $col1 = 'col-md-5';
            $col2 = 'col-md-4';
            $col3 = 'col-md-3';
            $col4 = 'col-md-7';
           ?>

           @foreach ($vars as $key => $val)
             @if (is_array($val[5]))
               <div class="row top-buffer">
                   <div class={{ $col1 }}>
                     <label>{{ $val[3] }}</label>
                   </div>
                   <div class={{ $col4 }}>
                     <select name="{{ $val[0] }}" id="{{ $val[0] }}" class="form-control input-md">
                         @foreach($val[5] as $key1 => $val1)
                          @if($val[2] == $key1)
                            <option selected value="{{ $key1 }}">{{ $val1 }}</option>
                          @else
                            <option value="{{ $key1 }}">{{ $val1 }}</option>
                          @endif
                         @endforeach
                       </select>
                   </div>
               </div>
             @else
               <div class="row top-buffer">
                   <div class={{ $col1 }}>
                     <label>{{ $val[3] }}</label>
                   </div>
                   <div class={{ $col2 }}>
                     <input required class="form-control" placeholder="{{ $val[3] }}" name="{{ $val[0] }}" type="text" id="{{ $val[0] }}" value="{{ $val[2] }}">
                   </div>
                   <div class={{ $col3 }}>
                     <label>{{ $val[4] }}</label>
                   </div>
               </div>
             @endif
           @endforeach


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


          <div class="col-md-4">
            <button type="Submit" value="Submit" class="btn btn-primary">Analyse</button>
          </div>
        </div>
      </div>
    </div>

    <div class="panel panel-primary">
      <div class="panel-heading">
          Analysis results
      </div>
      <div class="panel-body">
        <!-- <div class='col-md-12'> -->

        <?php $first = true; ?>

        <table>
          @foreach ($outputJson as $key => $val)
            @foreach ($val as $key1 => $val1)
              @if($key1 == 'output')
                @if($first)
                  <tr>
                    <!-- <th width="10%">No of joints</th> -->

                      @foreach($val1 as $key22 => $val22)
                        <th width="10%">{{$key22}}</th>
                      @endforeach
                  </tr>
                    <?php $first = false; ?>
                @endif

                <tr>
                  @foreach ($val1 as $key2 => $val2)
                    <td>{{$val2}}</td>
                  @endforeach
                </tr>

              @endif
            @endforeach
          @endforeach
        </table>

        <!-- </div> -->
      </div>
    </div>

    <!-- <div class="panel panel-primary">
      <div class="panel-heading">
          Detail analysis result
      </div>
      <div class="panel-body">
        <div class='col-md-12'>
          <textarea name="name" rows="10" cols="150">
            {{ $output }}
          </textarea>
            <input type="Text" class="form-control" id="output" name="output" value="{{ $output }}">
        </div>
      </div>
    </div> -->
  </div>



@endsection
