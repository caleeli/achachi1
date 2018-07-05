<?php
class node_pack extends node {
	
	public function run () {
		$e  = &$this ->node;
		$values  = &$this ->values;
		require_once  (ACH_COMMON_PATH . "/archive.php" );
		//@{$source}
		$source  = $this ->doTemplate ($values ["source" ],$values );
		//@{$target}
		$target  = $this ->doTemplate ($values ["target" ],$values );
		$_deploy  = new gzip_file (basename ($target ));
		$_deploy ->set_options (array  ('basedir' => dirname ($source ),'overwrite' => 1 ,'level' => 9 ));
		$_deploy ->add_files (basename ($source ));
		$_deploy ->create_archive ();
		if  ($_deploy ->error) {
			print_r ($_deploy ->error);
		}
		rename (dirname ($source ) . "/"  . basename ($target ),$target );
		print  ("Created file:$target \n");
		return $this ->encodeEmpty ();
	}
}
