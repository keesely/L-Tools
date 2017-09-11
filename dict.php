#!/usr/bin/env php
<?php
/*************************************************************************
 * @fileName youdao.php
 * @category PHP
 * @package void
 * @author Kee Guo <chinboy2012@gmail.com> 2015年07月10日 星期五 11时09分18秒
 * @since 10/07/2015
 * @version youdao.php 2015.07.10
 * ***********************************************************************/
$args = $argv;
array_shift($args);
if (!current($args)) exit("\r\n请输入要查寻的单词 Or 短语\r\n");
$dict = current($args);
$wdf = getEnv('HOME') . '/.dict.wdf';
if (is_file($wdf)) {
	$dictlist = file($wdf);
	foreach ($dictlist as $wk => $word) {
		$word = json_decode(trim($word));
		if (isset($word->source) && $dict == $word->source) {
			echo "Translate: {$word->translate}";
			echo "\r\n\t{$word->source}\r\n\t{$word->translate} \r\n";
			if (isset($word->entries)) {
				echo "Entries:";
				echo "\t" . implode("\r\n\t", $word->entries);
				echo "\r\n";
			}
			echo @"更新于：{$word->dates} Ln." . ($wk+1);
			exit("\r\n");
		}
	}
}

function getMillisecond() {
list($t1, $t2) = explode(' ', microtime());
return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}
//echo getMillisecond();

$c = "rY0D^0'nM0}g5Mm1z%1G4";
$stime = miscrotime();
$uri = new stdClass();
$uri->smartresult = 'dict';
$uri->smartresult = 'rule';
$uri->smartresult = 'ugc';
$uri->sessionFrom = 'http://dict.youdao.com/';
$url = 'http://fanyi.youdao.com/translate?' . http_build_query($uri); //smartresult=dict&smartresult=rule&smartresult=ugc&sessionFrom=http://dict.youdao.com/';
$params = new stdClass();
$params->type = 'AUTO';
$params->i = $dict;
$params->from = 'AUTO';
$params->to = 'AUTO';
$params->smartresult = 'dict';
$params->client = 'fanyideskweb';
$params->salt = getMillisecond() + intval(10 * (microtime(true)-time()), 10);//'1504769738410';
$params->sign = md5($params->client . $params->i . "\n" . $params->salt . $c);//'451b0f36db728503626aba6844cf9777';
$params->version = '2.1';
$params->doctype = 'json';
//$params->xmlVersion = '1.8';
$params->keyfrom = 'fanyi.web';
$params->ue = 'UTF-8';
$params->action = 'FY_BY_ENTER';
$params->typoResult = 'true';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$result = curl_exec($ch);
$js = json_decode($result);
if (!$js) exit($result);
$dict = new StdClass();
if (isset($js->translateResult)) {
	$cur = current(current($js->translateResult));
	if (isset($cur->tgt)) {
		echo "Translate: {$cur->tgt}";
		echo "\r\n\t{$cur->src}\r\n\t{$cur->tgt} \r\n";
		$dict->source = $cur->src;
		$dict->translate = $cur->tgt;
	}
}
if (isset($js->smartResult)) {
	echo "Entries:";
	echo "\t" . implode("\r\n\t", $js->smartResult->entries);
	$dict->entries = $js->smartResult->entries;
	echo "\r\n";
}
$dict->date = date('Y-m-d H:i:s');
//var_dump($js);
if ($dict) file_put_contents($wdf, json_encode((array) $dict, JSON_UNESCAPED_UNICODE) . "\r\n", FILE_APPEND);
curl_close($ch);
echo "\r\n耗时: ". number_format(miscrotime()-$stime, 8) . "秒. \r\n\r\n";

function miscrotime () {
	$stime = microtime();
	$stime = explode(' ', $stime);
	return floatval($stime[1] + $stime[0]);
}
