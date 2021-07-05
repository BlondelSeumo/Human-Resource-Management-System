<?php

namespace App\Http\Controllers\Front;

use App\Classes\Reply;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Holiday;
use Carbon\Carbon;
use DebugBar\DebugBar;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontBaseController;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class AttendanceFrontController extends FrontBaseController
{
    public function __construct()
    {
        parent::__construct();


        $this->pageTitle = Lang::get('core.attendance');

        $timezones = [
            "Pacific/Pago_Pago" => "-11:00",
            "Pacific/Midway" => "-11:00",
            "Pacific/Niue" => "-11:00",
            "Pacific/Honolulu" => "-10:00",
            "Pacific/Tahiti" => "-10:00",
            "Pacific/Rarotonga" => "-10:00",
            "Pacific/Marquesas" => "-09:30",
            "America/Adak" => "-09:00",
            "Pacific/Gambier" => "-09:00",
            "America/Anchorage" => "-08:00",
            "America/Nome" => "-08:00",
            "America/Sitka" => "-08:00",
            "America/Yakutat" => "-08:00",
            "America/Metlakatla" => "-08:00",
            "America/Juneau" => "-08:00",
            "Pacific/Pitcairn" => "-08:00",
            "America/Phoenix" => "-07:00",
            "America/Creston" => "-07:00",
            "America/Dawson" => "-07:00",
            "America/Los_Angeles" => "-07:00",
            "America/Dawson_Creek" => "-07:00",
            "America/Whitehorse" => "-07:00",
            "America/Hermosillo" => "-07:00",
            "America/Fort_Nelson" => "-07:00",
            "America/Tijuana" => "-07:00",
            "America/Vancouver" => "-07:00",
            "America/Chihuahua" => "-06:00",
            "America/Cambridge_Bay" => "-06:00",
            "America/Boise" => "-06:00",
            "America/Denver" => "-06:00",
            "America/El_Salvador" => "-06:00",
            "America/Costa_Rica" => "-06:00",
            "America/Mazatlan" => "-06:00",
            "America/Ojinaga" => "-06:00",
            "America/Guatemala" => "-06:00",
            "America/Edmonton" => "-06:00",
            "America/Inuvik" => "-06:00",
            "America/Belize" => "-06:00",
            "America/Managua" => "-06:00",
            "America/Swift_Current" => "-06:00",
            "America/Regina" => "-06:00",
            "Pacific/Galapagos" => "-06:00",
            "America/Yellowknife" => "-06:00",
            "America/Tegucigalpa" => "-06:00",
            "America/Monterrey" => "-05:00",
            "America/Menominee" => "-05:00",
            "America/Bahia_Banderas" => "-05:00",
            "America/Bogota" => "-05:00",
            "America/Cancun" => "-05:00",
            "America/Merida" => "-05:00",
            "America/Chicago" => "-05:00",
            "America/Winnipeg" => "-05:00",
            "America/Eirunepe" => "-05:00",
            "America/Atikokan" => "-05:00",
            "America/Matamoros" => "-05:00",
            "Pacific/Easter" => "-05:00",
            "America/Guayaquil" => "-05:00",
            "America/Indiana/Knox" => "-05:00",
            "America/Indiana/Tell_City" => "-05:00",
            "America/Jamaica" => "-05:00",
            "America/Lima" => "-05:00",
            "America/Mexico_City" => "-05:00",
            "America/Cayman" => "-05:00",
            "America/Rainy_River" => "-05:00",
            "America/Rankin_Inlet" => "-05:00",
            "America/Rio_Branco" => "-05:00",
            "America/North_Dakota/Center" => "-05:00",
            "America/Panama" => "-05:00",
            "America/Resolute" => "-05:00",
            "America/North_Dakota/New_Salem" => "-05:00",
            "America/North_Dakota/Beulah" => "-05:00",
            "America/New_York" => "-04:00",
            "America/Puerto_Rico" => "-04:00",
            "America/Porto_Velho" => "-04:00",
            "America/Grand_Turk" => "-04:00",
            "America/Guadeloupe" => "-04:00",
            "America/Grenada" => "-04:00",
            "America/Marigot" => "-04:00",
            "America/Martinique" => "-04:00",
            "America/Port_of_Spain" => "-04:00",
            "America/Port-au-Prince" => "-04:00",
            "America/Guyana" => "-04:00",
            "America/Indiana/Indianapolis" => "-04:00",
            "America/Manaus" => "-04:00",
            "America/Havana" => "-04:00",
            "America/Tortola" => "-04:00",
            "America/Indiana/Marengo" => "-04:00",
            "America/Indiana/Petersburg" => "-04:00",
            "America/Indiana/Vevay" => "-04:00",
            "America/Indiana/Vincennes" => "-04:00",
            "America/Indiana/Winamac" => "-04:00",
            "America/Iqaluit" => "-04:00",
            "America/Kentucky/Louisville" => "-04:00",
            "America/Kentucky/Monticello" => "-04:00",
            "America/Kralendijk" => "-04:00",
            "America/La_Paz" => "-04:00",
            "America/Pangnirtung" => "-04:00",
            "America/Dominica" => "-04:00",
            "America/Nassau" => "-04:00",
            "America/Campo_Grande" => "-04:00",
            "America/Montserrat" => "-04:00",
            "America/Lower_Princes" => "-04:00",
            "America/Aruba" => "-04:00",
            "America/Asuncion" => "-04:00",
            "America/Nipigon" => "-04:00",
            "America/Barbados" => "-04:00",
            "America/St_Barthelemy" => "-04:00",
            "America/St_Kitts" => "-04:00",
            "America/Blanc-Sablon" => "-04:00",
            "America/Boa_Vista" => "-04:00",
            "America/Detroit" => "-04:00",
            "America/St_Thomas" => "-04:00",
            "America/St_Lucia" => "-04:00",
            "America/Caracas" => "-04:00",
            "America/Toronto" => "-04:00",
            "America/Antigua" => "-04:00",
            "America/St_Vincent" => "-04:00",
            "America/Anguilla" => "-04:00",
            "America/Cuiaba" => "-04:00",
            "America/Curacao" => "-04:00",
            "America/Santo_Domingo" => "-04:00",
            "America/Thunder_Bay" => "-04:00",
            "Atlantic/Stanley" => "-03:00",
            "America/Montevideo" => "-03:00",
            "America/Paramaribo" => "-03:00",
            "America/Moncton" => "-03:00",
            "America/Sao_Paulo" => "-03:00",
            "America/Thule" => "-03:00",
            "America/Santarem" => "-03:00",
            "Antarctica/Rothera" => "-03:00",
            "America/Santiago" => "-03:00",
            "America/Punta_Arenas" => "-03:00",
            "Antarctica/Palmer" => "-03:00",
            "America/Recife" => "-03:00",
            "Atlantic/Bermuda" => "-03:00",
            "America/Maceio" => "-03:00",
            "America/Argentina/Tucuman" => "-03:00",
            "America/Araguaina" => "-03:00",
            "America/Argentina/Buenos_Aires" => "-03:00",
            "America/Argentina/Catamarca" => "-03:00",
            "America/Argentina/Cordoba" => "-03:00",
            "America/Argentina/Jujuy" => "-03:00",
            "America/Argentina/Mendoza" => "-03:00",
            "America/Argentina/Rio_Gallegos" => "-03:00",
            "America/Argentina/Salta" => "-03:00",
            "America/Argentina/San_Juan" => "-03:00",
            "America/Argentina/San_Luis" => "-03:00",
            "America/Argentina/La_Rioja" => "-03:00",
            "America/Argentina/Ushuaia" => "-03:00",
            "America/Fortaleza" => "-03:00",
            "America/Halifax" => "-03:00",
            "America/Goose_Bay" => "-03:00",
            "America/Glace_Bay" => "-03:00",
            "America/Belem" => "-03:00",
            "America/Cayenne" => "-03:00",
            "America/Bahia" => "-03:00",
            "America/St_Johns" => "-02:30",
            "America/Noronha" => "-02:00",
            "America/Godthab" => "-02:00",
            "America/Miquelon" => "-02:00",
            "Atlantic/South_Georgia" => "-02:00",
            "Atlantic/Cape_Verde" => "-01:00",
            "Africa/Bissau" => "+00:00",
            "Africa/Freetown" => "+00:00",
            "Africa/Dakar" => "+00:00",
            "Africa/Conakry" => "+00:00",
            "Atlantic/Reykjavik" => "+00:00",
            "Africa/Banjul" => "+00:00",
            "Atlantic/Azores" => "+00:00",
            "Africa/Bamako" => "+00:00",
            "Africa/Accra" => "+00:00",
            "Atlantic/St_Helena" => "+00:00",
            "Africa/Lome" => "+00:00",
            "America/Scoresbysund" => "+00:00",
            "Africa/Abidjan" => "+00:00",
            "Africa/Nouakchott" => "+00:00",
            "Africa/Monrovia" => "+00:00",
            "America/Danmarkshavn" => "+00:00",
            "Africa/Ouagadougou" => "+00:00",
            "Africa/Sao_Tome" => "+00:00",
            "Europe/Dublin" => "+01:00",
            "Europe/Isle_of_Man" => "+01:00",
            "Europe/Jersey" => "+01:00",
            "Africa/Porto-Novo" => "+01:00",
            "Africa/Bangui" => "+01:00",
            "Europe/Lisbon" => "+01:00",
            "Europe/London" => "+01:00",
            "Africa/Niamey" => "+01:00",
            "Africa/Brazzaville" => "+01:00",
            "Africa/Casablanca" => "+01:00",
            "Africa/Ndjamena" => "+01:00",
            "Africa/Douala" => "+01:00",
            "Africa/El_Aaiun" => "+01:00",
            "Africa/Luanda" => "+01:00",
            "Africa/Malabo" => "+01:00",
            "Atlantic/Canary" => "+01:00",
            "Africa/Libreville" => "+01:00",
            "Africa/Lagos" => "+01:00",
            "Africa/Kinshasa" => "+01:00",
            "Atlantic/Faroe" => "+01:00",
            "Atlantic/Madeira" => "+01:00",
            "Africa/Tunis" => "+01:00",
            "Africa/Algiers" => "+01:00",
            "Europe/Guernsey" => "+01:00",
            "Europe/Paris" => "+02:00",
            "Europe/Ljubljana" => "+02:00",
            "Europe/Luxembourg" => "+02:00",
            "Europe/Madrid" => "+02:00",
            "Europe/Malta" => "+02:00",
            "Europe/Kaliningrad" => "+02:00",
            "Europe/Oslo" => "+02:00",
            "Europe/Monaco" => "+02:00",
            "Africa/Lusaka" => "+02:00",
            "Europe/Gibraltar" => "+02:00",
            "Europe/Copenhagen" => "+02:00",
            "Europe/Busingen" => "+02:00",
            "Europe/Budapest" => "+02:00",
            "Europe/Brussels" => "+02:00",
            "Europe/Bratislava" => "+02:00",
            "Europe/Prague" => "+02:00",
            "Europe/Berlin" => "+02:00",
            "Europe/Belgrade" => "+02:00",
            "Europe/Andorra" => "+02:00",
            "Europe/Amsterdam" => "+02:00",
            "Africa/Tripoli" => "+02:00",
            "Africa/Windhoek" => "+02:00",
            "Europe/Podgorica" => "+02:00",
            "Europe/Sarajevo" => "+02:00",
            "Europe/Warsaw" => "+02:00",
            "Africa/Gaborone" => "+02:00",
            "Antarctica/Troll" => "+02:00",
            "Africa/Blantyre" => "+02:00",
            "Europe/Zagreb" => "+02:00",
            "Europe/Rome" => "+02:00",
            "Africa/Bujumbura" => "+02:00",
            "Europe/Vienna" => "+02:00",
            "Africa/Cairo" => "+02:00",
            "Europe/Vatican" => "+02:00",
            "Europe/Vaduz" => "+02:00",
            "Africa/Ceuta" => "+02:00",
            "Africa/Mbabane" => "+02:00",
            "Europe/Tirane" => "+02:00",
            "Africa/Harare" => "+02:00",
            "Europe/Stockholm" => "+02:00",
            "Africa/Johannesburg" => "+02:00",
            "Europe/Skopje" => "+02:00",
            "Africa/Khartoum" => "+02:00",
            "Africa/Kigali" => "+02:00",
            "Africa/Maseru" => "+02:00",
            "Africa/Maputo" => "+02:00",
            "Africa/Lubumbashi" => "+02:00",
            "Europe/San_Marino" => "+02:00",
            "Europe/Zurich" => "+02:00",
            "Indian/Comoro" => "+03:00",
            "Europe/Athens" => "+03:00",
            "Indian/Mayotte" => "+03:00",
            "Europe/Riga" => "+03:00",
            "Europe/Bucharest" => "+03:00",
            "Europe/Chisinau" => "+03:00",
            "Europe/Zaporozhye" => "+03:00",
            "Europe/Vilnius" => "+03:00",
            "Europe/Helsinki" => "+03:00",
            "Europe/Istanbul" => "+03:00",
            "Europe/Kiev" => "+03:00",
            "Europe/Kirov" => "+03:00",
            "Europe/Uzhgorod" => "+03:00",
            "Europe/Tallinn" => "+03:00",
            "Europe/Sofia" => "+03:00",
            "Europe/Mariehamn" => "+03:00",
            "Europe/Minsk" => "+03:00",
            "Europe/Simferopol" => "+03:00",
            "Europe/Moscow" => "+03:00",
            "Indian/Antananarivo" => "+03:00",
            "Asia/Amman" => "+03:00",
            "Asia/Aden" => "+03:00",
            "Africa/Mogadishu" => "+03:00",
            "Asia/Kuwait" => "+03:00",
            "Asia/Nicosia" => "+03:00",
            "Asia/Baghdad" => "+03:00",
            "Antarctica/Syowa" => "+03:00",
            "Asia/Jerusalem" => "+03:00",
            "Asia/Bahrain" => "+03:00",
            "Asia/Gaza" => "+03:00",
            "Asia/Qatar" => "+03:00",
            "Asia/Famagusta" => "+03:00",
            "Asia/Riyadh" => "+03:00",
            "Africa/Nairobi" => "+03:00",
            "Asia/Hebron" => "+03:00",
            "Africa/Kampala" => "+03:00",
            "Asia/Damascus" => "+03:00",
            "Asia/Beirut" => "+03:00",
            "Africa/Dar_es_Salaam" => "+03:00",
            "Africa/Djibouti" => "+03:00",
            "Africa/Asmara" => "+03:00",
            "Africa/Addis_Ababa" => "+03:00",
            "Africa/Juba" => "+03:00",
            "Indian/Mauritius" => "+04:00",
            "Asia/Tbilisi" => "+04:00",
            "Europe/Saratov" => "+04:00",
            "Asia/Dubai" => "+04:00",
            "Europe/Astrakhan" => "+04:00",
            "Indian/Mahe" => "+04:00",
            "Europe/Ulyanovsk" => "+04:00",
            "Asia/Baku" => "+04:00",
            "Indian/Reunion" => "+04:00",
            "Europe/Samara" => "+04:00",
            "Asia/Muscat" => "+04:00",
            "Asia/Yerevan" => "+04:00",
            "Europe/Volgograd" => "+04:00",
            "Asia/Kabul" => "+04:30",
            "Asia/Tehran" => "+04:30",
            "Asia/Aqtobe" => "+05:00",
            "Asia/Aqtau" => "+05:00",
            "Asia/Karachi" => "+05:00",
            "Antarctica/Mawson" => "+05:00",
            "Asia/Oral" => "+05:00",
            "Asia/Tashkent" => "+05:00",
            "Indian/Kerguelen" => "+05:00",
            "Indian/Maldives" => "+05:00",
            "Asia/Atyrau" => "+05:00",
            "Asia/Qyzylorda" => "+05:00",
            "Asia/Dushanbe" => "+05:00",
            "Asia/Samarkand" => "+05:00",
            "Asia/Yekaterinburg" => "+05:00",
            "Asia/Ashgabat" => "+05:00",
            "Asia/Colombo" => "+05:30",
            "Asia/Kolkata" => "+05:30",
            "Asia/Kathmandu" => "+05:45",
            "Asia/Dhaka" => "+06:00",
            "Asia/Bishkek" => "+06:00",
            "Asia/Thimphu" => "+06:00",
            "Asia/Omsk" => "+06:00",
            "Antarctica/Vostok" => "+06:00",
            "Indian/Chagos" => "+06:00",
            "Asia/Urumqi" => "+06:00",
            "Asia/Almaty" => "+06:00",
            "Asia/Qostanay" => "+06:00",
            "Indian/Cocos" => "+06:30",
            "Asia/Yangon" => "+06:30",
            "Antarctica/Davis" => "+07:00",
            "Asia/Tomsk" => "+07:00",
            "Asia/Vientiane" => "+07:00",
            "Asia/Barnaul" => "+07:00",
            "Asia/Krasnoyarsk" => "+07:00",
            "Asia/Pontianak" => "+07:00",
            "Asia/Ho_Chi_Minh" => "+07:00",
            "Asia/Hovd" => "+07:00",
            "Asia/Phnom_Penh" => "+07:00",
            "Asia/Jakarta" => "+07:00",
            "Indian/Christmas" => "+07:00",
            "Asia/Novosibirsk" => "+07:00",
            "Asia/Novokuznetsk" => "+07:00",
            "Asia/Bangkok" => "+07:00",
            "Antarctica/Casey" => "+08:00",
            "Asia/Shanghai" => "+08:00",
            "Asia/Brunei" => "+08:00",
            "Asia/Kuala_Lumpur" => "+08:00",
            "Australia/Perth" => "+08:00",
            "Asia/Manila" => "+08:00",
            "Asia/Ulaanbaatar" => "+08:00",
            "Asia/Macau" => "+08:00",
            "Asia/Kuching" => "+08:00",
            "Asia/Makassar" => "+08:00",
            "Asia/Taipei" => "+08:00",
            "Asia/Choibalsan" => "+08:00",
            "Asia/Irkutsk" => "+08:00",
            "Asia/Hong_Kong" => "+08:00",
            "Asia/Singapore" => "+08:00",
            "Australia/Eucla" => "+08:45",
            "Asia/Chita" => "+09:00",
            "Asia/Tokyo" => "+09:00",
            "Pacific/Palau" => "+09:00",
            "Asia/Khandyga" => "+09:00",
            "Asia/Yakutsk" => "+09:00",
            "Asia/Seoul" => "+09:00",
            "Asia/Dili" => "+09:00",
            "Asia/Jayapura" => "+09:00",
            "Asia/Pyongyang" => "+09:00",
            "Australia/Adelaide" => "+09:30",
            "Australia/Darwin" => "+09:30",
            "Australia/Broken_Hill" => "+09:30",
            "Pacific/Guam" => "+10:00",
            "Pacific/Port_Moresby" => "+10:00",
            "Antarctica/DumontDUrville" => "+10:00",
            "Pacific/Chuuk" => "+10:00",
            "Australia/Currie" => "+10:00",
            "Pacific/Saipan" => "+10:00",
            "Australia/Hobart" => "+10:00",
            "Australia/Sydney" => "+10:00",
            "Australia/Lindeman" => "+10:00",
            "Australia/Melbourne" => "+10:00",
            "Asia/Ust-Nera" => "+10:00",
            "Asia/Vladivostok" => "+10:00",
            "Australia/Brisbane" => "+10:00",
            "Australia/Lord_Howe" => "+10:30",
            "Pacific/Pohnpei" => "+11:00",
            "Asia/Srednekolymsk" => "+11:00",
            "Antarctica/Macquarie" => "+11:00",
            "Asia/Sakhalin" => "+11:00",
            "Pacific/Efate" => "+11:00",
            "Pacific/Bougainville" => "+11:00",
            "Asia/Magadan" => "+11:00",
            "Pacific/Kosrae" => "+11:00",
            "Pacific/Noumea" => "+11:00",
            "Pacific/Norfolk" => "+11:00",
            "Pacific/Guadalcanal" => "+11:00",
            "Pacific/Tarawa" => "+12:00",
            "Pacific/Wake" => "+12:00",
            "Pacific/Wallis" => "+12:00",
            "Pacific/Nauru" => "+12:00",
            "Pacific/Majuro" => "+12:00",
            "Pacific/Kwajalein" => "+12:00",
            "Pacific/Funafuti" => "+12:00",
            "Pacific/Fiji" => "+12:00",
            "Pacific/Auckland" => "+12:00",
            "Antarctica/McMurdo" => "+12:00",
            "Asia/Anadyr" => "+12:00",
            "Asia/Kamchatka" => "+12:00",
            "Pacific/Chatham" => "+12:45",
            "Pacific/Fakaofo" => "+13:00",
            "Pacific/Enderbury" => "+13:00",
            "Pacific/Apia" => "+13:00",
            "Pacific/Tongatapu" => "+13:00",
            "Pacific/Kiritimati" => "+14:00",
        ];
        $this->timezones = array_flip($timezones);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if ($this->setting->attendance_setting_set != 1) {
            \App::abort('403', 'Not Authorised');
        }
        $this->attendanceActive = 'active';

        // Creating date objects
        $today = Carbon::now();
        $yesterday = Carbon::yesterday();

        // Office start and end times
        /** @var Carbon $end_time */
        $end_time = $this->setting->getOfficeEndTime($today);

        /** @var Carbon $start_time */
        $start_time = $this->data["setting"]->getOfficeStartTime($today);

        // Yesterday office start time
        /** @var Carbon $yesterday_end_time */
        $yesterday_end_time = clone $this->setting->getOfficeEndTime($yesterday);
        $yesterday_end_time->subDay();

        /** @var Carbon $yesterday_start_time */
        $yesterday_start_time = clone $this->data["setting"]->getOfficeStartTime($yesterday);
        $yesterday_start_time->subDay();

        // Today and yesterday dates
        $dates = [$today->format("Y-m-d"), $yesterday->format("Y-m-d")];

        $today_attendance = Attendance::where('date', $dates[0])
            ->where('employee_id', '=', employee()->id)
            ->orderBy('date')
            ->first();

        $yesterday_attendance = Attendance::where('date', $dates[1])
            ->where('employee_id', '=', employee()->id)
            ->orderBy('date')
            ->first();

        $working_attendance = null;

        // If less than 6 hours have passed since yesterday's office end time,
        // allow clocking for yesterday

        if ($today->diffInHours($yesterday_end_time) <= 6) {
            $working_attendance = $yesterday_attendance;
            $working_end_time = $yesterday_end_time;
        } else {
            $working_attendance = $today_attendance;
            $working_end_time = $end_time;
        }

        // Check today's attendance
        if ($working_attendance != null) {
            if ($working_attendance->status == "absent") {
                $this->set_attendance = 3;
            } else {
                if ($working_attendance->clock_in != null) {
                    if ($working_attendance->clock_out != null) {
                        $this->set_attendance = 0;
                        $this->clock_set = 0;
                        $this->clock_in_time = $working_attendance->clock_in->timezone($this->setting->timezone);
                        $this->clock_out_time = $working_attendance->clock_out->timezone($this->setting->timezone);
                    } else {
                        $this->clock_set = 1;
                        $this->clock_in_time = $working_attendance->clock_in->timezone($this->setting->timezone);
                        $this->set_attendance = 1;
                    }
                } else {

                    $this->clock_set = 0;
                    $this->set_attendance = 1;

                }
                $this->notes = $working_attendance->notes;
                $this->working_from = $working_attendance->working_from;
            }
        } else {
            if ($today > $working_end_time) {
                // Cannot clock in after office hours
                $this->clock_set = 0;
                $this->set_attendance = 2;
            } else {
                $this->set_attendance = 1;
                $this->clock_set = 0;
                $this->notes = '';
                $this->working_from = '';
            }

        }

        $this->local_time = Carbon::now(new \DateTimeZone($this->setting->timezone));
        $this->ip_address = $_SERVER['REMOTE_ADDR'];

        return \View::make('front.attendance.index', $this->data);
    }

    /**
     * @return array
     */
    public function clockIn()
    {
        // Creating date objects
        $today = Carbon::now();
        $yesterday = Carbon::yesterday();

        // Yesterday office start time
        /** @var Carbon $yesterday_end_time */
        $yesterday_end_time = clone $this->setting->getOfficeEndTime($yesterday);
        $yesterday_end_time->subDay();

        /** @var Carbon $yesterday_start_time */
        $yesterday_start_time = clone $this->data["setting"]->getOfficeStartTime($yesterday);
        $yesterday_start_time->subDay();

        // Today and yesterday dates
        $dates = [$today->format("Y-m-d"), $yesterday->format("Y-m-d")];

        $today_attendance = Attendance::where('date', $dates[0])
            ->where('employee_id', '=', employee()->id)
            ->orderBy('date')
            ->first();

        $yesterday_attendance = Attendance::where('date', $dates[1])
            ->where('employee_id', '=', employee()->id)
            ->orderBy('date')
            ->first();

        $working_attendance = null;

        // If less than 6 hours have passed since yesterday's office end time,
        // allow clocking for yesterday

        if ($today->diffInHours($yesterday_end_time) <= 6) {
            $working_attendance = $yesterday_attendance;
            $working_day = $yesterday;
        } else {
            $working_attendance = $today_attendance;
            $working_day = $today;
        }

        $cur_time = $today->format('H:i:s');
        $time = Carbon::now()->timezone($this->timezones[$this->setting->timezone])->format('h:i A');
        $date_time = Carbon::now()->timezone($this->timezones[$this->setting->timezone])->format("Y-m-d H:i:s");

        // Check today's attendance
        if ($working_attendance != null) {
            if ($working_attendance->status == "absent") {
                return Reply::error( 'You have been marked absent for today');
            }
            $working_attendance->clock_in = $cur_time;
            $working_attendance->clock_in_ip_address = $_SERVER['REMOTE_ADDR'];
            $working_attendance->status = 'present';
            $working_attendance->notes = request()->get('notes');
            $working_attendance->working_from = request()->get('work_from');
            $working_attendance->office_start_time = $this->setting->office_start_time;
            $working_attendance->office_end_time = $this->setting->office_end_time;

            if ($this->setting->late_mark_after != null) {
                if ($working_attendance->clock_in->diffInMinutes($this->setting->getOfficeStartTime($working_day)) <
                    $this->setting->late_mark_after * -1) {
                    $working_attendance->is_late = 1;
                } else {
                    $working_attendance->is_late = 0;
                }
            }

            $working_attendance->save();
            return Reply::successWithData( 'You have successfully clocked in.',['time' => $time, 'timeDiff' => $working_day->diffForHumans(), 'time_date' => $date_time]);
        }
        $new_attendance = new Attendance();
        $new_attendance->employee_id = employee()->id;
        $new_attendance->date = $working_day->format("Y-m-d");
        $new_attendance->status = 'present';
        $new_attendance->clock_in = $cur_time;
        $new_attendance->clock_in_ip_address = $_SERVER['REMOTE_ADDR'];
        $new_attendance->notes = request()->get('notes');
        $new_attendance->working_from = request()->get('work_from');
        $new_attendance->office_start_time = $this->setting->office_start_time;
        $new_attendance->office_end_time = $this->setting->office_end_time;

        if ($this->setting->late_mark_after != null) {
            if ($new_attendance->clock_in->diffInMinutes($this->setting->getOfficeStartTime($working_day), false) <
                $this->setting->late_mark_after * -1) {
                $new_attendance->is_late = 1;
            } else {
                $new_attendance->is_late = 0;
            }
        }

        $new_attendance->save();
        return Reply::successWithData( 'You have successfully clocked in.',['time' => $time, 'timeDiff' => $working_day->diffForHumans(), 'time_date' => $date_time]);
    }

    function clockOut()
    {
        // Creating date objects
        $today = Carbon::now();
        $yesterday = Carbon::yesterday();

        // Yesterday office start time
        /** @var Carbon $yesterday_end_time */
        $yesterday_end_time = clone $this->setting->getOfficeEndTime($yesterday);
        $yesterday_end_time->subDay();

        /** @var Carbon $yesterday_start_time */
        $yesterday_start_time = clone $this->data["setting"]->getOfficeStartTime($yesterday);
        $yesterday_start_time->subDay();

        // Today and yesterday dates
        $dates = [$today->format("Y-m-d"), $yesterday->format("Y-m-d")];

        $today_attendance = Attendance::where('date', $dates[0])
            ->where('employee_id', '=', employee()->id)
            ->orderBy('date')
            ->first();

        $yesterday_attendance = Attendance::where('date', $dates[1])
            ->where('employee_id', '=', employee()->id)
            ->orderBy('date')
            ->first();

        $working_attendance = null;

        // If less than 6 hours have passed since yesterday's office end time,
        // allow clocking for yesterday

        if ($today->diffInHours($yesterday_end_time) <= 6) {
            $working_attendance = $yesterday_attendance;
        } else {
            $working_attendance = $today_attendance;
        }

        $cur_time = $today->format('H:i:s');

        // Check today's attendance
        if ($working_attendance != null) {
            if ($working_attendance->status == "absent") {
                return Reply::error('You have been marked absent for today');
            }
            if ($working_attendance->clock_in != null) {

                if ($working_attendance->clock_out != null) {
                    return Reply::error('Your attendance for today has already been marked');
                }
                $working_attendance->clock_out = $cur_time;
                $working_attendance->clock_out_ip_address = $_SERVER['REMOTE_ADDR'];
                $working_attendance->save();

                $clock_out = Carbon::now()->timezone($this->timezones[$this->setting->timezone]);

                return Reply::successWithData( 'Clock out time was set successfully', ['unset_time' => $clock_out->format("h:i A"), 'unset_time_diff' => $clock_out->diffForHumans(), 'date_time' => $clock_out->format('Y-m-d H:i:s')]);

            }

            return Reply::error('You have to clock in first');
        }
        return Reply::error('You have to clock in first');

    }

    public function ajax_attendance(Request $request)
    {
        $data_table = Attendance::select(DB::raw('(@cnt := if(@cnt IS NULL, 0,  @cnt) + 1) AS s_id'), 'date', 'status', 'clock_in', 'clock_out', "application_status")
            ->where('employee_id', '=', employee()->id);

        if ($request->has('from_date') && $request->has('to_date')) {
            $from_date = Carbon::parse(request()->get('from_date'))->format('Y-m-d');
            $to_date = Carbon::parse(request()->get('to_date'))->format('Y-m-d');

            $data_table = $data_table->whereBetween('date', [$from_date, $to_date]);
        } elseif ($request->has('from_date')) {
            $from_date = Carbon::parse(request()->get('from_date'))->format('Y-m-d');
            $data_table = $data_table->where('date', '>', $from_date);
        } elseif ($request->has('to_date')) {
            $to_date = Carbon::parse(request()->get('to_date'))->format('Y-m-d');
            $data_table = $data_table->where('date', '<', $to_date);
        }

        $data_table = $data_table->orderBy('date', 'desc')->get();

        return DataTables::of($data_table)->editColumn('clock_in', function ($row) {

            if ($row->clock_in == null) {
                return "-";
            }

            $clock_in = $row->clock_in->timezone($this->setting->timezone)->format('h:i A');

            return $clock_in;

        })->editColumn('clock_out', function ($row) {

            /** @var Carbon $clock_in */
            $clock_in = $row->clock_in;

            if ($clock_in == null || $row->status == "absent") {
                return "-";
            }

            /** @var Carbon $office_end_time */
            $office_end_time = $this->setting->getOfficeEndTime($row->date);

            /** @var Carbon $office_start_time */
            $office_start_time = $this->setting->getOfficeStartTime($row->date);

            /** @var Carbon $clock_out */
            $clock_out = $row->clock_out;

            $now = Carbon::now();

            if ($clock_in < $office_start_time) {
                $clock_in_time = $office_start_time;
            } else {
                $clock_in_time = $clock_in;
            }

            if ($office_start_time->diffInMinutes($clock_in, false) > 0) {
                $late_min = $office_start_time->diffInMinutes($clock_in);
            } else {
                $late_min = 0;
            }

            if ($row->clock_out == null) {
                if ($now > $office_end_time) {
                    $clock_out_time = $office_end_time;
                } else {
                    $clock_out_time = Carbon::now();
                }
            } elseif ($clock_out > $office_end_time) {
                $clock_out_time = $office_end_time;
            } else {
                $clock_out_time = $clock_out;
            }

            $clock_min = $clock_in_time->diffInMinutes($clock_out_time, false);
            $total_min = $office_start_time->diffInMinutes($office_end_time, false);

            $clock_min = ($clock_min >= 0) ? $clock_min : 0;
            $late_min = ($late_min >= 0) ? $late_min : 0;

            $clock_per = ($clock_min / $total_min) * 100;
            $late_per = ($late_min / $total_min) * 100;

            if ($row->clock_out != null) {
                $clock_out_display = Carbon::parse($row->clock_out)
                    ->timezone($this->timezones[$this->setting->timezone])
                    ->format('g:i A');
            } else {
                $clock_out_display = '-';
            }

            return '<div class="row" style="margin-right: 0px;margin-left: 0px;">
                            <div class="pull-left">
                                <label class="control-label">Clock In :</label> ' . Carbon::parse($row->clock_in)
                    ->timezone($this->timezones[$this->setting->timezone])
                    ->format('g:i A') . '
                            </div>
                            <div class="pull-right">
                                <label class="control-label">Clock Out :</label> ' . $clock_out_display . '
                            </div>
                            </div>
                            <div class="row" style="margin-right: 0px;margin-left: 0px;">
                            <div class="progress progress-u progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: ' . round($late_per) . '%">
                                    <span class="sr-only">' . round($late_per) . '% Complete</span>
                                </div>
                                <div class="progress-bar progress-bar-success" style="width: ' . round($clock_per) . '%">
                                    <span class="sr-only">' . round($clock_per) . '% Complete</span>
                                </div>
                            </div>
                            </div>
                        ';
        })->editColumn('date', function ($row) {

            $date = Carbon::parse($row->date)->timezone($this->setting->timezone)->format(' jS  F Y');

            return $date;
        })->editColumn('status', function ($row) {
            if ($row->clock_in == NULL) {
                $holiday = Holiday::where('date', '=', $row->date)
                    ->where('company_id', '=', $this->company_id)
                    ->first();
                if (!empty($holiday)) {
                    return '<span class="label label-info">Holiday</span>';
                } else if ($row->application_status != null) {
                    return "<span class=\"label label-warning\">On Leave</span>";
                } else {
                    return "<span class=\"label label-danger\">Absent</span>";
                }
            } else {
                return "<span class=\"label label-success\">" . ucfirst($row->status) . "</span>";
            }
        })->addColumn('Hours', function ($row) {

            $clock_in = $row->clock_in;

            if ($clock_in == null || $row->status == "absent") {
                return "-";
            }

            $office_end_time = $this->setting->getOfficeEndTime($row->date);

            /** @var Carbon $office_start_time */
            $office_start_time = $this->setting->getOfficeStartTime($row->date);
            $clock_out = $row->clock_out;

            $now = Carbon::now();

            /** @var Carbon $clock_in_time */
            $clock_in_time = $clock_in;

            if ($row->clock_out == null) {
                if ($now > $office_end_time) {
                    $clock_out_time = $office_end_time;
                } else {
                    $clock_out_time = Carbon::now();
                }
            } else {
                $clock_out_time = $clock_out;
            }

            $h = $clock_in_time->diffInHours($clock_out_time, false);
            $m = $clock_in_time->diffInMinutes($clock_out_time, false);

            $h = ($h < 0) ? 0 : $h;
            $m = ($m < 0) ? 0 : $m;

            $m = $m % 60;
            if ($h < 10) {
                $h = '0' . $h;
            }
            if ($m < 10) {
                $m = '0' . $m;
            }

            return $h . ":" . $m;
        })
            ->removeColumn('clock_in')
            ->rawColumns(['Hours','date','status','clock_out'])
            ->removeColumn('application_status')->make(true);
    }

    public function updateWorkFrom()
    {
        $date_obj = Carbon::now();
        $cur_date = $date_obj->format('Y-m-d');
        $fresh_attendance = Attendance::select('id', 'working_from')
            ->where('date', '=', $cur_date)
            ->where('employee_id', '=', employee()->id)
            ->first();
        if (empty($fresh_attendance)) {
            // Do not create new attendance if it does not exist
            //                $new_work               = new Attendance();
            //                $new_work->date         = $cur_date;
            //                $new_work->status       = 'present';
            //                $new_work->employee_id   = employee()->id;
            //                $new_work->working_from = request()->get('work_from');
            //                $new_work->save();

            return ['status' => 'success'];
        } else {
            $fresh_attendance->working_from = request()->get('work_from');
            $fresh_attendance->save();

            return ['status' => 'success'];
        }
    }

    public function updateNote()
    {
        $date_obj = Carbon::now();
        $cur_date = $date_obj->format('Y-m-d');
        $fresh_attendance = Attendance::select('id', 'notes')
            ->where('date', '=', $cur_date)
            ->where('employee_id', '=', employee()->id)
            ->first();
        if (empty($fresh_attendance)) {
            // Do not create new attendance if it does not exist
            //                $new_work             = new Attendance();
            //                $new_work->date       = $cur_date;
            //                $new_work->status     = 'present';
            //                $new_work->employee_id = employee()->id;
            //                $new_work->notes      = request()->get('notes');
            //                $new_work->save();

            return ['status' => 'success'];
        } else {
            $fresh_attendance->notes = request()->get('notes');
            $fresh_attendance->save();

            return ['status' => 'success'];
        }
    }

}
