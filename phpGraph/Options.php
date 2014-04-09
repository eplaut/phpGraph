<?php

class phpGraph_Options {
    
    private $_options = array(
        'responsive' => true,
        'width' => 600, // (int) width of grid
        'height' => 300, // (int) height of grid
        'paddingTop' => 10, // (int)
        'type' => 'line', // (string) line, bar, pie, ring, stock or h-stock (todo curve)
        'steps' => null, // (int) 2 graduations on y-axis are separated by $steps units. "steps" is automatically calculated but we can set the value with integer. No effect on stock and h-stock charts
        'filled' => true, // (bool) to fill lines/histograms/disks
        'tooltips' => false, // (bool) to show tooltips
        'circles' => true, // (bool) to show circles on graph (lines or histograms). No effect on stock and h-stock charts
        'stroke' => '#3cc5f1', // (string) color of lines by default. Use an array to personalize each line
        'background' => "#ffffff", // (string) color of grid background. Don't use short notation (#fff) because of $this->__genColor();
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
    
    function __construct(array $options = array()) {
        if (!empty($options)) {
            $this->_options = array_merge($this->_options, $options);
        }
    }
    
    public function updateOptions(array $options = array()) {
        if (!empty($options)) {
            $this->_options = array_merge($this->_options, $options);
        }
    }
    
    public function __get($name) {
        @$val = $this->_options[$name];
        return $val;
    }
    
    public function __set($name, $value) {
        $this->_options[$name] = $value;
    }
}











