@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Select analysis</div>

                <div class="panel-body">

                  <a href="#">DNVGL RP F105 Freespan spanning pipeline</a>
                  <label>The analysis presents allowable span (screening) in accordance with DNVGL RP F115 </label>

                  <br>
                  <br>

                  <a href="{{ route('f114.index')}}">DNVGL RP F114 Pipeline soil interaction analysis</a>
                  <label>The analysis presents the embedment, axial pipeline resistance and lateral pipeline resistance in accordance with DNVGL RP F114, May 2017 Edition </label>
                </div>

              </div>

                <!-- <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div> -->
            </div>
        </div>
    </div>
</div>
@endsection
