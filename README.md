kw_coordinates
==============

[![Build Status](https://travis-ci.org/alex-kalanis/kw_coordinates.svg?branch=master)](https://travis-ci.org/alex-kalanis/kw_coordinates)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/kw_coordinates/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/kw_coordinates/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/kw_coordinates/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_coordinates)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/kw_coordinates.svg?v1)](https://packagist.org/packages/alex-kalanis/kw_coordinates)
[![License](https://poser.pugx.org/alex-kalanis/kw_coordinates/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_coordinates)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/kw_coordinates/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/kw_coordinates/?branch=master)

Work with coordinates, have some basic stuff to expand to local or remote services.

## PHP Installation

```
{
    "require": {
        "alex-kalanis/kw_coordinates": "1.0"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


## PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Add some external packages with connection to the local or remote services.

3.) Connect the correct libraries to your code.

4.) Extend your libraries by interfaces inside the package.

5.) Just call setting and render

## Caveats

You might not be able to extend these interfaces directly to use other coordinate systems
due limitations of php math functions (like ```pow()``` - tested, got problematic results)
and you might need the external service that calculates the results in another language and
here comes only adapting the code into your current one.
