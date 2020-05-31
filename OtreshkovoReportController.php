<?php

namespace App\Http\Controllers\OtreshkovoReport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OtreshkovoReportController extends Controller
{
    public const OBJ = 'otreshkovo';
    public $_monthsList = array(
        ".01." => "января",
        ".02." => "февраля",
        ".03." => "марта",
        ".04." => "апреля",
        ".05." => "мая",
        ".06." => "июня",
        ".07." => "июля",
        ".08." => "августа",
        ".09." => "сентября",
        ".10." => "октября",
        ".11." => "ноября",
        ".12." => "декабря");


//----------------------------------------------------------------------------------------------------------------------
    public function index()//вывод отчета по ТО
    {
        if (Auth::user()) {
            $lastrecord = 0;
            if (isset ($_POST['mode'])) {
                $lastrecord = DB::table(self::OBJ . '_time_db')->OrderBy('id', 'desc')->first()->id;
                $currentrecord = $_POST['id'] + $_POST['mode'];
                if ($currentrecord < 1) {
                    $currentrecord = 1;
                }
                if ($currentrecord > $lastrecord) {
                    $currentrecord = $lastrecord;
                }
                $report = DB::table(self::OBJ . '_time_db')->where('id', '=', $currentrecord)->first();
                $id = $report->id;
                $names = DB::table(self::OBJ . '_dev_names_db')->get();
                $namearr[] = null;
                $i = 0;

                foreach ($names as $name) {
                    $namearr[$i] = $name->device_name;
                    $i++;
                }
                return view('reports.' . self::OBJ . '.' . self::OBJ . '_time_reports', compact('report', 'namearr', 'names', 'lastrecord', 'id'));

            } else {
                $report = DB::table(self::OBJ . '_time_db')->OrderBy('id', 'desc')->first();
                $id = 0;
                if (isset ($report->id)) {
                    $id = $report->id;
                }
                $names = DB::table(self::OBJ . '_dev_names_db')->get();
                $namearr[] = null;
                $i = 0;

                foreach ($names as $name) {
                    $namearr[$i] = $name->device_name;
                    $i++;
                }
                return view('reports.' . self::OBJ . '.' . self::OBJ . '_time_reports', compact('report', 'namearr', 'names', 'lastrecord', 'id'));
            }
        } else {
            return redirect('/home');
        }
    }
//----------------------------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------------------------
    public function index_alarm()//вывод отчета по авариям и действиям оператора
    {
        if (Auth::user()) {
            date_default_timezone_set('Europe/Moscow');
            $getDate = null;
            $today = date("Y-m-d");
            $like = null;

            if (isset($_GET['date'])) {
                if ($_GET['date'] != "") {
                    $getDate = $_GET['date'];
                } else {
                    $getDate = $today;
                }
            } else {
                $getDate = $today;
            }


            if (isset($_GET['like'])) {
                $getDate = $_GET['date'];
                $reports = DB::table(self::OBJ . '_alarms_db')
                    ->where('datetime', '>', $getDate . ' 00:00:00')
                    ->where('datetime', '<', $getDate . ' 23:59:59')
                    ->where('alarmclass', 'like', '%' . $_GET['like'] . '%')
                    ->orwhere(function ($query) {
                        $getDate = $_GET['date'];
                        $query->where('datetime', '>', $getDate . ' 00:00:00')
                            ->where('datetime', '<', $getDate . ' 23:59:59')
                            ->where('alarmtext', 'like', '%' . $_GET['like'] . '%');
                    })
                    ->OrderBy('num', 'desc')->paginate(30);
                $like = $_GET['like'];
            } else {
                $reports = DB::table(self::OBJ . '_alarms_db')
                    ->where('datetime', '>', $getDate . ' 00:00:00')
                    ->where('datetime', '<', $getDate . ' 23:59:59')
                    ->OrderBy('num', 'desc')->paginate(30);
            }

            $tmpdate = $getDate;
            $today = date("d.m.Y");
            $today = str_replace(substr($today, 2, 4), " " . $this->_monthsList[substr($today, 2, 4)] . " ", $today);
            $getDate = substr($getDate, -2, 2) . '.' . substr($getDate, -5, 2) . '.' . substr($getDate, 0, 4);
            $getDate = str_replace(substr($getDate, 2, 4), " " . $this->_monthsList[substr($getDate, 2, 4)] . " ", $getDate);

            return view('reports.' . self::OBJ . '.' . self::OBJ . '_alarm_reports', compact('today', 'reports', 'getDate', 'tmpdate', 'like'));
        } else {
            return redirect('/home');
        }
    }
//----------------------------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------------------------
    public function index_alarms_new()//вывод списка аварий по новой схеме
    {
        if (Auth::user()) {
            date_default_timezone_set('Europe/Moscow');
            $getDate = null;
            $today = date("Y-m-d");

            if (isset($_GET['date'])) {
                if ($_GET['date'] != "") {
                    $getDate = $_GET['date'];
                } else {
                    $getDate = $today;
                }
            } else {
                $getDate = $today;
            }


            $reports = DB::table(self::OBJ . '_alarms_db')
                ->where('datetime', '>', $getDate . ' 00:00:00')
                ->where('datetime', '<', $getDate . ' 23:59:59')
                ->where('msgtype', '=', 'alarm')
                ->where('alarmtext', '!=', 'Требуется ТО оборудования')
                ->where('alarmtext', '!=', 'Скоро ТО оборудования')
                ->OrderBy('num', 'desc')->paginate(30);


            $tmpdate = $getDate;
            $today = date("d.m.Y");
            $today = str_replace(substr($today, 2, 4), " " . $this->_monthsList[substr($today, 2, 4)] . " ", $today);
            $getDate = substr($getDate, -2, 2) . '.' . substr($getDate, -5, 2) . '.' . substr($getDate, 0, 4);
            $getDate = str_replace(substr($getDate, 2, 4), " " . $this->_monthsList[substr($getDate, 2, 4)] . " ", $getDate);

            return view('reports.' . self::OBJ . '.' . self::OBJ . '_alarms', compact('today', 'reports', 'getDate', 'tmpdate'));
        } else {
            return redirect('/home');
        }
    }
//----------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------
    public function index_alarm_new()//вывод списка аварий по новой схеме
    {
        if (Auth::user()) {
            date_default_timezone_set('Europe/Moscow');
            $getDate = null;
            $today = date("Y-m-d");

            if (isset($_GET['date'])) {
                if ($_GET['date'] != "") {
                    $getDate = $_GET['date'];
                } else {
                    $getDate = $today;
                }
            } else {
                $getDate = $today;
            }

            $getStartDateTime = null;
            $getLastDateTime = null;
            $title = null;

            $title = DB::table(self::OBJ . '_alarms_db')
                ->where('num', '=', $_GET['num'])->first();
            $getStartDateTime = $title->datetime;
            $getLastDateTime = date('Y-m-d H:i:s', strtotime(' -30 minutes', strtotime($getStartDateTime)));


            $reports = DB::table(self::OBJ . '_alarms_db')
                ->where('datetime', '>', $getLastDateTime)
                ->where('datetime', '<', $getStartDateTime)
                ->where('msgtype', '!=', 'alarm')
                ->OrderBy('num', 'desc')->paginate(30);

            $tmpdate = $getDate;

            //перевод даты в формат dd - название месяца - yyyy
            $today = date("d.m.Y");
            $today = str_replace(substr($today, 2, 4), " " . $this->_monthsList[substr($today, 2, 4)] . " ", $today);
            $getDate = substr($getDate, -2, 2) . '.' . substr($getDate, -5, 2) . '.' . substr($getDate, 0, 4);
            $getDate = str_replace(substr($getDate, 2, 4), " " . $this->_monthsList[substr($getDate, 2, 4)] . " ", $getDate);
            //конец перевода

            return view('reports.' . self::OBJ . '.' . self::OBJ . '_alarm', compact('today', 'reports', 'getDate', 'tmpdate', 'title', 'getStartDateTime', 'getLastDateTime'));
        } else {
            return redirect('/home');
        }
    }
//----------------------------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------------------------
    public function post()
    {
        if (isset($_POST)) {
            if ($_POST['comp_name'] == DB::table(self::OBJ . '_comp_name_db')->OrderBy('id', 'desc')->first()->comp_name) {
                DB::table(self::OBJ . '_time_db')->insert([
                    'created_at' => $_POST['created_at'],
                    'time_1' => $_POST['time_1'],
                    'time_2' => $_POST['time_2'],
                    'time_3' => $_POST['time_3'],
                    'time_4' => $_POST['time_4'],
                    'time_5' => $_POST['time_5'],
                    'time_6' => $_POST['time_6'],
                    'time_7' => $_POST['time_7'],
                    'time_8' => $_POST['time_8'],
                    'time_9' => $_POST['time_9'],
                    'time_10' => $_POST['time_10'],
                    'time_11' => $_POST['time_11'],
                    'time_12' => $_POST['time_12'],
                    'time_13' => $_POST['time_13'],
                    'time_14' => $_POST['time_14'],
                    'time_15' => $_POST['time_15'],
                    'time_16' => $_POST['time_16'],
                    'time_17' => $_POST['time_17'],
                    'time_18' => $_POST['time_18'],
                    'time_19' => $_POST['time_19'],
                    'time_20' => $_POST['time_20'],
                    'time_21' => $_POST['time_21'],
                    'time_22' => $_POST['time_22'],
                    'time_23' => $_POST['time_23'],
                    'time_24' => $_POST['time_24'],
                    'time_25' => $_POST['time_25'],
                    'time_26' => $_POST['time_26'],
                    'time_27' => $_POST['time_27'],
                    'time_28' => $_POST['time_28'],
                    'time_29' => $_POST['time_29'],
                    'time_30' => $_POST['time_30'],
                    'time_31' => $_POST['time_31'],
                    'time_32' => $_POST['time_32'],
                    'time_33' => $_POST['time_33'],
                    'time_34' => $_POST['time_34'],
                    'time_35' => $_POST['time_35'],
                    'time_36' => $_POST['time_36'],
                    'time_37' => $_POST['time_37'],
                    'time_38' => $_POST['time_38'],
                    'time_39' => $_POST['time_39'],
                    'time_40' => $_POST['time_40'],
                    'time_41' => $_POST['time_41'],
                    'time_42' => $_POST['time_42'],
                    'time_43' => $_POST['time_43'],
                    'time_44' => $_POST['time_44'],
                    'time_45' => $_POST['time_45'],
                    'time_46' => $_POST['time_46'],
                    'time_47' => $_POST['time_47'],
                    'time_48' => $_POST['time_48'],
                    'time_49' => $_POST['time_49'],
                    'time_50' => $_POST['time_50'],
                    'time_51' => $_POST['time_51'],
                    'time_52' => $_POST['time_52'],
                    'time_53' => $_POST['time_53'],
                    'time_54' => $_POST['time_54'],
                    'time_55' => $_POST['time_55'],
                    'time_56' => $_POST['time_56'],
                    'time_57' => $_POST['time_57'],
                    'time_58' => $_POST['time_58'],
                    'time_59' => $_POST['time_59'],
                    'time_60' => $_POST['time_60'],
                    'time_61' => $_POST['time_61'],
                    'time_62' => $_POST['time_62'],
                    'time_63' => $_POST['time_63'],
                    'time_64' => $_POST['time_64'],
                    'time_65' => $_POST['time_65'],
                    'time_66' => $_POST['time_66'],
                    'time_67' => $_POST['time_67'],
                    'time_68' => $_POST['time_68'],
                    'time_69' => $_POST['time_69'],
                    'time_70' => $_POST['time_70'],
                    'time_71' => $_POST['time_71'],
                    'time_72' => $_POST['time_72'],
                    'time_73' => $_POST['time_73'],
                    'time_74' => $_POST['time_74'],
                    'time_75' => $_POST['time_75'],
                    'time_76' => $_POST['time_76'],
                    'time_77' => $_POST['time_77'],
                    'time_78' => $_POST['time_78'],
                    'time_79' => $_POST['time_79'],
                    'time_80' => $_POST['time_80'],
                    'time_81' => $_POST['time_81'],
                    'time_82' => $_POST['time_82'],
                    'time_83' => $_POST['time_83'],
                    'time_84' => $_POST['time_84'],
                    'time_85' => $_POST['time_85'],
                    'time_86' => $_POST['time_86'],
                    'time_87' => $_POST['time_87'],
                    'time_88' => $_POST['time_88'],
                    'time_89' => $_POST['time_89'],
                    'time_90' => $_POST['time_90'],
                    'time_91' => $_POST['time_91'],
                    'time_92' => $_POST['time_92'],
                    'time_93' => $_POST['time_93'],
                    'time_94' => $_POST['time_94'],
                    'time_95' => $_POST['time_95'],
                    'time_96' => $_POST['time_96'],
                    'time_97' => $_POST['time_97'],
                    'time_98' => $_POST['time_98'],
                    'time_99' => $_POST['time_99'],
                    'time_100' => $_POST['time_100'],
                    'time_101' => $_POST['time_101'],
                    'time_102' => $_POST['time_102'],
                    'time_103' => $_POST['time_103'],
                    'time_104' => $_POST['time_104'],
                    'time_105' => $_POST['time_105'],
                    'time_106' => $_POST['time_106'],
                    'time_107' => $_POST['time_107'],
                    'time_108' => $_POST['time_108'],
                    'time_109' => $_POST['time_109'],
                    'time_110' => $_POST['time_110'],
                    'time_111' => $_POST['time_111'],
                    'time_112' => $_POST['time_112'],
                    'time_113' => $_POST['time_113'],
                    'time_114' => $_POST['time_114'],
                    'time_115' => $_POST['time_115'],
                    'time_116' => $_POST['time_116'],
                    'time_117' => $_POST['time_117'],
                    'time_118' => $_POST['time_118'],
                    'time_119' => $_POST['time_119'],
                    'time_120' => $_POST['time_120'],
                    'time_121' => $_POST['time_121'],
                    'time_122' => $_POST['time_122'],
                    'time_123' => $_POST['time_123'],
                    'time_124' => $_POST['time_124'],
                    'time_125' => $_POST['time_125'],
                    'time_126' => $_POST['time_126'],
                    'time_127' => $_POST['time_127'],
                    'time_128' => $_POST['time_128'],
                    'time_129' => $_POST['time_129'],
                    'time_130' => $_POST['time_130'],
                    'time_131' => $_POST['time_131'],
                    'time_132' => $_POST['time_132'],
                    'time_133' => $_POST['time_133'],
                    'time_134' => $_POST['time_134'],
                    'time_135' => $_POST['time_135'],
                    'time_136' => $_POST['time_136'],
                    'time_137' => $_POST['time_137'],
                    'time_138' => $_POST['time_138'],
                    'time_139' => $_POST['time_139'],
                    'time_140' => $_POST['time_140'],
                    'time_141' => $_POST['time_141'],
                    'time_142' => $_POST['time_142'],
                    'time_143' => $_POST['time_143'],
                    'time_144' => $_POST['time_144'],
                    'time_145' => $_POST['time_145'],
                    'time_146' => $_POST['time_146'],
                    'time_147' => $_POST['time_147'],
                    'time_148' => $_POST['time_148'],
                    'time_149' => $_POST['time_149'],
                    'time_150' => $_POST['time_150'],
                    'time_151' => $_POST['time_151'],
                    'time_152' => $_POST['time_152'],
                    'time_153' => $_POST['time_153'],
                    'time_154' => $_POST['time_154'],
                    'time_155' => $_POST['time_155'],
                    'time_156' => $_POST['time_156'],
                    'time_157' => $_POST['time_157'],
                    'time_158' => $_POST['time_158'],
                    'time_159' => $_POST['time_159'],
                    'time_160' => $_POST['time_160'],
                    'time_161' => $_POST['time_161'],
                    'time_162' => $_POST['time_162'],
                    'time_163' => $_POST['time_163'],
                    'time_164' => $_POST['time_164'],
                    'time_165' => $_POST['time_165'],
                    'time_166' => $_POST['time_166'],
                    'time_167' => $_POST['time_167'],
                    'time_168' => $_POST['time_168'],
                    'time_169' => $_POST['time_169'],
                    'time_170' => $_POST['time_170'],
                    'time_171' => $_POST['time_171'],
                    'time_172' => $_POST['time_172'],
                    'time_173' => $_POST['time_173'],
                    'time_174' => $_POST['time_174'],
                    'time_175' => $_POST['time_175'],
                    'time_176' => $_POST['time_176'],
                    'time_177' => $_POST['time_177'],
                    'time_178' => $_POST['time_178'],
                    'time_179' => $_POST['time_179'],
                    'time_180' => $_POST['time_180'],
                    'time_181' => $_POST['time_181'],
                    'time_182' => $_POST['time_182'],
                    'time_183' => $_POST['time_183'],
                    'time_184' => $_POST['time_184'],
                    'time_185' => $_POST['time_185'],
                    'time_186' => $_POST['time_186'],
                    'time_187' => $_POST['time_187'],
                    'time_188' => $_POST['time_188'],
                    'time_189' => $_POST['time_189'],
                    'time_190' => $_POST['time_190'],
                    'time_191' => $_POST['time_191'],
                    'time_192' => $_POST['time_192'],
                    'time_193' => $_POST['time_193'],
                    'time_194' => $_POST['time_194'],
                    'time_195' => $_POST['time_195'],
                    'time_196' => $_POST['time_196'],
                    'time_197' => $_POST['time_197'],
                    'time_198' => $_POST['time_198'],
                    'time_199' => $_POST['time_199'],
                    'time_200' => $_POST['time_200'],
                    'time_201' => $_POST['time_201'],
                    'time_202' => $_POST['time_202'],
                    'time_203' => $_POST['time_203'],
                    'time_204' => $_POST['time_204'],
                    'time_205' => $_POST['time_205'],
                    'time_206' => $_POST['time_206'],
                    'time_207' => $_POST['time_207'],
                    'time_208' => $_POST['time_208'],
                    'time_209' => $_POST['time_209'],
                    'time_210' => $_POST['time_210'],
                    'time_211' => $_POST['time_211'],
                    'time_212' => $_POST['time_212'],
                    'time_213' => $_POST['time_213'],
                    'time_214' => $_POST['time_214'],
                    'time_215' => $_POST['time_215'],
                    'time_216' => $_POST['time_216'],
                    'time_217' => $_POST['time_217'],
                    'time_218' => $_POST['time_218'],
                    'time_219' => $_POST['time_219'],
                    'time_220' => $_POST['time_220'],
                    'time_221' => $_POST['time_221'],
                    'time_222' => $_POST['time_222'],
                    'time_223' => $_POST['time_223'],
                    'time_224' => $_POST['time_224'],
                    'time_225' => $_POST['time_225'],
                    'time_226' => $_POST['time_226'],
                    'time_227' => $_POST['time_227'],
                    'time_228' => $_POST['time_228'],
                    'time_229' => $_POST['time_229'],
                    'time_230' => $_POST['time_230'],
                    'time_231' => $_POST['time_231'],
                    'time_232' => $_POST['time_232'],
                    'time_233' => $_POST['time_233'],
                    'time_234' => $_POST['time_234'],
                    'time_235' => $_POST['time_235'],
                    'time_236' => $_POST['time_236'],
                    'time_237' => $_POST['time_237'],
                    'time_238' => $_POST['time_238'],
                    'time_239' => $_POST['time_239'],
                    'time_240' => $_POST['time_240'],
                    'time_241' => $_POST['time_241'],
                    'time_242' => $_POST['time_242'],
                    'time_243' => $_POST['time_243'],
                    'time_244' => $_POST['time_244'],
                    'time_245' => $_POST['time_245'],
                    'time_246' => $_POST['time_246'],
                    'time_247' => $_POST['time_247'],
                    'time_248' => $_POST['time_248'],
                    'time_249' => $_POST['time_249'],
                    'time_250' => $_POST['time_250'],
                    'time_251' => $_POST['time_251'],
                    'time_252' => $_POST['time_252'],
                    'time_253' => $_POST['time_253'],
                    'time_254' => $_POST['time_254'],
                    'time_255' => $_POST['time_255'],
                    'time_256' => $_POST['time_256'],
                    'time_257' => $_POST['time_257'],
                    'time_258' => $_POST['time_258'],
                    'time_259' => $_POST['time_259'],
                    'time_260' => $_POST['time_260'],
                    'time_261' => $_POST['time_261'],
                    'time_262' => $_POST['time_262'],
                    'time_263' => $_POST['time_263'],
                    'time_264' => $_POST['time_264'],
                    'time_265' => $_POST['time_265'],
                    'time_266' => $_POST['time_266'],
                    'time_267' => $_POST['time_267'],
                    'time_268' => $_POST['time_268'],
                    'time_269' => $_POST['time_269'],
                    'time_270' => $_POST['time_270'],
                    'time_271' => $_POST['time_271'],
                    'time_272' => $_POST['time_272'],
                    'time_273' => $_POST['time_273'],
                    'time_274' => $_POST['time_274'],
                    'time_275' => $_POST['time_275'],
                    'time_276' => $_POST['time_276'],
                    'time_277' => $_POST['time_277'],
                    'time_278' => $_POST['time_278'],
                    'time_279' => $_POST['time_279'],
                    'time_280' => $_POST['time_280'],
                    'time_281' => $_POST['time_281'],
                    'time_282' => $_POST['time_282'],
                    'time_283' => $_POST['time_283'],
                    'time_284' => $_POST['time_284'],
                    'time_285' => $_POST['time_285'],
                    'time_286' => $_POST['time_286'],
                    'time_287' => $_POST['time_287'],
                    'time_288' => $_POST['time_288'],
                    'time_289' => $_POST['time_289'],
                    'time_290' => $_POST['time_290'],
                    'time_291' => $_POST['time_291'],
                    'time_292' => $_POST['time_292'],
                    'time_293' => $_POST['time_293'],
                    'time_294' => $_POST['time_294'],
                    'time_295' => $_POST['time_295'],

                    'time_do_TO_1' => $_POST['time_do_TO_1'],
                    'time_do_TO_2' => $_POST['time_do_TO_2'],
                    'time_do_TO_3' => $_POST['time_do_TO_3'],
                    'time_do_TO_4' => $_POST['time_do_TO_4'],
                    'time_do_TO_5' => $_POST['time_do_TO_5'],
                    'time_do_TO_6' => $_POST['time_do_TO_6'],
                    'time_do_TO_7' => $_POST['time_do_TO_7'],
                    'time_do_TO_8' => $_POST['time_do_TO_8'],
                    'time_do_TO_9' => $_POST['time_do_TO_9'],
                    'time_do_TO_10' => $_POST['time_do_TO_10'],
                    'time_do_TO_11' => $_POST['time_do_TO_11'],
                    'time_do_TO_12' => $_POST['time_do_TO_12'],
                    'time_do_TO_13' => $_POST['time_do_TO_13'],
                    'time_do_TO_14' => $_POST['time_do_TO_14'],
                    'time_do_TO_15' => $_POST['time_do_TO_15'],
                    'time_do_TO_16' => $_POST['time_do_TO_16'],
                    'time_do_TO_17' => $_POST['time_do_TO_17'],
                    'time_do_TO_18' => $_POST['time_do_TO_18'],
                    'time_do_TO_19' => $_POST['time_do_TO_19'],
                    'time_do_TO_20' => $_POST['time_do_TO_20'],
                    'time_do_TO_21' => $_POST['time_do_TO_21'],
                    'time_do_TO_22' => $_POST['time_do_TO_22'],
                    'time_do_TO_23' => $_POST['time_do_TO_23'],
                    'time_do_TO_24' => $_POST['time_do_TO_24'],
                    'time_do_TO_25' => $_POST['time_do_TO_25'],
                    'time_do_TO_26' => $_POST['time_do_TO_26'],
                    'time_do_TO_27' => $_POST['time_do_TO_27'],
                    'time_do_TO_28' => $_POST['time_do_TO_28'],
                    'time_do_TO_29' => $_POST['time_do_TO_29'],
                    'time_do_TO_30' => $_POST['time_do_TO_30'],
                    'time_do_TO_31' => $_POST['time_do_TO_31'],
                    'time_do_TO_32' => $_POST['time_do_TO_32'],
                    'time_do_TO_33' => $_POST['time_do_TO_33'],
                    'time_do_TO_34' => $_POST['time_do_TO_34'],
                    'time_do_TO_35' => $_POST['time_do_TO_35'],
                    'time_do_TO_36' => $_POST['time_do_TO_36'],
                    'time_do_TO_37' => $_POST['time_do_TO_37'],
                    'time_do_TO_38' => $_POST['time_do_TO_38'],
                    'time_do_TO_39' => $_POST['time_do_TO_39'],
                    'time_do_TO_40' => $_POST['time_do_TO_40'],
                    'time_do_TO_41' => $_POST['time_do_TO_41'],
                    'time_do_TO_42' => $_POST['time_do_TO_42'],
                    'time_do_TO_43' => $_POST['time_do_TO_43'],
                    'time_do_TO_44' => $_POST['time_do_TO_44'],
                    'time_do_TO_45' => $_POST['time_do_TO_45'],
                    'time_do_TO_46' => $_POST['time_do_TO_46'],
                    'time_do_TO_47' => $_POST['time_do_TO_47'],
                    'time_do_TO_48' => $_POST['time_do_TO_48'],
                    'time_do_TO_49' => $_POST['time_do_TO_49'],
                    'time_do_TO_50' => $_POST['time_do_TO_50'],
                    'time_do_TO_51' => $_POST['time_do_TO_51'],
                    'time_do_TO_52' => $_POST['time_do_TO_52'],
                    'time_do_TO_53' => $_POST['time_do_TO_53'],
                    'time_do_TO_54' => $_POST['time_do_TO_54'],
                    'time_do_TO_55' => $_POST['time_do_TO_55'],
                    'time_do_TO_56' => $_POST['time_do_TO_56'],
                    'time_do_TO_57' => $_POST['time_do_TO_57'],
                    'time_do_TO_58' => $_POST['time_do_TO_58'],
                    'time_do_TO_59' => $_POST['time_do_TO_59'],
                    'time_do_TO_60' => $_POST['time_do_TO_60'],
                    'time_do_TO_61' => $_POST['time_do_TO_61'],
                    'time_do_TO_62' => $_POST['time_do_TO_62'],
                    'time_do_TO_63' => $_POST['time_do_TO_63'],
                    'time_do_TO_64' => $_POST['time_do_TO_64'],
                    'time_do_TO_65' => $_POST['time_do_TO_65'],
                    'time_do_TO_66' => $_POST['time_do_TO_66'],
                    'time_do_TO_67' => $_POST['time_do_TO_67'],
                    'time_do_TO_68' => $_POST['time_do_TO_68'],
                    'time_do_TO_69' => $_POST['time_do_TO_69'],
                    'time_do_TO_70' => $_POST['time_do_TO_70'],
                    'time_do_TO_71' => $_POST['time_do_TO_71'],
                    'time_do_TO_72' => $_POST['time_do_TO_72'],
                    'time_do_TO_73' => $_POST['time_do_TO_73'],
                    'time_do_TO_74' => $_POST['time_do_TO_74'],
                    'time_do_TO_75' => $_POST['time_do_TO_75'],
                    'time_do_TO_76' => $_POST['time_do_TO_76'],
                    'time_do_TO_77' => $_POST['time_do_TO_77'],
                    'time_do_TO_78' => $_POST['time_do_TO_78'],
                    'time_do_TO_79' => $_POST['time_do_TO_79'],
                    'time_do_TO_80' => $_POST['time_do_TO_80'],
                    'time_do_TO_81' => $_POST['time_do_TO_81'],
                    'time_do_TO_82' => $_POST['time_do_TO_82'],
                    'time_do_TO_83' => $_POST['time_do_TO_83'],
                    'time_do_TO_84' => $_POST['time_do_TO_84'],
                    'time_do_TO_85' => $_POST['time_do_TO_85'],
                    'time_do_TO_86' => $_POST['time_do_TO_86'],
                    'time_do_TO_87' => $_POST['time_do_TO_87'],
                    'time_do_TO_88' => $_POST['time_do_TO_88'],
                    'time_do_TO_89' => $_POST['time_do_TO_89'],
                    'time_do_TO_90' => $_POST['time_do_TO_90'],
                    'time_do_TO_91' => $_POST['time_do_TO_91'],
                    'time_do_TO_92' => $_POST['time_do_TO_92'],
                    'time_do_TO_93' => $_POST['time_do_TO_93'],
                    'time_do_TO_94' => $_POST['time_do_TO_94'],
                    'time_do_TO_95' => $_POST['time_do_TO_95'],
                    'time_do_TO_96' => $_POST['time_do_TO_96'],
                    'time_do_TO_97' => $_POST['time_do_TO_97'],
                    'time_do_TO_98' => $_POST['time_do_TO_98'],
                    'time_do_TO_99' => $_POST['time_do_TO_99'],
                    'time_do_TO_100' => $_POST['time_do_TO_100'],
                    'time_do_TO_101' => $_POST['time_do_TO_101'],
                    'time_do_TO_102' => $_POST['time_do_TO_102'],
                    'time_do_TO_103' => $_POST['time_do_TO_103'],
                    'time_do_TO_104' => $_POST['time_do_TO_104'],
                    'time_do_TO_105' => $_POST['time_do_TO_105'],
                    'time_do_TO_106' => $_POST['time_do_TO_106'],
                    'time_do_TO_107' => $_POST['time_do_TO_107'],
                    'time_do_TO_108' => $_POST['time_do_TO_108'],
                    'time_do_TO_109' => $_POST['time_do_TO_109'],
                    'time_do_TO_110' => $_POST['time_do_TO_110'],
                    'time_do_TO_111' => $_POST['time_do_TO_111'],
                    'time_do_TO_112' => $_POST['time_do_TO_112'],
                    'time_do_TO_113' => $_POST['time_do_TO_113'],
                    'time_do_TO_114' => $_POST['time_do_TO_114'],
                    'time_do_TO_115' => $_POST['time_do_TO_115'],
                    'time_do_TO_116' => $_POST['time_do_TO_116'],
                    'time_do_TO_117' => $_POST['time_do_TO_117'],
                    'time_do_TO_118' => $_POST['time_do_TO_118'],
                    'time_do_TO_119' => $_POST['time_do_TO_119'],
                    'time_do_TO_120' => $_POST['time_do_TO_120'],
                    'time_do_TO_121' => $_POST['time_do_TO_121'],
                    'time_do_TO_122' => $_POST['time_do_TO_122'],
                    'time_do_TO_123' => $_POST['time_do_TO_123'],
                    'time_do_TO_124' => $_POST['time_do_TO_124'],
                    'time_do_TO_125' => $_POST['time_do_TO_125'],
                    'time_do_TO_126' => $_POST['time_do_TO_126'],
                    'time_do_TO_127' => $_POST['time_do_TO_127'],
                    'time_do_TO_128' => $_POST['time_do_TO_128'],
                    'time_do_TO_129' => $_POST['time_do_TO_129'],
                    'time_do_TO_130' => $_POST['time_do_TO_130'],
                    'time_do_TO_131' => $_POST['time_do_TO_131'],
                    'time_do_TO_132' => $_POST['time_do_TO_132'],
                    'time_do_TO_133' => $_POST['time_do_TO_133'],
                    'time_do_TO_134' => $_POST['time_do_TO_134'],
                    'time_do_TO_135' => $_POST['time_do_TO_135'],
                    'time_do_TO_136' => $_POST['time_do_TO_136'],
                    'time_do_TO_137' => $_POST['time_do_TO_137'],
                    'time_do_TO_138' => $_POST['time_do_TO_138'],
                    'time_do_TO_139' => $_POST['time_do_TO_139'],
                    'time_do_TO_140' => $_POST['time_do_TO_140'],
                    'time_do_TO_141' => $_POST['time_do_TO_141'],
                    'time_do_TO_142' => $_POST['time_do_TO_142'],
                    'time_do_TO_143' => $_POST['time_do_TO_143'],
                    'time_do_TO_144' => $_POST['time_do_TO_144'],
                    'time_do_TO_145' => $_POST['time_do_TO_145'],
                    'time_do_TO_146' => $_POST['time_do_TO_146'],
                    'time_do_TO_147' => $_POST['time_do_TO_147'],
                    'time_do_TO_148' => $_POST['time_do_TO_148'],
                    'time_do_TO_149' => $_POST['time_do_TO_149'],
                    'time_do_TO_150' => $_POST['time_do_TO_150'],
                    'time_do_TO_151' => $_POST['time_do_TO_151'],
                    'time_do_TO_152' => $_POST['time_do_TO_152'],
                    'time_do_TO_153' => $_POST['time_do_TO_153'],
                    'time_do_TO_154' => $_POST['time_do_TO_154'],
                    'time_do_TO_155' => $_POST['time_do_TO_155'],
                    'time_do_TO_156' => $_POST['time_do_TO_156'],
                    'time_do_TO_157' => $_POST['time_do_TO_157'],
                    'time_do_TO_158' => $_POST['time_do_TO_158'],
                    'time_do_TO_159' => $_POST['time_do_TO_159'],
                    'time_do_TO_160' => $_POST['time_do_TO_160'],
                    'time_do_TO_161' => $_POST['time_do_TO_161'],
                    'time_do_TO_162' => $_POST['time_do_TO_162'],
                    'time_do_TO_163' => $_POST['time_do_TO_163'],
                    'time_do_TO_164' => $_POST['time_do_TO_164'],
                    'time_do_TO_165' => $_POST['time_do_TO_165'],
                    'time_do_TO_166' => $_POST['time_do_TO_166'],
                    'time_do_TO_167' => $_POST['time_do_TO_167'],
                    'time_do_TO_168' => $_POST['time_do_TO_168'],
                    'time_do_TO_169' => $_POST['time_do_TO_169'],
                    'time_do_TO_170' => $_POST['time_do_TO_170'],
                    'time_do_TO_171' => $_POST['time_do_TO_171'],
                    'time_do_TO_172' => $_POST['time_do_TO_172'],
                    'time_do_TO_173' => $_POST['time_do_TO_173'],
                    'time_do_TO_174' => $_POST['time_do_TO_174'],
                    'time_do_TO_175' => $_POST['time_do_TO_175'],
                    'time_do_TO_176' => $_POST['time_do_TO_176'],
                    'time_do_TO_177' => $_POST['time_do_TO_177'],
                    'time_do_TO_178' => $_POST['time_do_TO_178'],
                    'time_do_TO_179' => $_POST['time_do_TO_179'],
                    'time_do_TO_180' => $_POST['time_do_TO_180'],
                    'time_do_TO_181' => $_POST['time_do_TO_181'],
                    'time_do_TO_182' => $_POST['time_do_TO_182'],
                    'time_do_TO_183' => $_POST['time_do_TO_183'],
                    'time_do_TO_184' => $_POST['time_do_TO_184'],
                    'time_do_TO_185' => $_POST['time_do_TO_185'],
                    'time_do_TO_186' => $_POST['time_do_TO_186'],
                    'time_do_TO_187' => $_POST['time_do_TO_187'],
                    'time_do_TO_188' => $_POST['time_do_TO_188'],
                    'time_do_TO_189' => $_POST['time_do_TO_189'],
                    'time_do_TO_190' => $_POST['time_do_TO_190'],
                    'time_do_TO_191' => $_POST['time_do_TO_191'],
                    'time_do_TO_192' => $_POST['time_do_TO_192'],
                    'time_do_TO_193' => $_POST['time_do_TO_193'],
                    'time_do_TO_194' => $_POST['time_do_TO_194'],
                    'time_do_TO_195' => $_POST['time_do_TO_195'],
                    'time_do_TO_196' => $_POST['time_do_TO_196'],
                    'time_do_TO_197' => $_POST['time_do_TO_197'],
                    'time_do_TO_198' => $_POST['time_do_TO_198'],
                    'time_do_TO_199' => $_POST['time_do_TO_199'],
                    'time_do_TO_200' => $_POST['time_do_TO_200'],
                    'time_do_TO_201' => $_POST['time_do_TO_201'],
                    'time_do_TO_202' => $_POST['time_do_TO_202'],
                    'time_do_TO_203' => $_POST['time_do_TO_203'],
                    'time_do_TO_204' => $_POST['time_do_TO_204'],
                    'time_do_TO_205' => $_POST['time_do_TO_205'],
                    'time_do_TO_206' => $_POST['time_do_TO_206'],
                    'time_do_TO_207' => $_POST['time_do_TO_207'],
                    'time_do_TO_208' => $_POST['time_do_TO_208'],
                    'time_do_TO_209' => $_POST['time_do_TO_209'],
                    'time_do_TO_210' => $_POST['time_do_TO_210'],
                    'time_do_TO_211' => $_POST['time_do_TO_211'],
                    'time_do_TO_212' => $_POST['time_do_TO_212'],
                    'time_do_TO_213' => $_POST['time_do_TO_213'],
                    'time_do_TO_214' => $_POST['time_do_TO_214'],
                    'time_do_TO_215' => $_POST['time_do_TO_215'],
                    'time_do_TO_216' => $_POST['time_do_TO_216'],
                    'time_do_TO_217' => $_POST['time_do_TO_217'],
                    'time_do_TO_218' => $_POST['time_do_TO_218'],
                    'time_do_TO_219' => $_POST['time_do_TO_219'],
                    'time_do_TO_220' => $_POST['time_do_TO_220'],
                    'time_do_TO_221' => $_POST['time_do_TO_221'],
                    'time_do_TO_222' => $_POST['time_do_TO_222'],
                    'time_do_TO_223' => $_POST['time_do_TO_223'],
                    'time_do_TO_224' => $_POST['time_do_TO_224'],
                    'time_do_TO_225' => $_POST['time_do_TO_225'],
                    'time_do_TO_226' => $_POST['time_do_TO_226'],
                    'time_do_TO_227' => $_POST['time_do_TO_227'],
                    'time_do_TO_228' => $_POST['time_do_TO_228'],
                    'time_do_TO_229' => $_POST['time_do_TO_229'],
                    'time_do_TO_230' => $_POST['time_do_TO_230'],
                    'time_do_TO_231' => $_POST['time_do_TO_231'],
                    'time_do_TO_232' => $_POST['time_do_TO_232'],
                    'time_do_TO_233' => $_POST['time_do_TO_233'],
                    'time_do_TO_234' => $_POST['time_do_TO_234'],
                    'time_do_TO_235' => $_POST['time_do_TO_235'],
                    'time_do_TO_236' => $_POST['time_do_TO_236'],
                    'time_do_TO_237' => $_POST['time_do_TO_237'],
                    'time_do_TO_238' => $_POST['time_do_TO_238'],
                    'time_do_TO_239' => $_POST['time_do_TO_239'],
                    'time_do_TO_240' => $_POST['time_do_TO_240'],
                    'time_do_TO_241' => $_POST['time_do_TO_241'],
                    'time_do_TO_242' => $_POST['time_do_TO_242'],
                    'time_do_TO_243' => $_POST['time_do_TO_243'],
                    'time_do_TO_244' => $_POST['time_do_TO_244'],
                    'time_do_TO_245' => $_POST['time_do_TO_245'],
                    'time_do_TO_246' => $_POST['time_do_TO_246'],
                    'time_do_TO_247' => $_POST['time_do_TO_247'],
                    'time_do_TO_248' => $_POST['time_do_TO_248'],
                    'time_do_TO_249' => $_POST['time_do_TO_249'],
                    'time_do_TO_250' => $_POST['time_do_TO_250'],
                    'time_do_TO_251' => $_POST['time_do_TO_251'],
                    'time_do_TO_252' => $_POST['time_do_TO_252'],
                    'time_do_TO_253' => $_POST['time_do_TO_253'],
                    'time_do_TO_254' => $_POST['time_do_TO_254'],
                    'time_do_TO_255' => $_POST['time_do_TO_255'],
                    'time_do_TO_256' => $_POST['time_do_TO_256'],
                    'time_do_TO_257' => $_POST['time_do_TO_257'],
                    'time_do_TO_258' => $_POST['time_do_TO_258'],
                    'time_do_TO_259' => $_POST['time_do_TO_259'],
                    'time_do_TO_260' => $_POST['time_do_TO_260'],
                    'time_do_TO_261' => $_POST['time_do_TO_261'],
                    'time_do_TO_262' => $_POST['time_do_TO_262'],
                    'time_do_TO_263' => $_POST['time_do_TO_263'],
                    'time_do_TO_264' => $_POST['time_do_TO_264'],
                    'time_do_TO_265' => $_POST['time_do_TO_265'],
                    'time_do_TO_266' => $_POST['time_do_TO_266'],
                    'time_do_TO_267' => $_POST['time_do_TO_267'],
                    'time_do_TO_268' => $_POST['time_do_TO_268'],
                    'time_do_TO_269' => $_POST['time_do_TO_269'],
                    'time_do_TO_270' => $_POST['time_do_TO_270'],
                    'time_do_TO_271' => $_POST['time_do_TO_271'],
                    'time_do_TO_272' => $_POST['time_do_TO_272'],
                    'time_do_TO_273' => $_POST['time_do_TO_273'],
                    'time_do_TO_274' => $_POST['time_do_TO_274'],
                    'time_do_TO_275' => $_POST['time_do_TO_275'],
                    'time_do_TO_276' => $_POST['time_do_TO_276'],
                    'time_do_TO_277' => $_POST['time_do_TO_277'],
                    'time_do_TO_278' => $_POST['time_do_TO_278'],
                    'time_do_TO_279' => $_POST['time_do_TO_279'],
                    'time_do_TO_280' => $_POST['time_do_TO_280'],
                    'time_do_TO_281' => $_POST['time_do_TO_281'],
                    'time_do_TO_282' => $_POST['time_do_TO_282'],
                    'time_do_TO_283' => $_POST['time_do_TO_283'],
                    'time_do_TO_284' => $_POST['time_do_TO_284'],
                    'time_do_TO_285' => $_POST['time_do_TO_285'],
                    'time_do_TO_286' => $_POST['time_do_TO_286'],
                    'time_do_TO_287' => $_POST['time_do_TO_287'],
                    'time_do_TO_288' => $_POST['time_do_TO_288'],
                    'time_do_TO_289' => $_POST['time_do_TO_289'],
                    'time_do_TO_290' => $_POST['time_do_TO_290'],
                    'time_do_TO_291' => $_POST['time_do_TO_291'],
                    'time_do_TO_292' => $_POST['time_do_TO_292'],
                    'time_do_TO_293' => $_POST['time_do_TO_293'],
                    'time_do_TO_294' => $_POST['time_do_TO_294'],
                    'time_do_TO_295' => $_POST['time_do_TO_295'],


                    'time_warning_1' => $_POST['time_warning_1'],
                    'time_warning_2' => $_POST['time_warning_2'],
                    'time_warning_3' => $_POST['time_warning_3'],
                    'time_warning_4' => $_POST['time_warning_4'],
                    'time_warning_5' => $_POST['time_warning_5'],
                    'time_warning_6' => $_POST['time_warning_6'],
                    'time_warning_7' => $_POST['time_warning_7'],
                    'time_warning_8' => $_POST['time_warning_8'],
                    'time_warning_9' => $_POST['time_warning_9'],
                    'time_warning_10' => $_POST['time_warning_10'],
                    'time_warning_11' => $_POST['time_warning_11'],
                    'time_warning_12' => $_POST['time_warning_12'],
                    'time_warning_13' => $_POST['time_warning_13'],
                    'time_warning_14' => $_POST['time_warning_14'],
                    'time_warning_15' => $_POST['time_warning_15'],
                    'time_warning_16' => $_POST['time_warning_16'],
                    'time_warning_17' => $_POST['time_warning_17'],
                    'time_warning_18' => $_POST['time_warning_18'],
                    'time_warning_19' => $_POST['time_warning_19'],
                    'time_warning_20' => $_POST['time_warning_20'],
                    'time_warning_21' => $_POST['time_warning_21'],
                    'time_warning_22' => $_POST['time_warning_22'],
                    'time_warning_23' => $_POST['time_warning_23'],
                    'time_warning_24' => $_POST['time_warning_24'],
                    'time_warning_25' => $_POST['time_warning_25'],
                    'time_warning_26' => $_POST['time_warning_26'],
                    'time_warning_27' => $_POST['time_warning_27'],
                    'time_warning_28' => $_POST['time_warning_28'],
                    'time_warning_29' => $_POST['time_warning_29'],
                    'time_warning_30' => $_POST['time_warning_30'],
                    'time_warning_31' => $_POST['time_warning_31'],
                    'time_warning_32' => $_POST['time_warning_32'],
                    'time_warning_33' => $_POST['time_warning_33'],
                    'time_warning_34' => $_POST['time_warning_34'],
                    'time_warning_35' => $_POST['time_warning_35'],
                    'time_warning_36' => $_POST['time_warning_36'],
                    'time_warning_37' => $_POST['time_warning_37'],
                    'time_warning_38' => $_POST['time_warning_38'],
                    'time_warning_39' => $_POST['time_warning_39'],
                    'time_warning_40' => $_POST['time_warning_40'],
                    'time_warning_41' => $_POST['time_warning_41'],
                    'time_warning_42' => $_POST['time_warning_42'],
                    'time_warning_43' => $_POST['time_warning_43'],
                    'time_warning_44' => $_POST['time_warning_44'],
                    'time_warning_45' => $_POST['time_warning_45'],
                    'time_warning_46' => $_POST['time_warning_46'],
                    'time_warning_47' => $_POST['time_warning_47'],
                    'time_warning_48' => $_POST['time_warning_48'],
                    'time_warning_49' => $_POST['time_warning_49'],
                    'time_warning_50' => $_POST['time_warning_50'],
                    'time_warning_51' => $_POST['time_warning_51'],
                    'time_warning_52' => $_POST['time_warning_52'],
                    'time_warning_53' => $_POST['time_warning_53'],
                    'time_warning_54' => $_POST['time_warning_54'],
                    'time_warning_55' => $_POST['time_warning_55'],
                    'time_warning_56' => $_POST['time_warning_56'],
                    'time_warning_57' => $_POST['time_warning_57'],
                    'time_warning_58' => $_POST['time_warning_58'],
                    'time_warning_59' => $_POST['time_warning_59'],
                    'time_warning_60' => $_POST['time_warning_60'],
                    'time_warning_61' => $_POST['time_warning_61'],
                    'time_warning_62' => $_POST['time_warning_62'],
                    'time_warning_63' => $_POST['time_warning_63'],
                    'time_warning_64' => $_POST['time_warning_64'],
                    'time_warning_65' => $_POST['time_warning_65'],
                    'time_warning_66' => $_POST['time_warning_66'],
                    'time_warning_67' => $_POST['time_warning_67'],
                    'time_warning_68' => $_POST['time_warning_68'],
                    'time_warning_69' => $_POST['time_warning_69'],
                    'time_warning_70' => $_POST['time_warning_70'],
                    'time_warning_71' => $_POST['time_warning_71'],
                    'time_warning_72' => $_POST['time_warning_72'],
                    'time_warning_73' => $_POST['time_warning_73'],
                    'time_warning_74' => $_POST['time_warning_74'],
                    'time_warning_75' => $_POST['time_warning_75'],
                    'time_warning_76' => $_POST['time_warning_76'],
                    'time_warning_77' => $_POST['time_warning_77'],
                    'time_warning_78' => $_POST['time_warning_78'],
                    'time_warning_79' => $_POST['time_warning_79'],
                    'time_warning_80' => $_POST['time_warning_80'],
                    'time_warning_81' => $_POST['time_warning_81'],
                    'time_warning_82' => $_POST['time_warning_82'],
                    'time_warning_83' => $_POST['time_warning_83'],
                    'time_warning_84' => $_POST['time_warning_84'],
                    'time_warning_85' => $_POST['time_warning_85'],
                    'time_warning_86' => $_POST['time_warning_86'],
                    'time_warning_87' => $_POST['time_warning_87'],
                    'time_warning_88' => $_POST['time_warning_88'],
                    'time_warning_89' => $_POST['time_warning_89'],
                    'time_warning_90' => $_POST['time_warning_90'],
                    'time_warning_91' => $_POST['time_warning_91'],
                    'time_warning_92' => $_POST['time_warning_92'],
                    'time_warning_93' => $_POST['time_warning_93'],
                    'time_warning_94' => $_POST['time_warning_94'],
                    'time_warning_95' => $_POST['time_warning_95'],
                    'time_warning_96' => $_POST['time_warning_96'],
                    'time_warning_97' => $_POST['time_warning_97'],
                    'time_warning_98' => $_POST['time_warning_98'],
                    'time_warning_99' => $_POST['time_warning_99'],
                    'time_warning_100' => $_POST['time_warning_100'],
                    'time_warning_101' => $_POST['time_warning_101'],
                    'time_warning_102' => $_POST['time_warning_102'],
                    'time_warning_103' => $_POST['time_warning_103'],
                    'time_warning_104' => $_POST['time_warning_104'],
                    'time_warning_105' => $_POST['time_warning_105'],
                    'time_warning_106' => $_POST['time_warning_106'],
                    'time_warning_107' => $_POST['time_warning_107'],
                    'time_warning_108' => $_POST['time_warning_108'],
                    'time_warning_109' => $_POST['time_warning_109'],
                    'time_warning_110' => $_POST['time_warning_110'],
                    'time_warning_111' => $_POST['time_warning_111'],
                    'time_warning_112' => $_POST['time_warning_112'],
                    'time_warning_113' => $_POST['time_warning_113'],
                    'time_warning_114' => $_POST['time_warning_114'],
                    'time_warning_115' => $_POST['time_warning_115'],
                    'time_warning_116' => $_POST['time_warning_116'],
                    'time_warning_117' => $_POST['time_warning_117'],
                    'time_warning_118' => $_POST['time_warning_118'],
                    'time_warning_119' => $_POST['time_warning_119'],
                    'time_warning_120' => $_POST['time_warning_120'],
                    'time_warning_121' => $_POST['time_warning_121'],
                    'time_warning_122' => $_POST['time_warning_122'],
                    'time_warning_123' => $_POST['time_warning_123'],
                    'time_warning_124' => $_POST['time_warning_124'],
                    'time_warning_125' => $_POST['time_warning_125'],
                    'time_warning_126' => $_POST['time_warning_126'],
                    'time_warning_127' => $_POST['time_warning_127'],
                    'time_warning_128' => $_POST['time_warning_128'],
                    'time_warning_129' => $_POST['time_warning_129'],
                    'time_warning_130' => $_POST['time_warning_130'],
                    'time_warning_131' => $_POST['time_warning_131'],
                    'time_warning_132' => $_POST['time_warning_132'],
                    'time_warning_133' => $_POST['time_warning_133'],
                    'time_warning_134' => $_POST['time_warning_134'],
                    'time_warning_135' => $_POST['time_warning_135'],
                    'time_warning_136' => $_POST['time_warning_136'],
                    'time_warning_137' => $_POST['time_warning_137'],
                    'time_warning_138' => $_POST['time_warning_138'],
                    'time_warning_139' => $_POST['time_warning_139'],
                    'time_warning_140' => $_POST['time_warning_140'],
                    'time_warning_141' => $_POST['time_warning_141'],
                    'time_warning_142' => $_POST['time_warning_142'],
                    'time_warning_143' => $_POST['time_warning_143'],
                    'time_warning_144' => $_POST['time_warning_144'],
                    'time_warning_145' => $_POST['time_warning_145'],
                    'time_warning_146' => $_POST['time_warning_146'],
                    'time_warning_147' => $_POST['time_warning_147'],
                    'time_warning_148' => $_POST['time_warning_148'],
                    'time_warning_149' => $_POST['time_warning_149'],
                    'time_warning_150' => $_POST['time_warning_150'],
                    'time_warning_151' => $_POST['time_warning_151'],
                    'time_warning_152' => $_POST['time_warning_152'],
                    'time_warning_153' => $_POST['time_warning_153'],
                    'time_warning_154' => $_POST['time_warning_154'],
                    'time_warning_155' => $_POST['time_warning_155'],
                    'time_warning_156' => $_POST['time_warning_156'],
                    'time_warning_157' => $_POST['time_warning_157'],
                    'time_warning_158' => $_POST['time_warning_158'],
                    'time_warning_159' => $_POST['time_warning_159'],
                    'time_warning_160' => $_POST['time_warning_160'],
                    'time_warning_161' => $_POST['time_warning_161'],
                    'time_warning_162' => $_POST['time_warning_162'],
                    'time_warning_163' => $_POST['time_warning_163'],
                    'time_warning_164' => $_POST['time_warning_164'],
                    'time_warning_165' => $_POST['time_warning_165'],
                    'time_warning_166' => $_POST['time_warning_166'],
                    'time_warning_167' => $_POST['time_warning_167'],
                    'time_warning_168' => $_POST['time_warning_168'],
                    'time_warning_169' => $_POST['time_warning_169'],
                    'time_warning_170' => $_POST['time_warning_170'],
                    'time_warning_171' => $_POST['time_warning_171'],
                    'time_warning_172' => $_POST['time_warning_172'],
                    'time_warning_173' => $_POST['time_warning_173'],
                    'time_warning_174' => $_POST['time_warning_174'],
                    'time_warning_175' => $_POST['time_warning_175'],
                    'time_warning_176' => $_POST['time_warning_176'],
                    'time_warning_177' => $_POST['time_warning_177'],
                    'time_warning_178' => $_POST['time_warning_178'],
                    'time_warning_179' => $_POST['time_warning_179'],
                    'time_warning_180' => $_POST['time_warning_180'],
                    'time_warning_181' => $_POST['time_warning_181'],
                    'time_warning_182' => $_POST['time_warning_182'],
                    'time_warning_183' => $_POST['time_warning_183'],
                    'time_warning_184' => $_POST['time_warning_184'],
                    'time_warning_185' => $_POST['time_warning_185'],
                    'time_warning_186' => $_POST['time_warning_186'],
                    'time_warning_187' => $_POST['time_warning_187'],
                    'time_warning_188' => $_POST['time_warning_188'],
                    'time_warning_189' => $_POST['time_warning_189'],
                    'time_warning_190' => $_POST['time_warning_190'],
                    'time_warning_191' => $_POST['time_warning_191'],
                    'time_warning_192' => $_POST['time_warning_192'],
                    'time_warning_193' => $_POST['time_warning_193'],
                    'time_warning_194' => $_POST['time_warning_194'],
                    'time_warning_195' => $_POST['time_warning_195'],
                    'time_warning_196' => $_POST['time_warning_196'],
                    'time_warning_197' => $_POST['time_warning_197'],
                    'time_warning_198' => $_POST['time_warning_198'],
                    'time_warning_199' => $_POST['time_warning_199'],
                    'time_warning_200' => $_POST['time_warning_200'],
                    'time_warning_201' => $_POST['time_warning_201'],
                    'time_warning_202' => $_POST['time_warning_202'],
                    'time_warning_203' => $_POST['time_warning_203'],
                    'time_warning_204' => $_POST['time_warning_204'],
                    'time_warning_205' => $_POST['time_warning_205'],
                    'time_warning_206' => $_POST['time_warning_206'],
                    'time_warning_207' => $_POST['time_warning_207'],
                    'time_warning_208' => $_POST['time_warning_208'],
                    'time_warning_209' => $_POST['time_warning_209'],
                    'time_warning_210' => $_POST['time_warning_210'],
                    'time_warning_211' => $_POST['time_warning_211'],
                    'time_warning_212' => $_POST['time_warning_212'],
                    'time_warning_213' => $_POST['time_warning_213'],
                    'time_warning_214' => $_POST['time_warning_214'],
                    'time_warning_215' => $_POST['time_warning_215'],
                    'time_warning_216' => $_POST['time_warning_216'],
                    'time_warning_217' => $_POST['time_warning_217'],
                    'time_warning_218' => $_POST['time_warning_218'],
                    'time_warning_219' => $_POST['time_warning_219'],
                    'time_warning_220' => $_POST['time_warning_220'],
                    'time_warning_221' => $_POST['time_warning_221'],
                    'time_warning_222' => $_POST['time_warning_222'],
                    'time_warning_223' => $_POST['time_warning_223'],
                    'time_warning_224' => $_POST['time_warning_224'],
                    'time_warning_225' => $_POST['time_warning_225'],
                    'time_warning_226' => $_POST['time_warning_226'],
                    'time_warning_227' => $_POST['time_warning_227'],
                    'time_warning_228' => $_POST['time_warning_228'],
                    'time_warning_229' => $_POST['time_warning_229'],
                    'time_warning_230' => $_POST['time_warning_230'],
                    'time_warning_231' => $_POST['time_warning_231'],
                    'time_warning_232' => $_POST['time_warning_232'],
                    'time_warning_233' => $_POST['time_warning_233'],
                    'time_warning_234' => $_POST['time_warning_234'],
                    'time_warning_235' => $_POST['time_warning_235'],
                    'time_warning_236' => $_POST['time_warning_236'],
                    'time_warning_237' => $_POST['time_warning_237'],
                    'time_warning_238' => $_POST['time_warning_238'],
                    'time_warning_239' => $_POST['time_warning_239'],
                    'time_warning_240' => $_POST['time_warning_240'],
                    'time_warning_241' => $_POST['time_warning_241'],
                    'time_warning_242' => $_POST['time_warning_242'],
                    'time_warning_243' => $_POST['time_warning_243'],
                    'time_warning_244' => $_POST['time_warning_244'],
                    'time_warning_245' => $_POST['time_warning_245'],
                    'time_warning_246' => $_POST['time_warning_246'],
                    'time_warning_247' => $_POST['time_warning_247'],
                    'time_warning_248' => $_POST['time_warning_248'],
                    'time_warning_249' => $_POST['time_warning_249'],
                    'time_warning_250' => $_POST['time_warning_250'],
                    'time_warning_251' => $_POST['time_warning_251'],
                    'time_warning_252' => $_POST['time_warning_252'],
                    'time_warning_253' => $_POST['time_warning_253'],
                    'time_warning_254' => $_POST['time_warning_254'],
                    'time_warning_255' => $_POST['time_warning_255'],
                    'time_warning_256' => $_POST['time_warning_256'],
                    'time_warning_257' => $_POST['time_warning_257'],
                    'time_warning_258' => $_POST['time_warning_258'],
                    'time_warning_259' => $_POST['time_warning_259'],
                    'time_warning_260' => $_POST['time_warning_260'],
                    'time_warning_261' => $_POST['time_warning_261'],
                    'time_warning_262' => $_POST['time_warning_262'],
                    'time_warning_263' => $_POST['time_warning_263'],
                    'time_warning_264' => $_POST['time_warning_264'],
                    'time_warning_265' => $_POST['time_warning_265'],
                    'time_warning_266' => $_POST['time_warning_266'],
                    'time_warning_267' => $_POST['time_warning_267'],
                    'time_warning_268' => $_POST['time_warning_268'],
                    'time_warning_269' => $_POST['time_warning_269'],
                    'time_warning_270' => $_POST['time_warning_270'],
                    'time_warning_271' => $_POST['time_warning_271'],
                    'time_warning_272' => $_POST['time_warning_272'],
                    'time_warning_273' => $_POST['time_warning_273'],
                    'time_warning_274' => $_POST['time_warning_274'],
                    'time_warning_275' => $_POST['time_warning_275'],
                    'time_warning_276' => $_POST['time_warning_276'],
                    'time_warning_277' => $_POST['time_warning_277'],
                    'time_warning_278' => $_POST['time_warning_278'],
                    'time_warning_279' => $_POST['time_warning_279'],
                    'time_warning_280' => $_POST['time_warning_280'],
                    'time_warning_281' => $_POST['time_warning_281'],
                    'time_warning_282' => $_POST['time_warning_282'],
                    'time_warning_283' => $_POST['time_warning_283'],
                    'time_warning_284' => $_POST['time_warning_284'],
                    'time_warning_285' => $_POST['time_warning_285'],
                    'time_warning_286' => $_POST['time_warning_286'],
                    'time_warning_287' => $_POST['time_warning_287'],
                    'time_warning_288' => $_POST['time_warning_288'],
                    'time_warning_289' => $_POST['time_warning_289'],
                    'time_warning_290' => $_POST['time_warning_290'],
                    'time_warning_291' => $_POST['time_warning_291'],
                    'time_warning_292' => $_POST['time_warning_292'],
                    'time_warning_293' => $_POST['time_warning_293'],
                    'time_warning_294' => $_POST['time_warning_294'],
                    'time_warning_295' => $_POST['time_warning_295'],
                ]);
            }
        }
        return null;
    }//запись в таблицу БД наработки
//----------------------------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------------------------
    public function post_dev_names()
    {
        if (isset($_POST)) {
            if ($_POST['comp_name'] == DB::table(self::OBJ . '_comp_name_db')->OrderBy('id', 'desc')->first()->comp_name) {
                DB::table(self::OBJ . '_dev_names_db')->truncate();
                for ($x = 0; $x < 296; $x++) DB::table(self::OBJ . '_dev_names_db')->insert(['device_name' => $_POST['dev_name_' . $x]]);
            }
        }
        return null;
    }//запись в таблицу БД имен устройств
//----------------------------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------------------------
    public function post_operator_action()
    {
        if (isset($_POST)) {
            if ($_POST['comp_name'] == DB::table(self::OBJ . '_comp_name_db')->OrderBy('id', 'desc')->first()->comp_name) {
                if (isset($_POST['device'])) {
                    if (isset($_POST['group'])) {
                        DB::table(self::OBJ . '_operator_action_db')->insert([
                            'datetime' => $_POST['datetime'],
                            'alarm' => $_POST['alarm'],
                            'device' => $_POST['device'],
                            'state' => $_POST['state'],
                            'group' => $_POST['group'],
                        ]);
                    } else {
                        DB::table(self::OBJ . '_operator_action_db')->insert([
                            'datetime' => $_POST['datetime'],
                            'alarm' => $_POST['alarm'],
                            'device' => $_POST['device'],
                            'state' => $_POST['state'],
                            'group' => '',
                        ]);
                    }
                } else {
                    if (isset($_POST['group'])) {
                        DB::table(self::OBJ . '_operator_action_db')->insert([
                            'datetime' => $_POST['datetime'],
                            'alarm' => $_POST['alarm'],
                            'device' => '',
                            'state' => $_POST['state'],
                            'group' => $_POST['group'],
                        ]);
                    } else {
                        DB::table(self::OBJ . '_operator_action_db')->insert([
                            'datetime' => $_POST['datetime'],
                            'alarm' => $_POST['alarm'],
                            'device' => '',
                            'state' => $_POST['state'],
                            'group' => '',
                        ]);
                    }
                }
            }
            return null;
        }
    }//запись в таблицу БД аварий и списка действий оператора
//----------------------------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------------------------
    public function post_comp_name()
    {
        if (isset($_POST)) {
            if ($_POST['code'] == DB::table('CONFIG')->OrderBy('id', 'desc')->first()->code) {
                DB::table(self::OBJ . '_comp_name_db')->insert([
                    'comp_name' => $_POST['comp_name'],
                ]);
            }
        }
        return null;
    }//установка нового имени компьютера
//----------------------------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------------------------
    public function download()
    {
        if (isset ($_GET['id'])) {
            $id = $_GET['id'];
            $report = DB::table(self::OBJ . '_time_db')->where('id', '=', $id)->first();
            $datestr = $report->created_at;
            $datestr = str_replace('-', '', $datestr);
            $datestr = str_replace(':', '', $datestr);
            $file_name = 'TMP_Reports/' . self::OBJ . date("-Ymd His-") . $datestr . '-report.rpt';
            $fp = fopen($file_name, "w");
            $textstr = "ReportSystemRestore";
            $textstr .= "\r\n";
            fwrite($fp, $textstr);
            for ($x = 1; $x < 296; $x++) {
                $field = 'time_' . $x;
                $textstr = $report->$field;
                $textstr .= "\r\n";

                $field = 'time_do_TO_' . $x;
                $textstr .= $report->$field;
                $textstr .= "\r\n";

                fwrite($fp, $textstr);
            }
            fclose($fp);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name));
            readfile($file_name);
            unlink($file_name);
            exit;
        }
        return redirect('/' . self::OBJ . '_time_reports');
    }//загрузка файла отчета наработки для восстановления
//----------------------------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------------------------
    public function post_alarms()//запись в таблицу БД аварий
    {
        if (isset($_POST)) {
            if ($_POST['comp_name'] == DB::table(self::OBJ . '_comp_name_db')->OrderBy('id', 'desc')->first()->comp_name) {
                $msgtype = 'alarm';
                if ($_POST['addtext2'] == 'Пользователь') {
                    $msgtype = 'operator';
                }
                if ($_POST['addtext2'] == null) {
                    $msgtype = 'nebulization';
                }
                if ($_POST['addtext2'] == 'Маршрут') {
                    $msgtype = 'route';
                }
                if ($_POST['alarmclass'] == 'System') {
                    $msgtype = 'system';
                }
                DB::table(self::OBJ . '_alarms_db')->insert([
                    'id' => $_POST['id'],
                    'datetime' => $_POST['datetime'],
                    'alarmtext' => $_POST['alarmtext'],
                    'alarmclass' => $_POST['alarmclass'],
                    'addtext1' => $_POST['addtext1'],
                    'addtext2' => $_POST['addtext2'],
                    'param1' => $_POST['param1'],
                    'param2' => $_POST['param2'],
                    'param3' => $_POST['param3'],
                    'state' => $_POST['state'],
                    'msgtype' => $msgtype,
                ]);
            }
        }
        return null;
    }
//----------------------------------------------------------------------------------------------------------------------













//----------------------------------------------------------------------------------------------------------------------
    public function index_speed()//вывод списка аварий по новой схеме
    {
        if (Auth::user()) {
            date_default_timezone_set('Europe/Moscow');
            $getDate = null;
            $today = date("Y-m-d");

            if (isset($_GET['date'])) {
                if ($_GET['date'] != "") {
                    $getDate = $_GET['date'];
                } else {
                    $getDate = $today;
                }
            } else {
                $getDate = $today;
            }


            $reports = DB::table(self::OBJ . '_alarms_db')
                ->where('datetime', '<', $getDate . ' 23:59:59')
                ->where('alarmtext', 'like', '%Ошибка скорости. Привод не вращается%')
                ->OrderBy('num', 'desc')->paginate(30);

            $tmpdate = $getDate;
            $today = date("d.m.Y");
            $today = str_replace(substr($today, 2, 4), " " . $this->_monthsList[substr($today, 2, 4)] . " ", $today);
            $getDate = substr($getDate, -2, 2) . '.' . substr($getDate, -5, 2) . '.' . substr($getDate, 0, 4);
            $getDate = str_replace(substr($getDate, 2, 4), " " . $this->_monthsList[substr($getDate, 2, 4)] . " ", $getDate);

            return view('reports.' . self::OBJ . '.' . self::OBJ . '_speed', compact('today', 'reports', 'getDate', 'tmpdate'));
        } else {
            return redirect('/home');
        }
    }
//----------------------------------------------------------------------------------------------------------------------

}
