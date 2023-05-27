# yocLib - Property List (PHP)

This yocLibrary enables your project to encode and decode Property List files in PHP.

## Status

[![CI](https://github.com/yocto/yoclib-propertylist-php/actions/workflows/ci.yml/badge.svg)](https://github.com/yocto/yoclib-propertylist-php/actions/workflows/ci.yml)

## Installation

`composer require yocto/yoclib-propertylist`

## Use

TODO

## Supported Formats

A Property List comes in one of the following formats:

| Format | Supported in this library | Description |
| - | - | - |
| ASCII | ⚠️ | (The original, legacy format developed by NeXT for NeXTStep and later OPENSTEP) |
| GNUstep ASCII | ⚠ | The NeXTStep/OPENSTEP ASCII format, but extended by GNU for GNUstep |
| GNUstep Binary | ❌ | A binary variant of the property list developed by GNU for GNUstep |
| XML | ✅ | Introduced in Apple's Mac OS X 10.0; currently version 1.0 |
| Binary | ❌ | Introduced in Apple's MAC OS X 10.2; currently version 1.0 |
| JSON | ✅ | Introduced in Apple's MAC OS X 10.7 |