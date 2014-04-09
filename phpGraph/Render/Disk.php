<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class phpGraph_Render_Disk {
    /**
     * To draw pie diagrams
     * @param $data array Unidimensionnal array
     * @param $options array Options
     * @return string Path of lines (with options)
     *
     * @author Cyril MAGUIRE
     */
    static public function draw($data, $options = array()) {

//        $options = array_merge($this->options, $options);

        extract($options);

        $lenght = count($data);
        $max = max($data);

        $total = 0;
        foreach ($data as $label => $value) {
            if ($value < 0) {
                $value = 0;
            }
            $total += $value;
        }
        $deg = array();
        $i = 0;
        foreach ($data as $label => $value) {

            if ($value < 0) {
                $value = 0;
            }
            if ($total == 0) {
                $deg[] = array(
                    'pourcent' => 0,
                    'val' => $value,
                    'label' => $label,
                    'tooltipLegend' => (is_array($tooltipLegend) && isset($tooltipLegend[$i])) ? $tooltipLegend[$i] : (isset($tooltipLegend) ? $tooltipLegend : ''),
                    'stroke' => (is_array($stroke) && isset($stroke[$i])) ? $stroke[$i] : phpGraph_Color::genColor(),
                );
            } else {
                $deg[] = array(
                    'pourcent' => round(((($value * 100) / $total) / 100), 2),
                    'val' => $value,
                    'label' => $label,
                    'tooltipLegend' => (is_array($tooltipLegend) && isset($tooltipLegend[$i])) ? $tooltipLegend[$i] : (isset($tooltipLegend) ? $tooltipLegend : ''),
                    'stroke' => (is_array($stroke) && isset($stroke[$i]) ) ? $stroke[$i] : phpGraph_Color::genColor(),
                );
            }
            $i++;
        }
        if (isset($legends)) {
            if (!is_array($legends) && !empty($legends) && !is_bool($legends)) {
                $legends = array(
                    'label' => $legends,
                    'stroke' => (is_array($stroke) ) ? $stroke[0] : phpGraph_Color::genColor()
                );
            } elseif (empty($legends)) {
                $notDisplayLegends = true;
            } elseif (is_bool($legends)) {
                $legends = array();
            }
            foreach ($deg as $k => $v) {
                if (!isset($legends[$k])) {
                    $legends[$k] = array(
                        'label' => $v['label'],
                        'stroke' => (is_array($stroke) && isset($stroke[$k]) ) ? $stroke[$k] : $v['stroke']
                    );
                } else {
                    $legends[$k] = array(
                        'label' => (isset($multi) ? $v['label'] : $legends[$k]),
                        'stroke' => $v['stroke']
                    );
                }
            }
        }
        $deg = array_reverse($deg);

        $heightLegends = 0;
        if (isset($legends) && !empty($legends)) {
            $heightLegends = count($legends) * 30 + 2 * $paddingTop;
        }

        phpGraph_Color::setColors($options['stroke']);

        $originX = (2 * $radius + 400) / 2;
        $originY = 10 + $titleHeight + 2 * $paddingTop;


        //Size of canevas will be bigger than grid size to display legends
        $return = "\n" . '<svg width="100%" height="100%" viewBox="0 0 ' . (2 * $radius + 400) . ' ' . (2 * $radius + 100 + $titleHeight + $paddingTop + $heightLegends) . '" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" version="1.1">' . "\n";
        $return .= "\n\t" . '<defs>';
        $return .= "\n\t\t" . '<marker id="Triangle"';
        $return .= "\n\t\t\t" . 'viewBox="0 0 10 10" refX="0" refY="5"';
        $return .= "\n\t\t\t" . 'markerUnits="strokeWidth"';
        $return .= "\n\t\t\t" . 'markerWidth="4" markerHeight="3"';
        $return .= "\n\t\t\t" . 'fill="#a1a1a1" fill-opacity="0.7"';
        $return .= "\n\t\t\t" . 'orient="auto">';
        $return .= "\n\t\t\t" . '<path d="M 0 0 L 10 5 L 0 10 z" />';
        $return .= "\n\t\t" . '</marker>';
        if (is_array($gradient)) {
            $id = 'BackgroundGradient' . rand();
            $return .= "\n\t\t" . '<linearGradient id="' . $id . '">';
            $return .= "\n\t\t\t" . '<stop offset="5%" stop-color="' . $gradient[0] . '" />';
            $return .= "\n\t\t\t" . '<stop offset="95%" stop-color="' . $gradient[1] . '" />';
            $return .= "\n\t\t" . '</linearGradient>';
            $return .= "\n\t" . '</defs>' . "\n";
            $background = 'url(#' . $id . ')';
            $return .= "\t" . '<rect x="0" y="0" width="' . (2 * $radius + 400) . '" height="' . (2 * $radius + 100 + $titleHeight + $paddingTop + $heightLegends) . '" class="graph-stroke" fill="' . $background . '" fill-opacity="1"/>' . "\n";
        } else {
            $return .= "\n\t" . '</defs>' . "\n";
        }

        if (isset($title)) {
            $return .= "\t" . '<text x="' . ($originX) . '" y="' . $titleHeight . '" text-anchor="middle" class="graph-title">' . $title . '</text>' . "\n";
        }

        $ox = $prevOriginX = $originX;
        $oy = $prevOriginY = $originY;
        $total = 1;

        $i = 0;
        while ($i <= $lenght - 1) {
            if ($deg[$i]['val'] != 0) {
                //Tooltips
                if ($tooltips == true) {
                    $return .= "\n\t\t" . '<g class="graph-active">';
                }
                $color = phpGraph_Color::genColor();
                $return .= "\n\t\t\t" . '<circle cx="' . $originX . '" cy="' . ($originY + 2 * $radius) . '" r="' . $radius . '" fill="' . $color . '" class="graph-pie"/>' . "\n\t\t\t";
                if (isset($legends) && !empty($legends)) {
                    $tmp = $legends[$i]['label'];
                    $legends[phpGraph_Utilities::recursive_array_search($deg[$i]['label'], $legends)]['label'] = $tmp;
                    $legends[$i]['stroke'] = $color;
                    $legends[$i]['label'] = $deg[$i]['label'];
                }

                $return .= "\n\t\t\t" . '<path d=" M ' . $originX . ' ' . ($originY + 2 * $radius) . ' L ' . $originX . ' ' . ($originY + 10) . '" class="graph-line" stroke="darkgrey" stroke-opacity="0.5" stroke-dasharray="2,2,2" marker-end="url(#Triangle)"/>';

                $return .= "\n\t\t\t" . '<text x="' . $originX . '" y="' . $originY . '" class="graph-legend" stroke="darkgrey" stroke-opacity="0.5">' . ($diskLegendsType == 'label' ? (isset($legends[$i]['label']) ? $legends[$i]['label'] : $deg[$i]['label']) : ($diskLegendsType == 'pourcent' ? ($deg[$i]['pourcent'] * 100) . '%' : $deg[$i]['val'])) . '</text>' . "\n\t\t\t";

                //End tooltips
                if ($tooltips == true) {
                    $return .= '<title class="graph-tooltip">' . $deg[$i]['tooltipLegend'] . (isset($legends[$i]['label']) ? $legends[$i]['label'] : $deg[$i]['label']) . ' : ' . $deg[$i]['val'] . '</title>';
                    $return .= "\n\t\t" . '</g>';
                }
                $i = $deg[$i]['label'];
                break;
            }
            $i++;
        }
        $tmp = array();
        foreach ($legends as &$ma)
            $tmp[] = &$ma['label'];
        array_multisort($tmp, $legends);

        foreach ($deg as $key => $value) {

            $total -= $value['pourcent'];

            $cos = cos((-90 + 360 * $total) * M_PI / 180) * $radius;
            $sin = sin((-90 + 360 * $total) * M_PI / 180) * $radius;

            $cosLeg = cos((-90 + 360 * $total) * M_PI / 180) * (2 * $radius);
            $sinLeg = sin((-90 + 360 * $total) * M_PI / 180) * (2 * $radius);

            //Tooltips
            if ($tooltips == true && $key < ($lenght - 1)) {
                $return .= "\n\t\t" . '<g class="graph-active">';
            }

            if ($total >= 0 && $total <= 0.25 || $total == 1) {
                $arc = 0;
                $gap = ($radius / 4);
                $gapTextX = ($radius / 4) - 10;
                $gapTextY = ($radius / 4) - 10;
            }
            if ($total > 0.25 && $total <= 0.5) {
                $arc = 0;
                $gap = -($radius / 4);
                $gapTextX = ($radius / 8);
                $gapTextY = -($radius / 4);
            }
            if ($total > 0.5 && $total < 0.75) {
                $arc = 1;
                $gap = -($radius / 4);
                $gapTextX = -($radius / 8) - 20;
                $gapTextY = ($radius / 8) - 20;
            }
            if ($total > 0.75 && $total < 1) {
                $arc = 1;
                $gap = ($radius / 4);
                $gapTextX = -($radius / 4);
                $gapTextY = ($radius / 4) - 10;
            }

            $return .= "\n\t\t\t" . '<path d="M ' . $originX . ' ' . ($originY + $radius) . '  A ' . $radius . ' ' . $radius . '  0 ' . $arc . ' 1 ' . ($originX + $cos) . ' ' . ($originY + 2 * $radius + $sin) . ' L ' . $originX . ' ' . ($originY + 2 * $radius) . ' z" fill="' . ($key < ($lenght - 1) ? $deg[$key + 1]['stroke'] : $legends[0]['stroke']) . '" class="graph-pie"/>' . "\n\t\t\t";

            if ($key < ($lenght - 1) && $deg[$key + 1]['val'] != 0 && $diskLegends == true && $deg[$key + 1]['label'] != $i) {
                $return .= "\n\t\t\t" . '<path d=" M ' . ($originX + $cos) . ' ' . ($originY + 2 * $radius + $sin) . ' L ' . ($originX + $cosLeg) . ' ' . ($originY + 2 * $radius + $sinLeg + $gap) . '" class="graph-line" stroke="darkgrey" stroke-opacity="0.5"  stroke-dasharray="2,2,2" marker-end="url(#Triangle)"/>';

                $return .= "\n\t\t\t" . '<text x="' . ($originX + $cosLeg + $gapTextX) . '" y="' . ($originY + 2 * $radius + $sinLeg + $gapTextY) . '" class="graph-legend" stroke="darkgrey" stroke-opacity="0.5">' . ($diskLegendsType == 'label' ? (isset($legends[$lenght - $key - 2]['label']) ? $legends[$lenght - $key - 2]['label'] : $deg[$key + 1]['label']) : ($diskLegendsType == 'pourcent' ? ($deg[$key + 1]['pourcent'] * 100) . '%' : $deg[$key + 1]['val'])) . '</text>' . "\n\t\t\t";
            }
            //End tooltips
            if ($tooltips == true && $key < ($lenght - 1)) {
                $return .= '<title class="graph-tooltip">' . $deg[$key + 1]['tooltipLegend'] . (isset($legends[$lenght - $key - 2]['label']) ? $legends[$lenght - $key - 2]['label'] : $deg[$key + 1]['label']) . ' : ' . $deg[$key + 1]['val'] . '</title>' . "\n\t\t" . '</g>';
            }
        }

        if (isset($legends) && !empty($legends) && !isset($notDisplayLegends)) {
            $leg = "\t" . '<g class="graph-legends">';
            foreach ($legends as $key => $value) {
                $leg .= "\n\t\t" . '<rect x="50" y="' . (4 * $radius + $titleHeight + $paddingTop + 30 + $key * (2 * $paddingTop)) . '" width="10" height="10" fill="' . ((is_array($stroke) && isset($stroke[$key]) ) ? $stroke[$key] : $value['stroke']) . '" class="graph-legend-stroke"/>';
                $leg .= "\n\t\t" . '<text x="70" y="' . (4 * $radius + $titleHeight + $paddingTop + 40 + $key * (2 * $paddingTop)) . '" text-anchor="start" class="graph-legend">' . $value['label'] . '</text>';
            }
            $leg .= "\n\t" . '</g>';

            $return .= $leg;
        }
        if ($type == 'ring' || isset($subtype)) {
            $return .= '<circle cx="' . $originX . '" cy="' . ($originY + 2 * $radius) . '" r="' . ($radius / 2) . '" fill="' . $background . '" class="graph-pie"/>';
        }

        return $return;
    }
}
