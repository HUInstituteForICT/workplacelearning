<?php
/**
 * This file (profile.blade.php) was created on 06/19/2016 at 16:17.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    {{ __('saved_learning_items.saved-items') }}
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
                    @if($item->category === 'tip' && $item->folder === null)
                        @card
                        <h4 class="maps" >{{date('d-m-Y', strtotime($item->created_at))}}</h4>
                        <a href="{{ route('saved-learning-items-delete', ['sli' => $item])}}"><span class="glyphicon glyphicon-trash delete-tip" aria-hidden="true"></span></a>
                        <a onclick="chooseItem({{ $item->sli_id }})" data-target="#addItemModel" data-toggle="modal"><span class="glyphicon glyphicon-plus add-tip" aria-hidden="true"></span></a>
                        <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px; margin-bottom: 10px"
                             role="alert">
                             <h4 class="tip-title">{{ __('tips.personal-tip') }}</h4>
                            <p>{{$evaluatedTips[$item->item_id]->getTipText()}}</p>
                        </div>
                        @endcard
                    @endif
                    @endforeach

                @endcard

            </div>
            <div class="col-md-6">
            @card
                <h2 class="maps">{{ __('folder.folders') }}</h2> <a data-target="#addFolderModel" data-toggle="modal"><span class="glyphicon glyphicon-plus add-collapse" aria-hidden="true"></span></a>

                @foreach($student->folders as $folder)
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#{{$folder->folder_id}}">{{ $folder->title }}</a>
                            @if ($folder->isShared())
                                <span class="folder-status label label-info">{{ __('folder.shared') }}</span>
                            @else
                                <span class="folder-status label label-default">{{ __('folder.prive') }}</span>
                            @endif
                            <div class="clearfix"></div>
                            <p class="sub-title-light">{{ count($folder->savedLearningItems)}} {{ __('folder.items') }}</p>
                            <div class="bullet">&#8226;</div>
                            <p class="sub-title-light">{{ count($folder->folderComments)}} {{ __('folder.comments') }}</p>
                        </h4>
                        </div>
                        <div id="{{$folder->folder_id}}" class="panel-collapse collapse">
                        
                        {{-- folder basic info --}}
                        <section class="section folder-info">
                            <p class="sub-title-light">{{ __('folder.created-on') }} {{ $folder->created_at->toFormattedDateString() }}</p>
                            <br>
                            {{ $folder->description }}
                        </section>

                        {{-- saved learning items --}}
                        @if (count($folder->savedLearningItems))
                            <hr>
                            <section class="section">
                                <h5>{{ __('folder.added-items') }} <span class="badge">{{ count($folder->savedLearningItems)}}</span></h5>
                                @foreach($folder->savedLearningItems as $item)
                                    @if($item->category === 'tip')
                                        <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px; margin-bottom: 10px"
                                            role="alert">
                                            <h4 class="tip-title">{{ __('tips.personal-tip') }}</h4>
                                            <p> {{$evaluatedTips[$item->item_id]->getTipText()}}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </section>
                        @endif
                        
                        {{-- comments --}}
                        @if (count($folder->folderComments))
                            <hr>
                            <section class="section comment-section">
                                <h5>{{ __('folder.comments') }} <span class="badge">{{ count($folder->folderComments)}}</span></h5>
                                @foreach ($folder->folderComments as $comment)
                                    @if ($comment->author->isStudent())
                                        <div class="comment student-comment">
                                            <p class="sub-title-light"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{date('H:i', strtotime($comment->created_at))}}</p>
                                            <p class="comment-date sub-title-light">{{ $comment->created_at->toFormattedDateString() }}</p>
                                            <p class="comment-author">{{ $comment->author->firstname }} {{ $comment->author->lastname }}</p>
                                            <p class="card-text">{{ $comment->text }}</p>
                                        </div>
                                    @else
                                        <div class="comment teacher-comment">
                                            <p class="sub-title-light"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{date('H:i', strtotime($comment->created_at))}}</p>
                                            <p class="comment-date sub-title-light">{{ $comment->created_at->toFormattedDateString() }}</p>
                                            <p class="comment-author teacher">{{ $comment->author->firstname }} {{ $comment->author->lastname }}</p>
                                            <p class="card-text">{{ $comment->text }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </section>
                        @endif

                        {{-- footer --}}
                        @if ($folder->isShared())
                            <div class="panel-footer">
                                {!! Form::open(array('url' =>  route('folder.addComment'))) !!}
                                <div class="form-group">
                                    <input type='text' value="{{$folder->folder_id}}" name='folder_id' class="form-control folder_id">
                                </div>
                                <div class="form-group">
                                    <textarea placeholder="{{ __('folder.add-comment-teacher') }}" name='folder_comment' class="form-control folder_comment"></textarea>
                                </div>
                                {{ Form::submit(__('general.send'), array('class' => 'right btn btn-primary sendComment')) }}
                                {{ Form::close() }}
                                <div class="clearfix"></div>
                            </div>
                        @elseif (!$folder->isShared() && $student->getCurrentWorkplaceLearningPeriod()->hasTeacher())
                            <div class="panel-footer">
                                {!! Form::open(array('url' =>  route('folder.shareFolderWithTeacher'))) !!}
                                <div class="form-group">
                                    <input type='text' value="{{$folder->folder_id}}" name='folder_id' class="form-control folder_id">
                                </div>
                                <div class="form-group">
                                    <textarea placeholder="{{ __('folder.question') }}" name='folder_comment' class="form-control folder_comment" required></textarea>
                                </div>
                                <div class="form-group">
                                    
                                    <label>{{ __('folder.comments') }}:</label><br>
                                    <select name="teacher" class="form-control">
                                        @foreach($student->getWorkplaceLearningPeriods() as $wplp)
                                            <option value="{{$wplp->teacher_id}}">{{$wplp->teacher->firstname}} {{$wplp->teacher->lastname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{ Form::submit(__('folder.share'), array('class' => 'right btn btn-primary shareFolder')) }}
                                {{ Form::close() }}
                                <div class="clearfix"></div>
                            </div>
                        @else
                            <div class="panel-footer">
                                <div class="custom-alert alert alert-info" role="alert">{{ __('folder.no-teacher') }}</div>
                            </div>
                        @endif
                        </div>
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

        {!! Form::open(array('url' =>  route('saved-learning-item.updateFolder'))) !!}

            <div class="form-group">
                <input type='text' name='sli_id' id="sli_id" class="form-control">
            </div>

            <div class="form-group">
                <select name="chooseFolder" class="form-control">
                    @foreach($student->folders as $folder)
                        <option value="{{$folder->folder_id}}">{{$folder->title}}</option>
                    @endforeach
                </select>
            </div>

            </div>
            <div class="modal-footer">
                {{ Form::submit(__('general.save'), array('class' => 'btn btn-primary', 'id' => 'addItemToFolder')) }}
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

        {!! Form::open(array('url' =>  route('folder.create'))) !!}
            <div class="form-group">
                <label>{{ __('folder.title') }}</label>
                <input id='folderTitle' type='text' name='folder_title' class="form-control" required>
            </div>
                          
            <div class="form-group">
                <label>{{ __('folder.description') }}</label>
                <textarea type='text' name='folder_description' id="folderDescription" class="form-control" required></textarea>
            </div>
            

        <div class="modal-footer">
            {{ Form::submit(__('general.save'), array('class' => 'btn btn-primary', 'id' => 'saveButton')) }}
            {{ Form::close() }}
        </div>
      </div>
      
    </div>
  </div>
  
</div>
@include('js.learningitem_save')
@stop