<?php
namespace Kunstmaan\DashboardBundle\Command\Helper\Analytics;


use Kunstmaan\DashboardBundle\Entity\AnalyticsOverview;

class ChartDataCommandHelper extends AbstractAnalyticsCommandHelper
{
    /**
     * get extra data
     *
     * @return array
     */
    protected function getExtra(AnalyticsOverview $overview) {
        $timespan = $overview->getTimespan() - $overview->getStartOffset();
        $extra = parent::getExtra($overview);

        if ($timespan <= 1) {
            $extra['dimensions'] = 'ga:date,ga:hour';
        } else if ($timespan <= 7) {
            $extra['dimensions'] = 'ga:date,ga:hour';
        } else if ($timespan <= 31) {
            $extra['dimensions'] = 'ga:week,ga:day,ga:date';
        } else {
            $extra['dimensions'] = 'ga:isoYearIsoWeek';
        }
        return $extra;
    }

    /**
     * get data and save it for the overview
     *
     * @param AnalyticsOverview $overview The overview
     */
    public function getData(&$overview)
    {
        $this->output->writeln("\t" . 'Fetching chart data..');

        // execute the query
        $metrics = 'ga:sessions, ga:users, ga:newUsers, ga:pageviews';
        $rows = $this->executeQuery($overview, $metrics);

        $chartData = array();
        $chartDataMaxValue = 0;
        $timespan = $overview->getTimespan() - $overview->getStartOffset();
        foreach ($rows as $row) {
            // metrics
            $sessions = $row[count($row) - 4];
            $users = $row[count($row) - 3];
            $newusers = $row[count($row) - 2];
            $pageviews = $row[count($row) - 1];

            $maxvalue = max($sessions, $users, $newusers, $pageviews);
            // set max chartdata value
            if ($chartDataMaxValue < $maxvalue) {
                $chartDataMaxValue = $maxvalue;
            }

            // timestamp
            $timestamp = $this->getTimeStamp($timespan, $row);

            // add to chart array
            $chartEntry = array(
                'timestamp' => $timestamp,
                'sessions' => $sessions,
                'users' => $users,
                'newusers' => $newusers,
                'pageviews' => $pageviews

            );
            $chartData[] = $chartEntry;
        }

        // adding data to the overview
        $overview->setChartDataMaxValue($chartDataMaxValue);
        $overview->setChartData(json_encode($chartData, JSON_NUMERIC_CHECK));
    }

}
