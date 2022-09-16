<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use Exception;

class ChartDataHelper
{
    static array $COLORS = [
        [100, 100, 200, 0.9],
        [100, 200, 100, 0.9],
        [200, 100, 100, 0.9],
        [200, 100, 200, 0.9],
        [100, 200, 200, 0.9],
        [200, 200, 100, 0.9],
    ];

    public static function buildFromArray(array $data, $multiple = []): array
    {
        $barNames = [];

        // Get all columns list
        foreach ($data as $sectionName => $bars) {
            foreach ($bars as $barName => $barValue) {
                $barNames[$barName] = $barName;
            }
        }

        // Set zero values
        foreach ($data as $sectionName => $bars) {
            foreach ($barNames as $barName) {
                $value = $data[$sectionName][$barName] ?? "0";

                if ($multiple[$barName] ?? false && $value !== 0) {
                    $value *= (float)$multiple[$barName];
                }
                $data[$sectionName][$barName] = $value;
            }
        }
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