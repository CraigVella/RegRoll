<?php
require 'medoo.min.php';

class RRDB extends medoo {
    protected $database_type = 'mysql';
    protected $charset = 'utf8';
    protected $database_name = 'rr';
    protected $server = '127.0.0.1';
    protected $username = 'rr';
    protected $password = 'rr';
}

class RollTable {
    
    const Yes = 'Y';
    const No = 'N';
    
    protected $_id = 0;
    protected $_against;
    protected $_against_roll;
    protected $_roll;
    protected $_against_included = RollTable::No;
    protected $_roll_complete = RollTable::No;
    protected $_gen_date = 0;
    protected $_roll_date = 0;
    
    public function save() {
        $db = new RRDB();
        if ($this->getId() == 0) {
            // New Object
            $this->_id = $db->insert('rolls', [
                'against' => $this->_against,
                'against_roll' => $this->_against_roll,
                'roll' => $this->_roll,
                'against_included' => $this->_against_included,
                'roll_complete' => $this->_roll_complete,
                'gen_date' => $this->_gen_date,
                'roll_date' => $this->_roll_date
            ]);
        } else {
            // Update Object
            $db->update('rolls', [
                'against' => $this->_against,
                'against_roll' => $this->_against_roll,
                'roll' => $this->_roll,
                'against_included' => $this->_against_included,
                'roll_complete' => $this->_roll_complete,
                'gen_date' => $this->_gen_date,
                'roll_date' => $this->_roll_date
            ], ['id' => $this->_id]);
        }
    }
    
    public function load($id) {
        $db = new RRDB();
        $data = $db->select('rolls', '*', ['id' => $id]);
        if (count($data) > 0) {
            $this->_id = $data[0]['id'];
            $this->_against = $data[0]['against'] ;
            $this->_against_roll = $data[0]['against_roll'];
            $this->_roll = $data[0]['roll'];
            $this->_against_included = $data[0]['against_included'];
            $this->_roll_complete = $data[0]['roll_complete'];
            $this->_gen_date = $data[0]['gen_date'];
            $this->_roll_date = $data[0]['roll_date'];
        }
    }
    
    public function getId() {
        return $this->_id;
    }
    
    public function setAgainstDiceSerial($serialDice) {
        $this->_against = $serialDice;
    }
    
    public function setAgainstRollSerial($serialRoll) {
        $this->_against_roll = $serialRoll;
    }
    
    public function setRollSerial ($serialRoll) {
        $this->_roll = $serialRoll;
    }
    
    public function getAgainstDiceSerial() {
        return $this->_against;
    }
    
    public function getAgainstRollSerial() {
        return $this->_against_roll;
    }
    
    public function getRollSerial() {
        return $this->_roll;
    }
    
    public function setAgainstIncluded($YesNo) {
        $this->_against_included = $YesNo;
    }
    
    public function getAgainstIncluded() {
        return $this->_against_included;
    }
    
    public function setRollComplete($YesNo) {
        $this->_roll_complete = $YesNo;
    }
    
    public function getRollComplete() {
        return $this->_roll_complete;
    }
    
    public function setRollDate($date) {
        $this->_roll_date = $date;
    }
    
    public function getRollDate() {
        return $this->_roll_date;
    }
    
    public function setGenDate($date) {
        $this->_gen_date = $date;
    }
    
    public function getGenDate() {
        return $this->_gen_date;
    }
    
}
?>