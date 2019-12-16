<?php
/**
 * This file (profile.blade.php) was created on 06/19/2016 at 16:17.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    Saved items
@stop
@section('content')
<?php
use App\Student;
use App\SavedLearningItem
/** @var Student $student */;
/** @var SavedLearningItem $sli */
/** @var Folder $folder */?>

    <div class="container-fluid">
        @card
        <h1>{{ __('saved_learning_items.saved') }}</h1>
        <div class="row">
            <div class="col-md-6">
                @card
                    <h2 class='maps'>{{ __('saved_learning_items.timeline') }}</h2>
                    <br>
                    @foreach($sli as $item)
                    @if($item->category === 'tip' && $item->folder == null)
                        @foreach($evaluatedTips as $evaluatedTip)
                        @if($evaluatedTip->getTip()->id == $item->item_id)
                        <h4>{{date('d-m-Y', strtotime($item->created_at))}}</h4>
                        @card
                        <h5>{{ __('tips.personal-tip') }}</h5>
                        <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px; margin-bottom: 10px"
                             role="alert">
                             <a onclick="chooseItem({{ $item->sli_id }})" data-target="#addItemModel" data-toggle="modal"><span class="glyphicon glyphicon-plus add-tip" aria-hidden="true"></span></a>
                             <h4 class="tip-title">{{ __('tips.personal-tip') }}</h4>
                            <p>{!! nl2br($evaluatedTip->getTipText()) !!}</p>
                        </div>
                        @endcard
                        @endif
                        @endforeach
                    @endif
                    @endforeach

                @endcard

            </div>
            <div class="col-md-6">
            @card
                <h2 class="maps">{{ __('folder.folders') }}</h2> <a data-target="#addFolderModel" data-toggle="modal"><span class="glyphicon glyphicon-plus add-collapse" aria-hidden="true"></span></a>

                @foreach($folders as $folder)
                    <div class="card card-body">
                        <button class="btn-collapse" data-toggle="collapse" href="#{{$folder->folder_id}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <strong>{{ $folder->title }}</strong>
                        </a>
                        <div class="collapse" id="{{$folder->folder_id}}">
                            <i>{{date('d-m-Y', strtotime($folder->created_at))}}</i>
                            <br>
                            {{ $folder->description }}

                            @foreach($sli as $item)
                                @if($item->category == 'tip')
                                    @foreach($tips as $tip)
                                        @if($tip->id == $item->item_id && $item->folder == $folder->folder_id)
                                            <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px; margin-bottom: 10px"
                                                role="alert">
                                                <h4 class="tip-title">{{ __('tips.personal-tip') }}</h4>
                                                <p>{!! nl2br($tip->tipText) !!}</p>
                                            </div>
                                         @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>   
                 @endforeach

            @endcard
            </div>

         </div>
    </div>
    @endcard
          <!-- Modal to add a item to folder-->
    <div class="modal fade" id="addItemModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{ __('folder.add-to-folder') }}</h4>
        </div>
        <div class="modal-body">
        {!! Form::open(array(
            'url' =>  route('saved-learning-item.updateFolder')))
            !!}

        <div class="form-group">
            <input type='text' name='sli_id' id="sli_id" class="form-control">
        </div>

        <div class="form-group">
            <select name="chooseFolder">
            @foreach($folders as $folder)
            <option value="{{$folder->folder_id}}">{{$folder->title}}</option>
            @endforeach
            </select>
        </div>

           
        </div>
        <div class="modal-footer">
        {{ Form::submit('Opslaan', array('class' => 'btn btn-primary', 'id' => 'addItemToFolder')) }}
            {{ Form::close() }}
      
        </div>
      </div>
      
    </div>
    </div>

      <!-- Modal to add a folder-->
    <div class="modal fade" id="addFolderModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{ __('folder.new-folder') }}</h4>
        </div>
        <div class="modal-body">

        {!! Form::open(array(
            'url' =>  route('folder.create')))
            !!}
            <div class="form-group">
                <label>{{ __('folder.title') }}</label>
                <input id='folderTitle' type='text' name='folder_title' class="form-control">
            </div>
                          
            <div class="form-group">
                <label>{{ __('folder.description') }}</label>
                <textarea type='text' name='folder_description' id="folderDescription" class="form-control"></textarea>
            </div>
            

        <div class="modal-footer">
            {{ Form::submit('Opslaan', array('class' => 'btn btn-primary', 'id' => 'saveButton')) }}
            {{ Form::close() }}
           
        </div>
      </div>
      
    </div>
  </div>
  
</div>
@include('js.learningitem_save')
@stop
