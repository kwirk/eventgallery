<?php
/**
 * NumericRange is used to specify a low and high value and easily tell if another value
 * is withing the range.
 * 
 * @package multi_bit_shift_php_plugin
 */
class NumericRange {
	
	/**
	 * private: low number
	 * @var int
	 */
	var $low;
	/**
	 * private: high number
	 * @var int
	 */
	var $high;
	
	/**
	 * Initializes the range.
	 * @param int Low end of range
	 * @param int High end of range
	 */
	function NumericRange($low,$high) {
		$this->low = $low;
		$this->high = $high;
	}
	
	/**
	 * Tests if a number is in the range, inclusive.
	 * @return boolean true if in range, false otherwise
	 * @param int Number to test if in range
	 */
	function inRangeInclusive($val) {
		return ($val >= $this->low && $val <= $this->high);
	}
	
	/**
	 * Tests if a number is in the range, exclusive.
	 * @return boolean true if in range, false otherwise
	 * @param int Number to test if in range
	 */
	function inRangeExclusive($val) {
		return ($val > $this->low && $val < $this->high);
	}
	
	/**
	 * Tests if a value is less than or equal to the maximum
	 * @return boolean true if less than or equal to maximum value.
	 * @param int Number to test
	 */
	function atMostMaximum($val) {
		return ($val <= $this->high);
	}
	
	/**
	 * Tests if a value is greater than or equal to the minimum
	 * @return boolean true if greater than or equal to minimum value.
	 * @param int Number to test
	 */
	function atLeastMinimum($val) {
		return ($val >= $this->low);
	}
}
?>