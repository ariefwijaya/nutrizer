<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once FCPATH.'vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\Style\BorderBuilder;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\Border;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

if (!function_exists('exportExcel')) {
    function exportExcel($columnExcel,$rowExcel,$fileName)
    {
        $CI = &get_instance();

        $pathFile = $fileName;
    
        $borderHeader = (new BorderBuilder())
                ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_DOUBLE)
                ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                ->build();

        $styleHeader = (new StyleBuilder())
           ->setFontBold()
           ->setFontColor( Color::BLACK)
           ->setBorder($borderHeader)
           ->setBackgroundColor( Color::rgb(208, 230, 255))
           ->build();

        $borderBody = (new BorderBuilder())
           ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
           ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
           ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
           ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
           ->build();

        $styleBody = (new StyleBuilder())
            ->setFontColor( Color::BLACK)
            ->setBorder($borderBody)
            ->build();

        $styleTitle = (new StyleBuilder())
            ->setFontBold()
            ->setFontColor( Color::BLACK)
            ->build();

        $writer = WriterEntityFactory::createXLSXWriter(); // for XLSX files
        $writer->openToFile(FCPATH.$pathFile); 
        
        //set Header
        $rowsHeader = array();
        foreach ($columnExcel as $key => $value) {
        //    if($value=="id") continue;
        
           $rowValue = getFormattedColumnNameUpper($value);
           $rowsHeader[]= $rowValue;
        }
         /** Create a row with cells and apply the style to all cells */
         $row = WriterEntityFactory::createRowFromArray($rowsHeader, $styleHeader);

         /** Add the row to the writer */
         $writer->addRow($row);

        foreach ($rowExcel as $key => $value) {
            $rowsBody = array();
            foreach ($columnExcel as $keySub => $valueSub) {
                // if($valueSub=="id") continue;
                if(isset($value[$valueSub])){
                    $cellVal = $value[$valueSub];
                }else{
                    $cellVal=null;
                }   
                if(is_numeric($cellVal)){
                    $cellVal = $cellVal+0;
                }

                $rowsBody[] =  $cellVal;
            }

            /** Create a row with cells and apply the style to all cells */
            $row = WriterEntityFactory::createRowFromArray($rowsBody, $styleBody);

            /** Add the row to the writer */
            $writer->addRow($row);

        }
        $writer->close();
        return base_url().$pathFile;
    }
}