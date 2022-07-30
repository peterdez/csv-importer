<?php
namespace Adebanjo;
use Adebanjo\Database;
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class CountryModel extends Database
{

    function getAllCountry()
    {
        $sqlSelect = "SELECT * FROM countries";
        $result = $this->select($sqlSelect);
        return $result;
    }

    public function getCountries($limit, $theq, $pageq)
    {
        
        if(isset($theq)){
            $limit = $limit;
            $page = 1;
            if($pageq > 1)
            {
                $start = (($pageq - 1) * $limit);

                $page = $pageq;
            }
            else
            {
                $start = 0;
            }

            if($theq != ''){
                $param = "%{$theq}%";
                $q = "SELECT
                *
                FROM
                (SELECT country_id, common_name, continent_code, currency_code, official_name, iso_numeric_code FROM countries) t1
                LEFT JOIN
                (SELECT iso_numeric_code, common_name AS cur_common_name, symbol FROM currencies) t2
                ON t1.iso_numeric_code = t2.iso_numeric_code";
                $q .= " WHERE concat(common_name,currency_code) LIKE ? ORDER BY country_id ASC LIMIT ?, ?";
                
                return $this->select($q, "sii", [$param, $start, $limit]);
                
            }

            else{
                //$q = "SELECT country_id, common_name, continent_code, currency_code, official_name, iso_numeric_code FROM countries";
                $q = "SELECT
                *
                FROM
                (SELECT country_id, common_name, continent_code, currency_code, official_name, iso_numeric_code FROM countries) t1
                LEFT JOIN
                (SELECT iso_numeric_code, common_name AS cur_common_name, symbol FROM currencies) t2
                ON t1.iso_numeric_code = t2.iso_numeric_code";
                $q .= " ORDER BY country_id ASC LIMIT ?, ?";
                return $this->select($q, "ii", [$start, $limit]);
            }

        }
        
    }

    public function getTotalRecords(){
        $sql = "SELECT * from countries";
        $con = $this->connection;
        //error_log(print_r($con));

        if ($result = mysqli_query($con, $sql)) {

            // Return the number of rows in result set
            $rowcount = mysqli_num_rows( $result );
            
            return $rowcount;
        } 
    }


    function readCountryRecords()
    {
    
            // Allowed mime types
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
            
            // Validate whether selected file is a CSV file
            if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
                
                // If the file is uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    
                    // Open uploaded CSV file with read-only mode
                    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                    
                    // Skip the first line
                    fgetcsv($csvFile);
                    $importCount = 0;
                    // Parse data from CSV file line by line
                    while(($line = fgetcsv($csvFile)) !== FALSE){
                        // Get row data
                        $continent_code   = $line[0];
                        $currency_code  = $line[1];
                        $iso2_code  = $line[2];
                        $iso3_code = $line[3];
                        $iso_numeric_code = $line[4];
                        $fips_code = $line[5];
                        $calling_code = $line[6];
                        $common_name = $line[7];
                        $official_name = $line[8];
                        $endonym = $line[9];
                        $demonym = $line[10];
                        
                        $insertId = $this->insertCountry($continent_code, $currency_code, $iso2_code, $iso3_code, $iso_numeric_code, $fips_code, $calling_code, $common_name, $official_name, $endonym, $demonym);
                        if (! empty($insertId)) {
                            $output["type"] = "success";
                            $output["message"] = "Import completed.";
                            $importCount ++;
                        }
                    }
                    
                    // Close opened CSV file
                    fclose($csvFile);
                    
                    $qstring = '?status=success';
                }else{
                    $qstring = '?status=err';
                }
            }else{
                $qstring = '?status=invalid_file';
            }
        
        
        // Redirect to the listing page
        if($qstring){
            header('Location: '.$_SERVER['PHP_SELF'] . $qstring);
        }
        /*if($qstring == '?status=succ'){
            header('Location: '.$_SERVER['PHP_SELF'] . $qstring);
        }
        else{
            header('Location: '.$_SERVER['PHP_SELF'] . $qstring);
        }*/
        
    }

    function hasEmptyRow(array $column)
    {
        $columnCount = count($column);
        $isEmpty = true;
        for ($i = 0; $i < $columnCount; $i ++) {
            if (! empty($column[$i]) || $column[$i] !== '') {
                $isEmpty = false;
            }
        }
        return $isEmpty;
    }

    function insertCountry($continent_code, $currency_code, $iso2_code, $iso3_code, $iso_numeric_code, $fips_code, $calling_code, $common_name, $official_name, $endonym, $demonym)
    {
        $sql = "SELECT iso_numeric_code FROM countries WHERE iso_numeric_code = ?";
        $paramType = "s";
        $paramArray = array(
            $iso_numeric_code
        );
        $result = $this->select($sql, $paramType, $paramArray);
        $insertId = 0;
        if (empty($result)) {
            $sql = "INSERT into countries (continent_code,currency_code,iso2_code,iso3_code,iso_numeric_code,fips_code,calling_code,common_name,official_name,endonym,demonym)
                       values (?,?,?,?,?,?,?,?,?,?,?)";
            $paramType = "sssssssssss";
            $paramArray = array(
                $continent_code,
                $currency_code,
                $iso2_code,
                $iso3_code,
                $iso_numeric_code,
                $fips_code,
                $calling_code,
                $common_name,
                $official_name,
                $endonym,
                $demonym

            );
            $insertId = $this->insert($sql, $paramType, $paramArray);
        }
        return $insertId;
    }

    function readCurrencyRecords()
    {
    
            // Allowed mime types
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
            
            // Validate whether selected file is a CSV file
            if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
                
                // If the file is uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    
                    // Open uploaded CSV file with read-only mode
                    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                    
                    // Skip the first line
                    fgetcsv($csvFile);
                    $importCount = 0;
                    // Parse data from CSV file line by line
                    while(($line = fgetcsv($csvFile)) !== FALSE){
                        // Get row data
                        $iso_code   = $line[0];
                        $iso_numeric_code  = $line[1];
                        $common_name  = $line[2];
                        $official_name = $line[3];
                        $symbol = $line[4];
                        
                        $insertId = $this->insertCurrency($iso_code, $iso_numeric_code, $common_name, $official_name, $symbol);
                        if (! empty($insertId)) {
                            $output["type"] = "success";
                            $output["message"] = "Import completed.";
                            $importCount ++;
                        }
                    }
                    
                    // Close opened CSV file
                    fclose($csvFile);
                    
                    $qstring = '?status=success';
                }else{
                    $qstring = '?status=err';
                }
            }else{
                $qstring = '?status=invalid_file';
            }
        
        
        // Redirect to the listing page
        if($qstring){
            header('Location: '.$_SERVER['PHP_SELF'] . $qstring);
        }
        /*if($qstring == '?status=succ'){
            header('Location: '.$_SERVER['PHP_SELF'] . $qstring);
        }
        else{
            header('Location: '.$_SERVER['PHP_SELF'] . $qstring);
        }*/
        
    }

    function insertCurrency($iso_code, $iso_numeric_code, $common_name, $official_name, $symbol)
    {
        $sql = "SELECT iso_code FROM currencies WHERE iso_code = ?";
        $paramType = "s";
        $paramArray = array(
            $iso_code
        );
        $result = $this->select($sql, $paramType, $paramArray);
        $insertId = 0;
        if (empty($result)) {
            $sql = "INSERT into currencies (iso_code,iso_numeric_code,common_name,official_name,symbol)
                       values (?,?,?,?,?)";
            $paramType = "sssss";
            $paramArray = array(
                $iso_code,
                $iso_numeric_code,
                $common_name,
                $official_name,
                $symbol

            );
            $insertId = $this->insert($sql, $paramType, $paramArray);
        }
        return $insertId;
    }
}