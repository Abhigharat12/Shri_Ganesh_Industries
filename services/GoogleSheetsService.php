<?php
/**
 * Google Sheets Service
 * Handles authentication and data fetching from Google Sheets
 * 
 * This service uses a Service Account to authenticate with Google Sheets API
 * and fetch work log data from employee-specific Google Sheets.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    private $client;
    private $service;
    private $credentialsPath;
    
    /**
     * Constructor - Initialize Google Sheets client with Service Account
     */
    public function __construct()
    {
        // Set credentials path - secure location inside project
        $this->credentialsPath = __DIR__ . '/../config/google-credentials.json';
        
        // Check if credentials file exists
        if (!file_exists($this->credentialsPath)) {
            throw new Exception("Google credentials file not found at: " . $this->credentialsPath);
        }
        
        // Initialize Google Client
        $this->client = new Client();
        $this->client->setApplicationName('Employee Work Log Management');
        $this->client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAuthConfig($this->credentialsPath);
        
        // Create Sheets service
        $this->service = new Sheets($this->client);
    }
    
    /**
     * Fetch data from a Google Sheet
     * 
     * @param string $spreadsheetId The Google Sheet ID
     * @param string $range The range to fetch (e.g., 'Sheet1!A:G')
     * @return array Array of rows from the sheet
     */
    public function getSheetData($spreadsheetId, $range = null)
    {
        try {
            // Verify spreadsheet ID is provided
            if (empty($spreadsheetId)) {
                throw new Exception("Spreadsheet ID is required");
            }
            
            // If range is null, try to get the first sheet's name
            if ($range === null) {
                $spreadsheet = $this->service->spreadsheets->get($spreadsheetId);
                $sheets = $spreadsheet->getSheets();
                if (!empty($sheets)) {
                    $sheetName = $sheets[0]->getProperties()->getTitle();
                    $range = $sheetName . '!A:G';
                } else {
                    throw new Exception("No sheets found in the spreadsheet");
                }
            }
            
            // Get the sheet data
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
            
            if (empty($values)) {
                return [];
            }
            
            return $values;
            
        } catch (Exception $e) {
            // If specific range failed (maybe Sheet1 doesn't exist), try the first sheet
            if ($range !== null && strpos($range, 'Sheet1!') === 0) {
                return $this->getSheetData($spreadsheetId, null);
            }
            
            error_log("Error fetching Google Sheet data: " . $e->getMessage());
            throw new Exception("Failed to fetch data from Google Sheet: " . $e->getMessage());
        }
    }
    
    /**
     * Fetch work log data from a Google Sheet and parse it
     * Expected columns: Editable Date | System Record Date | Description | Hours | Overtime | Remarks
     * 
     * @param string $spreadsheetId The Google Sheet ID
     * @return array Parsed work log entries
     */
    public function fetchWorkLogs($spreadsheetId)
    {
        try {
            // Fetch all data from the sheet (try Sheet1 first, but fallback to the first sheet)
            $data = $this->getSheetData($spreadsheetId, 'Sheet1!A:G');
            
            if (empty($data)) {
                return [];
            }
            
            // Find the header row (contains "Date" and "Description")
            $headerRowIndex = -1;
            foreach ($data as $index => $row) {
                $rowString = implode(' ', $row);
                if (stripos($rowString, 'Date') !== false && stripos($rowString, 'Description') !== false) {
                    $headerRowIndex = $index;
                    break;
                }
            }
            
            if ($headerRowIndex === -1) {
                // If no header found, assume standard format and skip first row
                $headerRow = ['Editable Date', 'System Record Date', 'Description', 'Hours', 'Overtime', 'Remarks'];
                $startRow = 1;
                $data = array_slice($data, 1);
            } else {
                $headerRow = $data[$headerRowIndex];
                $startRow = $headerRowIndex + 1;
                $data = array_slice($data, $startRow);
            }
            
            // Map column indices based on header names
            $colMap = [
                'editable_date' => -1,
                'system_record_date' => -1,
                'description' => -1,
                'hours' => -1,
                'overtime' => -1,
                'remarks' => -1
            ];
            
            foreach ($headerRow as $idx => $cell) {
                $cell = trim($cell);
                if (stripos($cell, 'Date') !== false) {
                    if ($colMap['editable_date'] === -1) {
                        $colMap['editable_date'] = $idx;
                        $colMap['system_record_date'] = $idx; // Default fallback
                    } else {
                        $colMap['system_record_date'] = $idx;
                    }
                } elseif (stripos($cell, 'Description') !== false) {
                    $colMap['description'] = $idx;
                } elseif (stripos($cell, 'Hour') !== false) {
                    $colMap['hours'] = $idx;
                } elseif (stripos($cell, 'Over') !== false && stripos($cell, 'Time') !== false) {
                    $colMap['overtime'] = $idx;
                } elseif (stripos($cell, 'Remark') !== false) {
                    $colMap['remarks'] = $idx;
                }
            }
            
            // Fallback for missing columns if header was not found or was incomplete
            if ($colMap['description'] === -1) {
                // Try to find description by looking at first data row
                if (!empty($data) && count($data[0]) > 2) {
                    // Assume col after date is description
                    $colMap['description'] = ($colMap['editable_date'] !== -1) ? $colMap['editable_date'] + 1 : 2;
                }
            }
            
            $workLogs = [];
            
            foreach ($data as $rowIndex => $row) {
                // Skip empty rows
                if (empty($row) || (count($row) < 2)) {
                    continue;
                }
                
                $editable_date = null;
                $system_record_date = null;
                
                if ($colMap['editable_date'] !== -1 && isset($row[$colMap['editable_date']])) {
                    $editable_date = $this->parseDate($row[$colMap['editable_date']]);
                }
                
                if ($colMap['system_record_date'] !== -1 && isset($row[$colMap['system_record_date']])) {
                    $system_record_date = $this->parseDate($row[$colMap['system_record_date']]);
                }
                
                // Fallback for dates
                if ($system_record_date === null && $editable_date !== null) {
                    $system_record_date = $editable_date;
                } elseif ($system_record_date !== null && $editable_date === null) {
                    $editable_date = $system_record_date;
                }
                
                // Skip if no date found at all
                if ($system_record_date === null) {
                    continue;
                }
                
                // Parse the row data
                $workLog = [
                    'row_index' => $startRow + $rowIndex + 1, // 1-based index in original sheet
                    'editable_date' => $editable_date,
                    'system_record_date' => $system_record_date,
                    'description' => ($colMap['description'] !== -1 && isset($row[$colMap['description']])) ? trim($row[$colMap['description']]) : '',
                    'hours' => ($colMap['hours'] !== -1 && isset($row[$colMap['hours']])) ? $this->parseDecimal($row[$colMap['hours']]) : 0,
                    'overtime' => ($colMap['overtime'] !== -1 && isset($row[$colMap['overtime']])) ? $this->parseDecimal($row[$colMap['overtime']]) : 0,
                    'remarks' => ($colMap['remarks'] !== -1 && isset($row[$colMap['remarks']])) ? trim($row[$colMap['remarks']]) : ''
                ];
                
                // Create a unique identifier for this row to prevent duplicates
                $workLog['source_row_identifier'] = $workLog['system_record_date'] . '_' . $workLog['row_index'];
                
                $workLogs[] = $workLog;
            }
            
            return $workLogs;
            
        } catch (Exception $e) {
            error_log("Error fetching work logs: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Parse date from various formats to YYYY-MM-DD
     * 
     * @param mixed $date The date string to parse
     * @return string|null Parsed date in YYYY-MM-DD format or null if invalid
     */
    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }
        
        // Try to parse the date
        $date = trim($date);
        
        // Handle different date formats
        $formats = [
            'Y-m-d',      // 2024-01-15
            'd/m/Y',      // 15/01/2024
            'd-m-Y',      // 15-01-2024
            'm/d/Y',      // 01/15/2024
            'd M Y',      // 15 Jan 2024
            'M d, Y',     // Jan 15, 2024
            'd F Y',      // 15 January 2024
        ];
        
        foreach ($formats as $format) {
            $parsedDate = DateTime::createFromFormat($format, $date);
            if ($parsedDate !== false) {
                return $parsedDate->format('Y-m-d');
            }
        }
        
        // Try strtotime as last resort
        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        // If all else fails, return null
        return null;
    }
    
    /**
     * Parse decimal value from string
     * 
     * @param mixed $value The value to parse
     * @return float Parsed decimal value
     */
    private function parseDecimal($value)
    {
        if (empty($value)) {
            return 0.00;
        }
        
        // Remove any non-numeric characters except decimal point and minus
        $value = trim($value);
        
        // Handle Indian number format (comma as thousand separator)
        $value = str_replace(',', '', $value);
        
        // Try to convert to float
        $decimal = floatval($value);
        
        return round($decimal, 2);
    }
    
    /**
     * Validate if a spreadsheet exists and is accessible
     * 
     * @param string $spreadsheetId The Google Sheet ID
     * @return bool True if accessible, false otherwise
     */
    public function validateSpreadsheet($spreadsheetId)
    {
        try {
            $this->service->spreadsheets->get($spreadsheetId);
            return true;
        } catch (Exception $e) {
            error_log("Spreadsheet validation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get spreadsheet metadata
     * 
     * @param string $spreadsheetId The Google Sheet ID
     * @return array|null Spreadsheet metadata or null if error
     */
    public function getSpreadsheetInfo($spreadsheetId)
    {
        try {
            $spreadsheet = $this->service->spreadsheets->get($spreadsheetId);
            return [
                'title' => $spreadsheet->getProperties()->getTitle(),
                'sheet_count' => count($spreadsheet->getSheets())
            ];
        } catch (Exception $e) {
            error_log("Error getting spreadsheet info: " . $e->getMessage());
            return null;
        }
    }
}
