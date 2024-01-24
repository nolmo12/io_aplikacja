 @extends('layout')
 @section('content')
 <div style="background-color: #20222a">
 <div class="empty-div"></div>

    <div class="user-section">

        <div class="user-info">

            <div class="user-header">
                <div class="user-avatar">
                    <img src="{{asset('storage/images/'.$user->profile_picture)}}" alt="User" class="user-photo">
                </div>
                <div class="user-details">
                    <h1 class="user-name">{{$user->name}}</h1>
                </div>
            </div>

            <div class="user-stats">
                <div class="stat">
                    <p class="stat-label">Games Played</p>
                    <p class="stat-value">{{$user->games_played}}</p>
                </div>
                <div class="stat">
                    <p class="stat-label">Games Won</p>
                    <p class="stat-value">{{$user->games_won}}</p>
                </div>
                <div class="stat">
                    <p class="stat-label">Win Ratio</p>
                    @if($user->games_played != 0)
                    <p class="stat-value">{{($user->games_won / $user->games_played) * 100}}</p>
                    @else
                    <p class="stat-value">0%</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection