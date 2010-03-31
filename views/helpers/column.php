<?php  

/* 

Jamie's column helper for splitting text into columns and outputting a particular one. 
Paragraphs will not be split over columns.

Works by counting paragraphs and dividing the number of paragraphs fairly between columns.

TODO - be more intelligent by counting number of words and splitting by nearest paragraph boundary.

*/


class ColumnHelper extends AppHelper { 

	var $helpers = array ('Html'); 


	function single ($text, $column_id, $number_of_cols) {
		

		
		if($column_id+1 > $number_of_cols) {
			return '';
		}
		
		// create an array of paragraphs
		
		$blocklevel = 'ADDRESS|BLOCKQUOTE|DIV|DL|FIELDSET|FORM|H1|H2|H3|H4|H5|H6|OL|P|PRE|TABLE|UL';

		preg_match_all('#(<('.$blocklevel.')[^>]*>.*</('.$blocklevel.')>)#Usi', $text, $matches); #U modifier: ungreedy "*", s: '.' includes newline
		
		$parags = $matches[0];

		$num_parags = count($parags);
		$parags_per_col = floor($num_parags / $number_of_cols);
		$rem = $num_parags % $number_of_cols;
		
		//echo 'p per col : '.$parags_per_col."\n";
		//echo 'rem : '.$rem."\n";
		
		
		if($column_id+1 <= $rem) {
			$parags_this_col = $parags_per_col +1;
		}
		else {
			$parags_this_col = $parags_per_col;
		}
		//echo 'parags this col : '.$parags_this_col."\n";
		
		$remainderised_cols_b4_this = $rem;
		if($column_id < $rem ) {
			$remainderised_cols_b4_this = $column_id;
		}
		
		//echo 'remainderised b4 : '.$remainderised_cols_b4_this."\n";
		
		$start_parag = $column_id * $parags_per_col + ($remainderised_cols_b4_this);
		$end_parag = $start_parag + $parags_this_col -1;
		
		//echo 'start : '.$start_parag."\n";
		//echo 'end : '.$end_parag."\n";
		
		
		$out = '';
		for($i=$start_parag; $i<=$end_parag; $i++) {
			$out .= $parags[$i]."\n";
		}
		
		
		return $out;
		
		

	}

 
} 
?>