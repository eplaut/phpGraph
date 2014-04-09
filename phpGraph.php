<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


# ------------------ BEGIN LICENSE BLOCK ------------------
#     ___________________________________________________                  
#    |                                                  |
#    |                  PHP GRAPH       ____            |
#    |                                 |    |           |
#    |                        ____     |    |           |
#    |               /\      |    |    |    |           |
#    |             /   \     |    |    |    |           |
#    |      /\   /      \    |    |____|    |           |
#    |    /   \/         \   |    |    |    |           |
#    |  /                 \  |    |    |    |           |
#    |/____________________\_|____|____|____|___________|
#
# @update     2013-12-27
# @copyright  2013 Cyril MAGUIRE
# @licence    http://www.cecill.info/licences/Licence_CeCILL_V2.1-fr.txt CONTRAT DE LICENCE DE LOGICIEL LIBRE CeCILL version 2.1
# @link       http://jerrywham.github.io/phpGraph/
# @version    1.1
#
# ------------------- END LICENSE BLOCK -------------------

/** PHPExcel root directory */
if (!defined('PHPGRAPH_ROOT')) {
    define('PHPGRAPH_ROOT', dirname(__FILE__) . '/');
    require_once(PHPGRAPH_ROOT . 'phpGraph/Autoloader.php');
}

class phpGraph {

    public $options = array(
        'responsive' => true,
        'width' => null, // (int) width of grid
        'height' => null, // (int) height of grid
        'paddingTop' => 10, // (int)
        'type' => 'line', // (string) line, bar, pie, ring, stock or h-stock (todo curve)
        'steps' => null, // (int) 2 graduations on y-axis are separated by $steps units. "steps" is automatically calculated but we can set the value with integer. No effect on stock and h-stock charts
        'filled' => true, // (bool) to fill lines/histograms/disks
        'tooltips' => false, // (bool) to show tooltips
        'circles' => true, // (bool) to show circles on graph (lines or histograms). No effect on stock and h-stock charts
        'stroke' => '#3cc5f1', // (string) color of lines by default. Use an array to personalize each line
        'background' => "#ffffff", // (string) color of grid background. Don't use short notation (#fff) because of phpGraph_Color::genColor();
        'opacity' => '0.5', // (float) between 0 and 1. No effect on stock and h-stock charts
        'gradient' => null, // (array) 2 colors from left to right
        'titleHeight' => 0, // (int) Height of main title
        'tooltipLegend' => '', // (string or array) Text display in tooltip with y value. Each text can be personalized using an array. No effect on stock and h-stock charts
        'legends' => '', // (string or array or bool) General legend for each line/histogram/disk displaying under diagram
        'title' => null, // (string) Main title. Title wil be displaying in a tooltip too.
        'radius' => 100, // (int) Radius of pie
        'diskLegends' => false, // (bool) to display legends around a pie
        'diskLegendsType' => 'label', // (string) data, pourcent or label to display around a pie as legend
        'responsive' => true, // (bool) to avoid svg to be responsive (dimensions fixed)
    );
    public $colors = array();

    /**
     * Constructor
     *
     * @param    $width integer Width of grid
     * @param    $height integer Height of grid
     * @param   $options array Options
     * @return    stdio
     *
     * @author    Cyril MAGUIRE
     * */
    public function __construct($width = 600, $height = 300, $options = array()) {
        if (!empty($options)) {
            $this->options = $options;
        }
        if (!empty($width)) {
            $this->options['width'] = $width;
        }
        if (!empty($height)) {
            $this->options['height'] = $height;
        }
        if (is_string($this->options['stroke'])) {
            $this->options['stroke'] = array(0 => $this->options['stroke']);
        }
        if (is_string($this->options['type'])) {
            $this->options['type'] = array(0 => $this->options['type']);
        }
    }

    /**
     * Main function
     * @param $data array Uni or bidimensionnal array
     * @param $option array Array of options
     * @return string SVG 
     *
     * @author Cyril MAGUIRE
     */
    public function draw($data, $options = array(), $output = "php://stdout") {
        $return = '';

        //We add 10 units in viewbox to display x legend correctly
        $options['paddingLegendX'] = 10;

        $options = array_merge($this->options, $options);
        
        $return = phpGraph_Render::render($data, $options);

        $this->colors = array();
        return $return;
    }

}
