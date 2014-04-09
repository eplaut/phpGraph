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

        $error = null;
        if (!isset($data[$labels[$i]]['open'])) {
            $error[] = 'open';
        }
        if (!isset($data[$labels[$i]]['close'])) {
            $error[] = 'close';
        }
        if (!isset($data[$labels[$i]]['max'])) {
            $error[] = 'max';
        }
        if (!isset($data[$labels[$i]]['min'])) {
            $error[] = 'min';
        }
        if ($error) {
            $return = "\t\t" . '<path id="chemin" d="M ' . (2 * $unitX + 50) . ' ' . $stepY . ' H ' . (($Xmax - $Xmin) * $unitX) . '" class="graph-line" stroke="transparent" fill="#fff" fill-opacity="0"/>' . "\n";
            $return .= "\t\t" . '<text><textPath xlink:href="#chemin">Error : "';
            foreach ($error as $key => $value) {
                $return .= $value . (count($error) > 1 ? ' ' : '');
            }
            $return .= '" missing</textPath></text>' . "\n";
            return $return;
        }
//        $options = array_merge($this->options, $options);

        extract($options);

        $return = '';
        if ($data[$labels[$i]]['close'] > $data[$labels[$i]]['open']) {
            $return .= "\n\t" . '<rect x="' . ($unitX * $data[$labels[$i]]['open'] + 50) . '" y="' . ($stepY - 10) . '" width="' . (($unitX * $data[$labels[$i]]['close']) - ($unitX * $data[$labels[$i]]['open'])) . '" height="20" class="graph-bar" fill="' . $stroke . '" fill-opacity="1"/>';
        }
        if ($data[$labels[$i]]['close'] == $data[$labels[$i]]['open']) {
            $return .= "\n\t" . '<path d="M' . ($unitX * $data[$labels[$i]]['open'] + 50 + 5) . ' ' . ($stepY) . ' l -5 -5, -5 5, 5 5 z" class="graph-line" stroke="' . $stroke . '" fill="' . $stroke . '" fill-opacity="1"/>';
        }
        // //Limit Up
        $return .= "\n\t" . '<path d="M' . ($unitX * $data[$labels[$i]]['max'] + 50) . ' ' . ($stepY) . '  L' . ($unitX * $data[$labels[$i]]['close'] + 50) . ' ' . ($stepY) . ' " class="graph-line" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
        $return .= '<use xlink:href="#plotLimit' . $id . '" transform="translate(' . ($unitX * $data[$labels[$i]]['max'] + 50) . ',' . ($stepY - 5) . ')"/>';
        // //Limit Down
        $return .= "\n\t" . '<path d="M' . ($unitX * $data[$labels[$i]]['min'] + 50) . ' ' . ($stepY) . '  L' . ($unitX * $data[$labels[$i]]['open'] + 50) . ' ' . ($stepY) . ' " class="graph-line" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
        $return .= '<use xlink:href="#plotLimit' . $id . '" transform="translate(' . ($unitX * $data[$labels[$i]]['min'] + 50) . ',' . ($stepY - 5) . ')"/>';
        if ($tooltips == true) {
            //Open
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($unitX * $data[$labels[$i]]['open'] + 50) . '" cy="' . $stepY . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">' . $data[$labels[$i]]['open'] . '</title>' . "\n\t\t" . '</g>';
            //Close
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($unitX * $data[$labels[$i]]['close'] + 50) . '" cy="' . $stepY . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">' . $data[$labels[$i]]['close'] . '</title>' . "\n\t\t" . '</g>';
            //Max
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($unitX * $data[$labels[$i]]['max'] + 50) . '" cy="' . $stepY . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">' . $data[$labels[$i]]['max'] . '</title>' . "\n\t\t" . '</g>';
            //Min
            $return .= "\n\t\t" . '<g class="graph-active">';
            $return .= "\n\t\t\t" . '<circle cx="' . ($unitX * $data[$labels[$i]]['min'] + 50) . '" cy="' . $stepY . '" r="1" stroke="' . $stroke . '" opacity="0" class="graph-point-active"/>';
            $return .= "\n\t" . '<title class="graph-tooltip">' . $data[$labels[$i]]['min'] . '</title>' . "\n\t\t" . '</g>';
        }
        return $return;
    }

}
