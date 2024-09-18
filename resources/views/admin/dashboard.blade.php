@extends('layouts.master')
@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ number_format($user->wallet->balance, 2)}}</h3>
                <p>Balance</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer"> <i class="fas "></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{number_format($totalBalance->balance, 2)}}</h3>
                  @if($role['0'] == 'Admin')
                 <p>Master Total Balance</p>
                  @elseif($role['0'] == 'Master')
                  <p>Agent Total Balance</p>
                  @else
                  <p>Player
                      Total Balance</p>
                  @endif
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
                @if($role['0'] == 'Admin')
              <a href="{{route('admin.master.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    @elseif($role['0'] == 'Master')
                    <a href="{{route('admin.agent.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                @else
                    <a href="{{route('admin.player.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                @endif
            </div>
          </div>
          <!-- ./col -->
            @if($role[0] == 'Agent')
                @else
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$agent_count}}</h3>

                <p>Agent Count</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="{{route('admin.agentList')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
            @endif
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$player_count}}</h3>

                <p>Player Count</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{route('admin.playerList')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- ./col -->
        </div>
      </div>
    </section>
@endsection
