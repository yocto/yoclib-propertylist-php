<?php
namespace YOCLIB\PropertyList;

use DateTimeInterface;

class PropertyList{

    public const FLAG_FORCE_QUOTATION_MARKS = 'force_quotation_marks';

    public const FORMAT_ASCII = 'ascii';
    public const FORMAT_ASCII_GNUSTEP = 'ascii_gnustep';
    public const FORMAT_BINARY_GNUSTEP = 'binary_gnustep';
    public const FORMAT_XML = 'xml';
    public const FORMAT_BINARY = 'binary';
    public const FORMAT_JSON = 'json';

    public const TYPE_ARRAY = 'array';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_DATA = 'data';
    public const TYPE_DATE = 'date';
    public const TYPE_DICTIONARY = 'dictionary';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_REAL = 'real';
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
        if($type===self::TYPE_ARRAY && is_array($object) && self::array_is_list($object)){
            return true;
        }
        if($type===self::TYPE_BOOLEAN && is_bool($object)){
            return true;
        }
        if($type===self::TYPE_DATA && is_string($object) && !empty($object) && !ctype_print($object)){
            return true;
        }
        if($type===self::TYPE_DATE && $object instanceof DateTimeInterface){
            return true;
        }
        if($type===self::TYPE_DICTIONARY && (is_object($object) || (is_array($object) && !self::array_is_list($object)))){
            return true;
        }
        if($type===self::TYPE_INTEGER && is_integer($object)){
            return true;
        }
        if($type===self::TYPE_REAL && is_float($object)){
            return true;
        }
        if($type===self::TYPE_STRING && is_string($object)){
            return true;
        }
        return false;
    }

    /**
     * @param mixed|DateTimeInterface $object
     * @param $format
     * @param $flags
     * @return false|string
     */
    private static function serializeObject($object,$format,$flags=0){
        if(self::isType($object,self::TYPE_ARRAY)){
            if($format===self::FORMAT_ASCII){
                $arr = [];
                foreach($object AS $item){
                    $arr[] = self::serializeObject($item,$format,$flags);
                }
                return '('.implode(', ',$arr).')';
            }
            if($format===self::FORMAT_ASCII_GNUSTEP){
                $arr = [];
                foreach($object AS $item){
                    $arr[] = self::serializeObject($item,$format,$flags);
                }
                return '('.implode(', ',$arr).')';
            }
            //TODO GNU Binary
            if($format===self::FORMAT_XML){
                $arr = '<array>';
                foreach($object AS $item){
                    $arr .= self::serializeObject($item,$format,$flags);
                }
                $arr .= '</array>';
                return $arr;
            }
            //TODO Binary
            if($format===self::FORMAT_JSON){
                $arr = [];
                foreach($object AS $item){
                    $arr[] = json_decode(self::serializeObject($item,$format,$flags));
                }
                return json_encode($arr);
            }
        }
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
        if(self::isType($object,self::TYPE_DATA)){
            if($format===self::FORMAT_ASCII){
                return '<'.implode(' ',str_split(strtoupper(bin2hex($object)),16*2)).'>';
            }
            if($format===self::FORMAT_ASCII_GNUSTEP){
                return '<'.implode(' ',str_split(strtoupper(bin2hex($object)),16*2)).'>';
            }
            //TODO GNU Binary
            if($format===self::FORMAT_XML){
                return '<data>'.base64_encode($object).'</data>';
            }
            //TODO Binary
            if($format===self::FORMAT_JSON){
                return json_encode(utf8_encode($object));
            }
        }
        if(self::isType($object,self::TYPE_DATE)){
            if($format===self::FORMAT_ASCII){
                //TODO Fix flag: Force Quotation Marks
                //TODO String casted date format
                $timezone = $object->format('O');
                return '"'.$object->format('Y-m-d H:i:s ').($timezone==='+0000'?'Z':$timezone).'"';
            }
            if($format===self::FORMAT_ASCII_GNUSTEP){
                $timezone = $object->format('O');
                return '<*D'.$object->format('Y-m-d H:i:s ').($timezone==='+0000'?'Z':$timezone).'>';
            }
            //TODO GNU Binary
            if($format===self::FORMAT_XML){
                $timezone = $object->format('O');
                return '<date>'.$object->format('Y-m-d\\TH:i:s').($timezone==='+0000'?'Z':$timezone).'</date>';
            }
            //TODO Binary
            if($format===self::FORMAT_JSON){
                $timezone = $object->format('O');
                return json_encode($object->format('Y-m-d\\TH:i:s').($timezone==='+0000'?'Z':$timezone));
            }
        }
        if(self::isType($object,self::TYPE_DICTIONARY)){
            if($format===self::FORMAT_ASCII){
                $dict = [];
                foreach($object AS $key=>$item){
                    $dict[] = self::serializeObject($key,$format,$flags).' = '.self::serializeObject($item,$format,$flags).';';
                }
                return '{'.implode(' ',$dict).'}';
            }
            if($format===self::FORMAT_ASCII_GNUSTEP){
                $dict = [];
                foreach($object AS $key=>$item){
                    $dict[] = self::serializeObject($key,$format,$flags).' = '.self::serializeObject($item,$format,$flags).';';
                }
                return '{'.implode(' ',$dict).'}';
            }
            //TODO GNU Binary
            if($format===self::FORMAT_XML){
                $dict = '<dict>';
                foreach($object AS $key=>$item){
                    $dict .= '<key>'.htmlentities($key).'</key>';
                    $dict .= self::serializeObject($item,$format,$flags);
                }
                $dict .= '</dict>';
                return $dict;
            }
            //TODO Binary
            if($format===self::FORMAT_JSON){
                $dict = [];
                foreach($object AS $key=>$item){
                    $dict[$key] = json_decode(self::serializeObject($item,$format,$flags));
                }
                return json_encode((object) $dict);
            }
        }
        if(self::isType($object,self::TYPE_INTEGER)){
            if($format===self::FORMAT_ASCII){
                //TODO Fix flag: Force Quotation Marks
                return '"'.$object.'"';
            }
            if($format===self::FORMAT_ASCII_GNUSTEP){
                //TODO Fix flag: Force Quotation Marks
                return '<*I'.$object.'>';
            }
            //TODO GNU Binary
            if($format===self::FORMAT_XML){
                return '<integer>'.htmlentities($object).'</integer>';
            }
            //TODO Binary
            if($format===self::FORMAT_JSON){
                return json_encode($object);
            }
        }
        if(self::isType($object,self::TYPE_REAL)){
            if($format===self::FORMAT_ASCII){
                //TODO Fix flag: Force Quotation Marks
                return '"'.$object.'"';
            }
            if($format===self::FORMAT_ASCII_GNUSTEP){
                //TODO Fix flag: Force Quotation Marks
                return '<*R'.$object.'>';
            }
            //TODO GNU Binary
            if($format===self::FORMAT_XML){
                return '<real>'.htmlentities($object).'</real>';
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

    /**
     * @param array $arr
     * @return bool
     */
    private static function array_is_list(array $arr): bool{
        if(function_exists('array_is_list')){
            return array_is_list($arr);
        }
        if($arr===[]){
            return true;
        }
        return array_keys($arr)===range(0,count($arr)-1);
    }

}