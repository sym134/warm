<?php

require_once __DIR__ . '/../src/admin/renderer/BaseRenderer.php';
require_once __DIR__ . '/../src/admin/renderer/form/FormItemTrait.php';
require_once __DIR__ . '/../src/admin/renderer/form/Formitem.php';
require_once __DIR__ . '/../src/admin/renderer/form/InputText.php';

use warm\admin\renderer\expand\renderer\expand\expand\expand\expand\expand\expand\expand\expand\expand\expand\expand\expand\form\Formitem;
use warm\admin\renderer\expand\renderer\expand\expand\expand\expand\expand\expand\expand\expand\expand\expand\expand\expand\form\InputText;

echo "Testing Formitem trait usage...\n";

// Test Formitem class (direct usage)
$formItem = new Formitem();
$formItem->label('My Label');
if ($formItem->get('label') !== 'My Label') die("Formitem label fail\n");
echo "Formitem class PASS\n";

// Test InputText class (trait usage)
$inputText = new InputText();
// InputText uses FormItemTrait now
$inputText->label('Text Label');
if ($inputText->get('label') !== 'Text Label') die("InputText label fail\n");

$inputText->name('my_text');
if ($inputText->get('name') !== 'my_text') die("InputText name fail\n");

$inputText->required(true);
if ($inputText->get('required') !== true) die("InputText required fail\n");

echo "InputText with FormItemTrait PASS\n";
