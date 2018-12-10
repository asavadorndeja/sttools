@extends('layouts.app')

@section('content')

<div class="Container">

  <div class="container">
    <div class="col-md-12">
      <h1>Absolute stability analysis (DNVGL RP F109)</h1>
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

<form method="get" class="form-group" action="{{ action('DNVGLRPF109Controller@index') }}">

  <div class="row">

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Dimensions
        </div>
        <div class="panel-body">
          <figure class="panel-image">
            <img src="{{URL::asset('/image/DNVGLRPF109.png')}}" alt="Input " class="img-responsive center-block"></div>
          </figure>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
            Pipeline input data
        </div>
        <div class="panel-body">
          <div class="form-group">
            @foreach($PLs as $key => $val)
              <div class="row top-buffer">
                <!-- Label -->
                @foreach($val as $key1 => $val1)
                  @if ($key1 == 3)
                    <div class="col-md-6">
                      <label>{{$val1}}</label>
                    </div>
                  @endif
                @endforeach

                <!-- Input data -->
                @foreach($val as $key1 => $val1)
                <!-- Group input -->
                  @if (($key1 == 2) and ($val[6] == False))
                    @if (is_array($val[5]))
                      @foreach($val1 as $key2 => $val2)
                        @if($key2 == 0)
                          <div class='col-md-6'>
                            <select name="{{$val[0]}}{{$key2}}" id="{{$val[0]}}{{$key2}}" class="form-control input-md">
                                @foreach($val[5] as $key3 => $val3)
                                 @if($val2 == $key3)
                                   <option selected value="{{ $key3 }}">{{ $val3 }}</option>
                                 @else
                                   <option value="{{ $key3 }}">{{ $val3 }}</option>
                                 @endif
                                @endforeach
                              </select>
                          </div>
                        @endif
                      @endforeach
                    @else
                      @foreach($val1 as $key2 => $val2)
                        @if($key2 == 0)
                        <div class='col-md-6'>
                          <input required class="form-control" style="text-align:right;" name="{{$val[0]}}{{$key2}}" type="text" value={{$val2}} id="{{$val[0]}}{{$key2}}">
                        </div>
                        @endif
                      @endforeach
                    @endif
                  @endif

                  <!-- Individual input -->
                  @if (($key1 == 2) and ($val[6] == True))
                    @if (is_array($val[5]))
                      @foreach($val1 as $key2 => $val2)
                      <div class='col-md-2'>
                        <select name="{{$val[0]}}{{$key2}}" id="{{$val[0]}}{{$key2}}" class="form-control input-md">
                            @foreach($val[5] as $key3 => $val3)
                             @if($val2 == $key3)
                               <option selected value="{{ $key3 }}">{{ $val3 }}</option>
                             @else
                               <option value="{{ $key3 }}">{{ $val3 }}</option>
                             @endif
                            @endforeach
                          </select>
                      </div>
                      @endforeach
                    @else
                      @foreach($val1 as $key2 => $val2)
                        <div class='col-md-2'>
                          <input required class="form-control" style="text-align:right;" name="{{$val[0]}}{{$key2}}" type="text" value={{$val2}} id="{{$val[0]}}{{$key2}}">
                        </div>
                      @endforeach
                    @endif
                  @endif
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
            Seabed input data
        </div>
        <div class="panel-body">
          <div class="form-group">
            @foreach($SBs as $key => $val)
              <div class="row top-buffer">
                <!-- Label -->
                @foreach($val as $key1 => $val1)
                  @if ($key1 == 3)
                    <div class="col-md-6">
                      <label>{{$val1}}</label>
                    </div>
                  @endif
                @endforeach

                <!-- Input data -->
                @foreach($val as $key1 => $val1)
                <!-- Group input -->
                  @if (($key1 == 2) and ($val[6] == False))
                    @if (is_array($val[5]))
                      @foreach($val1 as $key2 => $val2)
                        @if($key2 == 0)
                          <div class='col-md-6'>
                            <select name="{{$val[0]}}{{$key2}}" id="{{$val[0]}}{{$key2}}" class="form-control input-md">
                                @foreach($val[5] as $key3 => $val3)
                                 @if($val2 == $key3)
                                   <option selected value="{{ $key3 }}">{{ $val3 }}</option>
                                 @else
                                   <option value="{{ $key3 }}">{{ $val3 }}</option>
                                 @endif
                                @endforeach
                              </select>
                          </div>
                        @endif
                      @endforeach
                    @else
                      @foreach($val1 as $key2 => $val2)
                        @if($key2 == 0)
                        <div class='col-md-6'>
                          <input required class="form-control" style="text-align:right;" name="{{$val[0]}}{{$key2}}" type="text" value={{$val2}} id="{{$val[0]}}{{$key2}}">
                        </div>
                        @endif
                      @endforeach
                    @endif
                  @endif

                  <!-- Individual input -->
                  @if (($key1 == 2) and ($val[6] == True))
                    @if (is_array($val[5]))
                      @foreach($val1 as $key2 => $val2)
                      <div class='col-md-2'>
                        <select name="{{$val[0]}}{{$key2}}" id="{{$val[0]}}{{$key2}}" class="form-control input-md">
                            @foreach($val[5] as $key3 => $val3)
                             @if($val2 == $key3)
                               <option selected value="{{ $key3 }}">{{ $val3 }}</option>
                             @else
                               <option value="{{ $key3 }}">{{ $val3 }}</option>
                             @endif
                            @endforeach
                          </select>
                      </div>
                      @endforeach
                    @else
                      @foreach($val1 as $key2 => $val2)
                        <div class='col-md-2'>
                          <input required class="form-control" style="text-align:right;" name="{{$val[0]}}{{$key2}}" type="text" value={{$val2}} id="{{$val[0]}}{{$key2}}">
                        </div>
                      @endforeach
                    @endif
                  @endif
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
            Environmental input data
        </div>
        <div class="panel-body">
          <div class="form-group">
            @foreach($ENs as $key => $val)
              <div class="row top-buffer">
                <!-- Label -->
                @foreach($val as $key1 => $val1)
                  @if ($key1 == 3)
                    <div class="col-md-6">
                      <label>{{$val1}}</label>
                    </div>
                  @endif
                @endforeach

                <!-- Input data -->
                @foreach($val as $key1 => $val1)
                <!-- Group input -->
                  @if (($key1 == 2) and ($val[6] == False))
                    @if (is_array($val[5]))
                      @foreach($val1 as $key2 => $val2)
                        @if($key2 == 0)
                          <div class='col-md-6'>
                            <select name="{{$val[0]}}{{$key2}}" id="{{$val[0]}}{{$key2}}" class="form-control input-md">
                                @foreach($val[5] as $key3 => $val3)
                                 @if($val2 == $key3)
                                   <option selected value="{{ $key3 }}">{{ $val3 }}</option>
                                 @else
                                   <option value="{{ $key3 }}">{{ $val3 }}</option>
                                 @endif
                                @endforeach
                              </select>
                          </div>
                        @endif
                      @endforeach
                    @else
                      @foreach($val1 as $key2 => $val2)
                        @if($key2 == 0)
                        <div class='col-md-6'>
                          <input required class="form-control" style="text-align:right;" name="{{$val[0]}}{{$key2}}" type="text" value={{$val2}} id="{{$val[0]}}{{$key2}}">
                        </div>
                        @endif
                      @endforeach
                    @endif
                  @endif

                  <!-- Individual input -->
                  @if (($key1 == 2) and ($val[6] == True))
                    @if (is_array($val[5]))
                      @foreach($val1 as $key2 => $val2)
                      <div class='col-md-2'>
                        <select name="{{$val[0]}}{{$key2}}" id="{{$val[0]}}{{$key2}}" class="form-control input-md">
                            @foreach($val[5] as $key3 => $val3)
                             @if($val2 == $key3)
                               <option selected value="{{ $key3 }}">{{ $val3 }}</option>
                             @else
                               <option value="{{ $key3 }}">{{ $val3 }}</option>
                             @endif
                            @endforeach
                          </select>
                      </div>
                      @endforeach
                    @else
                      @foreach($val1 as $key2 => $val2)
                        <div class='col-md-2'>
                          <input required class="form-control" style="text-align:right;" name="{{$val[0]}}{{$key2}}" type="text" value={{$val2}} id="{{$val[0]}}{{$key2}}">
                        </div>
                      @endforeach
                    @endif
                  @endif
                @endforeach
              </div>
            @endforeach
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


          <div class="col-md-2">
            <button type="Submit" value="Submit" class="btn btn-primary">Analyse</button>
          </div>
          <!-- <div class="col-md-2">
            <label>Insert code to get full report</label>
          </div>
          <div class="col-md-4">
            <input class="form-control" style="text-align:left;" name="password" type="text"  id="password">
          </div> -->
        </div>
      </div>
    </div>

    <div class="panel panel-primary">
      <div class="panel-heading">
          Result
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="container">
            <div class="row">
              <div class="col-md-6"><b><u>Parameter</u></b></div>
              <div class="col-md-2"><b><u>Case 1</u></b></div>
              <div class="col-md-2"><b><u>Case 2</u></b></div>
              <div class="col-md-2"><b><u>Case 3</u></b></div>
            </div>
          </div>
          <div class="container">
          <?php $iMax = count($outputJson[0]) ?>
          @for ($i = 0; $i < $iMax; $i++)
            <div class="row">
              @foreach ($outputJson as $key0 => $val0)
                <?php $j = 0 ?>
                @if ($key0 == 0)
                <?php $first = 1 ?>
                @endif
                @foreach ($val0 as $key1 => $val1)

                  @if ($j == $i)
                    @if ($first == 1)
                      <div class="col-md-6">{{$key1}}</div>
                      <?php $first = 0 ?>
                    @endif
                      <div class="col-md-2" >{{$val1}}</div>
                  @endif
                  <?php $j = $j +1 ?>
                @endforeach
              @endforeach
            </div>
          @endfor
        </div>
        </div>
      </div>
    </div>

</form>
</div>


<!-- <form method="post" class="form-group" action="{{ action('EmailController@sendReport') }}">

  {{ csrf_field() }}

  <div class="container">
    <div class="panel panel-primary">
      <div class="panel-heading">
          Get a report
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="row">
            <div class="col-md-12">
              <label>Insert your email. The report will be sent you shortly. Syntera confirms that your email will be used for report delivery only.</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <input required class="form-control" placeholder="Your email address" name="Email" type="Email" id="Email">
            </div>
            <div class="col-md-6">
              <button type="Report" value="Submit" class="btn btn-primary">Get a report</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
</div> -->

@endsection
