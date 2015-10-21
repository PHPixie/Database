# PHPixie Database

Supports a common query interface for MySQL, PostgreSQl, SQLite and MongoDB

[![Build Status](https://travis-ci.org/PHPixie/Database.svg?branch=master)](https://travis-ci.org/PHPixie/Database)
[![Test Coverage](https://codeclimate.com/github/PHPixie/Database/badges/coverage.svg)](https://codeclimate.com/github/PHPixie/Database)
[![Code Climate](https://codeclimate.com/github/PHPixie/Database/badges/gpa.svg)](https://codeclimate.com/github/PHPixie/Database)
[![HHVM Status](https://img.shields.io/hhvm/phpixie/database.svg?style=flat-square)](http://hhvm.h4cc.de/package/phpixie/database)

[![Author](http://img.shields.io/badge/author-@dracony-blue.svg?style=flat-square)](https://twitter.com/dracony)
[![Source Code](http://img.shields.io/badge/source-phpixie/database-blue.svg?style=flat-square)](https://github.com/phpixie/database)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](https://github.com/phpixie/database/blob/master/LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/phpixie/database.svg?style=flat-square)](https://packagist.org/packages/phpixie/database)

- [PHPixie Database](#phpixie-database)
    - [Initializing](#initializing)
    - [Querying](#querying)
        - [Operators](#operators)
        - [Tables, Subqueries and JOINs](#tables-subqueries-and-joins)
        - [Aggregation](#aggregation)
        - [Other types of queries](#other-types-of-queries)
        - [Placeholders](#placeholders)
    - [Transactions](#transactions)
    - [MongoDB](#mongodb)

## Initializing

```php
$slice = new \PHPixie\Slice();
$database = new \PHPixie\Database($slice->arrayData(array(
    'default' => array(
        'driver' => 'pdo',
        'connection' => 'sqlite::memory:'
    )
)));
```

> If you are using the PHPixie Framework the Database component is automatically initalized for you.
> Access it via `$frameworkBuilder->components()->database()` and configure in the `/config/database.php` file.

```php
return array(
    //You can define multiple connections
    //each with a different name
    'default' => array(
        'driver'     => 'pdo',
        
        //MySQL
        'connection' => 'mysql:host=localhost;dbname=phpixie',
        
        //or SQLite
        'connection' => 'sqlite:/some/file',
        
        //or Postgres
        'connection' => 'pgsql:dbname=exampledb',
        
        'user'       => 'root',
        'password'   => 'password'
    ),
    
    'other' => array(
        //connecting to MongoDD
        'driver'   => 'mongo',
        'database' => 'phpixie',
        'user'     => 'pixie',
        'password' => 'password'
    )
);
```

## Querying

Querying relational database and MongoDB collections is very similar in PHPixie. Let's look at relational databases first

```php
$connection = $database->get('default');

// SELECT * FROM `posts` WHERE `status`='published'
// LIMIT 5 OFFSET 1
$query = $connection->selectQuery();
$posts = $query
    ->table('posts')
    ->where('status', 'Published')
    ->limit(5)
    ->offset(1)
    ->execute();
    
// Specifying fields
$query->fields(array('id'));

// You can remove limit, offset
// specified fields, etc from the query
// using clearSomething()
$query->clearFields();

//And get it using getSomething()
$query->getFields();

//Using OR and XOR logic
$query
    ->where('status', 'published')
    ->orWhereNot('status', 'deleted')
    ->xorWhere('id', 5);
    
//Shorthand functions
$query
    ->and('status', 'published')
    ->orNot('status', 'deleted')
    ->xor('id', 5);

// WHERE `status` = 'published'
// OR NOT (`id` = 4 AND `views` = 5)
$query
    ->where('status', 'published')
    ->startOrNotGroup()
        ->where('id', 4)
        ->and('views', 4)
    ->endGroup();

// Less verbose syntax
$query
    ->where('status', 'published')
    ->or(function(query) {
        $query
            ->where('id', 4)
            ->and('views', 4);
    });
    
// More verbose syntax
// Useful for programmatic filters
$query
    ->addOperatorCondition(
        $logic    = 'and',
        $negate   = false,
        $field    = 'status',
        $operator = '=',
        array('published')
    )
    ->startConditionGroup(
        $logic    = 'and',
        $negate   = false
    );

```

> Using `and`, `or` and `xor` add conditions to the last used conditon type.
> So calling `or` after `where()` will be same as `orWhere()`, while using it
> after `having()` will be considered as `orHaving()`.

### Operators

```php
// So far we only compared fields with values
// But there are other operators available

// >, < , >=, <=, '!='
$query->where('views', '>', 5);

// comparies fields to other fields
// can be done by adding an '*'
$query->where('votes', '>=*', 'votesRequired');

// Between
$query->where('votes', 'between', 5, 6);

// In
$query->where('votes', 'in', array(5, 6));

// Like
$query->where('name', 'like', 'Welcome%');

// Regexp
$query->where('name', 'regexp', '.*');

// SQL expression
$expression = $database->sqlExpression('LOWER(?)', array('text'));
$query->where('title', $expression);

// You can also use it for fields
// SELECT COUNT(1) as `count`
$expression = $database->sqlExpression('COUNT(1)');
$query->fields(array(
    'count' => $expression
));
```

### Tables, Subqueries and JOINs

```php
// When specofying a table
// you can also define an alias for it
$query->table('posts', 'p');

// INNER JOIN `categories`
$query->join('categories')

// LEFT JOIN `categories` AS `c`
$query->join('categories', 'c', 'left')

$query
    ->on('p.categoryId', 'c.categoryId');
    
// The on() conditions can be used in
// the same way as where(), and apply
// to the last join() statement
$query
    ->join('categories', 'c', 'left')
        ->on('p.categoryId', 'c.id')
        ->or('p.parentCategoryId', 'c.id')
    ->join('authors')
        ->on('p.authorId', 'authors.id');
        
// You can use subqueries as tables,
// but you must supply the alias parameter

$query->join($subqeury, 'c', 'left')

//UNIONs
$query->union($subquery, $all = true);
```

### Aggregation

After you define you fields you cn use `HAVING` in the same way you would use `WHERE`;
```php
$query
    ->fields(array(
        'count' => $database->sqlExpression('COUNT(1)');
    ))
    ->having('count', '>', 5)
    ->or('count', '<', 2);
```

### Other types of queries

```php
// Delete syntax is very similar to select
// except it doesn't support HAVING syntax
$connection->deleteQuery()
    ->where('id', 5)
    ->execute();

// Count query is a shorthand that returns the count
// of matched items
$count = $connection->countQuery()
    ->where('id', '>', 5)
    ->execute();

// Inserting
$insertQuery = $connection->insertQuery();
$insertQuery->data(array(
    'id'    => 1,
    'title' => 'Hello'
))->execute();

// Insert multiple rows
$insertQuery->batchData(
    array('id', 'title'),
    array(
        array(1, 'Hello'),
        array(2, 'World'),
    )
)->execute();

// Getting insert id
$connection->insertId();

// Updating
$updateQuery = $connection->updateQuery();
$updateQuery
    ->set('name', 'Hello')
    ->where('id', 4)
    ->execute();

// increment values
$updateQuery
    ->increment(array(
        'views' => 1
    ))
    ->execute();
```

### Placeholders

Query placeholders are another way to ease programmatic query building. You can create a placeholder and then
later replace it with actual conditions. Here is an example:

```php
$query
    ->where('status', 'published')
    ->startOrGroup();

// Add placeholder inside the OR goup
$placeholder = $query->addPlaceholder(
    $logic  = 'and',
    $negate = false,
    $allowEmpty = false
);

$query
        ->and('views', '>', 5);
    ->endGroup();

// so far this results in
// WHERE `status` = 'published'
// OR (<placeholder> AND `views` > 5)

// Now we can replace the placeholder by
// adding conditions to it
$placeholder->where('votes', '>', 5);
```

## Transactions

The basic usage for transactions is to rollback them if an exception occured and then rethrow
the exception

```php
$database->beginTransaction();
// ...
try {
    // ...
    $database->commitTransaction();
} catch(\Exception $e) {
    $database->rollbackTransaction();
    throw $e;
}
```

PHPixie also supports transaction savepoints which can be used to for some more adavcenced behavior:

```php
$name = $database->savepointTransaction();
$database->rollbackTransactionTo($name);
```

## MongoDB

Querying MongoDB is very similar to querying SQL databases. Of course you can not use relational methods
like `JOIN` and `HAVING` statements, transactions, etc. But instead you get additional features in addition:

```php
$posts = $query
    ->collection('posts')
    // subdocument conditions
    ->where('author.name', 'Dracony')
    ->limit(1)
    ->offset(1)
    ->execute();

$connection->updateQuery()
    ->collection('posts')
    ->set('done', true)
    ->unset(array('started', 'inProgress'))
    ->execute();
    
$connection->insertQuery()
    ->collection('posts')
    ->batchData(array(
        array(
            'name' => 'Trixie'
        ),
        array(
            'name' => 'Stella'
        )
    ))
    ->execute();
```

An easier way of querying subdocuments can be achieved using subdocument groups:

```php
//setting conditions for subdocuments
$query
    ->startOrNotSubdocumentGroup('author')
        ->where('name', 'Dracony')
    ->endGroup();
    
//setting conditions for subarray items
$query
    ->startOrNotSubarrayItemGroup('authors')
        ->where('name', 'Dracony')
    ->endGroup();
```
