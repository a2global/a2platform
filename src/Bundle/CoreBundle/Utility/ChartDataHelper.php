<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use Exception;
use DateTime;

class ChartDataHelper
{
    static array $COLORS = [
        [100, 100, 200, 0.9],
        [100, 200, 100, 0.9],
        [200, 100, 100, 0.9],
        [200, 100, 200, 0.9],
        [100, 200, 200, 0.9],
        [200, 200, 100, 0.9],
        [150, 100, 100, 0.9],
        [100, 150, 100, 0.9],
        [100, 100, 150, 0.9],
        [150, 150, 100, 0.9],
        [100, 150, 150, 0.9],
        [150, 100, 150, 0.9],
        [50, 100, 100, 0.9],
        [100, 50, 100, 0.9],
        [100, 100, 50, 0.9],
        [50, 50, 100, 0.9],
        [100, 50, 50, 0.9],
        [50, 100, 50, 0.9],
    ];

    public static function buildFromArray(array $data, DateTime $from, DateTime $to, $multiple = []): array
    {
        $barNames = [];

        // Get all columns list
        foreach ($data as $sectionName => $bars) {
            foreach ($bars as $barName => $barValue) {
                $barNames[$barName] = $barName;
            }
        }

        // Set zero values
        $date = $from;
        $filledWithZeroData = [];
        $daysTotal = $from->diff($to)->days;

        for ($i = 0; $i < $daysTotal; $i++) {
            $day = $date->format('y-m-d');

            foreach ($barNames as $barName) {
                $value = $data[$day][$barName] ?? 0;

                if ($multiple[$barName] ?? false && $value !== 0) {
                    $value *= (float)$multiple[$barName];
                }
                $filledWithZeroData[$day][$barName] = $value;
            }
            $date->modify('+1 day');
        }
        $data = $filledWithZeroData;

        $chartData = [
            'labels' => array_keys($data),
        ];
        $datasets = [];

        foreach ($data as $sectionData) {
            $i = 0;
            foreach ($sectionData as $barName => $barData) {
                $label = StringUtility::normalize($barName);

                if ($multiple[$barName] ?? false) {
                    switch ($multiple[$barName]) {
                        case 0.001:
                            $label .= ' ×1k';
                            break;
                        case 1000:
                            $label .= ' /1k';
                            break;
                        default:
                            throw new Exception('Unsupported multiplier');
                    }
                }
                $datasets[$barName]['label'] = $label;
                $datasets[$barName]['backgroundColor'] = 'rgba(' . implode(',', static::$COLORS[$i]) . ')';
                $datasets[$barName]['data'][] = $barData;
                $i++;
            }
        }
        $chartData['datasets'] = array_values($datasets);

        return $chartData;
    }
}