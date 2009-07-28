<?php
/*
 * Amazon Web Service with PHP/SOAP
 * @author P_BLOG Project
 * @since  2005-05-07 22:57:08
 * updated 2006-02-04 14:54:24
 */

// User Config
//------------------------------------

$associate_id    = '';
$developer_token = '';

//------------------------------------

if (!empty($_GET['keyword'])) {

    if (isset($_GET['p'])) {
        $page = $_GET['p'];
    } else {
        $page = '1';
    }
    
    $soap = new SoapClient("http://soap.amazon.co.jp/schemas3/AmazonWebServices.wsdl");
    $keyword = mb_convert_encoding($_GET['keyword'], "UTF-8", "auto");
    $param = array(
        'keyword' => $keyword,
        'mode' => 'books-jp',
        'locale' => 'jp',
        'sort' => '+salesrank',
        'type' => 'lite',
        'page' => $page,
        'tag' => $associate_id,
        'devtag' => $developer_token
    );
    
    // Request & Result
    $result = $soap->KeywordSearchRequest($param);
    mb_convert_variables("UTF-8", "auto", $result);
    $result_message = '「<span class="highlight">'.$keyword.'</span>」で検索、'.$result->TotalResults.'件がヒットしました。';
    
    // Pager
    $pager = '';
    $page_max = ceil($result->TotalResults / 10);
    for ($i = 0; $i < $page_max; $i++) {
        $current = ($i + 1);
        if ($page == $current) {
            $pager .= '<strong>'.$current.'</strong>';
        } else {
            $pager .= '<a href="'.$_SERVER['PHP_SELF'].'?keyword='.urlencode($keyword).'&amp;p='.$current.'">'.$current.'</a>';
        }
    }
    
    // Initialize the item information
    $detail_url = '';
    $detail_imageurlsmall = 'http://images-jp.amazon.com/images/G/09/x-locale/detail/thumb-no-image.gif';
    $detail_productname = '';
    $detail_authors = '';
    $detail_listprice = '';
    
    $item_list = '';
    foreach ($result->Details as $detail) {
        if (isset($detail->Url)) {
            $detail_url = $detail->Url;
        }
        if (isset($detail->ProductName)) {
            $detail_productname = $detail->ProductName;
        }
        if (isset($detail->ImageUrlSmall)) {
            $detail_imageurlsmall = $detail->ImageUrlSmall;
        }
        if (isset($detail->ImageUrlLarge)) {
            $detail_imageurllarge = $detail->ImageUrlLarge;
        }
        if (isset($detail->Authors)) {
            $detail_authors = $detail->Authors;
            if (is_array($detail_authors)) {
                $detail_authors = join(' , ', $detail_authors);    
            }
        }
        if (isset($detail->ListPrice)) {
            $detail_listprice = $detail->ListPrice;
        }
        if (isset($detail->OurPrice)) {
            $detail_ourprice = $detail->OurPrice;
        }
        if (isset($detail->ReleaseDate)) {
            $detail_releasedate = $detail->ReleaseDate;
        }
        if (isset($detail->Manufacturer)) {
            $detail_manufacturer = $detail->Manufacturer;
        }
        if (isset($detail->Availability)) {
            $detail_availability = $detail->Availability;
        }
        $item_list .=<<<EOD
<!-- Item -->
<dt class="float-clear">
<a href="{$detail_url}">{$detail_productname}</a>
</dt>
<dd class="float-left">
<a href="{$detail_imageurllarge}">
<img src="{$detail_imageurlsmall}" alt="{$detail_productname}" />
</a>
</dd>
<dd>著者：{$detail_authors}</dd>
<dd>発刊日：{$detail_releasedate} / {$detail_manufacturer}</dd>
<dd>価格：<span class="price">{$detail_listprice}</span> | {$detail_availability}</dd>

EOD;
    }
    $result_list =<<<EOD
<dl>
{$item_list}</dl>
EOD;

} else {
    $result_message = '待機中...';
    $result_list = '';
    $pager = '';
}

// Presentation!
$contents =<<<EOD
<h2>Amazon検索 with PHP/SOAP</h2>
<form action="{$_SERVER['PHP_SELF']}" method="get">
<p>
<input type="text" name="keyword" value="" />
<input type="submit" value="検索" />
</p>
</form>
<p id="result-message">{$result_message}</p>
<p class="flip-link">{$pager}</p>
{$result_list}
<p class="flip-link float-clear">{$pager}</p>
EOD;
?>