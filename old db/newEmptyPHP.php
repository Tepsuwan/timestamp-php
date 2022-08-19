<?php

$url = "http://demo.assignment.viseno.com/vp_services.php#CreateAssignmentEx";
$xml = '<?xml version="1.0" encoding="UTF-8"?>'
        . '<CREATE_ASSIGNMENT_REQ>'
        . '<SERVICE_KEY>1b769e92-c918-11e7-bc43-06108be92400</SERVICE_KEY>'
        . '<PARTNER_USER_EMAIL>tom@baezeni.com</PARTNER_USER_EMAIL>'
        . '<PARTNER_USER_NAME>Tom</PARTNER_USER_NAME>'
        . '<NAME>project 01</NAME>'
        . '<PROSPECT_ID>001</PROSPECT_ID>'
        . '<COUNTRY_CODE>th</COUNTRY_CODE>'
        . '<LANGUAGE_CODE>en</LANGUAGE_CODE>'
        . '<ASSIGNMENT_FURNISHING>fai</ASSIGNMENT_FURNISHING>'
        . '<URGENT>false</URGENT>'
        . '<INCLUDE_WALK>false</INCLUDE_WALK>'
        . '<INCLUDE_ROOMSKETCHER>false</INCLUDE_ROOMSKETCHER>'
        . '<INCLUDE_2D>true</INCLUDE_2D>'
        . '<INCLUDE_3D>true</INCLUDE_3D>'
        . '<INCLUDE_MEASUREMENT>false</INCLUDE_MEASUREMENT>'
        . '<REVIEW_FLOORPLANS>false</REVIEW_FLOORPLANS>'
        . '<LEVEL_DETAILS><NAME>levleName-0</NAME>'
        . '<IS_LOCKED>false</IS_LOCKED>'
        . '</LEVEL_DETAILS>'
        . '<LEVEL_DETAILS>'
        . '<NAME>levleName-1</NAME>'
        . '<IS_LOCKED>false</IS_LOCKED>'
        . '</LEVEL_DETAILS>'
        . '</CREATE_ASSIGNMENT_REQ>';


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $input_xml);
$result = curl_exec($ch);
curl_close($ch);
var_dump($result);


$arr = array(
    "SERVICE_KEY" => "1b769e92-c918-11e7-bc43-06108be92400",
    "PARTNER_USER_EMAIL" => "tom@baezeni.com",
    "PARTNER_USER_NAME" => "Tom",
    "NAME" => "Project 01",
    "PROSPECT_ID" => "001",
    "COUNTRY_CODE" => "th",
    "LANGUAGE_CODE" => "en",
    "ASSIGNMENT_FURNISHING" => "fai",
    "URGENT" => "false",
    "INCLUDE_WALK" => "false",
    "INCLUDE_ROOMSKETCHER" => "false",
    "INCLUDE_2D" => "true",
    "INCLUDE_3D" => "true",
    "INCLUDE_MEASUREMENT" => "false",
    "REVIEW_FLOORPLANS" => "false",
    $this->getLevel()
);
$xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?>' . '<CREATE_ASSIGNMENT_REQ/>');
$this->mdXml->array2xml($arr, $xml_data);
$input_xml = $xml_data->asXML();

///////////////////////////////////////////////

Class Model_generatexml extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function array2xml($data, $xml = false) {
        foreach ($data as $k => $v) {
            $attrArr = array();
            $kArray = explode(' ', $k);
            $tag = array_shift($kArray);

            if (count($kArray) > 0) {
                foreach ($kArray as $attrValue) {
                    $attrArr[] = explode('=', $attrValue);
                }
            }

            if (is_array($v)) {
                if (is_numeric($k)) {
                    $this->array2xml($v, $xml);
                } else {
                    $child = $xml->addChild($tag);
                    if (isset($attrArr)) {
                        foreach ($attrArr as $attrArrV) {
                            $child->addAttribute($attrArrV[0], $attrArrV[1]);
                        }
                    }
                    $this->array2xml($v, $child);
                }
            } else {
                $child = $xml->addChild($tag, $v);
                if (isset($attrArr)) {
                    foreach ($attrArr as $attrArrV) {
                        $child->addAttribute($attrArrV[0], $attrArrV[1]);
                    }
                }
            }
        }
        return $xml;
    }

}
