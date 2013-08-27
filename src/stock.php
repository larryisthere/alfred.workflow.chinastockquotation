<?php
require_once('workflows.php');

class Stock extends Workflows {

    private $types = array('sh', 'sz');
    private $api = array('price'=> 'http://hq.sinajs.cn/list=', 'info' => 'http://suggest3.sinajs.cn/suggest/name=info&key=');
    private $stocks = array();
    private $config = 'list.config';

    /**
    * Description:
    * Wrap the super class's request, to convert the charcode, and process jsonp to a PHP variable
    *
    * @param string - a url that will be requested
    * @return string - the data returned by the remote API
    */
    protected function curl($url) {
        $return   = array();
        $response = explode("\n",iconv("GBK","UTF-8",$this->request($url)));
        if($response[count($response)-1] == "") {
            array_pop($response);
        }
        foreach ($response as $key => $value) {
            preg_match("/\_([\w\d]{8})\=/", $value, $matches);
            eval(preg_replace('/^(.+)\=/i', '$data = ', $value));
            if($data=="") continue;
            if(count($matches)) {
                $code = $matches[1];
                $type = substr($code, 0, 2);
                $code = substr($code, 2);
                array_push($return, $type.','.$code.','.$data);
            } else {
                array_push($return, $data);
            }
        }
        return $return;
    }

    /**
    * Description:
    * Retrieve single company's price from SINA's API
    *
    * @param string - a company's code with its exchange flag at the beginning
    * @return null - but store results in a local variable $this->stock
    */
    protected function get_quotation($numbers){
        $keys = array('type','code','name', 'opening', 'closing', 'now', 'high', 'low', 'buy', 'sell', 'volume', 'amount', '买一量', '买一价', '买二量', '买二价', '买三量', '买三价', '买四量', '买四价', '买五量', '买五价', '卖一量', '卖一价', '卖二量', '卖二价', '卖三量', '卖三价', '卖四量', '卖四价', '卖五量', '卖五价', 'date', 'time', 'other');
        $stocks = $this->curl($this->api['price'] . "$numbers");
        if(!is_array($stocks)) return;
        $numbers_arr = explode(",", $numbers);
        foreach ($stocks as $key => $stock) {
            $values = explode(",", $stock);
            array_push($this->stocks, array_combine($keys, $values));
        }
    }

    /**
    * Description:
    * Retrieve a list of company with the short form of Pinyin of a company's name
    *
    * @param string - a short form of company name's Pinyin
    * @return array - contains the stock information of at least one conpany
    *         false - if there's no match
    */
    protected function get_code($chars) {
        $keys = array('brief', 'board', 'code', 'Code', 'name', 'pinyin');
        
        $info = $this->curl($this->api['info'].$chars);
        function combine(&$value, $key, $keys) {
            $arr_values = explode(',', $value);
            if(count($arr_values) == count($keys)) {
                $value = array_combine($keys, $arr_values);
            } else {
                $value = array();
            }
        }

        function filter($data) {
            if(count($data) > 0 && $data['board'] == '11') return $data;
        }

        if(count($info) == 0) {
            $rt = array();
        } else {
            $values = explode(";", $info[0]);
            array_walk($values, "combine", $keys);
            $rt = array_filter($values, "filter");
        }

        return $rt;
    }

    /**
    * Description:
    * filter codes, returns only validate codes
    *
    * @param string - a string composed by one or more codes delimited with comma
    * @return string - only 6 digits code will be return
    *
    */
    protected function filter_code($chars) {
        $return = array();
        if(trim($chars) != "") {
            $char_arr = explode(",", $chars);
            foreach ($char_arr as $key => $char) {
                if(preg_match('/^\d{6}$/', $char)) {
                    array_push($return, $char);
                }
            }
        }
        return implode(",", $return);
    }

    /**
    * Description:
    * Check from remote if the code if available
    *
    * @param string - a 6 digits number
    * @return boolean - true if there is, otherwise false
    *
    */
    protected function check_availability($code) {
        $param_arr = array();
        foreach ($this->types as $key => $value) {
            array_push($param_arr, $value.$code);
        }
        $stocks = $this->curl($this->api['price'] . implode(",", $param_arr));
        $stock = explode(",", $stocks[0]);
        return count($stocks) > 0 ? $stock[0].$stock[1] : false;
    }

    /**
    * Description:
    * output text to alfred with fewer codes
    *
    * @param string - any text you want to notice user
    * @return string - xml-formatted string with pre-defined format
    *
    */
    protected function notice($title, $detail = "") {
        if(trim($title)=="") return;
        $this->result('0','null',$title,$detail,'tip.png');
    }

    /**
    * Description:
    * Output with Workflows' help
    *
    * @return string - xml of alfred recognized format
    *
    */
    protected function output() {
        // $suggest->id, $suggest->alt, $suggest->title, '作者: '. implode(",", $suggest->author) .' 评分: '. $suggest->rating->average .'/'. $suggest->rating->numRaters .' 标签: '. implode(",", array_map('get_name', $suggest->tags)), 'C5C34466-B858-4F14-BF5E-FD05FA0903DA.png' 
        foreach ($this->stocks as $key => $value) {
            $now    = intval($value['now']);
            $now    = $now > 0 ? $value['now'] : '停牌';
            if(is_numeric($now)) {
                $change = round(($value['now']-$value['closing'])/$value['closing']*10000)/100;
                $change = ($change > 0 ? '+'.$change : $change).'%';
            } else {
                $change = '';
            }
            $name   = $value['name'];
            $name   = strlen(utf8_decode($name)) < 4 ? $name.'　' : $name;
            $volume = floor($value['volume'] / 100);
            $amount = floor($value['amount'] / 10000);
            $arg    = "http://finance.sina.com.cn/realstock/company/".$value['type'].$value['code']."/nc.shtml";
            $this->result(md5($name), $arg, $value['code'].'  '.$name.'  '.$now.'  '.$change, '量: '.$volume.'手 额: '. $amount.'万 买: '.$value['buy'].' 卖: '.$value['sell'].' 高: '.$value['high'].' 低: '.$value['low'].' 开: '.$value['opening'].' 收: '.$value['closing'], $value['type'].'.png');
        }
        if(count($this->results()) == 0) {
            $this->notice('没能找到相应的股票','您可能输入了错误的代码，请检查一下吧');
        }
        return $this->toxml();
    }

    /**
    * Description:
    * Add one or more stocks to personal list for the purpose of querying in a single request.
    *
    * @param string - comma separated string of code of stock
    * @return notice - system notice center will show the count of successful results.
    *
    */

    protected function add_traversely($code, &$target, &$duplicates) {
        $result = $this->check_availability($code);
        if(!$result) return false;
        if(!in_array($result, $target)) {
            array_push($target, $result);
        } else {
            array_push($duplicates, $code);
        }
    }

    public function add($codes_str) {

        $duplicates = array();
        $list = $list_ori = $this->read($this->config);
        $list = is_array($list) ? $list : array($list);
        $codes_arr = explode(",", trim($codes_str));
        if(is_array($codes_arr)) {
            foreach ($codes_arr as $key => $value) {
                $this->add_traversely(trim($value), $list, $duplicates);
            }
        } else {
            $this->add_traversely($codes_str, $list, $duplicates);
        }
        if($list != $list_ori || count($duplicates) != 0) {
            $this->write($list, $this->config);
            return array('result'=>true, 'added'=>array_diff($codes_arr, $duplicates), 'duplicates'=>$duplicates);
        }
        else {
            return false;
        }
    }

    /**
    * Description:
    * Remove one from personal list.
    *
    * @param string - one code of stock
    * @return notice - system notice center will show the notice of success.
    *
    */
    public function remove($code) {
        $list = $this->read($this->config);
        if(!is_array($list)) return false;
        foreach ($list as $key => $value) {
            if(preg_match('/'.$code.'$/', $value)) {
                $position = $key;
                break;
            }
        }
        if(is_numeric($position)) {
            array_splice($list, $position, 1);
            $this->write($list, $this->config);
            return true;
        } else {
            return false;
        }
    }

    /**
    * Description:
    * Show all personal stocks with data stored at local
    *
    * @return string - xml formatted data for alfred
    *
    */
    public function show() {
        $list = $this->read($this->config);
        if(!is_array($list) || count($list) == 0) return false;
        $this->get_quotation(implode(",", $list));
        return true;
    }

    /**
    * Description:
    * Query remote server to retrieve data
    *
    * @param string - a single text word of {list|code|pinyin}
    * @return string - a xml formatted for alfred
    *
    */
    protected function query($chars) {
        $param_arr = array();
        if($chars == 'list'){
            if(!$this->show()) {
                $this->notice('您还未添加自选股', '输入 {add+空格+股票代码(多个可以都好分隔)} 添加');
                return $this->toxml();
            }
        } elseif(preg_match('/^\d{6}$/', $chars)) {
            foreach ($this->types as $key => $value) {
                array_push($param_arr, $value.$chars);
            }
            $this->get_quotation(implode(",", $param_arr));
        } else {
            $code = $this->get_code($chars);
            foreach ($code as $key => $value) {
                array_push($param_arr, $value['Code']);
            }
            $this->get_quotation(implode(",", $param_arr));
        }
        return $this->output();
    }

    /**
    * Description:
    * Operates the personal list as a shortcut
    *
    * @param string - {add codes|remove code}
    * @return null - show result directly
    *
    */
    protected function operate($cmd, $param) {
        if(!in_array($cmd, array('add', 'remove'))) {
            $this->notice('目前仅支持: ','add 股票代码,... - 添加到自选; remove 股票代码 - 从自选股删除; list - 显示自选股');
        } else {
            $param = $this->filter_code(trim($param));
            if($param!="") {
                switch($cmd) {
                    case 'add':
                        if($result = $this->add($param)) {
                            $tmp_str = "";
                            if(count($result['added'])) {
                                $tmp_str .= implode(",", $result['added']).' 已添加到自选股 ';
                            }
                            if(count($result['duplicates'])) {
                                $tmp_str .= implode(",", $result['duplicates'])." 重复了";
                            }
                            $this->notice($tmp_str);
                        } else {
                            $this->notice('输入的代码有误，请检查');
                        }
                        break;
                    case 'remove':
                        if($this->remove($param)) {
                            $this->notice('移除 '.$param.' 成功');
                        } else {
                            $this->notice($param.' 不在您的自选列表中');
                        }
                        break;
                    default:
                        $this->notice('目前仅支持: ','add 股票代码,... - 添加到自选; remove 股票代码 - 从自选股删除; list - 显示自选股');
                }
            } else {
                $this->notice('您暂时只能通过股票代码进行操作');
            }
        }
        if(count($this->results())>0) {
            return $this->toxml();
        }
    }

    /**
    * Description:
    * Get input characters while user typing and choose correct function to deal with
    *
    * @param string - could be a stock code or a short form of a company's name
    * @return string - xml formatted data for alfred showing
    * 
    */
    public function controller($chars) {
        $querystring = preg_split('/\s+/', trim(stripslashes($chars)));
        if(count($querystring) == 1) {
            return $this->query($querystring[0]);
        } else {
            return $this->operate($querystring[0], $querystring[1]);
        }
    }
}
