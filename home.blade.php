@extends('layouts.app')

@section('content')

@if ($role == 'SERVICE' or $role == 'ADMIN' or $role == 'RUSYACHMEN' )
    <h3><span class="badge badge-primary">Комплекс с.Отрешково:</span></h3>
    <div class="row">
        <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="public/picts/Otreshkovo_TO.png" class="d-inline-block align-top" alt="icon" style="height: 90px;">
                    <!--<h5 class="card-title">с.Отрешково.</h5>-->
                    <p class="card-text">Наработка оборудования.</p>
                    <a href="{{ url('/otreshkovo_time_reports') }}" class="btn btn-primary">Отчет</a>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="public/picts/Alarm.png" class="d-inline-block align-top" alt="icon" style="height: 90px;">
                    <!--<h5 class="card-title">с.Отрешково.</h5>-->
                    <p class="card-text">Аварийные ситуации.</p>
                    <a href="{{ url('/otreshkovo_alarms') }}" class="btn btn-primary">Отчет</a>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="public/picts/Operator.png" class="d-inline-block align-top" alt="icon" style="height: 90px;">
                    <!--<h5 class="card-title">с.Отрешково.</h5>-->
                    <p class="card-text">Аварии и действия оператора.</p>
                    <a href="{{ url('/otreshkovo_alarm_reports') }}" class="btn btn-primary">Отчет</a>
                </div>
            </div>
        </div>
    </div>

    @if ($user == 'ASU' OR $user == 'Admin')
        <div class="row">
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="public/picts/Alarm.png" class="d-inline-block align-top" alt="icon" style="height: 90px;">
                        <!--<h5 class="card-title">с.Отрешково.</h5>-->
                        <p class="card-text">Аварии скорости.</p>
                        <a href="{{ url('/otreshkovo_speed') }}" class="btn btn-primary">Отчет</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <hr style="background-color: #0d0d0d"/>
@endif



@if ($role == 'SERVICE' or $role == 'ADMIN' )
    <h3><span class="badge badge-primary">Комплекс с.Глушково:</span></h3>
    <div class="row">
        <div class="col-sm-5">
            <div class="card text-center">
                <div class="card-body">
                    <img src="public/picts/Glushkovo_TO.png" class="d-inline-block align-top" alt="icon" style="height: 90px;">
                    <!--<h5 class="card-title">с.Отрешково.</h5>-->
                    <p class="card-text">Наработка оборудования.</p>
                    <a href="{{ url('/glushkovo_time_reports') }}" class="btn btn-primary">Отчет</a>
                </div>
            </div>
        </div>

        <div class="col-sm-5">
            <div class="card text-center">
                <div class="card-body">
                    <img src="public/picts/Operator.png" class="d-inline-block align-top" alt="icon" style="height: 90px;">
                    <!--<h5 class="card-title">с.Отрешково.</h5>-->
                    <p class="card-text">Аварии и действия оператора.</p>
                    <a href="{{ url('/glushkovo_alarm_reports') }}" class="btn btn-primary">Отчет</a>
                </div>
            </div>
        </div>
    </div>
    <hr style="background-color: #0d0d0d"/>
@endif


@if ($role == 'SERVICE' or $role == 'ADMIN')
    <h3><span class="badge badge-primary">Комплекс с.Обоянь:</span></h3>
    <div class="row">
        <div class="col-sm-5">
            <div class="card text-center">
                <div class="card-body">
                    <img src="public/picts/Oboyan_TO.png" class="d-inline-block align-top" alt="icon" style="height: 90px;">
                    <!--<h5 class="card-title">с.Отрешково.</h5>-->
                    <p class="card-text">Наработка оборудования.</p>
                    <a href="{{ url('/oboyan_time_reports') }}" class="btn btn-primary">Отчет</a>
                </div>
            </div>
        </div>

        <div class="col-sm-5">
            <div class="card text-center">
                <div class="card-body">
                    <img src="public/picts/Operator.png" class="d-inline-block align-top" alt="icon" style="height: 90px;">
                    <!--<h5 class="card-title">с.Отрешково.</h5>-->
                    <p class="card-text">Аварии и действия оператора.</p>
                    <a href="{{ url('/oboyan_alarm_reports') }}" class="btn btn-primary">Отчет</a>
                </div>
            </div>
        </div>
    </div>
    <hr style="background-color: #0d0d0d"/>
@endif
@endsection
