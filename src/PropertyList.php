<?php
namespace YOCLIB\PropertyList;

use DateTime;
use DateTimeInterface;
use Exception;
use SimpleXMLElement;

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

    public function deserialize($format,$input){
        if($format===self::FORMAT_ASCII){
            $this->object = self::deserializeObject($input,$format);
        }
        if($format===self::FORMAT_ASCII_GNUSTEP){
            $this->object = self::deserializeObject($input,$format);
        }
        //TODO GNU Binary
        if($format===self::FORMAT_XML){
            try{
                $plist = new SimpleXMLElement($input);
                $object = $plist->children()[0] ?? null;
                if($object){
                    $this->object = self::deserializeObject($plist->children()[0],$format);
                }
            }catch(Exception $e){
            }
        }
        //TODO Binary
        if($format===self::FORMAT_JSON){
            $this->object = self::deserializeObject(json_decode($input),$format);
        }
        return $this;
    }

    /**
     * @param $format
     * @return string|null
     */
    public function serialize($format){
        if($format===self::FORMAT_ASCII){
            return self::serializeObject($this->object,$format);
        }
        if($format===self::FORMAT_ASCII_GNUSTEP){
            return self::serializeObject($this->object,$format);
        }
        //TODO GNU Binary
        if($format===self::FORMAT_XML){
            return '<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="'.$this->version.'">'.self::serializeObject($this->object,$format).'</plist>';
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

    private static function deserializeObject($object,$format,$flags=0,&$hierarchyLength=0,$isXMLKey=false){
        if($format===self::FORMAT_ASCII){
            $type = null;
            $quotationString = false;
            for($i=0;$i<strlen($object);$i++){
                if($type==null){
                    if($object[$i]==='"'){
                        $type = self::TYPE_STRING;
                        $quotationString = true;
                        break;
                    }
                    if($object[$i]==='<'){
                        $type = self::TYPE_DATA;
                        break;
                    }
                    if($object[$i]==='('){
                        $type = self::TYPE_ARRAY;
                        break;
                    }
                    if($object[$i]==='{'){
                        $type = self::TYPE_DICTIONARY;
                        break;
                    }
                    if(ctype_alnum($object[$i])){
                        $type = self::TYPE_STRING;
                    }
                }
            }
            $index = $i;

            if($type===self::TYPE_ARRAY){
                $array = [];
                for($i=1;$i<strlen($object);$i++){
                    $len = 0;
                    $obj = self::deserializeObject(substr($object,$i),$format,$flags,$len);
                    if($obj===null){
                        break;
                    }
                    $i += $len;
                    $array[] = $obj;
                    for($j=$i;$j<strlen($object);$j++){
                        $i++;
                        if($object[$j]===','){
                            break 1;
                        }
                        if($object[$j]===')'){
                            $hierarchyLength = $j-$index+1;
                            break 2;
                        }
                    }
                }
                return $array;
            }
            if($type===self::TYPE_DATA){
                $data = '';
                for($i=$index+1;$i<strlen($object);$i++){
                    if($object[$i]==='>'){
                        $hierarchyLength = $i-$index;
                        break;
                    }
                    $data .= $object[$i];
                }
                return hex2bin(str_replace(' ','',$data));
            }
            if($type===self::TYPE_DICTIONARY){
                $dict = [];
                for($i=1;$i<strlen($object);$i++){
                    $len = 0;
                    $key = self::deserializeObject(substr($object,$i),$format,$flags,$len);
                    if($key===null){
                        break;
                    }
                    $i += $len;
                    for($j=$i;$j<strlen($object);$j++){
                        $i++;
                        $len++;
                        if($object[$j]==='='){
                            break 1;
                        }
                    }
                    $i++;//TMP
                    $obj = self::deserializeObject(substr($object,$i),$format,$flags,$len);
                    if($obj===null){
                        break;
                    }
                    $i += $len;
                    $dict[$key] = $obj;
                    for($j=$i;$j<strlen($object);$j++){
                        $i++;
                        if($object[$j]===';'){
                            break 1;
                        }
                        if($object[$j]==='}'){
                            $hierarchyLength = $i-$index;
                            break 2;
                        }
                    }
                }
                return (object) $dict;
            }
            if($type==self::TYPE_STRING){
                $string = '';
                if($quotationString){
                    for($i=$index+1;$i<strlen($object);$i++){
                        if($object[$i]==='"'){
                            $hierarchyLength = $i-$index;
                            break;
                        }
                        $string .= $object[$i];
                    }
                }else{
                    for($i=$index;$i<strlen($object);$i++){
                        if(!ctype_alnum($object[$i])){
                            $hierarchyLength = $i-$index;
                            break;
                        }
                        $string .= $object[$i];
                    }
                }
                $d1 = DateTime::createFromFormat('Y-m-d H:i:s O',$string);
                $d2 = DateTime::createFromFormat('Y-m-d H:i:s \\Z',$string);
                if($d1 || $d2){//TODO Add flags
                    return $d1 ?? $d2;
                }
                if($string=='true'){//TODO Add flags
                    return true;
                }
                if($string=='false'){//TODO Add flags
                    return false;
                }
                return $string;
            }
            return null;
        }
        if($format===self::FORMAT_ASCII_GNUSTEP){
            $type = null;
            $quotationString = false;
            for($i=0;$i<strlen($object);$i++){
                if($type==null){
                    if($object[$i]==='"'){
                        $type = self::TYPE_STRING;
                        $quotationString = true;
                        break;
                    }
                    if($object[$i]==='<' && ($object[$i+1] ?? -1)==='*'){
                        $subType = ($object[$i+2] ?? -1);
                        if($subType==='B'){
                            $type = self::TYPE_BOOLEAN;
                        }
                        if($subType==='D'){
                            $type = self::TYPE_DATE;
                        }
                        if($subType==='I'){
                            $type = self::TYPE_INTEGER;
                        }
                        if($subType==='R'){
                            $type = self::TYPE_REAL;
                        }
                        break;
                    }
                    if($object[$i]==='<'){
                        $type = self::TYPE_DATA;
                        break;
                    }
                    if($object[$i]==='('){
                        $type = self::TYPE_ARRAY;
                        break;
                    }
                    if($object[$i]==='{'){
                        $type = self::TYPE_DICTIONARY;
                        break;
                    }
                    if(ctype_alnum($object[$i])){
                        $type = self::TYPE_STRING;
                    }
                }
            }
            $index = $i;

            if($type===self::TYPE_ARRAY){
                $array = [];
                for($i=1;$i<strlen($object);$i++){
                    $len = 0;
                    $obj = self::deserializeObject(substr($object,$i),$format,$flags,$len);
                    if($obj===null){
                        break;
                    }
                    $i += $len;
                    $array[] = $obj;
                    for($j=$i;$j<strlen($object);$j++){
                        $i++;
                        if($object[$j]===','){
                            break 1;
                        }
                        if($object[$j]===')'){
                            $hierarchyLength = $i-$index;
                            break 2;
                        }
                    }
                }
                return $array;
            }
            if($type===self::TYPE_BOOLEAN){
                $boolean = '';
                for($i=$index+3;$i<strlen($object);$i++){
                    if($object[$i]==='>'){
                        $hierarchyLength = $i-$index;
                        break;
                    }
                    $boolean .= $object[$i];
                }
                if($boolean==='Y'){
                    return true;
                }
                if($boolean==='N'){
                    return false;
                }
                return null;
            }
            if($type===self::TYPE_DATA){
                $data = '';
                for($i=$index+1;$i<strlen($object);$i++){
                    if($object[$i]==='>'){
                        $hierarchyLength = $i-$index;
                        break;
                    }
                    $data .= $object[$i];
                }
                return hex2bin(str_replace(' ','',$data));
            }
            if($type===self::TYPE_DATE){
                $date = '';
                for($i=$index+3;$i<strlen($object);$i++){
                    if($object[$i]==='>'){
                        $hierarchyLength = $i-$index;
                        break;
                    }
                    $date .= $object[$i];
                }
                $d1 = DateTime::createFromFormat('Y-m-d H:i:s O',$date);
                $d2 = DateTime::createFromFormat('Y-m-d H:i:s \\Z',$date);
                if($d1 || $d2){
                    return $d1 ?? $d2;
                }
                return null;
            }
            if($type===self::TYPE_DICTIONARY){
                $dict = [];
                for($i=1;$i<strlen($object);$i++){
                    $len = 0;
                    $key = self::deserializeObject(substr($object,$i),$format,$flags,$len);
                    if($key===null){
                        break;
                    }
                    $i += $len;
                    for($j=$i;$j<strlen($object);$j++){
                        $i++;
                        $len++;
                        if($object[$j]==='='){
                            break 1;
                        }
                    }
                    $i++;//TMP
                    $obj = self::deserializeObject(substr($object,$i),$format,$flags,$len);
                    if($obj===null){
                        break;
                    }
                    $i += $len;
                    $dict[$key] = $obj;
                    for($j=$i;$j<strlen($object);$j++){
                        $i++;
                        if($object[$j]===';'){
                            break 1;
                        }
                        if($object[$j]==='}'){
                            $hierarchyLength = $i-$index;
                            break 2;
                        }
                    }
                }
                return (object) $dict;
            }
            if($type===self::TYPE_INTEGER){
                $integer = '';
                for($i=$index+3;$i<strlen($object);$i++){
                    if($object[$i]==='>'){
                        $hierarchyLength = $i-$index;
                        break;
                    }
                    $integer .= $object[$i];
                }
                return intval($integer);
            }
            if($type===self::TYPE_REAL){
                $real = '';
                for($i=$index+3;$i<strlen($object);$i++){
                    if($object[$i]==='>'){
                        $hierarchyLength = $i-$index;
                        break;
                    }
                    $real .= $object[$i];
                }
                return floatval($real);
            }
            if($type===self::TYPE_STRING){
                $string = '';
                if($quotationString){
                    for($i=$index+1;$i<strlen($object);$i++){
                        if($object[$i]==='"'){
                            $hierarchyLength = $i-$index;
                            break;
                        }
                        $string .= $object[$i];
                    }
                }else{
                    for($i=$index;$i<strlen($object);$i++){
                        if(!ctype_alnum($object[$i])){
                            break;
                        }
                        $string .= $object[$i];
                    }
                }
                return $string;
            }
            return null;
        }
        //TODO GNU Binary
        if($format===self::FORMAT_XML){
            $objectTag = $object->getName();
            if($isXMLKey){
                if($objectTag==='key'){
                    $string = (string) $object;
                    return trim($string);
                }
                return null;
            }
            if($objectTag==='array'){
                $arr = [];
                foreach($object->children() AS $child){
                    $arr[] = self::deserializeObject($child,$format);
                }
                return $arr;
            }
            if($objectTag==='true'){
                return true;
            }
            if($objectTag==='false'){
                return false;
            }
            if($objectTag==='data'){
                $data = (string) $object;
                return base64_decode($data);
            }
            if($objectTag==='date'){
                $date = (string) $object;
                $d1 = DateTime::createFromFormat('Y-m-d\\TH:i:sO',$date);
                $d2 = DateTime::createFromFormat('Y-m-d\\TH:i:s\\Z',$date);
                if($d1 || $d2){
                    return $d1 ?? $d2;
                }
            }
            if($objectTag==='dict'){
                $children = [];
                foreach($object->children() AS $child){
                    $children[] = $child;
                }
                $dict = [];
                for($i=0;$i<count($children);$i+=2){
                    $len = -1;
                    $key = self::deserializeObject($children[$i],$format,$flags,$len,true);
                    $obj = self::deserializeObject($children[$i+1],$format);
                    $dict[$key] = $obj;
                }
                return (object) $dict;
            }
            if($objectTag==='integer'){
                return (int) $object;
            }
            if($objectTag==='real'){
                return (double) $object;
            }
            if($objectTag==='string'){
                $string = (string) $object;
                return trim($string);
            }
        }
        //TODO Binary
        if($format===self::FORMAT_JSON){
            if(is_array($object)){
                $arr = [];
                foreach($object AS $item){
                    $arr[] = self::deserializeObject($item,$format,$flags);
                }
                return $arr;
            }
            if(is_bool($object)){
                return $object;
            }
            if(is_int($object)){
                return $object;
            }
            if(is_float($object)){
                return $object;
            }
            if(is_string($object)){
                $d1 = DateTime::createFromFormat('Y-m-d\\TH:i:sO',$object);
                $d2 = DateTime::createFromFormat('Y-m-d\\TH:i:s\\Z',$object);
                if($d1 || $d2){//TODO Add flags
                    return $d1 ?? $d2;
                }
                return utf8_decode($object);
            }
            if(is_object($object)){
                $arr = [];
                foreach($object AS $key=>$item){
                    $arr[$key] = self::deserializeObject($item,$format,$flags);
                }
                return (object) $arr;
            }
        }
        return null;
    }

    /**
     * @param mixed|DateTimeInterface $object
     * @param $format
     * @param $flags
     * @return string
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