@extends('admin.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{__('message.notifications')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.notifications')}}</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('message.notifications').' '.__('message.list')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @if($notifications)
                            <ul class="todo-list">
                            @foreach($notifications  as $n)
                                <li>
                                    @php
                                    $type = $n['type'] == 'candidate_membership' || $n['type'] == 'membership' ? 'membership' : $n['type'];
                                    @endphp
                                    <div class="notif-list-icon"><i class="{{notificationItemIcon($type)}}"></i></div>
                                    <span class="text">
                                        <strong>{{$n['title']}}</strong>
                                        <small class="badge badge-info"><i class="far fa-clock"></i> {{timeAgoByTimeStamp($n['created_at'])}}</small><br />
                                        {!!$n['description']!!}
                                    </span>
                                    <div class="tools">
                                        <i class="fas fa-trash-o"></i>
                                    </div>
                                </li>                                
                            @endforeach
                            </ul>
                            {!!$pagination!!}
                            @else
                            {{__('message.no_notifications_found')}}
                            @endif
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
