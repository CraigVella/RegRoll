<?php

class RRApplication {
    
    protected $renderTemplate;
    protected $twigObj;
    protected $renderArray;
    
    public function __construct() {
        $twigLoader = new Twig_Loader_Filesystem('/var/www/html/rr/templates');
        $this->twigObj = new Twig_Environment($twigLoader, array(
            //'cache' => '/var/www/cache',
        ));
        
        $this->renderArray = array();
        $this->renderArray[System][LibPath] = '/rr/lib/';
        $this->renderArray[System][DicePath] = $this->renderArray[System][LibPath] . 'img/dice/';
    }
    
    public function rollEm($rollId, $serialRoll) {
        $rt = new RollTable();
        $rt->load($rollId);
        if ($rt->getRollComplete() == RollTable::Yes) {
            header("Location: /rr/roll/$rollId");
            die();
        }
        if ($rt->getAgainstIncluded() == RollTable::No) {
            // If against was not in table it is now in serial roll
            $serializedDiceArray = explode('-',$serialRoll);
            $againstDiceCollection = new DiceCollection();
            $againstDiceCollection->createPoolFromSerializedDiceAmount($serializedDiceArray[0]);
            $againstDiceCollection->rollDice();
            $rt->setAgainstRollSerial($againstDiceCollection->getDiceRollsSerialzed());
            $myDiceCollection = new DiceCollection();
            $myDiceCollection->createPoolFromSerializedDiceAmount($serializedDiceArray[1]);
            $myDiceCollection->rollDice();
            $rt->setRollSerial($myDiceCollection->getDiceRollsSerialzed());
        } else {
            $againstDiceCollection = new DiceCollection();
            $againstDiceCollection->createPoolFromSerializedDiceAmount($rt->getAgainstDiceSerial());
            $againstDiceCollection->rollDice();
            $rt->setAgainstRollSerial($againstDiceCollection->getDiceRollsSerialzed());
            $myDiceCollection = new DiceCollection();
            $myDiceCollection->createPoolFromSerializedDiceAmount($serialRoll);
            $myDiceCollection->rollDice();
            $rt->setRollSerial($myDiceCollection->getDiceRollsSerialzed());
        }
        $rt->setRollDate(time());
        $rt->setRollComplete(RollTable::Yes);
        $rt->save();
        header("Location: /rr/roll/$rollId");
        die();
    }
    
    public function showError($error) {
        $this->renderArray['Error'] = $error;
        $this->renderTemplate = $this->twigObj->loadTemplate('error.html');
    }
    
    public function showRoll($rollId) {
        $rt = new RollTable();
        $rt->load($rollId);
        if ($rt->getId() == 0) {
            // roll does not exist
            $this->showError("These are not the rolls you are looking for...");
            return;
        }
        $this->renderArray['RollTable'] = $rt;
        if ($rt->getAgainstIncluded() == RollTable::Yes) {
            $againstCollection = new DiceCollection();
            $againstCollection->createPoolFromSerializedDiceAmount($rt->getAgainstDiceSerial());
            $this->renderArray['AgainstDiceArray'] = $againstCollection->getDiceArray();
        }
        if ($rt->getRollComplete() == RollTable::Yes) {
            $againstRoll = new DiceCollection();
            $myRoll = new DiceCollection();
            $againstRoll->createPoolFromSerializedDiceRolled($rt->getAgainstRollSerial());
            $myRoll->createPoolFromSerializedDiceRolled($rt->getRollSerial());
            $this->renderArray['AgainstRollArray'] = $againstRoll->getDiceArray();
            $this->renderArray['MyRollArray'] = $myRoll->getDiceArray();
        }
        $this->renderTemplate = $this->twigObj->loadTemplate('showRoll.html');
    }
    
    public function showGenerateMain() {
        $this->renderTemplate = $this->twigObj->loadTemplate('generateMain.html');
    }
    
    public function generateRollURL($diffDie) {
        $difficultyCollection = new DiceCollection();
        $difficultyCollection->createPoolFromSerializedDiceAmount($diffDie);
        $roll = new RollTable();
        if (count($difficultyCollection->getDiceArray()) > 0) {
            // Difficutly Set
            $roll->setAgainstDiceSerial($difficultyCollection->getDiceAmountSerialized());
            $roll->setAgainstIncluded(RollTable::Yes);
        }
        $roll->setGenDate(time());
        $roll->save();
        $this->renderArray['RollURL'] = "http://reztek.net/rr/roll/" . $roll->getId();
    }
    
    public function showRollURL() {
        $this->renderTemplate = $this->twigObj->loadTemplate('URLGet.html');
    }
    
    public function showMain() {
        $this->renderTemplate = $this->twigObj->loadTemplate('landing.html');
    }
    
    public function render() {
        echo ($this->renderTemplate->render($this->renderArray));
    }
}

?>