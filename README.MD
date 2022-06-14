```
$db = new OracleDb();
$db->open();

$sql = "select * from zlp_th";
$row = $db->getOne($sql);
```
