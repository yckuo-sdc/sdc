<?php
class HttpHelper {
    /**
     * 使用 print_r(curl('http://www.tainan.gov.tw/', [CURLINFO_HTTP_CODE, CURLINFO_EFFECTIVE_URL]));
     * 
     * 預設只取表頭
     * 
     * $getinfo:
     *  透過curl_getinfo撈指定資料 ex.CURLINFO_HTTP_CODE、CURLINFO_EFFECTIVE_URL
     *  if empty then return HEADER string, else if array then return array
     *  see. https://www.php.net/manual/zh/function.curl-getinfo.php
     * 
     */
    public function getUrlResponse($url, $curlopt = array(), $getinfo = array()) {
        $agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36";
        $curl = curl_init();
        
        $opt_array = array(
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => $agent,
            CURLOPT_RETURNTRANSFER => true, //講curl_exec()獲取的信息以文件流的形式返回，而不是直接輸出。
            CURLOPT_VERBOSE => false, // 啟用時會匯報所有的信息，存放在STDERR或指定的CURLOPT_STDERR中
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HEADER => true, // 顯示表頭
            CURLOPT_NOBODY => true, // 忽略內容
            CURLOPT_FOLLOWLOCATION => true, // 遞迴，跟著頁面跳轉
            CURLOPT_MAXREDIRS => 5, //  避免無限遞迴
            CURLOPT_HTTPHEADER => array('Expect:'), //避免data資料過長問題  
            CURLOPT_SSL_VERIFYPEER => FALSE, // 略過檢查 SSL 憑證有效性
            CURLOPT_SSL_VERIFYHOST => FALSE, // 略過從證書中檢查SSL加密演算法是否存在
		);

        // 導入使用者設定
        foreach ($curlopt as $key => $value) {
            if (is_numeric($key)) {
                $opt_array[$key] = $value;
            } else if (is_string($key)) {
                if (strpos($key, 'CURLOPT_') === false) {
                    $key = 'CURLOPT_' . $key;
                }
                $opt_array[constant($key)] = $value;
            }
        }
        
		curl_setopt_array($curl, $opt_array);

        $result = curl_exec($curl);
        $curl_info = curl_getinfo($curl);
        curl_close($curl);

        return $curl_info;
    }
}
