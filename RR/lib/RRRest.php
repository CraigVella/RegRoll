<?php
require_once 'RRApplication.php';
require_once 'EoEDice.php';

class RESTResult {
    public $Result = "Success";
    public $ResultCode = 0;
    public $Data = array();
}

class RRRest {
    protected $_appInstance;
    public function __construct(RRApplication $app) {
        $this->_appInstance = $app;
    }
    
    public function genRoll($serialAgainst = '') {
        $rollId = $this->_appInstance->generateRoll($serialAgainst);
        $result = new RESTResult();
        $result->Data['RollID'] = $rollId;
        $result->Data['RollURL'] = 'http://' . RRApplication::getHost() . RRApplication::getWebApplicationDirectory() . 'roll/' . $rollId;
        echo (json_encode($result,JSON_PRETTY_PRINT));
    }
    
    public function roll($rollId, $diceSerial = '') {
        $this->_appInstance->executeRoll($rollId, $diceSerial);
        $this->getRoll($rollId);
    }
    
    public function roller($serialDice) {
        $result = new RESTResult();
        $result->Result = "Custom Dice Roll";
        $result->ResultCode = 0;
        $dc = new DiceCollection();
        $dc->createPoolFromSerializedDiceAmount($serialDice);
        $dc->rollDice();
        $result->Data['Roll']['DiceTypeSerial'] = $dc->getDiceAmountSerialized();
        $result->Data['Roll']['DiceRollSerial'] = $dc->getDiceRollsSerialzed();
        foreach ($dc->getDiceArray() as $die) {
            $result->Data['Roll'][] = $die->getJsonArray();
        }
        echo (json_encode($result,JSON_PRETTY_PRINT));
    }
    
    public function getRoll($rollId) {
        $rt = new RollTable();
        $result = new RESTResult();
        $rt->load($rollId);
        if ($rt->getId() == 0) {
            $result->Result = "Roll Cannot be found";
            $result->ResultCode = -1;
        } else {
            $result->Data['Roll']['GeneratedDate'] = $rt->getGenDate();
            $result->Data['Roll']['Complete'] = $rt->getRollComplete();
            $result->Data['Roll']['AgainstIncluded'] = $rt->getAgainstIncluded();
            if ($rt->getRollComplete() == 'N') {
                if ($rt->getAgainstIncluded() == 'Y') {
                    // Populate the against die even though roll not complete
                    $result->Data['Roll']['AgainstDice']['DiceTypeSerial'] = $rt->getAgainstDiceSerial();
                    $adc = new DiceCollection();
                    $adc->createPoolFromSerializedDiceAmount($rt->getAgainstDiceSerial());
                    foreach ($adc->getDiceArray() as $die) {
                        $result->Data['Roll']['AgainstDice'][] = $die->getJsonArray();
                    }
                }
            } else {
                // Roll is completed - show all info
                $result->Data['Roll']['RollDate'] = $rt->getRollDate();
                if ($rt->getAgainstRollSerial()){
                    // There is an Against Roll
                    $againstColl = new DiceCollection();
                    $againstColl->createPoolFromSerializedDiceRolled($rt->getAgainstRollSerial());
                    $result->Data['Roll']['AgainstDice']['DiceTypeSerial'] = $againstColl->getDiceAmountSerialized();
                    $result->Data['Roll']['AgainstDice']['DiceRollSerial'] = $againstColl->getDiceRollsSerialzed();
                    foreach ($againstColl->getDiceArray() as $die) {
                        $result->Data['Roll']['AgainstDice'][] = $die->getJsonArray();
                    }
                }
                if ($rt->getRollSerial()) {
                    $rollColl = new DiceCollection();
                    $rollColl->createPoolFromSerializedDiceRolled($rt->getRollSerial());
                    $result->Data['Roll']['PlayerDice']['DiceTypeSerial'] = $rollColl->getDiceAmountSerialized();
                    $result->Data['Roll']['PlayerDice']['DiceRollSerial'] = $rollColl->getDiceRollsSerialzed();
                    foreach ($rollColl->getDiceArray() as $die) {
                        $result->Data['Roll']['PlayerDice'][] = $die->getJsonArray();
                    }
                }
            }
        }
        echo (json_encode($result,JSON_PRETTY_PRINT));
    }
}