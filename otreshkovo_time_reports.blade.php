@extends('layouts.app')

@section('content')

    <div id="popupWin" class="modalwin">
        <div class="modal_div">
            <?php
            echo '<h5><span id="Name_0" class="badge badge-dark modal_badge" style="display:none"></span></h5>';
            for ($i = 1; $i <=295; $i++){
                $devname = $names[$i] -> device_name;
                echo '<h5><span id="Name_'.$i.'" class="badge badge-dark modal_badge" style="display:none">'.$devname.'</span></h5>';
            }
            ?>
                <p><button class="closebutton" onclick="closeModalWin()"></button></p>
        </div>
        <!--<hr>-->
        <img id="devImg" src="" alt="" style="display:none"/>
        <img id="schemNames" src="" alt="" style="display:none"/>
    </div>

<div class="title_div">
    <h4><span class="badge badge-primary title">Комплекс с.Отрешково.</span></h4>
    <h4><span class="badge badge-info title">Отчет о наработках оборудования.</span></h4>
    <h4><span class="badge badge-dark title">Дата обновления:{{$report -> created_at}}</span></h4>
</div>

<hr class="blackline"/>

<script type="text/javascript">
    function showModalWin(id) {
        if (id >0 & id < 1000)
        {
            var darkLayer = document.createElement('div'); // слой затемнения
            darkLayer.id = 'shadow'; // id чтобы подхватить стиль
            document.body.appendChild(darkLayer); // включаем затемнение
            var modalWin = document.getElementById('popupWin'); // находим наше "окно"
            modalWin.style.display = 'block'; // "включаем" его
            var devName = document.getElementById('Name_'+id); // создаем имя устройства
            devName.style.display = 'block';
            var img = document.getElementById('devImg'); // ищем элемент картинки.
            img.src = '/public/picts/Devices/Otreshkovo/ID_'+id+'.png';
            img.style.height = '90%';
            img.style.margin = '0 auto';
            img.style.display = 'block';
        }
        else{
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
        }

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
        var devName;
        var schemNames = document.getElementById('schemNames');
        for (let i = 0; i <= 295; i++) { // придется перебрать все названия
            devName = document.getElementById('Name_'+i);
            devName.style.display = 'none'; // делаем надпись невидимой
        }
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

<div style="display: flex; justify-content: center">
    <form method="POST" action="{{'/otreshkovo_time_reports_mode'}}">
        @csrf
        <p><input type="hidden" name="mode" value="-1">
            <input type="hidden" name="id" value="{{$report -> id}}">
            <input type="image" src="/public/picts/previous.png"  alt="<<<" style="height: 60px"></p>
    </form>

    <form method="GET" action="{{'/otreshkovo_time_reports'}}">
        @csrf
        <p>
            <input type="image" src="/public/picts/report.png"  alt="Отчет" style="height: 60px"></p>
    </form>

    <form method="POST" action="{{'/otreshkovo_time_reports_mode'}}">
        @csrf
        <p><input type="hidden" name="mode" value="1">
            <input type="hidden" name="id" value="{{$report -> id}}">
            <input type="image" src="/public/picts/next.png"  alt=">>>" style="height: 60px"></p>
    </form>

    <form method="GET" action="{{'/otreshkovo_time_reports_download'}}">
        @csrf
        <p>
            <input type="hidden" name="id" value="{{$report -> id}}">
            <input type="image" src="/public/picts/upload.png"  alt="Выгрузить" style="height: 60px"></p>
    </form>
</div>




<!-- СТРОКА 1---------------------------------------------------------------------------------------------------------->
<div class="row">
    <!--ЯЧЕЙКА 1------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Нории.</span></h4>
                <table class="table table-sm table-striped" style="text-align: center; word-break: break-all;">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>часов</th>
                        <th style="width: 15%">До ТО,<br/>часов</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 1; $i <=19; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------------->




<hr class="blackline"/>
<!-- СТРОКА 2---------------------------------------------------------------------------------------------------------->
<div class="row">
    <!--ЯЧЕЙКА 1------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Конвейеры.</span></h4>
                <table class="table table-sm table-striped" style=" text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>часов</th>
                        <th style="width: 15%">До ТО,<br/>часов</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 20; $i <=60; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--ЯЧЕЙКА 2------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Конвейеры.</span></h4>
                <table class="table table-sm table-striped" style=" text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>часов</th>
                        <th style="width: 15%">До ТО,<br/>часов</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 61; $i <=99; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!--------------------------------------------------------------------------------------------------------------------->




<hr class="blackline"/>
<!-- СТРОКА 3---------------------------------------------------------------------------------------------------------->
<div class="row">
    <!--ЯЧЕЙКА 1------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Разное.</span></h4>
                <table class="table table-sm table-striped" style=" text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>часов</th>
                        <th style="width: 15%">До ТО,<br/>часов</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 100; $i <=109; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--ЯЧЕЙКА 2------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Шлюзовые затворы.</span></h4>
                <table class="table table-sm table-striped" style=" text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>часов</th>
                        <th style="width: 15%">До ТО,<br/>часов</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 110; $i <=119; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!--------------------------------------------------------------------------------------------------------------------->




<hr class="blackline"/>
<!-- СТРОКА 4---------------------------------------------------------------------------------------------------------->
<div class="row">
    <!--ЯЧЕЙКА 1------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Аспирация.</span></h4>
                <table class="table table-sm table-striped" style=" text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>часов</th>
                        <th style="width: 15%">До ТО,<br/>часов</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 120; $i <=129; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--ЯЧЕЙКА 2------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Виброактиваторы.</span></h4>
                <table class="table table-sm table-striped" style=" text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>часов</th>
                        <th style="width: 15%">До ТО,<br/>часов</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 130; $i <=139; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!--------------------------------------------------------------------------------------------------------------------->




<hr class="blackline"/>
<!-- СТРОКА 5---------------------------------------------------------------------------------------------------------->
<div class="row">
    <!--ЯЧЕЙКА 1------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Вентиляторы.</span></h4>
                <table class="table table-sm table-striped" style="text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>часов</th>
                        <th style="width: 15%">До ТО,<br/>часов</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 140; $i <=169; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--ЯЧЕЙКА 2------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Вентиляторы.</span></h4>
                <table class="table table-sm table-striped" style="text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>часов</th>
                        <th style="width: 15%">До ТО,<br/>часов</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 170; $i <=199; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------------->




<hr class="blackline"/>
<!-- СТРОКА 6---------------------------------------------------------------------------------------------------------->
<div class="row">
    <!--ЯЧЕЙКА 1------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Задвижки.</span></h4>
                <table class="table table-sm table-striped" style="text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>переключений</th>
                        <th style="width: 15%">До ТО,<br/>дней</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 200; $i <=229; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--ЯЧЕЙКА 2------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Задвижки.</span></h4>
                <table class="table table-sm table-striped" style="text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>переключений</th>
                        <th style="width: 15%">До ТО,<br/>дней</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 230; $i <=259; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!--------------------------------------------------------------------------------------------------------------------->



<hr class="blackline"/>
<!-- СТРОКА 7---------------------------------------------------------------------------------------------------------->
<div class="row">
    <!--ЯЧЕЙКА 1------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Клапаны.</span></h4>
                <table class="table table-sm table-striped" style="text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>переключений</th>
                        <th style="width: 15%">До ТО,<br/>дней</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 260; $i <=277; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--ЯЧЕЙКА 2------------------------------------------------------------------------------------------------------->
    <div class="col-sm-6" style="margin: 0px auto">
        <div class="card text-center">
            <div class="card-body">
                <h4><span class="badge badge-dark"style="width: 200px">Клапаны.</span></h4>
                <table class="table table-sm table-striped" style="text-align: center">
                    <!--Table head-->
                    <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 48%">Наименование.</th>
                        <th style="width: 23%">Наработка,<br/>переключений</th>
                        <th style="width: 15%">До ТО,<br/>дней</th>
                        <th style="width: 5%"></th>
                    </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                    <?php
                    for ($i = 278; $i <=295; $i++) {
                        $devname = $names[$i] -> device_name;
                        $devtime = 'time_'.$i;
                        $devtimeTO = 'time_do_TO_'.$i;
                        $devworn = 'time_warning_'.$i;
                        if ( $devname != null){
                            if ($report -> $devtimeTO < $report -> $devworn)
                            {
                                echo '<tr style="background-color: #cf556c ">';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            else
                            {
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<th scope="row">'.$devname.'</th>';
                            }
                            echo '<td>'.$report->$devtime.'</td>';
                            echo '<td>'.$report->$devtimeTO.'</td>';
                            echo '<td><button class="findbuttonto" onclick="showModalWin('.$i.')"></button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------------->


<hr class="blackline"/>
<a href="home" class="btn btn-primary">Назад</a>
@endsection
