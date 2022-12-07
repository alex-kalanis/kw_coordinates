# kw_coordinates

Work with coordinates, some basic stuff to expand

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
