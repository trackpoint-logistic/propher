# PDO Extension

This extension is used for automatic Postgres type conversion.

## Supported types

- json
- jsonb
- date
- timestamp

## Example


### Code
```php
use Trackpoint\PDOExtension;


$pdo = new PDO();
$pdo->setAttribute(PDO::ATTR_STATEMENT_CLASS, ['PDOPGStatement', [$pdo]]);

$ps = $pdo->prepare('select date from table');

$ps->execute();

var_dump($ps->fetch());

```

### Output

```
["date"]=>
  object(DateTime)#3 (3) {
    ["date"]=>
    string(26) "2010-01-01 00:00:00.000000"
    ["timezone_type"]=>
    int(3)
    ["timezone"]=>
    string(15) "Europe/Helsinki"
  }
```

