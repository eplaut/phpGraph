<?php

class phpGraph_Render_Bar {
    /**
     * To draw histograms
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

        //Bar
        $bar = '';
        $i = 0;
        $c = '';
        $t = '';
        foreach ($data as $label => $value) {

            //Tooltips and circles
            if ($tooltips == true) {
                $c .= "\n\t\t" . '<g class="graph-active">';
            }

            $stepY = $value * $unitY;

            //$min>=0
            $coordonnees1 = 'x="' . ($i * $stepX + 50) . '" y="' . ($HEIGHT + $unitY * ($min - $value)) . '"';
            //On recule d'un demi pas pour que la valeur de x soit au milieu de la barre de diagramme
            $coordonnees2 = 'x="' . ($i * $stepX + 50 - $stepX / 2) . '" y="' . ($HEIGHT - $stepY) . '"';
            //$min<0
            $coordonnees3 = 'x="' . ($i * $stepX + 50) . '" y="' . ($HEIGHT + $unitY * ($min - $value)) . '"';
            $coordonnees4 = 'x="' . ($i * $stepX + 50 - $stepX / 2) . '" y="' . ($HEIGHT + $unitY * ($min - $value)) . '"';
            //$min<0 et $value<0
            $coordonnees5 = 'x="' . ($i * $stepX + 50 - $stepX / 2) . '" y="' . ($HEIGHT + $unitY * ($min - $value) + $stepY) . '"';
            $coordonnees6 = 'x="' . ($i * $stepX + 50) . '" y="' . ($HEIGHT + $unitY * ($min - $value) + $stepY) . '"';
            //$min>=0 et $value == $max
            $coordonnees7 = 'x="' . ($i * $stepX + 50 - $stepX / 2) . '" y="' . ($HEIGHT - $stepY) . '"';
            $coordonnees8 = 'x="' . ($i * $stepX + 50) . '" y="' . ($paddingTop + $titleHeight) . '"';
            //$value == 0
            $coordonnees9 = 'x="50" y="' . ($HEIGHT + $unitY * $min) . '"';
            if ($value == 0) {
                $stepY = 1;
            }
            //Diagramme
            //On est sur la première valeur, on divise la largeur de la barre en deux
            if ($i == 0) {
                if ($value == $max) {
                    if ($min >= 0) {
                        $bar .= "\n\t" . '<rect ' . $coordonnees8 . ' width="' . ($stepX / 2) . '" height="' . $height . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                    } else {
                        $bar .= "\n\t" . '<rect ' . $coordonnees8 . ' width="' . ($stepX / 2) . '" height="' . $height . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                    }

                    $c .= "\n\t\t\t" . '<circle c' . str_replace('y="', 'cy="', $coordonnees8) . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                } else {
                    if ($min >= 0) {
                        $bar .= "\n\t" . '<rect ' . $coordonnees1 . ' width="' . ($stepX / 2) . '" height="' . $stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';

                        $c .= "\n\t\t\t" . '<circle c' . str_replace('y="', 'cy="', $coordonnees1) . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                    } else {
                        if ($value == $min) {
                            $bar .= "\n\t" . '<rect ' . $coordonnees6 . ' width="' . ($stepX / 2) . '" height="' . -$stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                        } else {
                            if ($value == 0) {
                                $bar .= "\n\t" . '<rect ' . $coordonnees9 . ' width="' . ($stepX / 2) . '" height="1" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            } else {
                                $bar .= "\n\t" . '<rect ' . $coordonnees3 . ' width="' . ($stepX / 2) . '" height="' . $stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            }
                        }

                        $c .= "\n\t\t\t" . '<circle c' . str_replace('y="', 'cy="', $coordonnees3) . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                    }
                }
            } else {
                if ($value == $max) {
                    if ($min >= 0) {
                        //Si on n'est pas sur la dernière valeur
                        if ($i != $lenght - 1) {
                            $bar .= "\n\t" . '<rect ' . $coordonnees2 . ' width="' . $stepX . '" height="' . $stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                        } else {
                            $bar .= "\n\t" . '<rect ' . $coordonnees7 . ' width="' . ($stepX / 2) . '" height="' . $height . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                        }
                        $c .= "\n\t\t\t" . '<circle c' . str_replace('y="', 'cy="', $coordonnees1) . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                    } else {
                        if ($value >= 0) {
                            //Si on n'est pas sur la dernière valeur
                            if ($i != $lenght - 1) {
                                $bar .= "\n\t" . '<rect ' . $coordonnees4 . ' width="' . $stepX . '" height="' . $stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            } else {
                                $bar .= "\n\t" . '<rect ' . $coordonnees4 . ' width="' . ($stepX / 2) . '" height="' . $stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            }
                        } else {
                            //Si on n'est pas sur la dernière valeur
                            if ($i != $lenght - 1) {
                                $bar .= "\n\t" . '<rect ' . $coordonnees5 . ' width="' . $stepX . '" height="' . -$stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            } else {
                                $bar .= "\n\t" . '<rect ' . $coordonnees5 . ' width="' . ($stepX / 2) . '" height="' . -$stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            }
                        }

                        $c .= "\n\t\t\t" . '<circle c' . str_replace('y="', 'cy="', $coordonnees3) . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                    }
                } else {
                    if ($min >= 0) {
                        //Si on n'est pas sur la dernière valeur
                        if ($i != $lenght - 1) {
                            $bar .= "\n\t" . '<rect ' . $coordonnees2 . ' width="' . $stepX . '" height="' . $stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                        } else {
                            $bar .= "\n\t" . '<rect ' . $coordonnees2 . ' width="' . ($stepX / 2) . '" height="' . $stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                        }

                        $c .= "\n\t\t\t" . '<circle c' . str_replace('y="', 'cy="', $coordonnees1) . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                    } else {
                        if ($value >= 0) {
                            //Si on n'est pas sur la dernière valeur
                            if ($i != $lenght - 1) {
                                $bar .= "\n\t" . '<rect ' . $coordonnees4 . ' width="' . $stepX . '" height="' . ($stepY) . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            } else {
                                $bar .= "\n\t" . '<rect ' . $coordonnees4 . ' width="' . ($stepX / 2) . '" height="' . $stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            }
                        } else {
                            //Si on n'est pas sur la dernière valeur
                            if ($i != $lenght - 1) {
                                $bar .= "\n\t" . '<rect ' . $coordonnees5 . ' width="' . $stepX . '" height="' . -$stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            } else {
                                $bar .= "\n\t" . '<rect ' . $coordonnees5 . ' width="' . ($stepX / 2) . '" height="' . -$stepY . '" class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>';
                            }
                        }

                        $c .= "\n\t\t\t" . '<circle c' . str_replace('y="', 'cy="', $coordonnees3) . ' r="3" stroke="' . $stroke . '" class="graph-point-active"/>';
                    }
                }
            }
            $i++;
            //End of tooltips
            if ($tooltips == true) {
                $c .= '<title class="graph-tooltip">' . (is_array($tooltipLegend) ? $tooltipLegend[$i] : $tooltipLegend) . $value . '</title>' . "\n\t\t" . '</g>';
            }
        }

        //Filling
        if ($filled === true) {
            if ($opacity == 1) {
                $opacity = '1" stroke="#424242';
            }
            $barFilled = str_replace(' class="graph-bar" stroke="' . $stroke . '" fill="#fff" fill-opacity="0"/>', ' class="graph-bar" fill="' . $stroke . '" fill-opacity="' . $opacity . '"/>', $bar);
            $return .= $barFilled;
        }

        $return .= $bar;

        if ($circles == true) {
            $return .= "\n\t" . '<g class="graph-point">';
            $return .= $c;
            $return .= "\n\t" . '</g>' . "\n";
        }
        return $return;
    }

}
