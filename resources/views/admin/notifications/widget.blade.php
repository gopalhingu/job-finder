<!-- Notifications Dropdown Menu -->
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        @if($notifications['count'] > 0)
        <span class="badge badge-warning navbar-badge">{{$notifications['count']}}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{$notifications['count']}} {{__('message.new_notifications')}}</span>
        @foreach($notifications['results'] as $n)
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item" title="{{$n['title']}}">
            <i class="{{notificationItemIcon($n['type'])}}"></i> {{trimString($n['title'], 18)}}
            <span class="float-right text-muted text-sm">{{timeAgoByTimeStamp($n['created_at'])}}</span>
        </a>
        @endforeach
        <div class="dropdown-divider"></div>
        <a href="{{route('admin-notifications-list-view')}}" class="dropdown-item dropdown-footer">
            {{__('message.see_all_notifications')}}
        </a>
    </div>
</li>
