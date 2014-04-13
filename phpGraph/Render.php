<?php

class phpGraph_Render {
    function render(array $data, array $options = array()) {
        $return = '';
        extract($options);

        phpGraph_Color::setBackgroundColor($background);

        if (isset($title)) {
            $options['titleHeight'] = $titleHeight = 40;
        }
        if ($opacity < 0 || $opacity > 1) {
            $options['opacity'] = 0.5;
        }

        $HEIGHT = $height + $titleHeight + $paddingTop;

        $heightLegends = 0;
        if (isset($legends) && !empty($legends)) {
            $heightLegends = count($legends) * 30 + 2 * $paddingTop;
        }

        $pie = '';

        if ($type != 'pie' && $type != 'ring') {
            $arrayOfMin = $arrayOfMax = $arrayOfLenght = $labels = array();
            $tmp['type'] = array();
            //For each diagrams with several lines/histograms
            foreach ($data as $line => $datas) {
                if ($type == 'stock' || (is_array($type) && in_array('stock', $type)) || $type == 'h-stock' || (is_array($type) && in_array('h-stock', $type))) {
                    $arrayOfMin[] = isset($datas['min']) ? floor($datas['min']) : 0;
                    $arrayOfMax[] = isset($datas['max']) ? ceil($datas['max']) : 0;
                    $arrayOfLenght[] = count($data);
                    $labels = array_merge(array_keys($data), $labels);
                    if (is_string($type)) {
                        $tmp['type'][$line] = $type;
                    }
                    $multi = true;
                } else {
                    if (is_array($datas)) {
                        $valuesMax = array_map('ceil', $datas);
                        $valuesMin = array_map('ceil', $datas);
                        $arrayOfMin[] = min($valuesMin);
                        $arrayOfMax[] = max($valuesMax);
                        $arrayOfLenght[] = count($datas);
                        $labels = array_merge(array_keys($datas), $labels);
                        if (is_string($type)) {
                            $tmp['type'][] = $type;
                        }
                        $multi = true;
                    } else {
                        $multi = false;
                    }
                }
            }
            if ($multi == true) {
                if (!empty($tmp['type'])) {
                    $type = $options['type'] = $tmp['type'];
                }
                unset($tmp);

                $labels = array_unique($labels);

                if ($type == 'h-stock' || (is_array($type) && in_array('h-stock', $type))) {
                    $min = 0;
                    $max = count($labels);
                    $Xmax = max($arrayOfMax);
                    $Xmin = min($arrayOfMin);
                    $lenght = $Xmax - $Xmin;
                } else {
                    $min = min($arrayOfMin);
                    $max = max($arrayOfMax);
                    $lenght = max($arrayOfLenght);
                }
                if ($type == 'stock' || (is_array($type) && in_array('stock', $type))) {
                    array_unshift($labels, '');
                    $labels[] = '';
                    $lenght += 2;
                }
            } else {
                $labels = array_keys($data);
                $lenght = count($data);
                $min = min($data);
                $max = max($data);
            }
            if ($type == 'h-stock' || (is_array($type) && in_array('h-stock', $type))) {

                $l = strlen(abs($Xmax)) - 1;
                if ($l == 0) {
                    $l = 1;
                    $XM = ceil($Xmax);
                    $stepX = 1;
                    $M = $lenght + 1;
                    $steps = 1;
                    if ($XM == 0) {
                        $XM = 1;
                    }
                    $unitX = $width / $XM;
                    $widthViewBox = $width + $XM + 50;
                } else {
                    $XM = ceil($Xmax / ($l * 10)) * ($l * 10);
                    $stepX = $l * 10;
                    $M = $lenght + 1;
                    $steps = 1;
                    if ($Xmin > 0 || ($Xmin < 0 && $Xmax < 0)) {
                        $Xmin = 0;
                    }
                    if ($XM == 0) {
                        $XM = 1;
                    }
                    $unitX = ($width / $XM);
                    $widthViewBox = $width + ($XM / $stepX) * $unitX;
                }
            } else {

                $l = strlen(abs($max)) - 1;
                if ($l == 0) {
                    $l = 1;
                    $M = ceil($max);
                    $steps = 1;
                } else {
                    $M = ceil($max / ($l * 10)) * ($l * 10);
                    $steps = $l * 10;
                }

                $max = $M;
                if (isset($options['steps']) && is_int($steps)) {
                    $steps = $options['steps'];
                }
                $stepX = $width / ($lenght - 1);
                $widthViewBox = $lenght * $stepX + $stepX;
            }

            $unitY = ($height / abs(($max + $steps) - $min));
            $gridV = $gridH = '';
            $x = $y = '';

            //Size of canevas will be bigger than grid size to display legends
            if ($responsive == true) {
                $return .= "\n" . '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xml:lang="fr" xmlns:xlink="http://www.w3/org/1999/xlink" class="graph" width="100%" height="100%" viewBox="0 0 ' . ($widthViewBox) . ' ' . ($HEIGHT + $heightLegends + $titleHeight + 2 * $paddingTop + $paddingLegendX) . '" preserveAspectRatio="xMidYMid meet">' . "\n";
            } else {
                $return .= "\n" . '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xml:lang="fr" xmlns:xlink="http://www.w3/org/1999/xlink" class="graph" width="' . ($lenght * $stepX + $stepX) . '" height="' . ($HEIGHT + $heightLegends + $titleHeight + 2 * $paddingTop) . '" viewBox="0 0 ' . ($widthViewBox) . ' ' . ($HEIGHT + $heightLegends + $titleHeight + 2 * $paddingTop + $paddingLegendX) . '" preserveAspectRatio="xMidYMid meet">' . "\n";
            }
            if ($type == 'stock' || (is_array($type) && in_array('stock', $type))) {
                $plotLimit = "\n\t" . '<defs>';
                $plotLimit .= "\n\t\t" . '<g id="plotLimit">';
                $plotLimit .= "\n\t\t\t" . '<path d="M 0 0 L 10 0" class="graph-line" stroke="" stroke-opacity="1"/>';
                $plotLimit .= "\n\t\t" . '</g>';
                $plotLimit .= "\n\t" . '</defs>' . "\n";
            }
            if ($type == 'h-stock' || (is_array($type) && in_array('h-stock', $type))) {
                $plotLimit = "\n\t" . '<defs>';
                $plotLimit .= "\n\t\t" . '<g id="plotLimit">';
                $plotLimit .= "\n\t\t\t" . '<path d="M 0 0 V 0 10" class="graph-line" stroke="" stroke-opacity="1"/>';
                $plotLimit .= "\n\t\t" . '</g>';
                $plotLimit .= "\n\t" . '</defs>' . "\n";
            }
            if (is_array($gradient)) {
                $id = 'BackgroundGradient' . rand();
                $return .= "\n\t" . '<defs>';
                $return .= "\n\t\t" . '<linearGradient id="' . $id . '">';
                $return .= "\n\t\t\t" . '<stop offset="5%" stop-color="' . $gradient[0] . '" />';
                $return .= "\n\t\t\t" . '<stop offset="95%" stop-color="' . $gradient[1] . '" />';
                $return .= "\n\t\t" . '</linearGradient>';
                $return .= "\n\t" . '</defs>' . "\n";
                $background = 'url(#' . $id . ')';
            }
            //Grid is beginning at 50 units from the left
            $return .= "\t" . '<rect x="50" y="' . ($paddingTop + $titleHeight) . '" width="' . $width . '" height="' . $height . '" class="graph-stroke" fill="' . $background . '" fill-opacity="1"/>' . "\n";
            if (isset($title)) {
                $return .= "\t" . '<title class="graph-tooltip">' . $title . '</title>' . "\n";
                $return .= "\t" . '<text x="' . (($width / 2) + 50) . '" y="' . $titleHeight . '" text-anchor="middle" class="graph-title">' . $title . '</text>' . "\n";
            }
            //Legends x axis
            $x .= "\t" . '<g class="graph-x">' . "\n";
            if (is_array($type) && in_array('h-stock', $type)) {
                for ($i = $Xmin; $i <= $XM; $i+=$stepX) {
                    //1 graduation every $steps units
                    $step = $unitX * $i;

                    $x .= "\t\t" . '<text x="' . (50 + $step) . '" y="' . ($HEIGHT + 2 * $paddingTop) . '" text-anchor="end" baseline-shift="-1ex" dominant-baseline="middle">' . $i . '</text>' . "\n";
                    //Vertical grid
                    if ($i != $Xmax) {
                        $gridV .= "\t\t" . '<path d="M ' . (50 + $step) . ' ' . ($paddingTop + $titleHeight) . ' V ' . ($HEIGHT) . '"/>' . "\n";
                    }
                }
            } else {
                $i = 0;
                foreach ($labels as $key => $label) {
                    //We add a gap of 50 units 
                    $x .= "\t\t" . '<text x="' . ($i * $stepX + 50) . '" y="' . ($HEIGHT + 2 * $paddingTop) . '" text-anchor="middle">' . $label . '</text>' . "\n";
                    //Vertical grid
                    if ($i != 0 && $i != $lenght) {
                        $gridV .= "\t\t" . '<path d="M ' . ($i * $stepX + 50) . ' ' . ($paddingTop + $titleHeight) . ' V ' . ($HEIGHT) . '"/>' . "\n";
                    }
                    $i++;
                }
            }
            $x .= "\t" . '</g>' . "\n";

            //Legendes y axis
            $y .= "\t" . '<g class="graph-y">' . "\n";
            if ($min > 0 || ($min < 0 && $max < 0)) {
                $min = 0;
            }
            for ($i = $min; $i <= ($max + $steps); $i+=$steps) {
                //1 graduation every $steps units
                if ($min < 0) {
                    $stepY = $HEIGHT + $unitY * ($min - $i);
                } else {
                    $stepY = $HEIGHT - ($unitY * $i);
                }

                if ($stepY >= ($titleHeight + $paddingTop + $paddingLegendX)) {
                    if (is_array($type) && in_array('h-stock', $type)) {
                        $y .= "\t\t" . '<g class="graph-active"><text x="40" y="' . $stepY . '" text-anchor="end" baseline-shift="-1ex" dominant-baseline="middle" >' . ($i > 0 ? (strlen($labels[$i - 1]) > 3 ? substr($labels[$i - 1], 0, 3) . '.</text><title>' . $labels[$i - 1] . '</title>' : $labels[$i - 1] . '</text>') : '</text>') . "</g>\n";
                    } else {
                        $y .= "\t\t" . '<text x="40" y="' . $stepY . '" text-anchor="end" baseline-shift="-1ex" dominant-baseline="middle" >' . $i . '</text>';
                    }
                    //Horizontal grid
                    $gridH .= "\t\t" . '<path d="M 50 ' . $stepY . ' H ' . ($width + 50) . '"/>' . "\n";
                }
            }
            $y .= "\t" . '</g>' . "\n";

            //Grid
            $return .= "\t" . '<g class="graph-grid">' . "\n";
            $return .= $gridH . "\n";
            $return .= $gridV;
            $return .= "\t" . '</g>' . "\n";

            $return .= $x;
            $return .= $y;
            if (!$multi) {
                $options['stroke'] = is_array($stroke) ? $stroke[0] : $stroke;
                switch ($type) {
                    case 'line':
                        $return .= phpGraph_Render_Line::draw($data, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options);
                        break;
                    case 'bar':
                        $return .= phpGraph_Render_Bar::draw($data, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options);
                        break;
                    case 'ring':
                    case 'pie':
                        if (is_array($stroke)) {
                            $options['stroke'] = $stroke;
                            $options['fill'] = $stroke;
                        }
                        if (is_array($legends)) {
                            $options['legends'] = $legends;
                        }
                        $pie .= phpGraph_Render_Disk::draw($data, $options);
                        $pie .= "\n" . '</svg>' . "\n";
                        break;
                    default:
                        $return .= phpGraph_Render_Line::draw($data, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options);
                        break;
                }
            } else {
                $i = 1;
                foreach ($data as $line => $datas) {
                    if (!isset($type[$line]) && !is_string($type) && is_numeric($line)) {
                        $type[$line] = 'line';
                    }
                    if (!isset($type[$line]) && !is_string($type) && !is_numeric($line)) {
                        $type[$line] = 'stock';
                    }
                    if (is_string($options['type'])) {
                        $type = array();
                        $type[$line] = $options['type'];
                    }
                    if (!isset($tooltipLegend[$line])) {
                        $options['tooltipLegend'] = '';
                    } else {
                        $options['tooltipLegend'] = $tooltipLegend[$line];
                    }
                    if (!isset($stroke[$line])) {
                        $stroke[$line] = phpGraph_Color::genColor();
                    }
                    $options['stroke'] = $STROKE = $stroke[$line];
                    $options['fill'] = $stroke[$line];
                    switch ($type[$line]) {
                        case 'line':
                            $return .= phpGraph_Render_Line::draw($datas, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options);
                            break;
                        case 'bar':
                            $return .= phpGraph_Render_Bar::draw($datas, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options);
                            break;
                        case 'stock':
                            $id = rand();
                            $return .= str_replace(array('id="plotLimit"', 'stroke=""'), array('id="plotLimit' . $id . '"', 'stroke="' . $stroke[$line] . '"'), $plotLimit);
                            $return .= phpGraph_Render_Stock::draw($data, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options, $i, $labels, $id);
                            $i++;
                            break;
                        case 'h-stock':
                            $id = rand();
                            $return .= str_replace(array('id="plotLimit"', 'stroke=""'), array('id="plotLimit' . $id . '"', 'stroke="' . $stroke[$line] . '"'), $plotLimit);
                            $return .= phpGraph_Render_Hstock::draw($data, $HEIGHT, $stepX, $unitX, $unitY, $lenght, $Xmin, $Xmax, $options, $i, $labels, $id);
                            $i++;
                            break;
                        case 'ring':
                            $options['subtype'] = 'ring';
                        case 'pie':
                            $options['multi'] = $multi;
                            if (is_array($stroke)) {
                                $options['stroke'] = $stroke;
                                $options['fill'] = $stroke;
                            }
                            if (is_array($legends)) {
                                $options['legends'] = $legends;
                            }
                            $pie .= phpGraph_Render_Disk::draw($datas, $options);
                            $pie .= "\n" . '</svg>' . "\n";
                            break;
                        default:
                            $return .= phpGraph_Render_Line::draw($datas, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options);
                            break;
                    }
                }
            }
            if (isset($legends) && !empty($legends)) {
                $leg = "\n\t" . '<g class="graph-legends">';
                if (!is_array($legends)) {
                    $legends = array(0 => $legends);
                }
                foreach ($legends as $key => $value) {
                    if (isset($type[$key]) && $type[$key] != 'pie' && $type[$key] != 'ring') {
                        if (is_array($stroke) && isset($stroke[$key])) {
                            $leg .= "\n\t\t" . '<rect x="50" y="' . ($HEIGHT + 30 + $key * (2 * $paddingTop)) . '" width="10" height="10" fill="' . $stroke[$key] . '" class="graph-legend-stroke"/>';
                        } else {
                            $leg .= "\n\t\t" . '<rect x="50" y="' . ($HEIGHT + 30 + $key * (2 * $paddingTop)) . '" width="10" height="10" fill="' . $stroke . '" class="graph-legend-stroke"/>';
                        }
                        $leg .= "\n\t\t" . '<text x="70" y="' . ($HEIGHT + 40 + $key * (2 * $paddingTop)) . '" text-anchor="start" class="graph-legend">' . $value . '</text>';
                    }
                    if (is_array($type) && (in_array('stock', $type) || in_array('h-stock', $type))) {
                        if (is_array($stroke)) {
                            $stroke = array_values($stroke);
                            if (isset($stroke[$key + 1])) {
                                $leg .= "\n\t\t" . '<rect x="50" y="' . ($HEIGHT + 30 + $key * (2 * $paddingTop)) . '" width="10" height="10" fill="' . $stroke[$key + 1] . '" class="graph-legend-stroke"/>';
                            }
                        }
                        $leg .= "\n\t\t" . '<text x="70" y="' . ($HEIGHT + 40 + $key * (2 * $paddingTop)) . '" text-anchor="start" class="graph-legend">' . $value . '</text>';
                    }
                }
                $leg .= "\n\t" . '</g>';
            } else {
                $leg = '';
            }
            $return .= $leg;
            $return .= "\n" . '</svg>' . "\n";
            $return .= $pie;
        } else {
            $options['tooltipLegend'] = array();
            if (isset($tooltipLegend) && !is_array($tooltipLegend)) {
                foreach ($data as $key => $value) {
                    $options['tooltipLegend'][] = $tooltipLegend;
                }
            }
            if (isset($tooltipLegend) && is_array($tooltipLegend)) {
                $options['tooltipLegend'] = $tooltipLegend;
            }
            $options['stroke'] = array();
            if (isset($stroke) && !is_array($stroke)) {
                foreach ($data as $key => $value) {
                    $options['stroke'][] = $stroke;
                }
            }
            if (isset($stroke) && is_array($stroke)) {
                $options['stroke'] = $stroke;
            }
            foreach ($data as $line => $datas) {
                if (is_array($datas)) {
                    if (is_array($stroke)) {
                        $options['stroke'] = $stroke;
                        $options['fill'] = $stroke;
                    }
                    if (is_array($legends)) {
                        $options['legends'] = $legends;
                    }
                    $return .= phpGraph_Render_Disk::draw($datas, $options);
                    $return .= "\n" . '</svg>' . "\n";
                    $multi = true;
                } else {
                    $multi = false;
                }
            }
            if (!$multi) {
                if (is_array($stroke)) {
                    $options['stroke'] = $stroke;
                    $options['fill'] = $stroke;
                }
                if (is_array($legends)) {
                    $options['legends'] = $legends;
                }
                $return .= phpGraph_Render_Disk::draw($data, $options);
                $return .= "\n" . '</svg>' . "\n";
            }
        }

        return $return;
    }
}
