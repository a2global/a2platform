<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

class ChartDataHelper
{
    static array $COLORS = [
        [100,100,200,0.9],
        [100,200,100,0.9],
        [200,100,100,0.9],
        [200,100,200,0.9],
        [100,200,200,0.9],
        [200,200,100,0.9],
    ];

    public static function buildFromArray(array $data): array
    {
        $chartData = [
            'labels' => array_keys($data),
        ];
        $datasets = [];

        foreach ($data as $sectionData) {
            $i = 0;
            foreach ($sectionData as $barName => $barData) {
                $datasets[$barName]['label'] = $barName;
                $datasets[$barName]['backgroundColor'] = 'rgba(' . implode(',', static::$COLORS[$i]) . ')';
                $datasets[$barName]['data'][] = $barData;
                $i++;
            }
        }
        $chartData['datasets'] = array_values($datasets);

        return $chartData;
    }
}