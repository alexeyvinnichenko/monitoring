@extends('layouts.app')

@section('content')
    <div class="header-div">
        <h4><span class="badge badge-primary" style="width: 350px; float: left; margin-right: 10px">Комплекс с.Отрешково.</span></h4>
        <h4><span class="badge badge-info" style="width: 350px; float: left; margin-right: 10px">Аварии и действия оператора.</span></h4>
        <h4><span class="badge badge-dark" style="width: 350px; float: left; margin-right: 10px">Сегодня :{{$today}}</span></h4>
    </div>
    <hr class="blackline"/>

    <div id="popupWin" class="modalwin">
        <div class="modal_div">
            <h5><span id="Name_0" class="badge badge-dark modal_badge" style="display:none"></span></h5>
            <p><button class="closebutton" onclick="closeModalWin()"></button></p>
        </div>
        <!--<hr>-->
        <img id="devImg" src="" alt="" style="display:none"/>
        <img id="schemNames" src="" alt="" style="display:none"/>
    </div>

    <script type="text/javascript">
        function showModalWin(id) {
            var darkLayer = document.createElement('div'); // слой затемнения
            darkLayer.id = 'shadow'; // id чтобы подхватить стиль
            document.body.appendChild(darkLayer); // включаем затемнение
            var modalWin = document.getElementById('popupWin'); // находим наше "окно"
            modalWin.style.display = 'block'; // "включаем" его
            var devName = document.getElementById('Name_0'); // находим имя устройства
            var schemNames = document.getElementById('schemNames');// список устройств
            var img = document.getElementById('devImg'); // ищем элемент картинки.
            if (id == 1001){
                schemNames.src = '/public/picts/Schemes/Otreshkovo/Otreshkovo_1_names.png';

                devName.innerText = 'Технологическая схема. Экран 1.';
                devName.style.display = 'block';

                img.src = '/public/picts/Schemes/Otreshkovo/Otreshkovo_1.png';
            }
            if (id ==1002){
                schemNames.src = '/public/picts/Schemes/Otreshkovo/Otreshkovo_2_names.png';

                devName.innerText = 'Технологическая схема. Экран 2.';
                devName.style.display = 'block';

                img.src = '/public/picts/Schemes/Otreshkovo/Otreshkovo_2.png';
            }
            img.style.width = '95%';
            img.style.minWidth = '900px';
            img.style.margin = '0 auto';
            img.style.display = 'block';

            schemNames.style.width = '95%';
            schemNames.style.minWidth = '900px';
            schemNames.style.margin = '0 auto';
            schemNames.style.display = 'block';

            darkLayer.onclick = function () {  // при клике на слой затемнения все исчезнет
                darkLayer.parentNode.removeChild(darkLayer); // удаляем затемнение
                modalWin.style.display = 'none'; // делаем окно невидимым
                devName.style.display = 'none'; // делаем подпись невидимой
                img.style.display = 'none';
                schemNames.style.display = 'none';
                return false;
            };
        }
        function closeModalWin() {
            var darkLayer = document.getElementById('shadow'); //находим затемнение
            var modalWin = document.getElementById('popupWin'); // находим наше "окно"
            var devName = document.getElementById('Name_0');
            var schemNames = document.getElementById('schemNames');

            devName.style.display = 'none'; // делаем надпись невидимой
            schemNames.style.display = 'none';
            darkLayer.parentNode.removeChild(darkLayer);// удаляем затемнение
            modalWin.style.display = 'none'; // "выключаем" окно
            return false;
        }
    </script>

    <div class="title_div">
        <img class="shem_little" src="/public/picts/Schemes/Otreshkovo/Otreshkovo_1_icon.png" alt="Схема 1" onclick="showModalWin(1001)">
        <img class="shem_little" src="/public/picts/Schemes/Otreshkovo/Otreshkovo_2_icon.png" alt="Схема 2" onclick="showModalWin(1002)">
    </div>
    <hr class="blackline"/>
    <!-- otreshkovo   -->

    <div class="header-div">
        <form method="GET" action="{{'/otreshkovo_alarm_reports'}}" style="margin: 0px 15px">
            @csrf
            <p><button class="reportbutton"></button></p>
        </form>

        <form method="GET" action="{{'/otreshkovo_alarm_reports'}}" style="margin: 0px 15px">
            @csrf
            <p><input type="date" name="date" value="<?php echo date("Y-m-d");?>" min="2018-10-10" max="2025-10-10" pattern="[0-9]{4}.[0-9]{2}.[0-9]{2}" style="width: 200px">
                <button class="calendarbutton"></button></p>
        </form>

        <form method="GET" action="{{'/otreshkovo_alarm_reports'}}" style="margin: 0px 15px">
            @csrf
            <p><input type="text" name="like" value="Конвейер" style="width: 200px">
                <input type="hidden" name="date" value={{$tmpdate}}>
                <button class="findbutton"></button></p>
        </form>
    </div>

    <div class="row" >
        <!--ЯЧЕЙКА 1------------------------------------------------------------------------------------------------------->
        <div class="col-sm-11" style="margin: 1% 5%">
            <div class="card text-center">
                <div class="card-body">
                    <?
                        if ($getDate > null){
                            echo '<h4><span class="badge badge-dark"style="width: 300px">События за '.$getDate.'</span></h4>';
                        }
                        else {
                            echo '<h4><span class="badge badge-dark"style="width: 300px">События за '.$today.'</span></h4>';
                        }
                    ?>
                    <table class="table table-sm table-striped" style="text-align: center; word-break: break-all;">
                        <!--Table head-->
                        <thead>
                        <tr>
                            <th style="width: 9%">ID.</th>
                            <th style="width: 40%">Сообщение.</th>
                            <th style="width: 24%">Источник.</th>
                            <th style="width: 17%">Дата.</th>
                            <th style="width: 10%">Статус.</th>
                        </tr>
                        </thead>
                        <!--Table head-->
                        <!--Table body-->
                        <tbody>

                        @forelse ($reports as $report)
                            @if($report->state == 'Появление' and $report->alarmclass != 'Действия оператора' and $report->msgtype != 'route' and $report->alarmclass != 'System')
                                <tr style="background-color: #F0E68C ">
                                    <th scope="row" align="left">{{$report->id}}</th>
                                    <td align="left">{{$report->alarmtext}}</td>
                                    <td align="middle">{{$report->alarmclass}}</td>
                                    <td align="middle">{{$report->datetime}}</td>
                                    <td align="middle">{{$report->state}}</td>
                                </tr>
                            @else
                                @if($report->alarmclass == 'Действия оператора')
                                    <tr style="background-color: #AFEEEE ">
                                        <th scope="row" align="left">{{$report->id}}</th>
                                        <td align="left">{{$report->alarmtext}}</td>
                                        <td align="middle">{{$report->alarmclass}}</td>
                                        <td align="middle">{{$report->datetime}}</td>
                                        <td align="middle">-</td>
                                    </tr>
                                @else
                                    @if($report->msgtype == 'route')
                                        <tr style="color: #A9A9A9; background-color: #FDF5E6 ">
                                            <th scope="row" align="left">{{$report->id}}</th>
                                            <td align="left">{{$report->alarmtext}}</td>
                                            <td align="middle">{{$report->alarmclass}}</td>
                                            <td align="middle">{{$report->datetime}}</td>
                                            <td align="middle">-</td>
                                        </tr>
                                    @else
                                        @if($report->alarmclass == 'System')
                                            <tr style="background-color: #E0FFFF; color: #A9A9A9">
                                                <th scope="row" align="left">{{$report->id}}</th>
                                                <td align="left">{{$report->alarmtext}}</td>
                                                <td align="middle">{{$report->alarmclass}}</td>
                                                <td align="middle">{{$report->datetime}}</td>
                                                <td align="middle">-</td>
                                            </tr>
                                        @else
                                            <tr >
                                                <th scope="row" align="left">{{$report->id}}</th>
                                                <td align="left">{{$report->alarmtext}}</td>
                                                <td align="middle">{{$report->alarmclass}}</td>
                                                <td align="middle">{{$report->datetime}}</td>
                                                <td align="middle">{{$report->state}}</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endif
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

    {{$reports->appends(['date' => $tmpdate, 'like' => $like])->links()}}

<hr class="blackline"/>
<a href="home" class="btn btn-primary">Назад</a>
@endsection
