<?php
//phpinfo();
$last_line = system('ls', $retval);
echo '
</pre>
<hr />Last line of the output: ' . $last_line . '
<hr />Return value: ' . $retval;