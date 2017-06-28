<?php
if (isset($_GET['go']) && preg_match('~^[a-z0-9]+://[-a-z0-9.]+(?::\\d+)?(?:/[-a-z0-9\\~!@#$%\\^&*()_=+\\[\\]{};:|/,.?]*)?$~i', $_GET['go'])) {
 header('Location: ' . $_GET['go']); exit;
}
?>