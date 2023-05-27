# yocLib - Property List (PHP)

This yocLibrary enables your project to encode and decode Property List files in PHP.

## Status

[![CI](https://github.com/yocto/yoclib-propertylist-php/actions/workflows/ci.yml/badge.svg)](https://github.com/yocto/yoclib-propertylist-php/actions/workflows/ci.yml)

## Installation

`composer require yocto/yoclib-propertylist`

## Use

### Serialization

```php
use YOCLIB\PropertyList\PropertyList;

$object = ['string_data',true,false];

$plist = new PropertyList;
$plist->setObject($object);

$xml = $plist->serialize(PropertyList::FORMAT_XML);
```

### Deserialization

```php
use YOCLIB\PropertyList\PropertyList;

$xml = '<plist xlmns="https://www.apple.com/DTDs/PropertyList-1.0.dtd" version="1.0"><array><string>string_data</string><true/><false/></array></plist>';

$plist = new PropertyList;
$plist->deserialize(PropertyList::FORMAT_XML,$xml);

$object = $plist->getObject();
```

## Supported Formats

A Property List comes in one of the following formats:

| Format | Supported in this library | Description |
| - | - | - |
| ASCII | ⚠️ (No comments yet) | (The original, legacy format developed by NeXT for NeXTStep and later OPENSTEP) |
| GNUstep ASCII | ⚠️ (No comments yet) | The NeXTStep/OPENSTEP ASCII format, but extended by GNU for GNUstep |
| GNUstep Binary | ❌ | A binary variant of the property list developed by GNU for GNUstep |
| XML | ✅ | Introduced in Apple's Mac OS X 10.0; currently version 1.0 |
| Binary | ❌ | Introduced in Apple's MAC OS X 10.2; currently version 1.0 |
| JSON | ✅ | Introduced in Apple's MAC OS X 10.7 |

*Note: At the moment, the implementation of the ASCII formats in this library should not be trusted. It doesn't understand comments, and it has some white-space detection hardcoded, so it is possible that it fails on a valid ASCII Property List.*