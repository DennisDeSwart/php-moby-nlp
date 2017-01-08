<?php
/**
 * @author Brent Rossen
 * Website: http://brent.lizandbrent.com
 */

/**
 * Class for accessing the moby thesaurus and parts of speech.
 * This class is capable of retrieving a list of synonyms for a word, or parts of speech for a word.
 *
 */
class MobyThesaurus {
	
	/**
	 * Gets the word in the thesaurus that is most similar to the passed word. Uses extension php_stem for stemming if it is available (highly recommended).
	 *
	 * @param string $word
	 * @return array The array of synonyms, array position 0 is the matched word 
	 */
	public static function GetSynonyms($word) {
		//get the thesaurus
		$thesaurus_array = file ( dirname ( __FILE__ ) . "/thesaurus_files/moby_thesaurus.txt" );
		
		//get the stemmed word, requires the PECL extension php_stem
		if (function_exists ( "stem" )) {
			$stemmed_word = stem ( $word );
		} else {
			//can't get the stemmed word
			$stemmed_word = $word;
		}
		
		//the array of potential entries
		$potential_entries = array ();
		
		//loop through the thesaurus entries
		foreach ( $thesaurus_array as $entry ) {
			if (MobyThesaurus::StartsWith ( $stemmed_word, $entry )) {
				$entry_arr = explode ( ",", $entry ); // replaced deprecated split for explode
				if ($entry_arr [0] == $word) {
					return $entry_arr;
				} else {
					array_push ( $potential_entries, $entry_arr );
				}
			}
		}
		
		//anything above 10 is way too far away
		$lowest_distance = 10;
		foreach ( $potential_entries as $entry ) {
			$distance = levenshtein ( $entry [0], $word );
			//keep only the word that is closest to the original word
			if ($distance < $lowest_distance) {
				$lowest_distance = $distance;
				$best_entry = $entry;
			}
		}
		
		if (isset ( $best_entry )) {
			return $best_entry;
		} else {
			return array ();
		}
	}
	
	/**
	 * Gets the PartsOfSpeech for the entries that start with the given word.
	 *
	 * @param string $word
	 * @return array of parts of speech
	 */
	public static function GetPartsOfSpeech($word) {
		//get the thesaurus
		$pos_array = file ( dirname ( __FILE__ ) . "/thesaurus_files/moby_part_of_speech.txt" );
		
		$poss = array ();
		foreach ( $pos_array as $entry ) {
			if (MobyThesaurus::StartsWith ( $word, $entry )) {
				//split the word from it's parts of speech
				$line_arr = explode ( "\\", $entry ); // replaced deprecated split for explode
				$poss[$line_arr[0]] = array();
				$line_arr [1] = trim ( $line_arr [1] );
				//go through each part of speech item
				for($i = 0; $i < strlen ( $line_arr [1] ); $i ++) {
					$symbol = trim ( $line_arr [1] [$i] );
					array_push ( $poss [$line_arr[0]], $symbol );
				}
			}
		}
		
		return $poss;
	}
	
	/**
	 * Discovers if haystack starts with needle
	 *
	 * @param string $needle
	 * @param string $haystack
	 * @return boolean
	 */
	private static function StartsWith($needle, $haystack) {
		return (substr($haystack,0,strlen($needle)) == $needle);
	}
}

?>