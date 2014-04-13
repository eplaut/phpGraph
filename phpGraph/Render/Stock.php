<?php

class phpGraph_Render_Stock {
    
    /**
     * To draw vertical stock chart
     * @param $data array Array with structure equal to array('index'=> array('open'=>val,'close'=>val,'min'=>val,'max'=>val))
     * @param $height integer Height of grid
     * @param $HEIGHT integer Height of grid + title + padding top
     * @param $stepX integer Distance between two graduations on x-axis
     * @param $unitY integer Unit of y-axis
     * @param $lenght integer Number of graduations on x-axis
     * @param $min integer Minimum value of data
     * @param $max integer Maximum value of data
     * @param $options array Options
     * @param $i integer index of current data
     * @param $labels array labels of x-axis
     * @param $id integer index of plotLimit
     * @return string Path of lines (with options)
     *
     * @author Cyril MAGUIRE
     */
    static public function draw($data, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options, $i, $labels, $id) {
        $errors = self::_validateInput($data, $i, $labels);
        if (!empty($errors)) {
            $return = "\t\t" . '<path id="chemin" d="M ' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $height + 10) . ' V ' . $height . '" class="graph-line" stroke="transparent" fill="#fff" fill-opacity="0"/>' . "\n";
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
            $return .= "\n\t" . '<path d="M' . ($i * $stepX + 50 + 5) . ' ' . ($HEIGHT - $unitY * $openPrice) . ' l -5 -5, -5 5, 5 5 z" class="graph-line" stroke="' . $stroke . '" fill="' . $stroke . '" fill-opacity="1"/>';
        } else {
            $return .= self::_addGradient($i, $stroke, $direction);
            $return .= "\n\t" . '<rect x="' . ($i * $stepX + 50 - $stepX / 4) . '" y="' . ($HEIGHT - $unitY * $maxOpenClosePrice) . '" width="' . ($stepX / 2) . '" height="' . ($unitY * ($maxOpenClosePrice - $minOpenClosePrice)) . '" class="graph-bar" fill="url(#Gradient' . $i . ')" fill-opacity="1"/>';
        }
        //Limit Up
        $return .= "\n\t" . '<path d="M' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $unitY * $maxOpenClosePrice) . '  L' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $unitY * $maxPrice) . ' " class="graph-line" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
        $return .= '<use xlink:href="#plotLimit' . $id . '" transform="translate(' . ($i * $stepX + 50 - 5) . ',' . ($HEIGHT - $unitY * $maxPrice) . ')"/>';
        //Limit Down
        $return .= "\n\t" . '<path d="M' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $unitY * $minOpenClosePrice) . '  L' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $unitY * $minPrice) . ' " class="graph-line" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
        $return .= '<use xlink:href="#plotLimit' . $id . '" transform="translate(' . ($i * $stepX + 50 - 5) . ',' . ($HEIGHT - $unitY * $minPrice) . ')"/>';
        if ($tooltips == true) {
            //Open
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT - $unitY * $openPrice) . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t\t\t" . '<title class="graph-tooltip">' . $openPrice . '</title>' . "\n\t\t" . '</g>';
            //Close
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT - $unitY * $closePrice) . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t\t\t" . '<title class="graph-tooltip">' . $closePrice . '</title>' . "\n\t\t" . '</g>';
            //Max
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT - $unitY * $maxPrice) . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t\t\t" . '<title class="graph-tooltip">' . $maxPrice . '</title>' . "\n\t\t" . '</g>';
            //Min
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT - $unitY * $minPrice) . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t\t\t" . '<title class="graph-tooltip">' . $minPrice . '</title>' . "\n\t\t" . '</g>';
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
                $return .= "\n\t\t" . '<linearGradient id="Gradient' . $id . '" x1="0" x2="0" y1="1" y2="0">';
                break;
            case 'down':
                $return .= "\n\t\t" . '<linearGradient id="Gradient' . $id . '" x1="0" x2="0" y1="0" y2="1">';
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
