<div class="col-md-6">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <label>Geometry</label>
    </div>
    <div class="panel-body">
      <figure class="panel-image">
        <img src="{{$inpFigure}}" alt="Input " class="img-responsive center-block"></div>
      </figure>
  </div>
</div>

<div class="row">



  @foreach($inputAll as $key => $value)
    @if($value[1] == 'list')
    <div class="col-md-6">
      <div class="panel panel-primary">

        <div class="panel-heading">
          <label>{{$key}}</label>
        </div>

        <div class="panel-body">
          <div class="form-group">
            @foreach($value[0] as $key => $val)
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
                <!-- Individual input -->
                  @if (($key1 == 2) and ($val[6] == False))
                    <!-- Individual input as listbox -->
                    @if (is_array($val[5]))
                      @foreach($val1 as $key2 => $val2)
                        @if($key2 == 0)
                          <div class='col-md-6'>
                            <select name="{{$val[0]}}{{$key2}}" id="{{$val[0]}}{{$key2}}" class="form-control input-md">
                                @foreach($val[5] as $key3 => $val3)
                                 @if($val2 == $val3)
                                   <option selected value="{{ $val3 }}">{{ $val3 }}</option>
                                 @else
                                   <option value="{{ $val3 }}">{{ $val3 }}</option>
                                 @endif
                                @endforeach
                              </select>
                          </div>
                        @endif
                      @endforeach
                      <!-- Individual input as input box-->
                    @else
                      @foreach($val1 as $key2 => $val2)
                        @if($key2 == 0)
                          @if(is_array($val2))
                          @else
                            <div class='col-md-6'>
                              <input required class="form-control" style="text-align:right;" name="{{$val[0]}}{{$key2}}" type="text" value={{$val2}} id="{{$val[0]}}{{$key2}}">
                            </div>
                          @endif
                        @endif
                      @endforeach
                    @endif
                  @endif

                  <!-- Group input -->
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
    @endif
  @endforeach

</div>

<!-- Table style input -->
<div class="row">
  @foreach($inputAll as $key => $value)
      @if($value[1] == 'table')

      <div class="col-md-12">
        <div class="panel panel-primary">

          <div class="panel-heading">
            <label>{{$key}}</label>
          </div>



          <div class="panel-body">
            <div class="form-group">
              @foreach($value[0] as $key => $val)
                <div class="row top-buffer">
                  <!-- Label -->
                  @foreach($val as $key1 => $val1)
                    @if ($key1 == 3)
                      <div class="col-md-5">
                        <label>{{$val1}}</label>
                      </div>
                    @endif
                  @endforeach

                  @foreach($val as $key1 => $val1)
                    @if ($key1 == 2)
                      @foreach($val1 as $key2 => $val2)
                        @if ($key2 == 0)
                          @foreach($val2 as $key3 => $val3)
                            <div class="col-md-1">
                              <input required class="form-control" style="text-align:right;" name="{{$val[0]}}{{$key3}}" type="text" value={{$val3}} id="{{$val[0]}}{{$key3}}">
                            </div>
                          @endforeach
                        @endif
                      @endforeach
                    @endif
                  @endforeach

                </div>
              @endforeach
            </div>
          </div>

        </div>
      </div>
      @endif
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

    </div>
  </div>
</div>
