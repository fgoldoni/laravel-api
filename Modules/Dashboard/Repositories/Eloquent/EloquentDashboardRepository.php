<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Dashboard\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Dashboard\Entities\Dashboard;
use Modules\Dashboard\Repositories\Contracts\DashboardRepository;

/**
 * Class EloquentDashboardRepository.
 */
class EloquentDashboardRepository extends RepositoryAbstract implements DashboardRepository
{
    public function model()
    {
        return Dashboard::class;
    }

    public function uniqueEventsDay(string $gaViewId, array $optParams, string $startDate, string $endDate)
    {
        return \Analytics::getAnalyticsService()->data_ga->get(
            'ga:' . $gaViewId,
            ('' === $startDate) ? '30daysAgo' : $startDate,
            ('' === $endDate) ? 'yesterday' : $endDate,
            'ga:uniqueEvents',
            $optParams
        )->rows;
    }

    public function uniqueEventsMonth(string $gaViewId, array $optParams, string $startDate, string $endDate)
    {
        return \Analytics::getAnalyticsService()->data_ga->get(
            'ga:' . $gaViewId,
            ('' === $startDate) ? '365daysAgo' : $startDate,
            ('' === $endDate) ? 'yesterday' : $endDate,
            'ga:uniqueEvents',
            $optParams
        )->rows;
    }

    public function wishesMonth($whitelabel, string $start, string $end)
    {
        $start = '' === $start ? date('Ymd', strtotime(date('Ymd') . '-12 months')) : $start;
        $end = '' === $end ? date('Ymd') : $end;

        $wishes = DB::table('wishes')
            ->select((DB::raw('DATE_FORMAT(wishes.created_at,"%Y%m%d") as date')), DB::raw('count(*) as nb_wishes'), (DB::raw('MONTH(wishes.created_at) as month')))
            ->having('date', '>=', $start)
            ->having('date', '<=', $end)
            ->where('whitelabel_id', '=', $whitelabel)
            ->groupBy('month')
            ->get()->toArray();

        if (!empty($wishes)) {
            foreach ($wishes as $key => $value) {
                $result['wishes'][$key][0] = $wishes[$key]->date;
                $result['wishes'][$key][1] = $wishes[$key]->nb_wishes;
            }
        } else {
            $result['wishes'] = [0, 0];
        }

        return $result['wishes'];
    }

    public function wishesDay($whitelabel, string $start, string $end)
    {
        $start = '' === $start ? date('Ymd', strtotime(date('Ymd') . '-1 months')) : $start;
        $end = '' === $end ? date('Ymd') : $end;

        $wishes = DB::table('wishes')
            ->select((DB::raw('DATE_FORMAT(wishes.created_at,"%Y%m%d") as date')), DB::raw('count(*) as nb_wishes'))
            ->having('date', '>=', $start)
            ->having('date', '<=', $end)
            ->where('whitelabel_id', '=', $whitelabel)
            ->groupBy('date')
            ->get()->toArray();

        if (!empty($wishes)) {
            foreach ($wishes as $key => $value) {
                $result['wishes'][$key][0] = $wishes[$key]->date;
                $result['wishes'][$key][1] = $wishes[$key]->nb_wishes;
            }
        } else {
            $result['wishes'] = [0, 0];
        }

        return $result['wishes'];
    }

    public function calculateBrowserData(array $result, array $browsers, int $sum)
    {
        foreach ($result['ga'] as $key => $value) {
            if (!\in_array($result['ga'][$key][0], $browsers, true)) {
                unset($result['ga'][$key]);
            }
        }
        $result['ga'] = array_values($result['ga']);
        foreach ($result['ga'] as $key => $value) {
            $sum = $sum + $result['ga'][$key][1];
        }
        foreach ($result['ga'] as $key => $value) {
            $result['ga'][$key][1] = round($result['ga'][$key][1] / $sum * 100, 1);
        }

        return $result['ga'];
    }

    public function calculateResponseData($result, $data, $stack)
    {
        $i = 0;
        $j = 0;
        foreach ($data as $k => $v) {
            list($year, $month, $day) = explode('-', $k);
            $stack[$k]['date'] = $year . $month;
            $stack[$k]['wish'] = $v;
        }

        $result['wishes'] = $stack;
        $result['data'] = $data;

        foreach ($result['ga'] as $key => $value) {
            foreach ($result['wishes'] as $kk => $vv) {
                if ($result['ga'][$key][0] === $result['wishes'][$kk]['date']) {
                    ++$i;
                    $j = 0;
                    $result['ga'][$key][1] = '0' === $result['ga'][$key][1] ? 0 : round(($result['wishes'][$kk]['wish'] / $result['ga'][$key][1]) * 100, 1);
                    break;
                }
                ++$j;
            }
            if (0 !== $j) {
                $result['ga'][$key][1] = 0;
            }
        }

        return $result['ga'];
    }

    public function loadClickRate($whitelabel, $start, $end)
    {
        $start = '' === $start ? date('Ymd') : $start;
        $end = '' === $end ? date('Ymd', strtotime($start . '+1 months')) : $end;

        $i = 0;
        $j = 0;
        $sent_emails = DB::table('sent_emails')
        ->select((DB::raw('DATE_FORMAT(sent_emails.created_at,"%Y%m%d") as date')), DB::raw('count(*) as nb_emails'))
        ->having('date', '>=', $start)
        ->having('date', '<=', $end)
        ->where('content', 'like', '%manual,' . $whitelabel . '%')
        ->groupBy('date')
        ->get()->toArray();

        $click_links = DB::table('sent_emails_url_clicked')
        ->join('sent_emails', 'sent_email_id', '=', 'sent_emails.id')
        ->select((DB::raw('DATE_FORMAT(sent_emails.created_at,"%Y%m%d") as date')), DB::raw('count(*) as clicks'))
        ->having('date', '>=', $start)
        ->having('date', '<=', $end)
        ->where('url', 'like', '%/wish/%')
        ->where('content', 'like', '%manual,' . $whitelabel . '%')
        ->where('sent_emails_url_clicked.clicks', '>=', 1)
        ->groupBy('date')
        ->get()->toArray();

        if (!empty($sent_emails)) {
            foreach ($sent_emails as $key => $value) {
                $result['sent'][$key][0] = $sent_emails[$key]->date;
                $result['sent'][$key][1] = $sent_emails[$key]->nb_emails;
            }
            if (!empty($click_links)) {
                foreach ($click_links as $key => $value) {
                    $result['click'][$key][0] = $click_links[$key]->date;
                    $result['click'][$key][1] = $click_links[$key]->clicks;
                }
            }
            $result['clickrate'] = $result['sent'];
            foreach ($result['clickrate'] as $key => $value) {
                $result['clickrate'][$key][0] = $result['sent'][$key][0];
                if (!empty($click_links) && '0' !== $result['clickrate'][$key][1]) {
                    foreach ($result['click'] as $k => $v) {
                        if ($result['clickrate'][$key][0] === $result['click'][$k][0]) {
                            ++$i;
                            $j = 0;
                            $result['clickrate'][$key][1] = round($result['click'][$k][1] / $result['clickrate'][$key][1] * 100, 1);
                            break;
                        }
                        ++$j;
                    }
                    if (0 !== $j) {
                        $result['clickrate'][$key][1] = 0;
                    }
                } else {
                    $result['clickrate'][$key][1] = 0;
                }
            }
        } else {
            $result['clickrate'] = [0, 0];
        }

        return $result['clickrate'];
    }

    public function loadClickRateauto($whitelabel, $start, $end)
    {
        $start = '' === $start ? date('Ymd') : $start;
        $end = '' === $end ? date('Ymd', strtotime($start . '+1 months')) : $end;

        $i = 0;
        $j = 0;
        $sent_emails = DB::table('sent_emails')
        ->select((DB::raw('DATE_FORMAT(sent_emails.created_at,"%Y%m%d") as date')), DB::raw('count(*) as nb_emails'))
        ->having('date', '>=', $start)
        ->having('date', '<=', $end)
        ->where('content', 'like', '%auto,' . $whitelabel . '%')
        ->groupBy('date')
        ->get()->toArray();

        $click_links = DB::table('sent_emails_url_clicked')
        ->join('sent_emails', 'sent_email_id', '=', 'sent_emails.id')
        ->select((DB::raw('DATE_FORMAT(sent_emails.created_at,"%Y%m%d") as date')), DB::raw('count(*) as clicks'))
        ->having('date', '>=', $start)
        ->having('date', '<=', $end)
        ->where('url', 'like', '%/wish/%')
        ->where('content', 'like', '%auto,' . $whitelabel . '%')
        ->where('sent_emails_url_clicked.clicks', '>=', 1)
        ->groupBy('date')
        ->get()->toArray();

        if (!empty($sent_emails)) {
            foreach ($sent_emails as $key => $value) {
                $result['sent'][$key][0] = $sent_emails[$key]->date;
                $result['sent'][$key][1] = $sent_emails[$key]->nb_emails;
            }
            if (!empty($click_links)) {
                foreach ($click_links as $key => $value) {
                    $result['click'][$key][0] = $click_links[$key]->date;
                    $result['click'][$key][1] = $click_links[$key]->clicks;
                }
            }
            $result['clickrate'] = $result['sent'];
            foreach ($result['clickrate'] as $key => $value) {
                $result['clickrate'][$key][0] = $result['sent'][$key][0];
                if (!empty($click_links) && '0' !== $result['clickrate'][$key][1]) {
                    foreach ($result['click'] as $k => $v) {
                        if ($result['clickrate'][$key][0] === $result['click'][$k][0]) {
                            ++$i;
                            $j = 0;
                            $result['clickrate'][$key][1] = round($result['click'][$k][1] / $result['clickrate'][$key][1] * 100, 1);
                            break;
                        }
                        ++$j;
                    }
                    if (0 !== $j) {
                        $result['clickrate'][$key][1] = 0;
                    }
                } else {
                    $result['clickrate'][$key][1] = 0;
                }
            }
        } else {
            $result['clickrate'] = [0, 0];
        }

        return $result['clickrate'];
    }

    public function loadOpenRate($whitelabel, $start, $end)
    {
        $start = '' === $start ? date('Ymd') : $start;
        $end = '' === $end ? date('Ymd', strtotime($start . '+1 months')) : $end;

        $i = 0;
        $j = 0;
        $open_emails = DB::table('sent_emails')
        ->select((DB::raw('DATE_FORMAT(sent_emails.created_at,"%Y%m%d") as date')), DB::raw('count(*) as nb_opens'))
        ->having('date', '>=', $start)
        ->having('date', '<=', $end)
        ->where('opens', '>=', 1)
        ->where('content', 'like', '%manual,' . $whitelabel . '%')
        ->groupBy('date')
        ->get()->toArray();

        $sent_emails = DB::table('sent_emails')
        ->select((DB::raw('DATE_FORMAT(sent_emails.created_at,"%Y%m%d") as date')), DB::raw('count(*) as nb_emails'))
        ->having('date', '>=', $start)
        ->having('date', '<=', $end)
        ->where('content', 'like', '%manual,' . $whitelabel . '%')
        ->groupBy('date')
        ->get()->toArray();

        if (!empty($sent_emails)) {
            foreach ($sent_emails as $key => $value) {
                $result['sent'][$key][0] = $sent_emails[$key]->date;
                $result['sent'][$key][1] = $sent_emails[$key]->nb_emails;
            }

            if (!empty($open_emails)) {
                foreach ($open_emails as $key => $value) {
                    $result['open'][$key][0] = $open_emails[$key]->date;
                    $result['open'][$key][1] = $open_emails[$key]->nb_opens;
                }
            }
            $result['openrate'] = $result['sent'];
            foreach ($result['openrate'] as $key => $value) {
                $result['openrate'][$key][0] = $result['sent'][$key][0];
                if (!empty($open_emails) && '0' !== $result['openrate'][$key][1]) {
                    foreach ($result['open'] as $k => $v) {
                        if ($result['openrate'][$key][0] === $result['open'][$k][0]) {
                            ++$i;
                            $j = 0;
                            $result['openrate'][$key][1] = round($result['open'][$k][1] / $result['openrate'][$key][1] * 100, 1);
                            break;
                        }
                        ++$j;
                    }
                    if (0 !== $j) {
                        $result['openrate'][$key][1] = 0;
                    }
                }
            }
        } else {
            $result['openrate'] = [0, 0];
        }

        return $result['openrate'];
    }

    public function loadOpenRateauto($whitelabel, $start, $end)
    {
        $start = '' === $start ? date('Ymd') : $start;
        $end = '' === $end ? date('Ymd', strtotime($start . '+1 months')) : $end;

        $i = 0;
        $j = 0;
        $open_emails = DB::table('sent_emails')
        ->select((DB::raw('DATE_FORMAT(sent_emails.created_at,"%Y%m%d") as date')), DB::raw('count(*) as nb_opens'))
        ->having('date', '>=', $start)
        ->having('date', '<=', $end)
        ->where('opens', '>=', 1)
        ->where('content', 'like', '%auto,' . $whitelabel . '%')
        ->groupBy('date')
        ->get()->toArray();

        $sent_emails = DB::table('sent_emails')
        ->select((DB::raw('DATE_FORMAT(sent_emails.created_at,"%Y%m%d") as date')), DB::raw('count(*) as nb_emails'))
        ->having('date', '>=', $start)
        ->having('date', '<=', $end)
        ->where('content', 'like', '%auto,' . $whitelabel . '%')
        ->groupBy('date')
        ->get()->toArray();

        if (!empty($sent_emails)) {
            foreach ($sent_emails as $key => $value) {
                $result['sent'][$key][0] = $sent_emails[$key]->date;
                $result['sent'][$key][1] = $sent_emails[$key]->nb_emails;
            }

            if (!empty($open_emails)) {
                foreach ($open_emails as $key => $value) {
                    $result['open'][$key][0] = $open_emails[$key]->date;
                    $result['open'][$key][1] = $open_emails[$key]->nb_opens;
                }
            }
            $result['openrate'] = $result['sent'];
            foreach ($result['openrate'] as $key => $value) {
                $result['openrate'][$key][0] = $result['sent'][$key][0];
                if (!empty($open_emails) && '0' !== $result['openrate'][$key][1]) {
                    foreach ($result['open'] as $k => $v) {
                        if ($result['openrate'][$key][0] === $result['open'][$k][0]) {
                            ++$i;
                            $j = 0;
                            $result['openrate'][$key][1] = round($result['open'][$k][1] / $result['openrate'][$key][1] * 100, 1);
                            break;
                        }
                        ++$j;
                    }
                    if (0 !== $j) {
                        $result['openrate'][$key][1] = 0;
                    }
                }
            }
        } else {
            $result['openrate'] = [0, 0];
        }

        return $result['openrate'];
    }

    public function getFilterCategory(string $category)
    {
        $shown = DB::table('filter_category')->where('name', $category)->value('shown');

        return $shown;
    }

    public function getFilterCategoryPosition(string $category)
    {
        $position = DB::table('filter_category')->where('name', $category)->value('position');

        return $position;
    }

    public function setFilterCategory(Request $request)
    {
        if (1 === $request->shown) {
            DB::table('filter_category')
            ->where('id', $request->id)
            ->update(['shown' => 1]);
        } else {
            DB::table('filter_category')
            ->where('id', $request->id)
            ->update(['shown' => 0]);
            $position = DB::table('filter_category')->where('id', $request->id)->value('position');
            if (0 !== $position) {
                DB::table('filter_category')
                ->where('position', '>', $position)
                ->decrement('position', 1);
                DB::table('filter_category')
                ->where('id', $request->id)
                ->update(['position' => 6]);
            }
        }
    }

    public function setFilterCategoryPosition($result, string $position, int $id1, int $id2)
    {
        if (\array_key_exists($id1, $result['dashboards']) && \array_key_exists($id2, $result['dashboards'])) {
            switch ($position) {
                case 1:
                    $result['dashboards'][$id1]->y = 2;
                    $result['dashboards'][$id2]->y = 2;
                    break;
                case 2:
                    $result['dashboards'][$id1]->y = 10;
                    $result['dashboards'][$id2]->y = 10;
                    break;
                case 3:
                    $result['dashboards'][$id1]->y = 18;
                    $result['dashboards'][$id2]->y = 18;
                    break;
                case 4:
                    $result['dashboards'][$id1]->y = 26;
                    $result['dashboards'][$id2]->y = 26;
                    break;
                case 5:
                    $result['dashboards'][$id1]->y = 34;
                    $result['dashboards'][$id2]->y = 34;
                    break;
                case 6:
                    $result['dashboards'][$id1]->y = 42;
                    $result['dashboards'][$id2]->y = 42;
                    break;
            }
        }
    }

    public function setFilterCategoryPositionById($dashboard)
    {
        switch ($dashboard['id']) {
            case 2:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 6]);
                }
                break;
            case 3:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 6]);
                }
                break;
            case 4:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 6]);
                }
                break;
            case 5:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 6)
                    ->update(['position' => 6]);
                }
                break;
            case 6:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 3)
                    ->update(['position' => 6]);
                }
                break;
            case 11:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 6]);
                }
                break;
            case 13:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 6]);
                }
                break;
            case 14:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 2)
                    ->update(['position' => 6]);
                }
                break;
            case 19:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 4)
                    ->update(['position' => 6]);
                }
                break;
            case 20:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 5)
                    ->update(['position' => 6]);
                }
                break;
            case 21:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 6]);
                }
                break;
            case 22:
                if (2 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 1]);
                } elseif (10 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 2]);
                } elseif (18 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 3]);
                } elseif (26 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 4]);
                } elseif (34 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 5]);
                } elseif (42 === $dashboard['y']) {
                    DB::table('filter_category')
                    ->where('id', 7)
                    ->update(['position' => 6]);
                }
                break;
        }
    }
}
