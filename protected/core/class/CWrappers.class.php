<?php
class CWrappers {
    public function __construct() {
        throw new CustomException(Parse::text('Cannot create instances of the Wrappers class'));
    }
}

?>
