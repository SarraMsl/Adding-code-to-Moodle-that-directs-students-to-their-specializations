<?php
require_once('../../config.php');
global $PAGE, $OUTPUT, $USER;



if (is_siteadmin()) {
    $PAGE->set_context(context_system::instance());
    $PAGE->set_title('Spécialité Dérigée de Master');
    $PAGE->navbar->add('Spécialité Dérigée de Master', new moodle_url('/local/myhtmlpae/classement.php'));

    echo $OUTPUT->header();
    echo $OUTPUT->heading('Spécialité Dérigée de Master ');
    echo "<br>";
    echo "<br>";

    echo '<div class="container">';
    // Use include() to display the contents of the file
    include("cla.php");
    echo '</div>';

    echo $OUTPUT->footer();
} else {
    // Display an error message or redirect to a different page
    echo "Access denied. You must be logged in as an administrator to view this page.";
}

?>
