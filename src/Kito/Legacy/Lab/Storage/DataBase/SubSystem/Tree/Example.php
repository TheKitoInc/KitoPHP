<?php

require_once dirname(__FILE__).'/Zone/class.finalzone.php';
$cnn = Sql::getConnection('mysql', ['Server' => '127.0.0.1', 'User' => 'root', 'Password' => '', 'Database' => 'blk_tree']);
$data = FinalZone::getRoot($cnn);

function tree($parent)
{
    $d = "<li><a href='?Id=".$parent->getId()."'>".$parent->getName().'</a>';

    foreach ($parent->getZones() as $zone) {
        $d .= '<ul>';
        $d .= tree($zone);
        $d .= '</ul>';
    }

    return $d.'</li>';
}

$a = $data->get('A');
$b = $data->get('B');
$c = $data->get('C');
$ca = $c->get('CA');
$cb = $c->get('CB');
$cc = $c->get('CC');
$d = $data->get('D');

$o = $b->getAttribute('att1');
$o->set('valatt1');
echo $o->get();

echo tree($data);
