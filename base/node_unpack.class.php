<?php
class node_unpack extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		require_once  (ACH_COMMON_PATH . "/archive.php" );
		//@{$source}
		$source  = $this ->doTemplate ($values ["source" ],$values );
		//@{$target}
		$target  = $this ->doTemplate ($values ["target" ],$values );
		$_deploy  = new gzip_file ($source );
		var_dump (realpath ($target ));
		if  (!file_exists ($target )):print "Created directory:$target \n";
		createDir ($target );
		endif ;
		$_deploy ->set_options (array  ("overwrite" => 1 ,"basedir" => $target ));
		$_deploy ->extract_files ();
		if  ($_deploy ->error) {
			print_r ($_deploy ->error);
		}
		return $this ->encodeEmpty ();
	}
}
