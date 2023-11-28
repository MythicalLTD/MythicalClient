<?php
/**
 * MythicalSystems 
 * 
 * Confidential code used in non-production environments is only intended for educational purposes.
 */
class CTable {
    private $table = [];

    public function AddRow() {
        $row = [];
        $this->table[] = $row;
        return count($this->table) - 1;
    }

    public function AddColumn() {
        foreach ($this->table as &$row) {
            $row[] = null;
        }

        $numColumns = count($this->table[0] ?? []);
        unset($row);
        return $numColumns - 1;
    }

    public function SetElement($column, $row, $value) {
        $this->table[$row][$column] = $value;
    }

    public function GetElement($column, $row) {
        return $this->table[$row][$column];
    }

    public function GetRowCount() {
        return count($this->table);
    }
}

class test1 {
    public static function processTableInput() {
        $table1 = new CTable();
        $storedNumbers = [];
        $count = 0;
        
        do {
            echo "Enter a number (99 to stop): ";
            $userInput = (int) readline();
            // Check if number is not 99
            if ($userInput !== 99) {
                if ($count % 2 !== 0) {
                    $nCol = $table1->AddColumn();
                    $nRow = $table1->AddRow();
                    $table1->SetElement($nCol, $nRow, $userInput);
                    $storedNumbers[] = $userInput;
                }
    
                $count++;
    
                // Calculate and display statistics
                if (!empty($storedNumbers)) {
                    $sum = array_sum($storedNumbers);
                    $min = min($storedNumbers);
                    $max = max($storedNumbers);
                    $average = $sum / count($storedNumbers);
    
                    echo "Sum: $sum, Min: $min, Max: $max, Average: $average\n";
                }
            }
        } while ($userInput !== 99);
    
        // Display stored numbers in reverse order
        echo "Stored numbers in reverse order: " . implode(' ', array_reverse($storedNumbers)) . "\n";
    
        // Calculate and display sum of numbers not stored
        $sumNotStored = array_sum($storedNumbers);
        echo "Sum of numbers not stored: $sumNotStored\n";
    }
}


test1::processTableInput();
?>
