<?php

abstract class SingleDie {
    protected $_numSides = 1;
    protected $_rolledSide = 1;
    protected $_dieAbbrev = 'si';
    protected $_dieName = 'Single';
    
    public function __construct($numOfSides, $dieAbbrev, $dieName) {
        if ($numOfSides < 1) $numOfSides = 1;
        $this->_numSides = $numOfSides;
        $this->_dieAbbrev = $dieAbbrev;
        $this->_dieName = $dieName;
    }
    
    public function roll() {
        $this->_rolledSide = mt_rand(1,$this->_numSides);
    }
    
    public function setDieSide($dieSide) {
        if ($dieSide > $this->_numSides) $dieSide = $this->_numSides;
        if ($dieSide < 1) $dieSide = 1;
        $this->_rolledSide = $dieSide;
    }
    
    public function getCurrentSide() {
        return $this->_rolledSide;
    }
    
    public function getDieAbbrev() {
        return $this->_dieAbbrev;
    }
    
    public function getDieLogo() {
        return $this->getDieAbbrev() . '.png';
    }
    
    public function getCurrentSideDieLogo() {
        return $this->getDieAbbrev() . ($this->getCurrentSide() - 1) . '.png';
    }
    
    public function getDieName() {
        return $this->_dieName;
    }
    
    public function getJsonArray() {
        $dieArray = array();
        $dieArray['dieAbbr'] = $this->getDieAbbrev();
        $dieArray['dieType'] = $this->getDieName();
        $dieArray['dieLogo'] = 'http://' . RRApplication::getDiceImgPath() . $this->getDieLogo();
        $dieArray['dieSide'] = $this->getCurrentSide();
        $dieArray['dieSImg'] = 'http://' .  RRApplication::getDiceImgPath() . $this->getCurrentSideDieLogo();
        return $dieArray;
    }
}

class AbilityDie extends SingleDie {
    public function __construct() {
        parent::__construct(8,'a','Ability');
    }
}

class ProficiencyDie extends SingleDie {
    public function __construct() {
        parent::__construct(12, 'p', 'Proficiency');
    }
}

class DifficultyDie extends SingleDie {
    public function __construct() {
        parent::__construct(8, 'd', 'Difficulty');
    }
}

class ChallengeDie extends SingleDie {
    public function __construct() {
        parent::__construct(12, 'c', 'Challenge');
    }
}

class BoostDie extends SingleDie {
    public function __construct() {
        parent::__construct(6, 'b', 'Boost');
    }
}

class SetbackDie extends SingleDie {
    public function __construct() {
        parent::__construct(6, 's', 'Setback');
    }
}

class ForceDie extends SingleDie {
    public function __construct() {
        parent::__construct(12, 'f', 'Force');
    }
}

abstract class DiceFactory {
    /* @return SingleDie */
    public static function getDieFromType($abbrev) {
        switch ($abbrev) {
            case 'a':
                return new AbilityDie();
            case 'p':
                return new ProficiencyDie();
            case 'd':
                return new DifficultyDie();
            case 'c':
                return new ChallengeDie();
            case 'b':
                return new BoostDie();
            case 's':
                return new SetbackDie();
            case 'f':
                return new ForceDie();
        }
    }
}

class DiceCollection {
    protected $_diceArray; 
    public function __construct() {
        $this->_diceArray = array();
    }
    
    public function addDieToPool(SingleDie $die) {
        $this->_diceArray[$die->getDieAbbrev()][] = $die;
    }
    
    public function getDiceAmountSerialized() {
        $outSerial = '';
        foreach ($this->_diceArray as $dieType => $typeArray) {
            $outSerial .= $dieType . ':' . count($typeArray) . '|';
        }
        $outSerial = rtrim($outSerial,'|');
        return $outSerial;
    }
    
    public function getDiceRollsSerialzed() {
        $outSerial = '';
        foreach ($this->_diceArray as $dieType => $typeArray) {
            foreach ($typeArray as $die) {
                $outSerial .= $dieType . ':' . $die->getCurrentSide() . '|';
            }
        }
        $outSerial = rtrim($outSerial,'|');
        return $outSerial;
    }
    
    public function rollDice() {
        foreach ($this->_diceArray as $dice) {
            foreach ($dice as $die) {
                $die->roll();
            }
        }
    }
    
    /* @return SingleDie[] */
    public function getDiceArray() {
        $arrayOfDice = array();
        foreach ($this->_diceArray as $dieTypeArray) {
            foreach ($dieTypeArray as $die) {
                $arrayOfDice[] = $die;
            }
        }
        return $arrayOfDice;
    }
    
    public function createPoolFromSerializedDiceAmount($serializedDice) {
        $encDieArray = explode('|',$serializedDice);
        if (empty($encDieArray[0])) return; // Nothing Exists
        foreach ($encDieArray as $dieAndAmt) {
            $dieAmtArray = explode(':',$dieAndAmt);
            for ($x = 0; $x < $dieAmtArray[1]; ++$x) {
                $this->addDieToPool(DiceFactory::getDieFromType($dieAmtArray[0]));
            }
        }
    }
    
    public function createPoolFromSerializedDiceRolled($serializedRoll) {
        $encDieArray = explode('|',$serializedRoll);
        if (empty($encDieArray[0])) return; // Nothing Exists
        foreach ($encDieArray as $dieAndRoll) {
            $dieRollSingle = explode(':',$dieAndRoll);
            $die = DiceFactory::getDieFromType($dieRollSingle[0]);
            $die->setDieSide($dieRollSingle[1]);
            $this->addDieToPool($die);
        }
    }
}
?>