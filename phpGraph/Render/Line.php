<?php

class phpGraph_Render_Line extends phpGraph_Render {
    /**
     * To draw lines
     * @param $data array Unidimensionnal array
     * @param $height integer Height of grid
     * @param $HEIGHT integer Height of grid + title + padding top
     * @param $stepX integer Unit of x-axis
     * @param $unitY integer Unit of y-axis
     * @param $lenght integer Size of data array
     * @param $min integer Minimum value of data
     * @param $max integer Maximum value of data
     * @param $options array Options
     * @return string Path of lines (with options)
     *
     * @author Cyril MAGUIRE
     */
    static public function draw($data, $height, $HEIGHT, $stepX, $unitY, $lenght, $min, $max, $options) {
        $return = '';

        extract($options);

//        $this->colors[] = $options['stroke'];

        //Ligne
        $i = 0;
        $c = '';
        $t = '';
        $path = "\t\t" . '<path d="';
        foreach ($data as $label => $value) {

            //$min<0 or $min>=0
            $coordonneesCircle1 = 'cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT + $unitY * ($min - $value)) . '"';
            //$min>=0 
            $coordonneesCircle2 = 'cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT + $unitY * ($min - $value) - $value) . '"';
            //$min == $value
            $coordonneesCircle3 = 'cx="' . ($i * $stepX + 50) . '" cy="' . ($HEIGHT + $unitY * ($min - $value) - $value * $unitY) . '"';

            //$min<0 
            $coordonnees1 = ($i * $stepX + 50) . ' ' . ($HEIGHT + $unitY * ($min - $value));
            //$min>=0
            $coordonnees2 = ($i * $stepX + 50) . ' ' . ($HEIGHT + $unitY * ($min - $value) - $value);
            //$min == $value
            $coordonnees3 = ($i * $stepX + 50) . ' ' . ($HEIGHT + $unitY * ($min - $value) - $value * $unitY);

            //Tooltips
            if ($tooltips == true) {
                $c .= "\n\t\t" . '<g class="graph-active">';
            }
            //Ligne
            if ($value != $max) {
                if ($value == $min) {
                    if ($i == 0) {
                        if ($min <= 0) {
                            $path .= 'M ' . $coordonnees1 . ' L';
                            //Tooltips and circles
                            $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle1 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                        } else {
                            $path .= 'M ' . $coordonnees3 . ' L';
                            //Tooltips and circles
                            $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle3 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                        }
                    } else {
                        if ($min <= 0) {
                            $path .= "\n\t\t\t\t" . $coordonnees1;
                            //Tooltips and circles
                            $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle1 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                        } else {
                            $path .= "\n\t\t\t\t" . $coordonnees2;
                            //Tooltips and circles
                            $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle1 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                        }
                    }
                } else {
                    if ($i == 0) {
                        if ($min <= 0) {
                            $path .= 'M ' . $coordonnees1 . ' L';
                            //Tooltips and circles
                            $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle1 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                        } else {
                            $path .= 'M ' . $coordonnees2 . ' L';
                            //Tooltips and circles
                            $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle1 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                        }
                    } else {
                        if ($i != $lenght - 1) {
                            if ($min <= 0) {
                                $path .= "\n\t\t\t\t" . $coordonnees1;
                                //Tooltips and circles
                                $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle1 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                            } else {
                                $path .= "\n\t\t\t\t" . $coordonnees2;
                                //Tooltips and circles
                                $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle2 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                            }
                        } else {
                            if ($min <= 0) {
                                $path .= "\n\t\t\t\t" . $coordonnees1;
                                //Tooltips and circles
                                $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle1 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                            } else {
                                $path .= "\n\t\t\t\t" . $coordonnees2;
                                //Tooltips and circles
                                $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle1 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                            }
                        }
                    }
                }
            } else {
                //Line
                if ($i == 0) {
                    $path .= 'M ' . ($i * $stepX + 50) . ' ' . ($titleHeight + 2 * $paddingTop) . ' L';
                    //Tooltips and circles
                    $c .= "\n\t\t\t" . '<circle cx="' . ($i * $stepX + 50) . '" cy="' . ($titleHeight + 2 * $paddingTop) . '" r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                } else {
                    $path .= "\n\t\t\t\t" . $coordonnees1;
                    //Tooltips and circles
                    $c .= "\n\t\t\t" . '<circle ' . $coordonneesCircle1 . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                }
            }
            $i++;
            //End tooltips
            if ($tooltips == true) {
                $c .= "\n\t\t\t" . '<title class="graph-tooltip">' . (is_array($tooltipLegend) ? $tooltipLegend[$i] : $tooltipLegend) . $value . '</title>' . "\n\t\t" . '</g>';
            }
        }
        if ($opacity > 0.8 && $filled === true) {
            $tmp = $stroke;
            $stroke = '#a1a1a1';
        }
        //End of line
        $pathLine = '" class="graph-line" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>' . "\n";
        //Filling
        if ($filled === true) {
            if ($min <= 0) {
                $path .= "\n\t\t\t\t" . (($i - 1) * $stepX + 50) . ' ' . ($HEIGHT + ($unitY) * ($min - $value) + ($unitY * $value)) . ' 50 ' . ($HEIGHT + ($unitY) * ($min - $value) + ($unitY * $value)) . "\n\t\t\t\t";
            } else {
                $path .= "\n\t\t\t\t" . (($i - 1) * $stepX + 50) . ' ' . $HEIGHT . ' 50 ' . $HEIGHT . "\n\t\t\t\t";
            }
            if ($opacity > 0.8) {
                $stroke = $tmp;
            }
            $return .= $path . '" class="graph-fill" fill="' . $stroke . '" fill-opacity="' . $opacity . '"/>' . "\n";
        }
        //Display line
        $return .= $path . $pathLine;

        if ($circles == true) {
            $return .= "\t" . '<g class="graph-point">';
            $return .= $c;
            $return .= "\n\t" . '</g>' . "\n";
        }
        return $return;
    }

}
