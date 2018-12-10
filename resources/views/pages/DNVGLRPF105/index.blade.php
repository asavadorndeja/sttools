@extends('layouts.app')

@section('content')

<div class="Container">

  <div class="container">
    <div class="col-md-12">
      <h1>Free span analysis (DNVGL RP F105)</h1>
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

<form method="get" class="form-group" action="{{ action('DNVGLRPF105Controller@index') }}">

  <div class="row">

    <div class="col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <label>Dimensions</label>
        </div>
        <div class="panel-body">
          <figure class="panel-image">
            <img src="{{URL::asset('/image/DNVGLRPF105.png')}}" alt="Input " class="img-responsive center-block"></div>
          </figure>
      </div>
    </div>

    @foreach($inputAll as $key => $value)
      <div class="col-md-6">
        <div class="panel panel-primary">

          <div class="panel-heading">
            <label>{{$key}}</label>
          </div>

          <div class="panel-body">
            <div class="form-group">
              @foreach($value as $key => $val)
                <div class="row top-buffer">
                  <!-- Label -->
                  @foreach($val as $key1 => $val1)
                    @if ($key1 == 3)
                      <div class="col-md-8">
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
                            <div class='col-md-4'>
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
                          <div class='col-md-4'>
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
    @endforeach

  </div>


    <div class="panel panel-primary">
      <div class="panel-heading">
          <label>Analysis options</label>
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

</form>
</div>

@endsection
