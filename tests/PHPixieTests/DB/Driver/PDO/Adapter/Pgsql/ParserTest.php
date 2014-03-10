<?php
namespace PHPixieTests\DB\Driver\PDO\Adapter\Pgsql;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\PDO\Adapter\Pgsql\Parser
 */
class ParserTest extends \PHPixieTests\DB\Driver\PDO\ParserTest
{
    protected $adapter = 'Pgsql';

    protected $expected = array(
        array('SELECT * FROM "fairies"', array()),
        array('SELECT "id", "pixie" AS "test" FROM "fairies"', array()),
        array('SELECT * FROM "fairies" WHERE "a" = ? OR ( "b" = ? XOR "c" = ? ) AND "d" = ?', array(1, 1, 1, 1)),
        array('SELECT * FROM "fairies" WHERE "a" = ? GROUP BY "id", "name" HAVING "b" = ? ORDER BY "id" DESC, "name" ASC LIMIT 7 OFFSET 9', array(1, 1)),
        array('SELECT * FROM ( SELECT * FROM "fairies" ) AS "b" INNER JOIN "pixies" ON "b"."id" = "pixies"."id" UNION ALL SELECT * FROM "pixies"', array()),
        array('SELECT * FROM ( test1 ) AS "b" LEFT OUTER JOIN ( test2 ) AS "c" ON "b"."id" = "c"."id" UNION test3', array(1, 2, 3)),
        array('UPDATE "fairies" INNER JOIN "pixies" ON "fairies"."id" = "pixies"."id" SET "id" = ?, "name" = ? WHERE "id" = ? ORDER BY "id" ASC LIMIT 6 OFFSET 7', array(3, 'Trixie', 7)),
        array('INSERT INTO "fairies"("id", "name") VALUES (?, ?)', array(3, 'Trixie')),
        array('DELETE FROM "fairies" WHERE "id" = ? ORDER BY "id" ASC LIMIT 6 OFFSET 7', array(7)),
        array('SELECT COUNT (1) AS "count" FROM "fairies" WHERE "a" = ?', array(1)),
        array('INSERT INTO "fairies" DEFAULT VALUES', array()),
    );

}
