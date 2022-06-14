```
$db = new OracleDb();
$db->open();

$sql = "select * from test";
$row = $db->getOne($sql);
```
