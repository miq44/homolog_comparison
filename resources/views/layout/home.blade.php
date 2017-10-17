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
         $json_data = Session::pull('exp_data') ;
        // dd($data);
         $data = json_decode($json_data, true);
         //dd($data['org_name']);
        ?>

        <div class="box box-success">

            <div class="box-header with-border">
               <div class="row">
                   <div class="col-md-2 form-group"><label id="org_name_1">{{$data['org_name'][0]}}</label></div>
                   <div class="col-md-4">
                       <select  class="form-control org_condition" style="width: 100%;" data-placeholder="Select a Condition"
                                                 tabindex="-1" aria-hidden="true" id="org_1_condition">
                           @foreach($data['columns'][0] as $key=>$value)
                               <option value="{{$value}}">{{$value}}</option>
                           @endforeach
                       </select>
                       </div>
                   <div class="col-md-2 form-group"><label id="org_name_2">{{$data['org_name'][1]}}</label></div>
                   <div class="col-md-4">
                       <select class="form-control org_condition" style="width: 100%;" data-placeholder="Select a Condition"
                               tabindex="-1" aria-hidden="true" id="org_2_condition">
                           @foreach($data['columns'][1] as $key=>$value)
                               <option value="{{$value}}">{{$value}}</option>
                           @endforeach
                       </select>
                   </div>
               </div>

            </div><!-- /.box-header -->
            <div class="box-body">
                {{--<div class="row" id="loading">--}}
                    {{--<img src="{{asset('public/images/loading.gif')}}" style="height: 500px;width: 100%;">--}}
                {{--</div>--}}
                {{--<div class="row" id="scatter_plot_with_exp" style="height: 500px; display: none;">--}}

                {{--</div>--}}
                <div class="box-body dataTables_wrapper form-inline dt-bootstrap">
                    <table id="expression_table" class="table table-striped table-bordered" cellspacing="0" width="100%">

                        <thead>
                        <tr>
                            <th>{{ $data['org_name'][0] }} Gene Id</th>
                            <th>{{ $data['columns'][0][0] }} Expression</th>
                            <th>{{ $data['columns'][1][0] }} Expression</th>
                            <th>{{ $data['org_name'][1] }} Gene Id</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>{{ $data['org_name'][0] }} Gene Id</th>
                            <th>{{ $data['columns'][0][0] }} Expression</th>
                            <th>{{ $data['columns'][1][0] }} Expression</th>
                            <th>{{ $data['org_name'][1] }} Gene Id</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($data['exp']['gene_id_1'] as $key=>$gene_id_1)
                            <?php
                              $col_1= '1_'.$data['columns'][0][0];
                              $col_2= '2_'.$data['columns'][1][0];
                            ?>
                            <tr>
                                <td>{{ $gene_id_1 }}</td>
                                <td>{{ $data['exp'][$col_1][$key] }}</td>
                                <td>{{ $data['exp'][$col_2][$key] }}</td>
                                <td>{{ $data['exp']['gene_id_2'][$key] }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="box">

        </div>
    @endif
@stop

@section('css')
    <link rel="stylesheet"
          href="{{ asset('public/vendor/lib/c3.min.css')}} ">
@stop

@section('js')
    <script src="https://d3js.org/d3.v3.js"></script>
    <script src="{{ asset('public/vendor/lib/c3.js') }}"></script>
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
            var dataTable = $('#expression_table').DataTable();

            $('.org_condition').on('change',function () {
                var column1 = $('#org_name_1').text()+' Gene Id';
                var column2 = $('#org_1_condition').val();
                var column3 = $('#org_2_condition').val();
                var column4 = $('#org_name_2').text()+' Gene Id';
                var dataset = getData(column2,column3);
                dataTable.clear();
                dataTable.rows.add(dataset);
                dataTable.draw();
                dataTable.columns(1).header().to$().text(column2+' Expression');
                dataTable.columns(2).header().to$().text(column3+' Expression');

            });
//           scatterChart.load({
//               columns:[
//                   firstOrgExp,
//                   secondOrgExp
//               ],
//               done:function () {
//                   $('#loading').hide();
//                   $('#scatter_plot_with_exp').show();
//               }
//           });

            function getData(col1,col2) {
                var dataset =[];
                var index1 = '1_'+col1;
                var index2 = '2_'+col2;
                var geneId1= exp.gene_id_1;
                geneId1.forEach(function(entry,key){
                    var row =[entry,exp[index1][key],exp[index2][key],exp.gene_id_2[key]];
                    dataset.push(row);
                });
                return dataset;
            };
        });
    </script>
@stop