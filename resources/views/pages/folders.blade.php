<?php
/**
 * This file (profile.blade.php) was created on 06/19/2016 at 16:17.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    {{ __('folder.folders') }}
@stop
@section('content')
<?php
use App\Student;
use App\SavedLearningItem
/** @var Student $student */;
/** @var SavedLearningItem $sli */
/** @var Folder $folder */?>

    <div class="container-fluid">
    <script>
            $(document).ready(function () {
                // Tooltips
                $('[data-toggle="tooltip"]').tooltip();
            });
    </script>
        @card
            <h1>{{ __('folder.folders') }} <a class="btn btn-info right" data-target="#addFolderModel" data-toggle="modal">{{ __('folder.create-folder') }}</a></h1>

            <div class="row">
                <div class="col-md-6">
                    @card
                    <h2 class="maps">{{ __('folder.prive') }}</h2>
                    {{-- <a data-target="#addFolderModel" data-toggle="modal"><span class="glyphicon glyphicon-plus add-collapse" aria-hidden="true"></span></a> --}}

                    @foreach($student->folders as $folder)
                        @if (!$folder->isShared())
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading" id="folder">
                                    <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#{{$folder->folder_id}}">{{ $folder->title }}</a>
                                        <div class="clearfix"></div>
                                        <p class="sub-title-light">{{ count($folder->savedLearningItems)}} {{ __('folder.items') }}</p>
                                        <div class="bullet">&#8226;</div>
                                        <p class="sub-title-light">{{ count($folder->folderComments)}} {{ __('folder.comments') }}</p>
                                    </h4>
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" id="dropdownMenuFolder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right no-top" aria-labelledby="dropdownMenuFolder">
                                            <li><a class="color-red" href="{{ route('folder.destroy', ['folder' => $folder]) }}" onclick="return confirm('{{ __('folder.delete-confirmation') }}')">{{ __('folder.delete-folder') }}</a></li>
                                        </ul>
                                    </div>
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
                                            <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px; margin-bottom: 10px" role="alert">
                                            <a href="{{ route('saved-learning-item.removeItemFromFolder', ['sli' => $item])}}"><span class="right glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                                    <h4 class="tip-title">{{ __('tips.personal-tip') }}</h4>
                                                    @if (in_array($item->item_id, array_keys($evaluatedTips)))
                                                        <p>{{$evaluatedTips[$item->item_id]->getTipText()}}</p>
                                                    @else
                                                        <p>{{ __('saved_learning_items.tip-not-found') }}</p>
                                                    @endif
                                                </div>
                                            @elseif ($item->category === 'activity')
                                                <div class="alert" style="background-color: #FFFFFF; color: 00A1E2; margin-left:2px; margin-bottom: 10px; border: 1px solid #00A1E2" role="alert">
                                                <a href="{{ route('saved-learning-item.removeItemFromFolder', ['sli' => $item])}}"><span class="right glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                                    <h4>Activiteit</h4>
                                                    <p><strong>{{date('d-m-Y', strtotime($activities[$item->item_id]->date))}}</strong>: {{$activities[$item->item_id]->description}}</p>
                                                     <!-- Acting -->
                                                        @if($student->educationProgram->educationprogramType->isActing())
                                                            <span class="glyphicon glyphicon-tasks activity_icons" aria-hidden="true"></span>{{$activities[$item->item_id]->situation}}
                                                        @endif
                                                    <!-- Producing -->
                                                        @if($student->educationProgram->educationprogramType->isProducing())
                                                            <span class="glyphicon glyphicon-time activity_icons" aria-hidden="true"></span>{{$activities[$item->item_id]->duration}} uur
                                                        @endif
                                                    <!-- Both -->
                                                         @if($activities[$item->item_id]->res_person_id === null) 
                                                            <br><span class="glyphicon glyphicon-user activity_icons" aria-hidden="true"></span>Alleen
                                                        @else
                                                        <br><span class="glyphicon glyphicon-user activity_icons" aria-hidden="true"></span>{{$resourcePerson[$item->item_id]->person_label}} 
                                                        @endif
                                                        <br><span class="glyphicon glyphicon-tag activity_icons" aria-hidden="true"></span>{{$categories[$item->item_id]->category_label}} 
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
                                            <textarea placeholder="{{ __('folder.add-comment-teacher') }}" name='folder_comment' class="form-control folder_comment" maxlength="255"></textarea>
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
                                            <textarea placeholder="{{ __('folder.question') }}" name='folder_comment' class="form-control folder_comment" maxlength="255" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            
                                            <label>{{ __('folder.choose-teacher') }}:</label><br>
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
                        @endif
                    @endforeach
                @endcard
                </div>
                <div class="col-md-6">
                    @card
                        <h2 class="maps">{{ __('folder.shared') }}</h2>

                        @foreach($student->folders as $folder)
                            @if ($folder->isShared())
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="folder">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" href="#{{$folder->folder_id}}">{{ $folder->title }}</a>
                                            <div class="clearfix"></div>
                                            <p class="sub-title-light">{{ count($folder->savedLearningItems)}} {{ __('folder.items') }}</p>
                                            <div class="bullet">&#8226;</div>
                                            <p class="sub-title-light">{{ count($folder->folderComments)}} {{ __('folder.comments') }}</p>
                                        </h4>
                                        <div class="dropdown">
                                            <a class="dropdown-toggle" id="dropdownMenuFolder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right no-top" aria-labelledby="dropdownMenuFolder">
                                                <li><a href="{{ route('folder.stop-sharing-folder', ['folder' => $folder]) }}">{{ __('folder.stop-sharing-folder') }}</a></li>
                                                <li><a onclick="setSelectedFolder({{ $folder }})" data-target="#AddItemsToFolderModel" data-toggle="modal">Items toevoegen</a></li>
                                                <li><a class="color-red" href="{{ route('folder.destroy', ['folder' => $folder]) }}" onclick="return confirm('{{ __('folder.delete-confirmation') }}')">{{ __('folder.delete-folder') }}</a></li>
                                            </ul>
                                        </div>
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
                                                <div class="alert" style="background-color: #00A1E2; color: white; margin-left:2px; margin-bottom: 10px" role="alert">
                                                        <a href="{{ route('saved-learning-item.removeItemFromFolder', ['sli' => $item])}}"><span class="right glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                                        <h4 class="tip-title">{{ __('tips.personal-tip') }}</h4>
                                                        @if (in_array($item->item_id, array_keys($evaluatedTips)))
                                                            <p>{{$evaluatedTips[$item->item_id]->getTipText()}}</p>
                                                        @else
                                                            <p>{{ __('saved_learning_items.tip-not-found') }}</p>
                                                        @endif
                                                    </div>
                                                @elseif ($item->category === 'activity')
                                                    <div class="alert" style="background-color: #FFFFFF; color: 00A1E2; margin-left:2px; margin-bottom: 10px; border: 1px solid #00A1E2" role="alert">
                                                    <a href="{{ route('saved-learning-item.removeItemFromFolder', ['sli' => $item])}}"><span class="right glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                                    <h4>Activiteit</h4>
                                                    <p><strong>{{date('d-m-Y', strtotime($activities[$item->item_id]->date))}}</strong>: {{$activities[$item->item_id]->description}}</p>
                                                   <!-- Acting -->
                                                    @if($student->educationProgram->educationprogramType->isActing())
                                                            <span class="glyphicon glyphicon-tasks activity_icons" aria-hidden="true"></span>{{$activities[$item->item_id]->situation}}
                                                        @endif
                                                    <!-- Producing -->
                                                        @if($student->educationProgram->educationprogramType->isProducing())
                                                            <span class="glyphicon glyphicon-time activity_icons" aria-hidden="true"></span>{{$activities[$item->item_id]->duration}} uur
                                                        @endif
                                                    <!-- Both -->
                                                         @if($activities[$item->item_id]->res_person_id === null) 
                                                            <br><span class="glyphicon glyphicon-user activity_icons" aria-hidden="true"></span>Alleen
                                                        @else
                                                        <br><span class="glyphicon glyphicon-user activity_icons" aria-hidden="true"></span>{{$resourcePerson[$item->item_id]->person_label}} 
                                                        @endif
                                                        <br><span class="glyphicon glyphicon-tag activity_icons" aria-hidden="true"></span>{{$categories[$item->item_id]->category_label}} 
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
                                                <textarea placeholder="{{ __('folder.add-comment-teacher') }}" name='folder_comment' class="form-control folder_comment" maxlength="255"></textarea>
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
                                                <textarea placeholder="{{ __('folder.question') }}" name='folder_comment' class="form-control folder_comment" maxlength="255" required></textarea>
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
                            @endif
                        @endforeach
                    @endcard
                </div>
            </div>
        @endcard
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
                <input id='folderTitle' type='text' name='folder_title' class="form-control" maxlength="100" required>
            </div>
                          
            <div class="form-group">
                <label>{{ __('folder.description') }}</label>
                <textarea type='text' name='folder_description' id="folderDescription" class="form-control" maxlength="255"></textarea>
            </div>
            

        <div class="modal-footer">
            {{ Form::submit(__('general.save'), array('class' => 'btn btn-primary', 'id' => 'saveButton')) }}
            {{ Form::close() }}
        </div>
      </div>
      
    </div>
  </div>
  
</div>

{{-- Modal to add items to a folder from the 'guidance' page --}}
<div class="modal fade" id="AddItemsToFolderModel" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                {{-- <h4 class="modal-title">{{ __('folder.add-items') }}</h4> --}}
                <h4 class="modal-title">Items toevoegen aan de map.</h4>
            </div>
            <div class="modal-body">
                <div class="selected-folder-info">
                    <h4 id="folder-title"></h4>
                    <p id="folder-created-at"></p>
                </div>
                <h4 id="selected-items-count" class="right no-margin"></h4>
                {{-- <p>{{ __('folder.items-limit-msg') }}</p> --}}
                <p>Kies maximaal drie items om aan deze map toe te voegen.
                    <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ __('folder.items-limit-hint') }}"></i>
                </p>

                {!! Form::open(array('url' =>  route('folder.AddItemsToFolder'))) !!}
                <div class="form-group">
                    <input class="hidden_element" type='text' name="selected_folder_id" id="selected_folder_id" class="form-control">
                </div>

                <div class="ml-learning-items">
                    <h5>Learning items</h5>
                    @foreach ($sli as $item)
                        @if ($item->category === 'tip')
                            @card
                            <div class="form-group item">
                                <input type="checkbox" name="check_list[]" value="{{$item->sli_id}}" onclick="countSelectedItems()"/>
                                <div class="alert" style="background-color: #00A1E2; color: white;" role="alert">
                                    <h4 class="tip-title">{{ __('tips.personal-tip') }}</h4>
                                    @if (in_array($item->item_id, array_keys($evaluatedTips)))
                                        <p>{{$evaluatedTips[$item->item_id]->getTipText()}}</p>
                                    @else
                                        <p>{{ __('saved_learning_items.tip-not-found') }}</p>
                                    @endif
                                </div>
                            </div>
                            @endcard
                            @elseif ($item->category === 'activity')
                                @card
                                <div class="form-group">
                                    <input type="checkbox" name="check_list[]" value="{{$item->sli_id}}" onclick="countSelectedItems()"/>
                                    <div class="alert" style="background-color: #FFFFFF; color: 00A1E2; margin-left:2px; margin-bottom: 10px; border: 1px solid #00A1E2" role="alert">
                                        <h4>Activiteit</h4>
                                        <p><strong>{{date('d-m-Y', strtotime($activities[$item->item_id]->date))}}</strong>: {{$activities[$item->item_id]->description}}</p>
                                         <!-- Acting -->
                                            @if($student->educationProgram->educationprogramType->isActing())
                                                <span class="glyphicon glyphicon-tasks activity_icons" aria-hidden="true"></span>{{$activities[$item->item_id]->situation}}
                                            @endif
                                         <!-- Producing -->
                                            @if($student->educationProgram->educationprogramType->isProducing())
                                                <span class="glyphicon glyphicon-time activity_icons" aria-hidden="true"></span>{{$activities[$item->item_id]->duration}} uur
                                            @endif
                                         <!-- Both -->
                                            @if($activities[$item->item_id]->res_person_id === null) 
                                                <br><span class="glyphicon glyphicon-user activity_icons" aria-hidden="true"></span>Alleen
                                            @else
                                            <br><span class="glyphicon glyphicon-user activity_icons" aria-hidden="true"></span>{{$resourcePerson[$item->item_id]->person_label}} 
                                            @endif
                                            <br><span class="glyphicon glyphicon-tag activity_icons" aria-hidden="true"></span>{{$categories[$item->item_id]->category_label}} 
                                    </div>
                                </div>
                                @endcard
                            @endif
                    @endforeach
                </div>
                    
                <div class="modal-footer">
                    {{ Form::submit(__('general.save'), array('class' => 'btn btn-primary', 'id' => 'addItemsButton')) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@include('js.learningitem_save')
@stop