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
        $errors = self::_validateInput($data, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options, $i, $labels, $id);
        if (!empty($errors)) {
            $return = "\t\t" . '<path id="chemin" d="M ' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $height + 10) . ' V ' . $height . '" class="graph-line" stroke="transparent" fill="#fff" fill-opacity="0"/>' . "\n";
            $return .= "\t\t" . '<text><textPath xlink:href="#chemin">Error : "';
            $return .= implode(' ', $errors);
            $return .= '" missing</textPath></text>' . "\n";
            return $return;
        }

        extract($options);

        $return = '';
        if ($data[$labels[$i]]['close'] < $data[$labels[$i]]['open']) {
            $return .= "\n\t" . '<rect x="' . ($i * $stepX + 50 - $stepX / 4) . '" y="' . ($HEIGHT - $unitY * $data[$labels[$i]]['open']) . '" width="' . ($stepX / 2) . '" height="' . ($unitY * $data[$labels[$i]]['open'] - ($unitY * $data[$labels[$i]]['close'])) . '" class="graph-bar" fill="' . $stroke . '" fill-opacity="1"/>';
        }
        if ($data[$labels[$i]]['close'] == $data[$labels[$i]]['open']) {
            $return .= "\n\t" . '<path d="M' . ($i * $stepX + 50 + 5) . ' ' . ($HEIGHT - $unitY * $data[$labels[$i]]['open']) . ' l -5 -5, -5 5, 5 5 z" class="graph-line" stroke="' . $stroke . '" fill="' . $stroke . '" fill-opacity="1"/>';
        }
        //Limit Up
        $return .= "\n\t" . '<path d="M' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $unitY * $data[$labels[$i]]['close']) . '  L' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $unitY * $data[$labels[$i]]['max']) . ' " class="graph-line" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
        $return .= '<use xlink:href="#plotLimit' . $id . '" transform="translate(' . ($i * $stepX + 50 - 5) . ',' . ($HEIGHT - $unitY * $data[$labels[$i]]['max']) . ')"/>';
        //Limit Down
        $return .= "\n\t" . '<path d="M' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $unitY * $data[$labels[$i]]['open']) . '  L' . ($i * $stepX + 50) . ' ' . ($HEIGHT - $unitY * $data[$labels[$i]]['min']) . ' " class="graph-line" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
        $return .= '<use xlink:href="#plotLimit' . $id . '" transform="translate(' . ($i * $stepX + 50 - 5) . ',' . ($HEIGHT - $unitY * $data[$labels[$i]]['min']) . ')"/>';
        if ($tooltips == true) {
            //Open
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT - $unitY * $data[$labels[$i]]['open']) . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">' . $data[$labels[$i]]['open'] . '</title>' . "\n\t\t" . '</g>';
            //Close
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT - $unitY * $data[$labels[$i]]['close']) . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">' . $data[$labels[$i]]['close'] . '</title>' . "\n\t\t" . '</g>';
            //Max
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT - $unitY * $data[$labels[$i]]['max']) . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">' . $data[$labels[$i]]['max'] . '</title>' . "\n\t\t" . '</g>';
            //Min
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT - $unitY * $data[$labels[$i]]['min']) . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">' . $data[$labels[$i]]['min'] . '</title>' . "\n\t\t" . '</g>';
        }
        return $return;
    }

    static private function _validateInput($data, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options, $i, $labels, $id) {
        $errors = array();
        if (!isset($data[$labels[$i]]['open'])) {
            $errors[] = 'open';
        }
        if (!isset($data[$labels[$i]]['close'])) {
            $errors[] = 'close';
        }
        if (!isset($data[$labels[$i]]['max'])) {
            $errors[] = 'max';
        }
        if (!isset($data[$labels[$i]]['min'])) {
            $errors[] = 'min';
        }
        return $errors;
    }    
}
