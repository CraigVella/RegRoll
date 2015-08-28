<?php
require '/var/www/vendor/autoload.php';
require 'lib/EoEDice.php';
require 'lib/RollTable.php';
require 'lib/RRApplication.php';

// ------------------------------------------------------- App Bootloader Below
$rrApp = new RRApplication();
$appRoute = new \Slim\Slim();
$appRoute->get('/', function () use ($rrApp) {
    // Generate Roll - Homepage
    $rrApp->showMain();
}); 
$appRoute->get('/roll/:rollid', function ($rollid) use ($rrApp) {
    // Get Roll
    $rrApp->showRoll($rollid);
});
$appRoute->get('/roll/:rollid/:serialdice', function ($rollid,$serialdice) use ($rrApp) {
    // Get Roll
    $rrApp->rollEm($rollid, $serialdice);
});
$appRoute->get('/generate', function () use ($rrApp) {
    // Generate Roll - selection screen
    $rrApp->showGenerateMain();
});
$appRoute->get('/generate/:dd', function ($dd) use ($rrApp) {
    // Generate Roll - with difficulty dice
    $rrApp->generateRollURL($dd);
    $rrApp->showRollURL();
});
$appRoute->run();
$rrApp->render();
// ------------------------------------------------------- App Bootloader Above
?>
