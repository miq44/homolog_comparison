@extends('adminlte::page')

@section('title', 'Dashboard')



@section('content')
    <div class="box box-success">
        {!! Form::open(['method' => 'POST', 'url'=>'/compare' ]) !!}
        <div class="box-header with-border">
            <h3 class="box-title">Select Any Two Organism</h3>

        </div><!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>First Organism</label>
                        <select class="js-example-basic-single js-states form-control"
                                data-placeholder="Select a Organism" style="width: 100%;" tabindex="-1"
                                aria-hidden="true" name="org_t_id_1">
                            @foreach($data as $stress_name=>$info)
                                <optgroup label="{{ $stress_name }}">
                                    @foreach($info as $key=>$org_info)
                                        <option value="{{$org_info['t_id']}}" @if($org_info['t_id']==old('org_t_id_1'))selected @endif>{{ $org_info['org_name'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select></div>
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Second Organism</label>

                        <select class="form-control select2" style="width: 100%;" data-placeholder="Select a Organism"
                                tabindex="-1" aria-hidden="true" name="org_t_id_2">

                            @foreach($data as $stress_name=>$info)
                                <optgroup label="{{ $stress_name }}">
                                    @foreach($info as $key=>$org_info)
                                        <option value="{{$org_info['t_id']}}" @if($org_info['t_id']==old('org_t_id_2'))selected @endif>{{ $org_info['org_name'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach

                        </select>
                    </div>
                </div>
                <!-- /.col -->
            </div>

        </div><!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary" style="float: right;">Submit</button>
        </div>
        {!! Form::close() !!}
    </div><!-- /.box -->
    @if(Session::has('exp_data'))
        <input type="hidden" id="all_data" value="{{ Session::get('exp_data') }}">
        <?php
         $data = Session::pull('exp_data') ;
         $data = json_decode($data);

        ?>

        <div class="box box-success">

            <div class="box-header with-border">
               <div class="row">
                   <div class="col-md-2">Arabidopsis</div>
                   <div class="col-md-4">
                       <select class="form-control" style="width: 100%;" data-placeholder="Select a Condition"
                                                 tabindex="-1" aria-hidden="true" id="org_1_condition">
                           @foreach($data->columns[0] as $key=>$value)
                               <option value="{{$value}}">{{$value}}</option>
                           @endforeach
                       </select>
                       </div>
                   <div class="col-md-2">Maize</div>
                   <div class="col-md-4">
                       <select class="form-control" style="width: 100%;" data-placeholder="Select a Condition"
                               tabindex="-1" aria-hidden="true" id="org_2_condition">
                           @foreach($data->columns[1] as $key=>$value)
                               <option value="{{$value}}">{{$value}}</option>
                           @endforeach
                       </select>
                   </div>
               </div>

            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row" id="scatter_plot_with_exp" style="height: 500px;">

                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    <link rel="stylesheet"
          href="{{ asset('vendor/lib/c3.min.css')}} ">
@stop

@section('js')
    <script src="https://d3js.org/d3.v3.js"></script>
    <script src="{{ asset('vendor/lib/c3.js') }}"></script>
    <script>
        $( document ).ready(function() {

            var data =  $('#all_data').val();
            data = JSON.parse(data);
            var exp= data['exp'];
            var firstOrgCond = '1_'+$('#org_1_condition').val();
            var secondOrgCond = '2_'+$('#org_2_condition').val();
            var firstOrgExp = exp[firstOrgCond];
            firstOrgExp.unshift('Arabidopsis');
            var secondOrgExp = exp[secondOrgCond];
            secondOrgExp.unshift('Maize');
           var scatterChart = c3.generate({
               bindto:'#scatter_plot_with_exp',
               data: {
                   xs:{
                     'Maize':'Arabidopsis',
                   },
                   columns: [

                   ],
                   type:'scatter'
               },
               axis: {
                   y: {
                       max: 400,
                       min: 0,
                       // Range includes padding, set 0 if no padding needed
                       // padding: {top:0, bottom:0}
                   },
                   X:{
                       tick:{
                           type:'category',
                           culling: {
                               max: 50 // the number of tick texts will be adjusted to less than this value
                           }
                       }
                   }
               }
           });

           scatterChart.load({
               columns:[
                   firstOrgExp,
                   secondOrgExp
               ],
               done:function () {
                   alert('Hello world');
               }
           });
        });
    </script>
@stop