<?php
require '/var/www/vendor/autoload.php';
require 'lib/EoEDice.php';
require 'lib/RollTable.php';
require 'lib/RRApplication.php';
require 'lib/RRRest.php';

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
    $rrApp->executeRoll($rollid, $serialdice);
    $rrApp->showExecuteRoll($rollid);
});
$appRoute->get('/generate', function () use ($rrApp) {
    // Generate Roll - selection screen
    $rrApp->showGenerateMain();
});
$appRoute->get('/generate/:dd', function ($dd) use ($rrApp) {
    // Generate Roll - with difficulty dice
    $rrApp->showRollURL($rrApp->generateRoll($dd));
});
// REST API ----------------------------------------------
$api = new RRRest($rrApp);
$appRoute->get('/rest/getroll/:rollid', function ($rollid) use ($api) {
    $api->getRoll($rollid);
});
$appRoute->get('/rest/roll/:rollid', function ($rollid) use ($api) {
    $api->roll($rollid);
});
$appRoute->get('/rest/roll/:rollid/:diceSerial', function ($rollid, $diceSerial) use ($api) {
    $api->roll($rollid,$diceSerial);
});
$appRoute->get('/rest/roller/:diceSerial' , function ($diceSerial) use ($api) {
    $api->roller($diceSerial);
});
$appRoute->get('/rest/generate/:diceSerial' , function ($diceSerial) use ($api) {
    $api->genRoll($diceSerial);
});
$appRoute->get('/rest/generate' , function () use ($api) {
    $api->genRoll();
});
// End of REST -------------------------------------------
$appRoute->run();
$rrApp->render();
// ------------------------------------------------------- App Bootloader Above
?>
