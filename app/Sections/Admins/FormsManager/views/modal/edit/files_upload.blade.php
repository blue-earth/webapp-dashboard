<?php
$options = unserialize($item->options);
$extensions = isset($options['extensions']) ? $options['extensions'] : [];
$fileSize = isset($options['file_size']) ? $options['file_size'] : 1;
?>
@extends('_default.modals.modal')
@section('title')
    @parent
    {{$sectionName}} - {{$actionName}}
@endsection
@section('class_modal')
    w600
@endsection
@section('content')
    <section class="panel panel-default">
        <header class="panel-heading font-bold">{{$form->name}} :: {{\Lang::get('/common/general.'.$type)}}</header>
        <div class="panel-body">
            <form role="form" data-action="{{URL::to('/forms-manager/edit/item/'.$item->id)}}">
                <div class="form-group">
                    <label>{{\Lang::get('/common/general.label')}}</label>
                    <input name="label" value="{{$item->label}}" type="text" placeholder="{{\Lang::get('/common/general.label')}}" class="form-control">
                </div>
                <div class="form-group">
                    <label>{{\Lang::get('/common/general.description')}}</label>
                    <textarea wyswig='basic' name="description" placeholder="{{\Lang::get('/common/general.description')}}" class="form-control">{{$item->description}}</textarea>
                </div>
                <div class="form-group">
                   <div class="checkbox i-checks m-b"><span class="font-bold">Images:</span>
                       <label class=""><input type="checkbox" value="jpg" @if(in_array('jpg',$extensions)) checked @endif  name="extensions[]"><i></i>.jpg</label>
                       <label class=""><input type="checkbox" value="jpeg" @if(in_array('jpeg',$extensions)) checked @endif name="extensions[]"><i></i>.jpeg</label>
                       <label class=""><input type="checkbox" value="png" @if(in_array('png',$extensions)) checked @endif  name="extensions[]"><i></i>.png</label>
                       <label class=""><input type="checkbox" value="gif" @if(in_array('gif',$extensions)) checked @endif  name="extensions[]"><i></i>.gif</label>
                       <label class=""><input type="checkbox" value="bmp" @if(in_array('bmp',$extensions)) checked @endif  name="extensions[]"><i></i>.bmp</label>
                   </div>
                   <div class="checkbox i-checks m-b"><span class="font-bold">Documents:</span>
                       <label class=""><input type="checkbox" value="doc" @if(in_array('doc',$extensions)) checked @endif  name="extensions[]"><i></i>.doc</label>
                       <label class=""><input type="checkbox" value="docx" @if(in_array('docx',$extensions)) checked @endif name="extensions[]"><i></i>.docx</label>
                       <label class=""><input type="checkbox" value="xls" @if(in_array('xls',$extensions)) checked @endif  name="extensions[]"><i></i>.xls</label>
                       <label class=""><input type="checkbox" value="xlsx" @if(in_array('xlsx',$extensions)) checked @endif name="extensions[]"><i></i>.xlsx</label>
                       <label class=""><input type="checkbox" value="pdf" @if(in_array('pdf',$extensions)) checked @endif  name="extensions[]"><i></i>.pdf</label>
                       <label class=""><input type="checkbox" value="txt" @if(in_array('txt',$extensions)) checked @endif  name="extensions[]"><i></i>.txt</label>
                       <label class=""><input type="checkbox" value="odt" @if(in_array('odt',$extensions)) checked @endif  name="extensions[]"><i></i>.odt</label>
                       <label class=""><input type="checkbox" value="rtf" @if(in_array('rtf',$extensions)) checked @endif  name="extensions[]"><i></i>.rtf</label>
                    </div>
                </div>
                <div class="form-group">

                    <label class="col-sm-12">Max file size (MB)</label>

                    <div class="row">
                        <div class="col-sm-8 m-b" id="file_size_slider">
                            <input name="file_size_slider"  value="{{$fileSize}}" data-slider-value="{{$fileSize}}" data-slider-min="0" data-slider-max="5" data-slider-step="0.5">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group ">
                                <input value="{{$fileSize}}" name="file_size" type="text" class="form-control text-right" readonly="">
                                <span class="input-group-addon hidden-sm">MB</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <button class="btn btn-sm btn-success" type="submit">Save</button>
                </div>
            </form>
        </div>
    </section>
    <div class="clearfix"></div>
@endsection
@section('css')
    {{ HTML::style( 'assets/js/slider/slider.css') }}
    <style>
        .w600{max-width:600px}
    </style>
@endsection
@section('js')
    {{ HTML::script( 'assets/js/slider/bootstrap-slider.js') }}
    <script>
        var sliderWidth = '100%';
        fixSize = function(){
            $('.slider.slider-horizontal').width(sliderWidth);
        };
        $(window).ready(function()
        {
            $(document).ready(function() {
                window.addEventListener('resize', function(event){
                    sliderWidth = $('#file_size_slider').width();
                    fixSize();
                });

                $('#file_size_slider input').slider({
                    formater: function(value) {
                        return value+' MB';
                    }
                }).on('slideStop', function(ev){
                    var $fileSize = ev.value;
                    $('input[name=file_size]').val($fileSize);
                });
                fixSize();
            });

            $(".modal form").on('submit', function(e){
                e.preventDefault();
                if(e.handled == 1){
                    e.handled = 1;
                    return false;
                }
                var form = $(this);
                data = form.serialize();
                url = form.data('action');
                $.ajax({
                    context: { element: form },
                    url: url,
                    type: "post",
                    dataType: "json",
                    data:data,
                    success:function(msg) {
                        if(msg.type == 'success'){
                            $('ol#items-list').load('/forms-manager/form/{{$form->id}}/refresh-items',function(){
                                reinitializeItemsList();
                            });
                            $('#ajaxModal').modal('hide');
                        }
                    }
                });
            });

        });
    </script>
@endsection