# Dat0r - My DataObject's Origin 

[![Build Status](https://travis-ci.org/berlinonline/Dat0r.png)](https://travis-ci.org/berlinonline/Dat0r)

Dat0r is a code-generation library that was built to ease our management of domain specific data-objects in php.
The main difference to existing php solutions, that allow generating code to handle data structures is,
that Dat0r is not an ORM and it doesn't implement any other persistence concerns.
It just takes care of defining data-structures and realizing them via code generation.
Besides plain containers for our data, we needed two more concerns to be respected by the lib.
* Ensure value consistency - no one wants ambigious data to be let in.
* Support complex structure definitions such as aggregated/nested objects or inheriting from other data-objects.

Consistency is achieved by a field specific validation of values.
Values are only set if validation succeeds, so data is most surely always held as defined.
The xml markup used to define data-structures allows to nest structure definitions
and supports data-object inheritance so you can reuse and extend common structure definitions.

In terms of layers Dat0r is made up of basically two:

1. Core Layer

A generic approach to modelling data-objects at runtime, that allows access to data and structure via meta api.
Uses a meta-data container called "Module" to create specific data-objects on demand.
Modules hold a "Field" object for each property defined on a data-object.
A "Field" provides property specific meta-data and defines the concrete strategy to use for property level concerns such as validation.
"Module" are also used to create "Document"s. A "Document" is a concrete instance of a defined data-object 
and actually holds the domain data (\0/ no more 'meta').

2. Domain Layer

Provides access to data via domain specific api that is exposed by generated data-objects.
... tbd

## Installation

This library can be used by integrating it via composer.

Add composer: 

    curl -s http://getcomposer.org/installer | php

Create a 'composer.json' file with the following content:

    {
        "require": {
            "shrink/cmf": "*"
        },
        "minimum-stability": "dev"
    }

Then install via composer:

    php composer.phar install


## Usage

An example for integration and usage can be found at https://github.com/shrink/draftcmm