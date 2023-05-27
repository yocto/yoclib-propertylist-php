<?php
namespace YOCLIB\PropertyList\Tests;

use DateTime;
use stdClass;

use PHPUnit\Framework\TestCase;

use YOCLIB\PropertyList\PropertyList;

class PropertyListTest extends TestCase{

    public function testDeserializingEmptyPropertyList(){
        $plist = new PropertyList;

        self::assertEquals(null,$plist->deserialize(PropertyList::FORMAT_ASCII,'')->getObject());
        self::assertEquals(null,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'')->getObject());
        //self::assertEquals(null,$plist->deserialize(PropertyList::FORMAT_BINARY_GNUSTEP,'')->getObject());
        self::assertEquals(null,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><a>huts</a></plist>')->getObject());
        //self::assertEquals(null,$plist->deserialize(PropertyList::FORMAT_BINARY));
        self::assertEquals(null,$plist->deserialize(PropertyList::FORMAT_JSON,'')->getObject());
    }

    public function testSerializingEmptyPropertyList(){
        $plist = new PropertyList;

        self::assertEquals('',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testDeserializingPropertyListWithEmptyArray(){
        $plist = new PropertyList;

        self::assertEquals([],$plist->deserialize(PropertyList::FORMAT_ASCII,'()')->getObject());
        self::assertEquals([],$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'()')->getObject());
        //self::assertEquals('',$plist->deserialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals([],$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array></array></plist>')->getObject());
        //self::assertEquals('',$plist->deserialize(PropertyList::FORMAT_BINARY));
        self::assertEquals([],$plist->deserialize(PropertyList::FORMAT_JSON,'[]')->getObject());
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

    public function testDeserializingPropertyListWithEmptyObject(){
        $plist = new PropertyList;

        self::assertEquals(new stdClass,$plist->deserialize(PropertyList::FORMAT_ASCII,'{}')->getObject());
        self::assertEquals(new stdClass,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'{}')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals(new stdClass,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><dict></dict></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals(new stdClass,$plist->deserialize(PropertyList::FORMAT_JSON,'{}')->getObject());
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

    public function testDeserializingPropertyListWithEmptyString(){
        $plist = new PropertyList;

        self::assertEquals('',$plist->deserialize(PropertyList::FORMAT_ASCII,'""')->getObject());
        self::assertEquals('',$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'""')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('',$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><string></string></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('',$plist->deserialize(PropertyList::FORMAT_JSON,'""')->getObject());
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

    public function testDeserializingPropertyListWithBooleanTrue(){
        $plist = new PropertyList;

        self::assertTrue($plist->deserialize(PropertyList::FORMAT_ASCII,'"true"')->getObject());
        self::assertTrue($plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'<*BY>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertTrue($plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><true/></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertTrue($plist->deserialize(PropertyList::FORMAT_JSON,'true')->getObject());
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

    public function testDeserializingPropertyListWithBooleanFalse(){
        $plist = new PropertyList;

        self::assertFalse($plist->deserialize(PropertyList::FORMAT_ASCII,'"false"')->getObject());
        self::assertFalse($plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'<*BN>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertFalse($plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><false/></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertFalse($plist->deserialize(PropertyList::FORMAT_JSON,'false')->getObject());
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

    public function testDeserializingPropertyListWithData(){
        $plist = new PropertyList;

        $data = chr(0).chr(1).chr(2).chr(3).chr(172);

        self::assertEquals($data,$plist->deserialize(PropertyList::FORMAT_ASCII,'<00010203AC>')->getObject());
        self::assertEquals($data,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'<00010203AC>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals($data,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><data>AAECA6w=</data></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals($data,$plist->deserialize(PropertyList::FORMAT_JSON,'"\u0000\u0001\u0002\u0003\u00ac"')->getObject());
    }

    public function testSerializingPropertyListWithData(){
        $plist = new PropertyList;
        $plist->setObject(chr(0).chr(1).chr(2).chr(3).chr(172));

        self::assertEquals('<00010203AC>',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('<00010203AC>',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><data>AAECA6w=</data></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('"\u0000\u0001\u0002\u0003\u00ac"',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testDeserializingPropertyListWithDate(){
        $plist = new PropertyList;

        $date = new DateTime('2023-05-25 15:16:17 +00:00');

        self::assertEquals($date,$plist->deserialize(PropertyList::FORMAT_ASCII,'"2023-05-25 15:16:17 Z"')->getObject());
        self::assertEquals($date,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'<*D2023-05-25 15:16:17 Z>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals($date,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><date>2023-05-25T15:16:17Z</date></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals($date,$plist->deserialize(PropertyList::FORMAT_JSON,'"2023-05-25T15:16:17Z"')->getObject());
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

    public function testDeserializingPropertyListWithInteger(){
        $plist = new PropertyList;

        self::assertEquals(1234,$plist->deserialize(PropertyList::FORMAT_ASCII,'"1234"')->getObject());
        self::assertEquals(1234,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'<*I1234>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals(1234,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><integer>1234</integer></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals(1234,$plist->deserialize(PropertyList::FORMAT_JSON,'1234')->getObject());
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

    public function testDeserializingPropertyListWithReal(){
        $plist = new PropertyList;

        self::assertEquals(12.34,$plist->deserialize(PropertyList::FORMAT_ASCII,'"12.34"')->getObject());
        self::assertEquals(12.34,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'<*R12.34>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals(12.34,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><real>12.34</real></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals(12.34,$plist->deserialize(PropertyList::FORMAT_JSON,'12.34')->getObject());
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

    public function testDeserializingPropertyListWithString(){
        $plist = new PropertyList;

        self::assertEquals('Hello there',$plist->deserialize(PropertyList::FORMAT_ASCII,'"Hello there"')->getObject());
        self::assertEquals('Hello there',$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'"Hello there"')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('Hello there',$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><string>Hello there</string></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('Hello there',$plist->deserialize(PropertyList::FORMAT_JSON,'"Hello there"')->getObject());
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

    public function testDeserializingPropertyListWithArray(){
        $plist = new PropertyList;

        $array = [true,false,chr(0).chr(1).chr(2).chr(3).chr(172),new DateTime('2023-05-25 15:16:17 +00:00'),1234,12.34,'Hello there'];

        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_ASCII,'("true", "false", <00010203AC>, "2023-05-25 15:16:17 Z", "1234", "12.34", "Hello there")')->getObject());
        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'(<*BY>, <*BN>, <00010203AC>, <*D2023-05-25 15:16:17 Z>, <*I1234>, <*R12.34>, "Hello there")')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array><true/><false/><data>AAECA6w=</data><date>2023-05-25T15:16:17Z</date><integer>1234</integer><real>12.34</real><string>Hello there</string></array></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_JSON,'[true,false,"\u0000\u0001\u0002\u0003\u00ac","2023-05-25T15:16:17Z",1234,12.34,"Hello there"]')->getObject());
    }

    public function testSerializingPropertyListWithArray(){
        $plist = new PropertyList;
        $plist->setObject([true,false,chr(0).chr(1).chr(2).chr(3).chr(172),new DateTime('2023-05-25 15:16:17 +00:00'),1234,12.34,'Hello there']);

        self::assertEquals('("true", "false", <00010203AC>, "2023-05-25 15:16:17 Z", "1234", "12.34", "Hello there")',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('(<*BY>, <*BN>, <00010203AC>, <*D2023-05-25 15:16:17 Z>, <*I1234>, <*R12.34>, "Hello there")',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array><true/><false/><data>AAECA6w=</data><date>2023-05-25T15:16:17Z</date><integer>1234</integer><real>12.34</real><string>Hello there</string></array></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('[true,false,"\u0000\u0001\u0002\u0003\u00ac","2023-05-25T15:16:17Z",1234,12.34,"Hello there"]',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testDeserializingPropertyListWithDictionary(){
        $plist = new PropertyList;

        $dict = (object) ['true key'=>true,'false key'=>false,'data key'=>chr(0).chr(1).chr(2).chr(3).chr(172),'date key'=>new DateTime('2023-05-25 15:16:17 +00:00'),'integer key'=>1234,'real key'=>12.34,'string key'=>'Hello there'];

        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_ASCII,'{"true key" = "true"; "false key" = "false"; "data key" = <00010203AC>; "date key" = "2023-05-25 15:16:17 Z"; "integer key" = "1234"; "real key" = "12.34"; "string key" = "Hello there";}')->getObject());
        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'{"true key" = <*BY>; "false key" = <*BN>; "data key" = <00010203AC>; "date key" = <*D2023-05-25 15:16:17 Z>; "integer key" = <*I1234>; "real key" = <*R12.34>; "string key" = "Hello there";}')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><dict><key>true key</key><true/><key>false key</key><false/><key>data key</key><data>AAECA6w=</data><key>date key</key><date>2023-05-25T15:16:17Z</date><key>integer key</key><integer>1234</integer><key>real key</key><real>12.34</real><key>string key</key><string>Hello there</string></dict></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_JSON,'{"true key":true,"false key":false,"data key":"\u0000\u0001\u0002\u0003\u00ac","date key":"2023-05-25T15:16:17Z","integer key":1234,"real key":12.34,"string key":"Hello there"}')->getObject());
    }

    public function testSerializingPropertyListWithDictionary(){
        $plist = new PropertyList;
        $plist->setObject((object) ['true key'=>true,'false key'=>false,'data key'=>chr(0).chr(1).chr(2).chr(3).chr(172),'date key'=>new DateTime('2023-05-25 15:16:17 +00:00'),'integer key'=>1234,'real key'=>12.34,'string key'=>'Hello there']);

        self::assertEquals('{"true key" = "true"; "false key" = "false"; "data key" = <00010203AC>; "date key" = "2023-05-25 15:16:17 Z"; "integer key" = "1234"; "real key" = "12.34"; "string key" = "Hello there";}',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('{"true key" = <*BY>; "false key" = <*BN>; "data key" = <00010203AC>; "date key" = <*D2023-05-25 15:16:17 Z>; "integer key" = <*I1234>; "real key" = <*R12.34>; "string key" = "Hello there";}',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><dict><key>true key</key><true/><key>false key</key><false/><key>data key</key><data>AAECA6w=</data><key>date key</key><date>2023-05-25T15:16:17Z</date><key>integer key</key><integer>1234</integer><key>real key</key><real>12.34</real><key>string key</key><string>Hello there</string></dict></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('{"true key":true,"false key":false,"data key":"\u0000\u0001\u0002\u0003\u00ac","date key":"2023-05-25T15:16:17Z","integer key":1234,"real key":12.34,"string key":"Hello there"}',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testDeserializingPropertyListWithArrayInDictionary(){
        $plist = new PropertyList;

        $dict = (object) ['key'=>[]];

        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_ASCII,'{"key" = ();}')->getObject());
        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'{"key" = ();}')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><dict><key>key</key><array></array></dict></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_JSON,'{"key":[]}')->getObject());
    }

    public function testSerializingPropertyListWithArrayInDictionary(){
        $plist = new PropertyList;
        $plist->setObject((object) ['key'=>[]]);

        self::assertEquals('{"key" = ();}',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('{"key" = ();}',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><dict><key>key</key><array></array></dict></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('{"key":[]}',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testDeserializingPropertyListWithArrayInArray(){
        $plist = new PropertyList;

        $array = [[]];

        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_ASCII,'(())')->getObject());
        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'(())')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array><array></array></array></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_JSON,'[[]]')->getObject());
    }

    public function testSerializingPropertyListWithArrayInArray(){
        $plist = new PropertyList;
        $plist->setObject([[]]);

        self::assertEquals('(())',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('(())',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array><array></array></array></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('[[]]',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testDeserializingPropertyListWithDictionaryInDictionary(){
        $plist = new PropertyList;

        $dict = (object) ['key'=>(object) []];

        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_ASCII,'{"key" = {};}')->getObject());
        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'{"key" = {};}')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><dict><key>key</key><dict></dict></dict></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals($dict,$plist->deserialize(PropertyList::FORMAT_JSON,'{"key":{}}')->getObject());
    }

    public function testSerializingPropertyListWithDictionaryInDictionary(){
        $plist = new PropertyList;
        $plist->setObject((object) ['key'=>(object) []]);

        self::assertEquals('{"key" = {};}',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('{"key" = {};}',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><dict><key>key</key><dict></dict></dict></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('{"key":{}}',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testDeserializingPropertyListWithDictionaryInArray(){
        $plist = new PropertyList;

        $array = [(object) []];

        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_ASCII,'({})')->getObject());
        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_ASCII_GNUSTEP,'({})')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_XML,'<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array><dict></dict></array></plist>')->getObject());
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals($array,$plist->deserialize(PropertyList::FORMAT_JSON,'[{}]')->getObject());
    }

    public function testSerializingPropertyListWithDictionaryInArray(){
        $plist = new PropertyList;
        $plist->setObject([(object) []]);

        self::assertEquals('({})',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('({})',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array><dict></dict></array></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('[{}]',$plist->serialize(PropertyList::FORMAT_JSON));
    }

}