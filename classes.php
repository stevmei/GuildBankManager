<?php
class MyTableHeader {
	public $titles = Array();
	public $centers = Array();
	public $widths = Array();
	public $sortindex;
	public $sortorder;
	public $extrasort = false;
	public $extraindex;
	public $filter;
	public function setTitle($input) {
		foreach ($input as $value) {
			$this->titles[] = $value;
		}
	}
	public function setCenter($input) {
		foreach ($input as $value) {
			$this->centers[] = $value;
		}
	}
	public function getCenter($index) {
		if (array_key_exists($index, $this->centers)) {
			if ($this->centers[$index]) {
				return " style=\"text-align: center\"";
			} else {
				return "";
			}
		} else {
			return "";
		}
	}
	public function setWidth($input) {
		foreach ($input as $value) {
			$this->widths[] = $value;
		}
	}
	public function setSortindex($input) {
		$this->sortindex = $input;
	}
	public function getSortindex($index) {
		if ($this->sortindex == $index) {
			if ($this->sortorder == SORT_ASC) {
				return "filter=".($this->filter)."&sortindex=".($this->sortindex)."&sortorder=desc";
			} else {
				if (($this->extrasort) && ($this->extraindex == $index)) {
					return "filter=".($this->filter)."&sortindex=-1&sortorder=asc";
				} else {
					return "filter=".($this->filter)."&sortindex=".($this->sortindex)."&sortorder=asc";
				}
			}
		} else {
			if ($this->sortorder == SORT_ASC) {
				if (($this->extrasort) && ($this->extraindex == $index)) {
					return "filter=".($this->filter)."&sortindex=-1&sortorder=desc";
				} else {
					return "filter=".($this->filter)."&sortindex=".($index)."&sortorder=desc";
				}
			} else {
				return "filter=".($this->filter)."&sortindex=".($index)."&sortorder=asc";
			}
		}
	}
	public function setSortorder($input) {
		$this->sortorder = $input;
	}
	public function setExtrasort($input, $key) {
		$this->extrasort = $input;
		$this->extraindex = $key;
	}
	public function setFilter($input) {
		$this->filter = $input;
	}
}
class MyTable {
	public $headers = Array();
	public $rows = Array();
	public $htmlrows = Array();
	public $extrarows = Array();
	public $temppage;
	public $extrasort = false;
	public $extraindex;
	public function setHeader($input) {
		$this->headers = $input;
	}
	public function setTemppage($input) {
		$this->temppage = $input;
	}
	public function setExtrasort($input, $key) {
		$this->extrasort = $input;
		$this->extraindex = $key;
	}
	public function getWidth() {
		$completewidth = 0;
		foreach ($this->headers->widths as $width) {
			$completewidth += $width;
		}
		return $completewidth;
	}
	public function addRow($input) {
		$this->rows[] = Array();
		foreach ($input as $value) {
			$this->rows[sizeof($this->rows) - 1][] = $value;
		}
	}
	public function addHtmlrow($input) {
		$this->htmlrows[] = Array();
		foreach ($input as $value) {
			$this->htmlrows[sizeof($this->htmlrows) - 1][] = $value;
		}
	}
	public function addExtraKey($input) {
		$this->extrarows[] = Array();
		foreach ($input as $value) {
			$this->extrarows[sizeof($this->extrarows) - 1][] = $value;
		}
	}
	public function sortTable() {
		$sortarrayA = array();
		$sortarrayB = array();
		if (($this->extrasort) && ($this->headers->sortindex == -1)) {
			foreach ($this->extrarows as $row) {
				$sortarrayA[] = $row[0];
				$sortarrayB[] = $row[0];
			}
		} else {
			foreach ($this->rows as $row) {
				$sortarrayA[] = $row[$this->headers->sortindex];
				$sortarrayB[] = $row[$this->headers->sortindex];
			}
		}
		array_multisort($sortarrayB, $this->headers->sortorder, $this->rows);
		array_multisort($sortarrayA, $this->headers->sortorder, $this->htmlrows);
	}
	public function printTable() {
		echo "<table class=\"myTable\" width=\"".($this->getWidth())."px\">\n<colgroup>\n";
		for ($i = 0; $i < sizeof($this->headers->widths); $i++) {
			echo "<col width=\"".($this->headers->widths[$i])."px\">\n";
		}
		echo "</colgroup>\n<tr>\n";
		for ($i = 0; $i < sizeof($this->headers->titles); $i++) {
			echo "<th".($this->headers->getCenter($i))."><a href=\"?page=".($this->temppage)."&".($this->headers->getSortindex($i))."\">".($this->headers->titles[$i])."</a></th>\n";
		}
		echo "</tr>\n";
		for ($i = 0; $i < sizeof($this->rows); $i++) {
			$styleclass = "";
			if (($i % 2) == 1) {
				$styleclass = " class=\"myTableAlt\"";
			} else {
				$styleclass = "";
			}
			echo "<tr".$styleclass.">\n";
			for ($j = 0; $j < sizeof($this->rows[$i]); $j++) {
				echo "<td".($this->headers->getCenter($j)).">".($this->htmlrows[$i][$j])."</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}
?>
