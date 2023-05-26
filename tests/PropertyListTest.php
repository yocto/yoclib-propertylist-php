<?php
namespace YOCLIB\PropertyList\Tests;

use DateTime;
use stdClass;

use PHPUnit\Framework\TestCase;

use YOCLIB\PropertyList\PropertyList;

class PropertyListTest extends TestCase{

    public function testSerializingEmptyPropertyList(){
        $plist = new PropertyList;

        self::assertEquals('',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithEmptyArray(){
        $plist = new PropertyList;
        $plist->setObject([]);

        self::assertEquals('()',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('()',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array></array></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('[]',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithEmptyObject(){
        $plist = new PropertyList;
        $plist->setObject(new stdClass);

        self::assertEquals('{}',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('{}',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><dict></dict></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('{}',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithEmptyString(){
        $plist = new PropertyList;
        $plist->setObject('');

        self::assertEquals('""',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('""',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><string></string></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('""',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithBooleanTrue(){
        $plist = new PropertyList;
        $plist->setObject(true);

        self::assertEquals('"true"',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('<*BY>',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><true/></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('true',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithBooleanFalse(){
        $plist = new PropertyList;
        $plist->setObject(false);

        self::assertEquals('"false"',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('<*BN>',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><false/></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('false',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithDate(){
        $plist = new PropertyList;
        $plist->setObject(new DateTime('2023-05-25 15:16:17 +00:00'));

        self::assertEquals('"2023-05-25 15:16:17 Z"',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('<*D2023-05-25 15:16:17 Z>',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><date>2023-05-25T15:16:17Z</date></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('"2023-05-25T15:16:17Z"',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithInteger(){
        $plist = new PropertyList;
        $plist->setObject(1234);

        self::assertEquals('"1234"',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('<*I1234>',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><integer>1234</integer></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('1234',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithReal(){
        $plist = new PropertyList;
        $plist->setObject(12.34);

        self::assertEquals('"12.34"',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('<*R12.34>',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><real>12.34</real></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('12.34',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithString(){
        $plist = new PropertyList;
        $plist->setObject('Hello there');

        self::assertEquals('"Hello there"',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('"Hello there"',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><string>Hello there</string></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('"Hello there"',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithArray(){
        $plist = new PropertyList;
        $plist->setObject([true,false,new DateTime('2023-05-25 15:16:17 +00:00'),1234,12.34,'Hello there']);

        self::assertEquals('("true", "false", "2023-05-25 15:16:17 Z", "1234", "12.34", "Hello there")',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('(<*BY>, <*BN>, <*D2023-05-25 15:16:17 Z>, <*I1234>, <*R12.34>, "Hello there")',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array><true/><false/><date>2023-05-25T15:16:17Z</date><integer>1234</integer><real>12.34</real><string>Hello there</string></array></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('[true,false,"2023-05-25T15:16:17Z",1234,12.34,"Hello there"]',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testSerializingPropertyListWithDictionary(){
        $plist = new PropertyList;
        $plist->setObject((object) ['true key'=>true,'false key'=>false,'date key'=>new DateTime('2023-05-25 15:16:17 +00:00'),'integer key'=>1234,'real key'=>12.34,'string key'=>'Hello there']);

        self::assertEquals('{"true key" = "true"; "false key" = "false"; "date key" = "2023-05-25 15:16:17 Z"; "integer key" = "1234"; "real key" = "12.34"; "string key" = "Hello there";}',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('{"true key" = <*BY>; "false key" = <*BN>; "date key" = <*D2023-05-25 15:16:17 Z>; "integer key" = <*I1234>; "real key" = <*R12.34>; "string key" = "Hello there";}',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><dict><key>true key</key><true/><key>false key</key><false/><key>date key</key><date>2023-05-25T15:16:17Z</date><key>integer key</key><integer>1234</integer><key>real key</key><real>12.34</real><key>string key</key><string>Hello there</string></dict></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('{"true key":true,"false key":false,"date key":"2023-05-25T15:16:17Z","integer key":1234,"real key":12.34,"string key":"Hello there"}',$plist->serialize(PropertyList::FORMAT_JSON));
    }

}