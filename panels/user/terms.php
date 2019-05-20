<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$sql_2 = 'select a.value,a.field,a.value_int finfo from '.CONFIG_MASTER.' a
where a.field in ("USER_NOTES","ADMIN_NOTES","TER_CON")
order by a.id';
//echo $sql_2;
$query_2 = $mysql->query($sql_2);
$rows_2 = $mysql->fetchArray($query_2);
$ter_con_of=$rows_2[2]['value'];
?>
<h3>
    
    <?php echo $ter_con_of;?>
</h3>