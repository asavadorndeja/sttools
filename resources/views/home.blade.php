@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- <div class="col-md-6 col-md-offset-2"> -->
        <div class="col-md-6">

            <div class="panel panel-primary">
                <div class="panel-heading">Onshore pipeline</div>

                <div class="panel-body">

                  <a href="{{ route('ASME318.index')}}">Wall thickness analysis (ASME B31.8)</a>
                  <label>Calculate the minimum required wall thickness in accodacne with ASME B31.8 </label>

                  <br>
                  <br>

                </div>

              </div>

            </div>
              <div class="col-md-6">

              <div class="panel panel-primary">
                  <div class="panel-heading">Offshore pipeline</div>

                  <div class="panel-body">

                    <a href="{{ route('DNVGLSTF101WT.index')}}">Wall thickness analysis (DNVGL ST F101)</a>
                    <label>Calculate the minimum required wall thickness in accodacne with DNVGL ST F101, October 2017 Edition</label>

                    <br>
                    <br>

                    <a href="{{ route('DNVGLRPF109.index')}}">Absolute stability analysis (DNVGL RP F109)</a>
                    <label>Calculate the absolute vertical and horizontal stability in accordance with DNVGL RP F109, May 2017 Edition</label>

                    <br>
                    <br>

                    <a href="{{ route('DNVGLRPF103.index')}}">Anode mass calculation (DNVGL RP F103)</a>
                    <label>Calculate presents the dimension of anode (Length) in accordance with DNVGL RP F103, July 2016 Edition</label>

                    <!-- <br>
                    <br>

                    <a href="{{ route('DNVGLRPF105.index')}}">Free span analysis (DNVGL RP F105)</a>
                    <label>Calculate the fatige life of the isolated span in accordance with DNVGL RP F109, June 2017 Edition </label> -->
<!--
                    <br>
                    <br>

                    <a href="{{ route('f114.index')}}">Pipeline soil interaction analysis (DNVGL RP F114)</a>
                    <label>Calculate the embedment, axial pipeline resistance and lateral pipeline resistance in accordance with DNVGL RP F114, May 2017 Edition </label> -->

                    <br>
                    <br>

                    <a href="{{ route('DNVGLRPF114.index')}}">Pipeline soil interaction analysis (DNVGL RP F114)</a>
                    <label>Calculate the embedment, axial pipeline resistance and lateral pipeline resistance in accordance with DNVGL RP F114, May 2017 Edition </label>

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
