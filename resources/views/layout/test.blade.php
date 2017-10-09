@extends('adminlte::page')

@section('title', 'Dashboard')



@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Select Any Two Organism</h3>

        </div><!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>First Organism</label>
                        <select class="js-example-basic-single js-states form-control" data-placeholder="Select a Organism" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <optgroup label="Drought">
                                <option>Arabidopsis Thaliana</option>
                                <option>Maize</option>
                            </optgroup>
                        </select></div>
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Second Organism</label>
                        <select class="form-control select2" style="width: 100%;" data-placeholder="Select a Organism" tabindex="-1" aria-hidden="true">

                            <optgroup label="Drought">
                                <option>Arabidopsis Thaliana</option>
                                <option>Maize</option>
                            </optgroup>

                        </select>
                    </div>
                </div>
                <!-- /.col -->
            </div>

        </div><!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary" style="float: right;">Submit</button>
        </div>
    </div><!-- /.box -->

@stop

@section('css')

@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop