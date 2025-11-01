@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <span class="pull-right">
                        <a href="{{route('surveys.index')}}" class="btn btn-default btn-xs">&laquo; All Surveys</a>
                        @if(is_null($audit))
                            <a href="{{route('surveys.invite', [$survey->id])}}" class="btn btn-default btn-xs"><i class="fa fa-users"></i> Invites</a>
                        @else
                            <a href="{{route('audits.invite', [$audit->id])}}" class="btn btn-default btn-xs"><i class="fa fa-users"></i> Invites</a>
                        @endif
                        <a href="{{route('surveys.show', [$survey->id])}}" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-stats"></i> Dashboard</a>
                    </span>
                    Survey - Invite Users to the <strong>SVY-{{$survey->id}}: {{$survey->title}}</strong> survey.
                </div>

                <div class="panel-body">

                    @if(\Illuminate\Support\Facades\Auth::user()->can_be(\AssetIQ\Models\SecurityRoleOptions::SurveyAdmin))
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <form class="form-horizontal" action="{{route('surveys.invite', [$survey->id])}}" method="post">
                                    {{csrf_field()}}
                                    <div class="input-group">
                                        {{\AssetIQ\ProjectX\Html\UserSelector::to_html('user_id')}}
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit">Add User</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="row" style="margin-top:50px;min-height: 300px;">
                        <div class="col-sm-12">
                            <table id="user_table" class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        @if(\Illuminate\Support\Facades\Auth::user()->can_be(\AssetIQ\Models\SecurityRoleOptions::SurveyAdmin))
                                            <th><i class="fa fa-envelope"></i></th>
                                            <th></th>
                                        @endif
                                        <th>Done?</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Title</th>
                                        <th>Locations</th>
                                        @if(\Illuminate\Support\Facades\Auth::user()->can_be(\AssetIQ\Models\SecurityRoleOptions::SurveyAdmin))
                                            <th></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($targets as $target)
                                        <tr>
                                            @if(\Illuminate\Support\Facades\Auth::user()->can_be(\AssetIQ\Models\SecurityRoleOptions::SurveyAdmin))
                                                <td><input form="send_invite_emails" class="target_chk" type="checkbox" name="targets[]" value="{{$target->id}}" /></td>
                                                <td><button class="btn btn-xs btn-link"><i class="fa fa-link clipboard" data-clipboard="{{route('survey.take', [$survey->hash, $target->hash])}}"></i></button></td>
                                            @endif
                                            <td>{{$target->is_survey_completed() ? 'Yes' : 'No'}}</td>
                                            <td>{{$target->last_name}}</td>
                                            <td>{{$target->first_name}}</td>
                                            <td>{{$target->email}}</td>
                                            <td>{{is_null($target->user_id) ? '--' : $target->user->title}}</td>
                                            <td>
                                                <?php
                                                $codes = [];
                                                if ( !is_null( $target->user_id ) )
                                                {
                                                    if ( !is_null( $target->user->default_location_id ) )
                                                    {
                                                        $codes[] = $target->user->default_location->code;
                                                    }
                                                    foreach ( $target->user->allocations as $allocation )
                                                    {
                                                        if ( !in_array( $allocation->location->code, $codes ) )
                                                        {
                                                            $codes[] = $allocation->location->code;
                                                        }
                                                    }
                                                    foreach ( $target->user->rosters as $roster )
                                                    {
                                                        if ( !in_array( $roster->location->code, $codes ) )
                                                        {
                                                            $codes[] = $roster->location->code;
                                                        }
                                                    }
                                                }
                                                sort( $codes )
                                                ?>
                                                {{count($codes) > 0 ? implode(', ', $codes) : '--' }}
                                            </td>
                                            @if(\Illuminate\Support\Facades\Auth::user()->can_be(\AssetIQ\Models\SecurityRoleOptions::SurveyAdmin))
                                                <td>
                                                    <form action="{{route('surveys.delete_invite', [$survey->id, $target->id])}}" class="confirm" method="post">
                                                        @method('DELETE')
                                                        {{csrf_field()}}
                                                        <button type="submit" class="btn btn-link"><i class="text-danger fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if(\Illuminate\Support\Facades\Auth::user()->can_be(\AssetIQ\Models\SecurityRoleOptions::SurveyAdmin))
                    <div class="panel-footer">
                        <div class="pull-right">
                            <a href="#" id="select_all">all</a>
                            <a href="#" id="select_none">none</a>
                            <a href="#" id="select_toggle">toggle</a>
                        </div>
                        <form id="send_invite_emails" class="form-inline" action="{{route('surveys.invite.send', [$survey->id])}}" method="post">
                            {{csrf_field()}}

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Send Invites</button> <em>Invites will be sent to checked users.</em>
                            </div>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="application/javascript">
        (function ($) {
            $(document).ready(function () {
                $('#user_table').DataTable({
                    dom        : 'Bfrtip',
                    buttons    : ['copy', 'csv', 'excel', 'pdf', 'print'],
                    paging     : false,
                    fixedHeader: true,
                    columnDefs : []
                });

                $('#select_all').click(function(e){
                    e.preventDefault();
                    $('.target_chk').prop('checked', true);
                })

                $('#select_none').click(function(e){
                    e.preventDefault();
                    $('.target_chk').prop('checked', false);
                })

                $('#select_toggle').click(function(e){
                    e.preventDefault();
                    $('.target_chk').each(function(){
                        $(this).prop('checked', !$(this).prop('checked'));
                    })
                })

            });
        })(jQuery);
    </script>
@endpush
