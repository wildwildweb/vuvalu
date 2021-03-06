<?php
// $Id: ReadCSV.inc 7 2012-04-27 12:48:49Z david $
/**
 * Use this to read CSV files. PHP's fgetcsv() does not conform to RFC
 * 4180. In particular, it doesn't handle the correct quote escaping
 * syntax. See http://tools.ietf.org/html/rfc4180
 *
 * David Houlder May 2010
 * http://davidhoulder.com
 */

class ReadCSV {
  const field_start = 0;
  const unquoted_field = 1;
  const quoted_field = 2;
  const found_quote = 3;
  const found_cr_q = 4;
  const found_cr =  5;

  private $file;
  private $sep;
  // If $eof is TRUE, the next next_char() will return FALSE.
  // Note that this is different to feof(), which is TRUE
  // _after_ EOF is encountered.
  private $eof;
  private $nc;

  /**
   * @param $file_path
   *  Path of file to read from
   * @param $sep
   *  column separator character
   * @param $skip
   *  initial character sequence to skip at strat of file if
   *  found. e.g. UTF-8 byte-order mark. Only used when skip==0.
   */

  public function __construct($file_path, $sep, $skip="", $offset=0) {
    $this->file = @fopen($file_path, 'rb');
    if ($this->file === FALSE) {
      $err = error_get_last();
      throw new Exception(t("Cannot open file !f: !m",
			    array('!f' => $file_path,
				  '!m' => $err['message'])));
    }
    $status = @fseek($this->file, $offset);
    if ($status == -1) {
      $err = error_get_last();
      throw new Exception(t("Cannot seek to !o on !f: !m)",
			    array('!o' => $offset,
				  '!f' => $file_path,
				  '!m' => $err['message'])));
    }

    // FIXME close during __destruct()?

    $this->sep = $sep;
    $this->nc = fgetc($this->file);
    if ($offset == 0) {
      // skip junk at start
      // FIXME - back up if only part matched?
      for ($i=0; $i < strlen($skip); $i++) {
	if ($this->nc !== $skip[$i])
	  break;
	$this->nc = fgetc($this->file);
      }
    }
    $this->eof = ($this->nc === FALSE);
  }

  private function next_char() {
    $c = $this->nc;
    $this->nc = fgetc($this->file);
    $this->eof = ($this->nc === FALSE);
    return $c;
  }

  /**
   * @return
   * Returns the current file offset position. This can be used as the
   * offset argument in a subsequent constructor call to enable
   * chunked reading of the CSV file.
   */
  public function offset() {
    return ftell($this->file)-1; // -1 because next_char() has a 1 char buffer
  }

  /**
   * @return
   * Returns file size in bytes
   */
  public function size() {
    $s = fstat($this->file);
    return $s['size'];
  }

  /**
   * Get next record from CSV file.
   *
   * @return
   *  array of strings from the next record in the CSV file, or NULL if
   *  there are no more records.
   */
  public function get_row() {
    if ($this->eof)
      return NULL;

    $row=array();
    $field="";
    $state=self::field_start;

    while (1) {
      $char = $this->next_char();

      if ($state == self::quoted_field) {
	if ($char === FALSE) {
	  // EOF. (TODO: error case - no closing quote)
	  $row[]=$field;
	  return $row;
	}
	// Fall through to accumulate quoted chars in switch() {...}
      } elseif ($char === FALSE || $char == "\n") {
	// End of record.
	// (TODO: error case if $state==self::field_start here - trailing comma)
	$row[] = $field;
	return $row;
      } elseif ($char == "\r") {
	// Possible start of \r\n line end, but might be just part of foo\rbar
	$state = ($state == self::found_quote)? self::found_cr_q: self::found_cr;
	continue;
      } elseif ($char == $this->sep && 
		($state == self::field_start ||
		 $state == self::found_quote ||
		 $state == self::unquoted_field)) {
	// End of current field, start of next field
	$row[]=$field;
	$field="";
	$state=self::field_start;
	continue;
      }

      switch ($state) {

      case self::field_start:
	if ($char == '"')
	  $state = self::quoted_field;
	else {
	  $state = self::unquoted_field;
	  $field .= $char;
	}
	break;

      case self::quoted_field:
	if ($char == '"')
	  $state = self::found_quote;
	else
	  $field .= $char;
	break;

      case self::unquoted_field:
	$field .= $char;
	// (TODO: error case if '"' in middle of unquoted field)
	break;

      case self::found_quote:
	// Found '"' escape sequence
	$field .= $char;
	$state = self::quoted_field;
	// (TODO: error case if $char!='"' - non-separator char after single quote)
	break;

      case self::found_cr:
	  // Lone \rX instead of \r\n. Treat as literal \rX. (TODO: error case?)
	$field .= "\r".$char;
	$state = self::unquoted_field;
	break;

      case self::found_cr_q:
	// (TODO: error case: "foo"\rX instead of "foo"\r\n or "foo"\n)
	$field .= "\r".$char;
	$state = self::quoted_field;
	break;
      }
    }
  }
}
