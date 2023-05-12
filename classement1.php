<?php
require_once('../../config.php');
global $PAGE, $OUTPUT, $USER;

if (is_siteadmin()) {
    $PAGE->set_context(context_system::instance());
    $PAGE->set_title('Spécialité Dérigée de Licence');
    $PAGE->navbar->add('Spécialité Dérigée de Licence', new moodle_url('/local/myhtmlpae/classement1.php'));

    echo $OUTPUT->header();
    echo $OUTPUT->heading('Spécialité Dérigée de Licence');
    echo "<br>";
    echo "<br>";
    echo '<div class="container">';
    // Use include() to display the contents of the file
    include("cla1.php");
    echo '</div>';

    echo $OUTPUT->footer();
} else {
    // Display an error message or redirect to a different page
    echo "Access denied. You must be logged in as an administrator to view this page.";
}
?>
