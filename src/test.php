<?php

require_once('stock.php');

class Test {

    private $s;
    private $input;
    private $comment;

    function __construct() {
        $this->s = new Stock();
    }

    public function run($input, $comment) {
        $this->input = $input;
        $this->comment = $comment;
        $return = $this->s->controller($this->input);
        print_r($this->comment.': ');
        switch($this->input) {
            case 'remove ':
            case 'add ':
            case 's33':
                $expect = '<?xml version="1.0"?><items><item uid="0" arg="null" valid="yes" autocomplete=""><title>&#x6CA1;&#x80FD;&#x627E;&#x5230;&#x76F8;&#x5E94;&#x7684;&#x80A1;&#x7968;</title><subtitle>&#x60A8;&#x53EF;&#x80FD;&#x8F93;&#x5165;&#x4E86;&#x9519;&#x8BEF;&#x7684;&#x4EE3;&#x7801;&#xFF0C;&#x8BF7;&#x68C0;&#x67E5;&#x4E00;&#x4E0B;&#x5427;</subtitle><icon>tip.png</icon></item></items>';
                break;
            case 'sw':
                $expect = '<?xml version="1.0"?><items><item uid="e82ee0af3a867b627b429d2ca77acaca" arg="http://finance.sina.com.cn/realstock/company/sz300056/nc.shtml" valid="yes" autocomplete=""><title>300056  &#x4E09;&#x7EF4;&#x4E1D;&#x3000;  15.450  +1.31%</title><subtitle>&#x91CF;: 67511&#x624B; &#x989D;: 10235&#x4E07; &#x4E70;: 15.440 &#x5356;: 15.450 &#x9AD8;: 15.550 &#x4F4E;: 14.570 &#x5F00;: 15.300 &#x6536;: 15.250</subtitle><icon>sz.png</icon></item><item uid="6dc1b7a5fc39f29991334bda393ced8b" arg="http://finance.sina.com.cn/realstock/company/sz000011/nc.shtml" valid="yes" autocomplete=""><title>000011  &#x6DF1;&#x7269;&#x4E1A;A  8.07  -2.42%</title><subtitle>&#x91CF;: 79507&#x624B; &#x989D;: 6444&#x4E07; &#x4E70;: 8.07 &#x5356;: 8.08 &#x9AD8;: 8.25 &#x4F4E;: 7.90 &#x5F00;: 8.21 &#x6536;: 8.27</subtitle><icon>sz.png</icon></item><item uid="748566616d43bcdd955aaff231c8baf6" arg="http://finance.sina.com.cn/realstock/company/sz002115/nc.shtml" valid="yes" autocomplete=""><title>002115  &#x4E09;&#x7EF4;&#x901A;&#x4FE1;  6.60  -2.65%</title><subtitle>&#x91CF;: 181357&#x624B; &#x989D;: 12028&#x4E07; &#x4E70;: 6.59 &#x5356;: 6.60 &#x9AD8;: 6.79 &#x4F4E;: 6.38 &#x5F00;: 6.73 &#x6536;: 6.78</subtitle><icon>sz.png</icon></item></items>';
                break;
            case '30005':
                $expect = '<?xml version="1.0"?><items><item uid="5501cb512c5d2dfc6cfce665ea71ef8e" arg="http://finance.sina.com.cn/realstock/company/sz300050/nc.shtml" valid="yes" autocomplete=""><title>300050  &#x4E16;&#x7EAA;&#x9F0E;&#x5229;  13.430  -4.89%</title><subtitle>&#x91CF;: 147237&#x624B; &#x989D;: 19976&#x4E07; &#x4E70;: 13.420 &#x5356;: 13.430 &#x9AD8;: 14.120 &#x4F4E;: 12.900 &#x5F00;: 14.120 &#x6536;: 14.120</subtitle><icon>sz.png</icon></item><item uid="12e0c3326652cacafaeca20455492fe9" arg="http://finance.sina.com.cn/realstock/company/sz300051/nc.shtml" valid="yes" autocomplete=""><title>300051  &#x4E09;&#x4E94;&#x4E92;&#x8054;  12.740  +2.66%</title><subtitle>&#x91CF;: 679070&#x624B; &#x989D;: 86217&#x4E07; &#x4E70;: 12.740 &#x5356;: 12.750 &#x9AD8;: 13.300 &#x4F4E;: 12.200 &#x5F00;: 12.580 &#x6536;: 12.410</subtitle><icon>sz.png</icon></item><item uid="c49402cee2a7a5eafb5c6fdea6d08c2b" arg="http://finance.sina.com.cn/realstock/company/sz300052/nc.shtml" valid="yes" autocomplete=""><title>300052  &#x4E2D;&#x9752;&#x5B9D;&#x3000;  57.600  +9.99%</title><subtitle>&#x91CF;: 187412&#x624B; &#x989D;: 105887&#x4E07; &#x4E70;: 57.550 &#x5356;: 57.600 &#x9AD8;: 57.610 &#x4F4E;: 52.580 &#x5F00;: 53.000 &#x6536;: 52.370</subtitle><icon>sz.png</icon></item><item uid="524bc3fefb70b972b7320b29b8a1f9b8" arg="http://finance.sina.com.cn/realstock/company/sz300053/nc.shtml" valid="yes" autocomplete=""><title>300053  &#x6B27;&#x6BD4;&#x7279;&#x3000;  11.000  +1.57%</title><subtitle>&#x91CF;: 149091&#x624B; &#x989D;: 16195&#x4E07; &#x4E70;: 10.990 &#x5356;: 11.000 &#x9AD8;: 11.070 &#x4F4E;: 10.550 &#x5F00;: 10.700 &#x6536;: 10.830</subtitle><icon>sz.png</icon></item><item uid="8a4499b5701cb6f55c1714fdc9431302" arg="http://finance.sina.com.cn/realstock/company/sz300054/nc.shtml" valid="yes" autocomplete=""><title>300054  &#x9F0E;&#x9F99;&#x80A1;&#x4EFD;  &#x505C;&#x724C;  </title><subtitle>&#x91CF;: 0&#x624B; &#x989D;: 0&#x4E07; &#x4E70;: 0.000 &#x5356;: 0.000 &#x9AD8;: 0.000 &#x4F4E;: 0 &#x5F00;: 0.000 &#x6536;: 19.130</subtitle><icon>sz.png</icon></item><item uid="9389dc7439d59b8b1ba35b83eae76b9f" arg="http://finance.sina.com.cn/realstock/company/sz300055/nc.shtml" valid="yes" autocomplete=""><title>300055  &#x4E07;&#x90A6;&#x8FBE;&#x3000;  36.450  +1.5%</title><subtitle>&#x91CF;: 21704&#x624B; &#x989D;: 7839&#x4E07; &#x4E70;: 36.430 &#x5356;: 36.450 &#x9AD8;: 36.700 &#x4F4E;: 35.000 &#x5F00;: 36.020 &#x6536;: 35.910</subtitle><icon>sz.png</icon></item><item uid="e82ee0af3a867b627b429d2ca77acaca" arg="http://finance.sina.com.cn/realstock/company/sz300056/nc.shtml" valid="yes" autocomplete=""><title>300056  &#x4E09;&#x7EF4;&#x4E1D;&#x3000;  15.450  +1.31%</title><subtitle>&#x91CF;: 67511&#x624B; &#x989D;: 10235&#x4E07; &#x4E70;: 15.440 &#x5356;: 15.450 &#x9AD8;: 15.550 &#x4F4E;: 14.570 &#x5F00;: 15.300 &#x6536;: 15.250</subtitle><icon>sz.png</icon></item><item uid="575c25316075fd4f9f28d2b5fb59c86f" arg="http://finance.sina.com.cn/realstock/company/sz300057/nc.shtml" valid="yes" autocomplete=""><title>300057  &#x4E07;&#x987A;&#x80A1;&#x4EFD;  15.130  -0.98%</title><subtitle>&#x91CF;: 74276&#x624B; &#x989D;: 11193&#x4E07; &#x4E70;: 15.130 &#x5356;: 15.140 &#x9AD8;: 15.460 &#x4F4E;: 14.730 &#x5F00;: 15.160 &#x6536;: 15.280</subtitle><icon>sz.png</icon></item><item uid="897ab0369ac2c2da4fbe8e8f8d006b97" arg="http://finance.sina.com.cn/realstock/company/sz300058/nc.shtml" valid="yes" autocomplete=""><title>300058  &#x84DD;&#x8272;&#x5149;&#x6807;  49.450  +1.81%</title><subtitle>&#x91CF;: 21211&#x624B; &#x989D;: 10457&#x4E07; &#x4E70;: 49.450 &#x5356;: 49.480 &#x9AD8;: 50.000 &#x4F4E;: 48.250 &#x5F00;: 48.250 &#x6536;: 48.570</subtitle><icon>sz.png</icon></item><item uid="ddee68dfc0d5f6475986f77acc55357f" arg="http://finance.sina.com.cn/realstock/company/sz300059/nc.shtml" valid="yes" autocomplete=""><title>300059  &#x4E1C;&#x65B9;&#x8D22;&#x5BCC;  16.600  -0.66%</title><subtitle>&#x91CF;: 311680&#x624B; &#x989D;: 51946&#x4E07; &#x4E70;: 16.600 &#x5356;: 16.610 &#x9AD8;: 17.120 &#x4F4E;: 15.920 &#x5F00;: 16.650 &#x6536;: 16.710</subtitle><icon>sz.png</icon></item></items>';
                break;
            case '300056':
                $expect = '<?xml version="1.0"?><items><item uid="e82ee0af3a867b627b429d2ca77acaca" arg="http://finance.sina.com.cn/realstock/company/sh300056/nc.shtml" valid="yes" autocomplete=""><title>300056  &#x4E09;&#x7EF4;&#x4E1D;&#x3000;  15.450  +1.31%</title><subtitle>&#x91CF;: 67511&#x624B; &#x989D;: 10235&#x4E07; &#x4E70;: 15.440 &#x5356;: 15.450 &#x9AD8;: 15.550 &#x4F4E;: 14.570 &#x5F00;: 15.300 &#x6536;: 15.250</subtitle><icon>sh.png</icon></item></items>';
                break;
            case 'add 300100':
                $expect = '<?xml version="1.0"?><items><item uid="0" arg="null" valid="yes" autocomplete=""><title>300100 &#x5DF2;&#x6DFB;&#x52A0;&#x5230;&#x81EA;&#x9009;&#x80A1; </title><subtitle></subtitle><icon>tip.png</icon></item></items>';
                break;
            case 'remove asdf':
            case 'add sddf':
            case 'add 666666':
                $expect = '<?xml version="1.0"?><items><item uid="0" arg="null" valid="yes" autocomplete=""><title>&#x8F93;&#x5165;&#x7684;&#x4EE3;&#x7801;&#x6709;&#x8BEF;&#xFF0C;&#x8BF7;&#x68C0;&#x67E5;</title><subtitle></subtitle><icon>tip.png</icon></item></items>';
                break;
            case 'remove 300100':
                $expect = '<?xml version="1.0"?><items><item uid="0" arg="null" valid="yes" autocomplete=""><title>&#x79FB;&#x9664; 300100 &#x6210;&#x529F;</title><subtitle></subtitle><icon>tip.png</icon></item></items>';
                break;
            case 'list':
                $expect = '<?xml version="1.0"?><items><item uid="0" arg="null" valid="yes" autocomplete=""><title>&#x60A8;&#x8FD8;&#x672A;&#x6DFB;&#x52A0;&#x81EA;&#x9009;&#x80A1;</title><subtitle>&#x8F93;&#x5165; {add+&#x7A7A;&#x683C;+&#x80A1;&#x7968;&#x4EE3;&#x7801;(&#x591A;&#x4E2A;&#x53EF;&#x4EE5;&#x90FD;&#x597D;&#x5206;&#x9694;)} &#x6DFB;&#x52A0;</subtitle><icon>tip.png</icon></item></items>';
                break;
            default:
                $expect = "";
                print_r($return);
        }
        $result = trim(preg_replace("/\n/i","",$return) == $expect ? "passed" : "failed");
        print_r($result);
        print_r("\n");
        if($result == 'failed') {
            print_r($return);
            print_r("\n");
        }
    }
}

$test = new Test();
// $test->run('s33', '查询无效内容');
$test->run('sw', '简拼查询返回多个正确结果');
// $test->run('30005', '代码查询返回多个正确结果');
// $test->run('300056', '查询有效代码');
// $test->run('add 300100','查询添加单个代码到自选股');
// $test->run('add 300100','查询添加重复代码到自选股');
// $test->run('add 300057,300058','查询添加多个代码到自选股');
// $test->run('add sddf','查询添加非代码到自选股');
// $test->run('add 666666','查询添加不存在的代码到自选股');
// $test->run('add ','查询添加空代码到自选股');
// $test->run('remove 300100','查询删除自选股');
// $test->run('remove ','查询删除空自选股');
// $test->run('remove asdf','查询删除无效自选股');
// $test->run('list', '为空时查询显示自选股');