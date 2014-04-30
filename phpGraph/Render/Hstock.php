<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class phpGraph_Render_Hstock {
    /**
     * To draw horizontal stock chart
     * @param $data array Array with structure equal to array('index'=> array('open'=>val,'close'=>val,'min'=>val,'max'=>val))
     * @param $HEIGHT integer Height of grid + title + padding top
     * @param $stepX integer Distance between two graduations on x-axis
     * @param $unitX integer Unit of x-axis
     * @param $unitY integer Unit of y-axis
     * @param $lenght integer Number of graduations on y-axis
     * @param $Xmin integer Minimum value of data
     * @param $Xmax integer Maximum value of data
     * @param $options array Options
     * @param $i integer index of current data
     * @param $labels array labels of y-axis
     * @param $id integer index of plotLimit
     * @return string Path of lines (with options)
     *
     * @author Cyril MAGUIRE
     */
    static public function draw($data, $HEIGHT, $stepX, $unitX, $unitY, $lenght, $Xmin, $Xmax, $options, $i, $labels, $id) {
        if ($i > 0) {
            $i--;
        }

        $stepY = $HEIGHT - ($unitY * ($i + 1));

        $errors = self::_validateInput($data, $i, $labels);
        if ($errors) {
            $return = "\t\t" . '<path id="chemin" d="M ' . (2 * $unitX + 50) . ' ' . $stepY . ' H ' . (($Xmax - $Xmin) * $unitX) . '" class="graph-line" stroke="transparent" fill="#fff" fill-opacity="0"/>' . "\n";
            $return .= "\t\t" . '<text><textPath xlink:href="#chemin">Error : "';
            $return .= implode(' ', $errors);
            $return .= '" missing</textPath></text>' . "\n";
            return $return;
        }

        extract($options);

        $openPrice = $data[$labels[$i]]['open'];
        $closePrice = $data[$labels[$i]]['close'];
        $minPrice = $data[$labels[$i]]['min'];
        $maxPrice = $data[$labels[$i]]['max'];
        $minOpenClosePrice = min($openPrice, $closePrice);
        $maxOpenClosePrice = max($openPrice, $closePrice);
        $return = '';
        if ($closePrice < $openPrice) {
            $direction = 'down';
        } elseif ($closePrice > $openPrice) {
            $direction = 'up';
        } 
        if ($closePrice == $openPrice) {
//            $return .= "\n\t" . '<path d="M' . ($i * $stepX + 50 + 5) . ' ' . ($HEIGHT - $unitY * $openPrice) . ' l -5 -5, -5 5, 5 5 z" class="graph-line" stroke="' . $stroke . '" fill="' . $stroke . '" fill-opacity="1"/>';
            $return .= "\n\t" . '<path d="M' . ($unitX * $openPrice + 50 + 5) . ' ' . ($stepY) . ' l -5 -5, -5 5, 5 5 z" class="graph-line" stroke="' . $stroke . '" fill="' . $stroke . '" fill-opacity="1"/>';
        } else {
            $return .= self::_addGradient($i, $stroke, $direction);
//            $return .= "\n\t" . '<rect x="' . ($i * $stepX + 50 - $stepX / 4) . '" y="' . ($HEIGHT - $unitY * $maxOpenClosePrice) . '" width="' . ($stepX / 2) . '" height="' . ($unitY * ($maxOpenClosePrice - $minOpenClosePrice)) . '" class="graph-bar" fill="url(#Gradient' . $i . ')" fill-opacity="1"/>';
            $return .= "\n\t" . '<rect x="' . ($unitX * $minOpenClosePrice + 50) . '" y="' . ($stepY - 10) . '" width="' . ($unitX * ($maxOpenClosePrice - $minOpenClosePrice)) . '" height="20" class="graph-bar" fill="url(#Gradient' . $i . ')" fill-opacity="1"/>';
        }
        // //Limit Up
        $return .= "\n\t" . '<path d="M' . ($unitX * $maxPrice + 50) . ' ' . ($stepY) . '  L' . ($unitX * $maxOpenClosePrice + 50) . ' ' . ($stepY) . ' " class="graph-line" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
        $return .= '<use xlink:href="#plotLimit' . $id . '" transform="translate(' . ($unitX * $maxPrice + 50) . ',' . ($stepY - 5) . ')"/>';
        // //Limit Down
        $return .= "\n\t" . '<path d="M' . ($unitX * $minPrice + 50) . ' ' . ($stepY) . '  L' . ($unitX * $minOpenClosePrice + 50) . ' ' . ($stepY) . ' " class="graph-line" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
        $return .= '<use xlink:href="#plotLimit' . $id . '" transform="translate(' . ($unitX * $minPrice + 50) . ',' . ($stepY - 5) . ')"/>';
        if ($tooltips == true) {
            //Open
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($unitX * $openPrice + 50) . '" cy="' . $stepY . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">Open: ' . $openPrice . '</title>' . "\n\t\t" . '</g>';
            //Close
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($unitX * $closePrice + 50) . '" cy="' . $stepY . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">Close: ' . $closePrice . '</title>' . "\n\t\t" . '</g>';
            //Max
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($unitX * $maxPrice + 50) . '" cy="' . $stepY . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">Max: ' . $maxPrice . '</title>' . "\n\t\t" . '</g>';
            //Min
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($unitX * $minPrice + 50) . '" cy="' . $stepY . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">Min: ' . $minPrice . '</title>' . "\n\t\t" . '</g>';
        }
        return $return;
    }

    static private function _validateInput($data, $i, $labels) {
        $errors = array();
        $keys = array('min', 'max', 'open', 'close');
        foreach ($keys as $key) {
            if (!isset($data[$labels[$i]][$key])) {
                $errors[] = $key;
            }
        }
        return $errors;
    }
    
    static private function _addGradient($id, $color, $direction) {
        $return = "\t<defs>";
        switch ($direction) {
            case 'up':
                $return .= "\n\t\t" . '<linearGradient id="Gradient' . $id . '" x1="0" x2="1" y1="0" y2="0">';
                break;
            case 'down':
                $return .= "\n\t\t" . '<linearGradient id="Gradient' . $id . '" x1="1" x2="0" y1="0" y2="0">';
                break;
        }
        $return .= '
                <stop offset="0%" stop-color="' . phpGraph_Color::getBrighterColor($color, 70) . '"/>
                <stop offset="100%" stop-color="' . $color . '"/>
            </linearGradient>';
        $return .= "\n\t</defs>";
        return $return;
    }
}
