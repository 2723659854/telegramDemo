<?php

namespace app\controller;

use support\Request;

class IndexController
{
    public function index(Request $request)
    {
        $tokenizer = new TheSeer\Tokenizer\Tokenizer();
        $tokens = $tokenizer->parse(file_get_contents(__DIR__ . '/src/XMLSerializer.php'));

        $serializer = new TheSeer\Tokenizer\XMLSerializer();
        $xml = $serializer->toXML($tokens);

        echo $xml;

    }

    public function view(Request $request)
    {
        return view('index/view', ['name' => 'webman']);
    }

    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

}
