@extends('layouts.app')

@section('content')
    <div class="header-div">
        <h4><span class="badge badge-primary" style="width: 350px; float: left; margin-right: 10px">Комплекс с.Отрешково.</span></h4>
        <h4><span class="badge badge-info" style="width: 350px; float: left; margin-right: 10px">Аварии по скорости до сегодня.</span></h4>
        <h4><span class="badge badge-dark" style="width: 350px; float: left; margin-right: 10px">Сегодня :{{$today}}</span></h4>
    </div>
    <hr class="blackline"/>
    <!-- otreshkovo   -->

    <!--<div class="header-div">
        <form method="GET" action="{{'/otreshkovo_alarms'}}" style="margin: 0px 15px">
            csrf
            <p><button class="reportbutton"></button></p>
        </form>

        <form method="GET" action="{{'/otreshkovo_alarms'}}" style="margin: 0px 15px">
            csrf
            <p><input type="date" name="date" value="2019-10-10" min="2018-10-10" max="2025-10-10" pattern="[0-9]{4}.[0-9]{2}.[0-9]{2}" style="width: 200px">
                <button class="calendarbutton"></button></p>
        </form>

    </div>-->

    <div class="row" >
        <!--ЯЧЕЙКА 1------------------------------------------------------------------------------------------------------->
        <div class="col-sm-11" style="margin: 1% 5%; min-width: 600px">
            <div class="card text-center">
                <div class="card-body">
                    <?
                        if ($getDate > null){
                            echo '<h4><span class="badge badge-dark"style="width: 300px">События до '.$getDate.'</span></h4>';
                        }
                        else {
                            echo '<h4><span class="badge badge-dark"style="width: 300px">События до '.$today.'</span></h4>';
                        }
                    ?>
                    <table class="table table-sm table-striped" style="text-align: center; word-break: break-all;">
                        <!--Table head-->
                        <thead>
                        <tr>
                            <th style="width: 9%">ID.</th>
                            <th style="width: 32%">Сообщение.</th>
                            <th style="width: 28%">Источник.</th>
                            <th style="width: 17%">Дата.</th>
                            <th style="width: 10%">Статус.</th>
                            <th style="width: 4%"></th>
                        </tr>
                        </thead>
                        <!--Table head-->
                        <!--Table body-->
                        <tbody>

                        @forelse ($reports as $report)
                            @if($report->state == 'Появление')
                                <tr style="background-color: #F0E68C ">
                                    <th scope="row" align="left">{{$report->id}}</th>
                                    <td align="left">{{$report->alarmtext}}</td>
                                    <td align="left">{{$report->alarmclass}}</td>
                                    <td align="left">{{$report->datetime}}</td>
                                    <td align="left">{{$report->state}}</td>
                                    <td align="left"></td>
                                </tr>
                            @else
                                <tr style="color: #C0C0C0 ">
                                    <th scope="row" align="left">{{$report->id}}</th>
                                    <td align="left">{{$report->alarmtext}}</td>
                                    <td align="left">{{$report->alarmclass}}</td>
                                    <td align="left">{{$report->datetime}}</td>
                                    <td align="left">{{$report->state}}</td>
                                    <td align="left"></td>
                                </tr>
                            @endif
                        @empty
                            <p>Нет записей за обозначенную дату.</p>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{$reports->appends(['date' => $tmpdate])->links()}}

<hr class="blackline"/>
<a href="home" class="btn btn-primary">Назад</a>
@endsection
