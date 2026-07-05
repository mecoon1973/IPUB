<?php
namespace Core;
use Core\Object\Paginate;
use Core\Object\PagiInfo;


class Helper {
    public function __construct() {
    }

    public function objectToArray($d) {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            return array_map(array(__CLASS__, 'objectToArray'), $d);
        }
        else {
            return $d;
        }
    }

    public function arrayToObject($d) {
        if (is_array($d)) {
            return (object) array_map(array(__CLASS__, 'arrayToObject'), $d);
        }
        else {
            return $d;
        }
    }

    /**
     * Tính toán phân trang
     *
     * @param Paginate $paginate
     * @return object
     */
    public function calculatorPagi(Paginate $paginate) {
        $page = explode("-", $paginate->page);
        $page = end($page);
        $pagination = config("app.pagination");
        $selectAll = false;
        if($paginate->limit == "all") {
            $paginate->limit = 20;
            $selectAll = true;
        }
        if ($paginate->limit > 0)
            $pagination['limit'] = $paginate->limit;
        if (count($paginate->sorted) > 0)
            $pagination['sorted'] = $paginate->sorted;
        $pagination['skip'] = (intval($page) - 1) * intval($paginate->limit);
        if($selectAll) {
            $pagination["limit"] = "all";
        }
        return (object)["page" => $page,"pagination" => $pagination];
    }

    /**
     * Phân trang
     * Trả về mảng các số trang và số trang hiện tại (dạng cũ)
     *
     * @param int $total
     * @param int|null $limit
     * @param int|null $current
     * @param int $adjacents
     * @return PagiInfo
     */
    public function pagination($total, $limit = null, $current = null, $adjacents = 3) {
        if (!is_null($current) && !is_numeric($current)) $current = 1;
        $result = array();
        if (isset($total, $limit) === true)
        {
            if($limit == "all") return new PagiInfo([]);
            $result = range(1, ceil($total / $limit));
            if (isset($current, $adjacents) === true)
            {
                if (($adjacents = floor($adjacents / 2) * 2 + 1) >= 1)
                {
                    $result = array_slice($result, max(0, min(count($result) - $adjacents, intval($current) - ceil($adjacents / 2))), $adjacents);
                }
            }
        }
        $pagi_number = array_filter($result, function($value) {
            return $value > 0;
        });
        $last = ceil($total/$limit);
        return new PagiInfo(["pagi_number" => $pagi_number, "last" => $last, "limit" => $limit, "current_page" => $current, "total" => $total]);
    }

    public function getQuery() {
        return str_replace(request()->url(), '',request()->fullUrl());
    }

    public function getFullPath() {
        $query = $this->getQuery();
        return request()->path().$query;
    }

    public function getEncodeCurrentUrl() {
        return urlencode(request()->fullUrl());
    }

    /**
     * Lấy đường dẫn hiện tại không bao gồm tham số page, trang-truoc, trang-sau
     * @return string Đường dẫn không bao gồm tham số page, trang-truoc, trang-sau
     */
    public function getPathWithoutPage() {
        $path = explode("/", request()->path());
        $lastPath = end($path);

        $pos = strpos($lastPath, "page-");
        $isBeforePage = $lastPath === "trang-truoc";
        $isAfterPage = $lastPath === "trang-sau";

        if(strval($pos) === "0" || $isBeforePage || $isAfterPage) {
            array_pop($path);
        }
        return implode("/", $path);
    }

    public function checkTypeIsInPostCategories($categories, $type) {
        if(isset($categories) && count($categories) > 0) {
            foreach ($categories as $cate) {
                if($cate->type == $type) {
                    return true;
                }
            }
        }
        return false;
    }

    public function changeCurrentQueryUrl($new_key = null, $new_value = null, $currentRoute = true) {
        $query = [];
        foreach (request()->query() as $key => $value) {
            if(is_array($value)) {
                foreach ($value as $v) {
                    $query[$key] = $key."=".$v;
                }
            } else {
                $query[$key] = $key."=".$value;
            }
        }
        if(!is_null($new_key)) {
            if(is_null($new_value)) {
                unset($query[$new_key]);
            } else {
                $query[$new_key] = $new_key."=".$new_value;
            }
        }
        $pathName = "";
        if($currentRoute) {
            $regexRoute = "page-[1-9][0-9]*";
            $pathName = request()->path();
            $pathName = "/".preg_replace("/\/$regexRoute/im", "", $pathName);
        }
        return $pathName."?".implode("&", $query);
    }

    public function convertStateQueryUrl($query) {
        if(is_array($query)) {
            return $this->convertQueryToString($query);
        } else {
            return $this->convertQueryToArray($query);
        }
    }

    public function convertQueryToString($query) {
        $_query = [];
        foreach ($query as $key => $value) {
            $_query[$key] = $key."=".$value;
        }
        return "?".implode("&", $_query);
    }

    public function convertQueryToArray($query) {
    }

    public function bin2hex_encrypt($str, $key = 'olm') {
        $shift = 5;//hexdec( bin2hex($key) ) % 16;
        $hex = bin2hex($str);
        $newhex = '';
        for($i = 0; $i < strlen($hex); $i++){
            $newhex .= dechex( (hexdec($hex[$i]) + $shift) % 16 );
        }
        return $newhex;
    }

    public function uploadFile($file, $path = "", $quality = 100) {
        $serverImage = config("label.CDN_HOC24_1");
        $pathUploadFile = $serverImage."/uploadImage1.php?base_path=".$path."&quality=".$quality;
        $res = [];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $pathUploadFile,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('file'=> new \CURLFILE($file->path())),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);
        if($response->stt == 1) {
            $res['status'] = "success";
            $res['message'] = $serverImage."/".$response->url;
        } else {
            $res['status'] = "error";
            $res['message'] = $response->message;
        }
        return $res;
    }

    public function renderImageCaptcha() {
        $w = 250;
        $h = 80;
        $chars = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfhjklzxcvbnm";
        $char_list = array();
        $captchar_str = "";
        for($i =0; $i < 6; $i++) {
            $rand = mt_rand(0,50);
            $c = $chars[$rand];
            $char_list[$i] = $c;
            $captchar_str .= $c;
        }
        session(['captchar.str' => strtolower($captchar_str)]);
        $img = imagecreatetruecolor($w,$h);
        $white =  imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 0, 0, $w, $h, $white);
        $font = getcwd().'/fonts/font_bold.ttf';

        for($i =0; $i < 6; $i++) {
            $tone = mt_rand(0,2);
            if($tone == 0) {
                $color =  imagecolorallocate($img, mt_rand(150,255), mt_rand(0,50), mt_rand(0,10));
            } else if($tone == 1) {
                $color =  imagecolorallocate($img, mt_rand(0,90), mt_rand(150,200), mt_rand(0,10));
            } else {
                $color =  imagecolorallocate($img, mt_rand(0,10), mt_rand(0,100), mt_rand(150,255));
            }
            $text = $char_list[$i];
            imagettftext($img, 32, mt_rand(-15,15),10 + $i*35, 60, $color, $font, $text);
        }
        imagepng($img);
    }

    public function isRequestHtml() {
        return str_contains(request()->header()["accept"][0], "html");
    }


    public function _call_user_func_custom($class, $func, $param = null) {
        if(!$param) {
            return $class->$func();
        } else {
            return is_array($param) ?
            call_user_func_array([$class,  $func], $param) :
            call_user_func([$class,  $func], $param);
        }
    }


}
