<?php

namespace Jizhi\Amis\Tests;

use PHPUnit\Framework\TestCase;
use Jizhi\Amis\Component;
use Jizhi\Amis\Page;
use Jizhi\Amis\Tpl;
use Jizhi\Amis\Form\InputText;
use Jizhi\Amis\Action;
use Jizhi\Amis\Form;

class BasicTest extends TestCase
{
    public function testComponentCreation()
    {
        $component = new Component();
        $component->set('type', 'test');
        $component->set('value', 'test_value');
        
        $data = $component->toArray();
        
        $this->assertEquals('test', $data['type']);
        $this->assertEquals('test_value', $data['value']);
    }

    public function testPageCreation()
    {
        $page = new Page();
        $page->title('Test Page');
        $page->body([
            new Tpl('Hello World')
        ]);
        
        $data = $page->toArray();
        
        $this->assertEquals('page', $data['type']);
        $this->assertEquals('Test Page', $data['title']);
        $this->assertCount(1, $data['body']);
    }

    public function testInputTextCreation()
    {
        $input = new InputText('username', '用户名');
        $input->placeholder('请输入用户名')
              ->required(true);
        
        $data = $input->toArray();
        
        $this->assertEquals('input-text', $data['type']);
        $this->assertEquals('username', $data['name']);
        $this->assertEquals('用户名', $data['label']);
        $this->assertEquals('请输入用户名', $data['placeholder']);
        $this->assertTrue($data['required']);
    }

    public function testFormCreation()
    {
        $form = new Form([
            new InputText('name', '姓名')
        ], [
            (new Action('primary', '提交'))->actionType('submit')
        ]);
        
        $data = $form->toArray();
        
        $this->assertEquals('form', $data['type']);
        $this->assertCount(1, $data['body']);
        $this->assertCount(1, $data['actions']);
    }
}