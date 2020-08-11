<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('array_merge_recursive_distinct'))
{   
     function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }
}

if ( ! function_exists('leadingZeroMonth'))
{   
    //Get Column Only
    function leadingZeroMonth($monthNumber)
    {
       $monthNumber = (string)$monthNumber;
       if(strlen($monthNumber)==1){
            return "0".$monthNumber;
       }else{
           return $monthNumber;
       }
    }   
}


if ( ! function_exists('getMonthName'))
{   
    //Get Column Only
    function getMonthName($monthNumber,$short=false)
    {
     
        $months = array("JANUARY",
                        "FEBRUARY",
                        "MARCH",
                        "APRIL",
                        "MAY",
                        "JUNE",
                        "JULY",
                        "AUGUST",
                        "SEPTEMBER",
                        "OCTOBER",
                        "NOVEMBER",
                        "DECEMBER"
                    );

        if(!is_numeric($monthNumber) && ($monthNumber>11 && $monthNumber<0)){
            return "Unknown";
        }
        else{
            $month = $months[$monthNumber];
            if($short){
                $month  = substr($month,0,3);
            }
            return $month;
        }
    }   
}

if ( ! function_exists('getMonthNumber'))
{   
    //Get Column Only
    function getMonthNumber($monthName,$leadingZero=false)
    {
        $substrMonth = substr($monthName,0,3);

        $months = array("JAN"=>"1",
                        "FEB"=>"2",
                        "MAR"=>"3",
                        "APR"=>"4",
                        "MAY"=>"5",
                        "JUN"=>"6",
                        "JUL"=>"7",
                        "AUG"=>"8",
                        "SEP"=>"9",
                        "OCT"=>"10",
                        "NOV"=>"11",
                        "DEC"=>"12"
                    );
        $monthNumber = $months[$substrMonth];

        if($leadingZero==true){ 
            if( strlen($monthNumber)==1){
                return "0".$monthNumber;
            }else{
                return $monthNumber;
            }
        }else{
            return $monthNumber;
        }
    }   
}

if ( ! function_exists('getFormattedColumnName'))
{   
    //Get Column Only
    function getFormattedColumnName($column,$delimiter="_")
    {
        if(!is_string($column)) return "";
        $lowerStr = strtolower($column);
        $delimittedStr = str_replace($delimiter," ",$lowerStr);
        $formattedStr = ucwords($delimittedStr);
        return $formattedStr;
    }   
    
}

if ( ! function_exists('arrayMultiDimUnique'))
{   
    //Get Column Only
    function arrayMultiDimUnique($array, $key) { 
        $temp_array = array(); 
        $i = 0; 
        $key_array = array(); 
        
        foreach($array as $val) { 
            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i] = $val[$key]; 
                $temp_array[$i] = $val; 
            } 
            $i++; 
        } 
        return $temp_array; 
    }  
    
}

if ( ! function_exists('getFormattedColumnNameUpper'))
{   
    //Get Column Only
    function getFormattedColumnNameUpper($column,$delimiter="_")
    {
        if(!is_string($column)) return "";
        $lowerStr = strtolower($column);
        $delimittedStr = str_replace($delimiter," ",$lowerStr);
        $formattedStr = strtoupper($delimittedStr);
        return $formattedStr;
    }   
    
}

if ( ! function_exists('convertCurrency'))
{   
    function convertCurrency($amount,$from_currency,$to_currency,$date_cur=""){
        $apikey = '4e91c7a33dd77a0d04f1';
        $dateParam = "now" ;
        if($date_cur!=""){
            $dateParam=$date_cur;
        }
        
        $date = new DateTime($dateParam, new DateTimeZone('Asia/Jakarta') );
        $dateOnly = $date->format('Y-m-d');
        $dateTime = $date->format('Y-m-d H:i:s');

        $from_Currency = urlencode($from_currency);
        $to_Currency = urlencode($to_currency);
        $query =  "{$from_Currency}_{$to_Currency}";

        $url = "https://free.currencyconverterapi.com/api/v6/convert?apiKey={$apikey}&q={$query}&compact=ultra&date={$dateOnly}";
       
        // echo $url; die();
        $proxy = '10.35.2.6:3128';

        // create curl resource
        $ch = curl_init();

        // set options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // read more about HTTPS http://stackoverflow.com/questions/31162706/how-to-scrape-a-ssl-or-https-url/31164409#31164409
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // $output contains the output string
        $json = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch); 

        $obj = json_decode($json, true);
      
        $val = floatval($obj["$query"]["$dateOnly"]);
      
        $total = $val * $amount;
        return $total;
      }
    
}


if ( ! function_exists('convertCurrencyBatch'))
{   
    function convertCurrencyBatch($amount,$listCurrency=array(),$date_cur=""){
        $apikey = '4e91c7a33dd77a0d04f1';
        $dateParam = "now" ;
        if($date_cur!=""){
            $dateParam=$date_cur;
        }
        
        $date = new DateTime($dateParam, new DateTimeZone('Asia/Jakarta') );
        $dateOnly = $date->format('Y-m-d');
        $dateTime = $date->format('Y-m-d H:i:s');

        $queryList = "";
        if(!is_array($listCurrency)) return "invalid format";
        
        $lengthList = count($listCurrency);
        for ($i=0; $i < $lengthList; $i++) { 
            $pair = $listCurrency[$i]['country_from']."_".$listCurrency[$i]['country_to'];
            $queryList.=$pair;
                if($i>=0 && $i<$lengthList-1)
                {
                    $queryList.=",";
                }
        }
        $queryListEncoded = urlencode($queryList);
        $query =  "{$queryListEncoded}";

        $url = "https://free.currencyconverterapi.com/api/v6/convert?apiKey={$apikey}&q={$query}&compact=ultra&date={$dateOnly}";
       
        // echo $url; die();
        $proxy = '10.35.2.6:3128';

        // create curl resource
        $ch = curl_init();

        // set options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // read more about HTTPS http://stackoverflow.com/questions/31162706/how-to-scrape-a-ssl-or-https-url/31164409#31164409
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // $output contains the output string
        $json = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch); 

        $obj = json_decode($json, true);
        
        if(is_null($obj)) return "invalid result";

        if(array_key_exists("status",$obj)){
            if($obj['status']==400){
                return "invalid result";
            }
        } 
        
        $valArray = array();
        foreach ($listCurrency as $key => $value) {
            $countryFrom = $value["country_from"];
            $countryTo = $value["country_to"];
            $factor = (double)($value['conv_factor']);
            $pair = $countryFrom."_".$countryTo;
            if(array_key_exists($dateOnly,$obj["$pair"])){
                $valExchange = (double)($obj["$pair"]["$dateOnly"]);
                if($amount==FALSE){
                    $total =$valExchange*1;
                }else{
                    $total = $valExchange * $amount;
                }
                
                $valArray[]=array(
                                "code"=>$pair,
                                "country_from"=>$countryFrom,
                                "country_to"=>$countryTo,
                                "currency_ori"=>$total,
                                "last_update"=>$dateTime
                            );
            }
        }

        return $valArray;
      }
    
}



if ( ! function_exists('extract_value'))
{   
     function extract_value($arrayData)
    {
        $data =[];
        foreach ($arrayData as $key => $value) {
            foreach ($value as $keyS => $valueS) {
                array_push($data,$valueS);
            }
        }
        return $data;
    }
}

// if ( ! function_exists('getConditionSqlByName'))
// {   
//     //Get Column Only
//     function getConditionSqlByName($name)
//     {
        
//         if($name=="equals"){
//             $conOp="=";
//         }
//         else if($name=="notEqual"){
//             $conOp="!=";
//         }
//         else if($name=="lessThan"){
//             $conOp="<";
//         }
//         else if($name=="lessThanOrEqual"){
//             $conOp="<=";
//         }
//         else if($name=="greaterThan"){
//             $conOp=">";
//         }
//         else if($name=="greaterThanOrEqual"){
//             $conOp=">=";
//         }
//         else{
//             $conOp = "";
//         }

//         return $conOp;

//     }   
    
// }

