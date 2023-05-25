<?php
namespace YOCLIB\PropertyList\Tests;

use PHPUnit\Framework\TestCase;
use YOCLIB\PropertyList\PropertyList;

class PropertyListTest extends TestCase{

    public function testEmptyPropertyList(){
        $plist = new PropertyList;

        self::assertEquals('',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('',$plist->serialize(PropertyList::FORMAT_JSON));
    }

    public function testPropertyListWithString(){
        $plist = new PropertyList;
        $plist->setObject('Hello there');

        self::assertEquals('"Hello there"',$plist->serialize(PropertyList::FORMAT_ASCII));
        self::assertEquals('"Hello there"',$plist->serialize(PropertyList::FORMAT_ASCII_GNUSTEP));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY_GNUSTEP));
        self::assertEquals('<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><string>Hello there</string></plist>',$plist->serialize(PropertyList::FORMAT_XML));
        //self::assertEquals('',$plist->serialize(PropertyList::FORMAT_BINARY));
        self::assertEquals('"Hello there"',$plist->serialize(PropertyList::FORMAT_JSON));
    }

}