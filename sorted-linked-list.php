<?php

class Node
{
    public $data;
    public $nextNode;

    public function __construct($data)
    {
        $this->data = $data;
        $this->nextNode = null;
    }
}

class LinkedList
{
    /**
     * @var bool Enables/disables log messages
     */
    private static $enableLog = true;

    private $firstNode;
    private $nodeCount;

    public function __construct()
    {
        $this->firstNode = null;
        $this->nodeCount = 0;
    }

    /**
     * Inserts new data (node) to the linked list in the correct order
     * @param int|string $newData
     * @return bool If inserts data returns true, returns false if $newData is the wrong data type
     */
    public function insertNode(int|string $newData)
    {
        if (!$this->isDataTypeValid($newData)) {
            return false;
        };

        $newNode = new Node($newData);
        $tempNode = new Node(null);

        if ($this->firstNode === null) {
            $this->firstNode = $newNode;
        } else if ($newNode->data <= $this->firstNode->data) {
            $newNode->nextNode = $this->firstNode;
            $this->firstNode = $newNode;
        } else {
            $tempNode = $this->firstNode;
            $currentNode = $this->firstNode->nextNode;

            while ($currentNode !== null && $newNode->data > $currentNode->data) {
                $tempNode = $currentNode;
                $currentNode = $currentNode->nextNode;
            }

            if ($currentNode !== null) {
                $tempNode->nextNode = $newNode;
                $newNode->nextNode = $currentNode;
            } else {
                $tempNode->nextNode = $newNode;
            }
        }

        $this->nodeCount++;
        $this->printLog("$newData was inserted to the list");
        return true;
    }

    /**
     * Reads data in the linked list and returns them in an array
     * @return array
     */
    public function readList()
    {
        $currentNode = $this->firstNode;
        $linkedList = [];

        if ($currentNode === null) {
            $this->printLog("List is empty.");
        } else {
            $this->printLog("Linked list: ");
            while ($currentNode !== null) {
                array_push($linkedList, $currentNode->data);
                $this->printLog($currentNode->data . " ");
                $currentNode = $currentNode->nextNode;
            }
        }
        return $linkedList;
    }

    /**
     * Updates specified data in the list for the new data
     * @param int|string $currentData
     * @param int|string $newData 
     * @return bool If data is updated returns true, returns false if doesn't find $currentData or $newData is the wrong data type,
     * also returns false if deleteNode() or insertNode() fails
     */
    public function updateNode($currentData, int|string $newData)
    {
        $originalData = $currentData;

        if (!$this->isDataTypeValid($newData)) {
            return false;
        }

        $currentNode = $this->firstNode;

        while ($currentNode !== null && $currentNode->data !== $currentData) {
            $currentNode = $currentNode->nextNode;
        }

        if ($currentNode === null) {
            $this->printLog("Data doesn't exist in the list.");
            return false;
        }

        $currentNode->data = $newData;

        $this->deleteNode($currentNode->data);
        $this->insertNode($currentNode->data);

        if(!$this->deleteNode($currentNode->data) || !$this->insertNode($currentNode->data)) {
            return false;
        }

        $this->printLog("Data in the list was updated $originalData -> $currentNode->data");
        return true;
    }

    /**
     * Deletes specified data from the list
     * @param int|string $data
     * @return bool If data is deleted returns true, returns false if doesn't find data in the list
     */
    public function deleteNode(int|string $data)
    {
        if (!$this->isDataTypeValid($data)) {
            return false;
        };

        $currentNode = $this->firstNode;
        $previousCachedNode = new Node(null);

        if ($currentNode !== null && $currentNode->data === $data) {
            $this->firstNode = $currentNode->nextNode;
        } else {
            while ($currentNode !== null && $currentNode->data !== $data) {
                $previousCachedNode = $currentNode;
                $currentNode = $currentNode->nextNode;
            }

            if ($currentNode === null) {
                $this->printLog("Data doesn't exist in the list.");
                return false;
            }

            $previousCachedNode->nextNode = $currentNode->nextNode;
        }

        $this->nodeCount--;
        $this->printLog("$data was deleted from the list");
        return true;
    }

    /**
     * Returns number of the nodes in the list
     * @return int
     */
    public function getNodeCount()
    {
        $this->printLog("Node count: $this->nodeCount");
        return $this->nodeCount;
    }

    /**
     * Checks if data which is to be inserted/updated/deleted is the same data type as data in the list
     * @param int|string $data
     * @return bool
     */
    private function isDataTypeValid($data)
    {
        if ($this->firstNode !== null && gettype($this->firstNode->data) !== gettype($data)) {
            $this->printLog("List can only contain one data type.");
            return false;
        }
        return true;
    }

    /**
     * Log messages
     * @param string $message
     */
    private function printLog($message)
    {
        if (self::$enableLog) {
            echo $message . PHP_EOL;
        }
    }
}
