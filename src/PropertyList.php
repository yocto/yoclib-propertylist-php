<?php
namespace YOCLIB\PropertyList;

class PropertyList{

    public const FLAG_FORCE_QUOTATION_MARKS = 'force_quotation_marks';

    public const FORMAT_ASCII = 'ascii';
    public const FORMAT_ASCII_GNUSTEP = 'ascii_gnustep';
    public const FORMAT_BINARY_GNUSTEP = 'binary_gnustep';
    public const FORMAT_XML = 'xml';
    public const FORMAT_BINARY = 'binary';
    public const FORMAT_JSON = 'json';

    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_STRING = 'string';

    public const VERSION_1_0 = '1.0';

    private $object;
    private $version = self::VERSION_1_0;

    /**
     * @return mixed
     */
    public function getObject(){
        return $this->object;
    }

    /**
     * @return mixed
     */
    public function getVersion(){
        return $this->version;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object): void{
        $this->object = $object;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version): void{
        $this->version = $version;
    }

    public function serialize($format){
        if($format===self::FORMAT_ASCII){
            return self::serializeObject($this->object,$format);
        }
        if($format===self::FORMAT_ASCII_GNUSTEP){
            return self::serializeObject($this->object,$format);
        }
        //TODO GNU Binary
        if($format===self::FORMAT_XML){
            $xml = '<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="'.$this->version.'">'.self::serializeObject($this->object,$format).'</plist>';
            return $xml;
        }
        //TODO Binary
        if($format===self::FORMAT_JSON){
            return self::serializeObject($this->object,$format);
        }
        return null;
    }

    private static function isType($object,$type){
        if($type===self::TYPE_BOOLEAN && is_bool($object)){
            return true;
        }
        if($type===self::TYPE_STRING && is_string($object)){
            return true;
        }
        return false;
    }

    private static function serializeObject($object,$format,$flags=0){
        if(self::isType($object,self::TYPE_BOOLEAN)){
            if($format===self::FORMAT_ASCII){
                //TODO Fix flag: Force Quotation Marks
                //TODO String casted booleans true/false or yes/no or other?
                return '"'.($object?'true':'false').'"';
            }
            if($format===self::FORMAT_ASCII_GNUSTEP){
                return '<*B'.($object?'Y':'N').'>';
            }
            //TODO GNU Binary
            if($format===self::FORMAT_XML){
                return $object?'<true/>':'<false/>';
            }
            //TODO Binary
            if($format===self::FORMAT_JSON){
                return json_encode($object);
            }
        }
        if(self::isType($object,self::TYPE_STRING)){
            if($format===self::FORMAT_ASCII){
                //TODO Fix flag: Force Quotation Marks
                return '"'.$object.'"';
            }
            if($format===self::FORMAT_ASCII_GNUSTEP){
                //TODO Fix flag: Force Quotation Marks
                return '"'.$object.'"';
            }
            //TODO GNU Binary
            if($format===self::FORMAT_XML){
                return '<string>'.htmlentities($object).'</string>';
            }
            //TODO Binary
            if($format===self::FORMAT_JSON){
                return json_encode($object);
            }
        }
        return '';
    }

}