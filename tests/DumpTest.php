<?php

namespace Studiow\Laravel\Table\Test;

use Illuminate\Http\Request;
use Studiow\Laravel\Table\Column\Column;
use Studiow\Laravel\Table\Filter\SelectFilter;
use Studiow\Laravel\Table\Table;

class DumpTest extends TestCase
{
    public function testDumpTest()
    {
        $data = [
            ['id' => 1, 'name' => 'willem', 'age' => 10],
            ['id' => 2, 'name' => 'hanne', 'age' => 11],
            ['id' => 3, 'name' => 'kwinten', 'age' => 5],
        ];

        $table = new Table($data, [
            (new Column('name', 'Name', true))->resolveWith(function ($value, $row) {
                return strtoupper($value);
            }),
            new Column('age', 'Age'),
            new Column('action', 'Action'),
        ], [
            new SelectFilter('name', 'Name', ['' => 'All', 'hanne' => 'Hanne', 'willem' => 'Willem']),
        ]);

        $table->addAction('test', 'Test');

        $table->column('action')->resolveWith(function ($value, $row) {
            return  '<a href="">'.data_get($row, 'id').'</a>';
        });
        $request = request();

        $request->query->set('orderBy', 'name');
        $request->query->set('order', 'asc');
        $request->query->set('name', '');
        echo $table->withRequest($request)->view();
    }
}
