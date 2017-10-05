<?php

/**
*/
function fPre($array) {
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}
/**
*/

/*
* @fGetPageContent
* @global {Stirng} sUserAgent - A valid user agent.
* @param {String} sUrl - Facebook post photo url.
* @return {String} - The content page.
*/
function fGetPageContent($sUrl) {
  global $sUserAgent;
  $oContext     = stream_context_create(
    array(
      'http' => array(
        'user_agent' => $sUserAgent
      )
    )
  );
  $sContent     = file_get_contents($sUrl, false, $oContext);
  return $sContent;
}

/*
* @fGetFacebookPhotoPost
* @param {String} sUrl - Facebook post photo url.
* @return {Array} The output result.
* @example "on error"
* array(
*   'error' => array(
*     'code' => '404|202'
*   )
* )
* @example "on success"
* array(
*   'success' => array(
*     'code' => '202',
*     'data' => array(
*       'url'             => 'String',
*       'author'          => 'String',
*       'authorImage'     => 'String - url',
*       'date'            => 'String - timestamp',
*       'text'            => 'String',
*       'image'           => 'String - url'
*     )
*   )
* )
*/
function fGetFacebookPhotoPost($sUrl) {
  $sContent     = fGetPageContent($sUrl);
  $aOutputData  = array();
  if( empty($sContent) ) {
    return array(
      'error' => array(
        'code' => '404'
      )
    );
  }
  $aOutputData['url']         = $sUrl;
  preg_match_all('/<meta property=\"og:title\" content=\"(.*)\" \/><meta property=\"og:description\" content=\"/', $sContent, $aOutputAuthor);
  $aOutputData['author']      = $aOutputAuthor[1][0];
  preg_match_all('/<img class=\"_s0 _4ooo _5xib _5sq7 _44ma _rw img\" src=\"(.*)\" alt=\"\"/', $sContent, $aOutputAuthorImage);
  $aOutputData['authorImage'] = $aOutputAuthorImage[1][0];
  // Timestamp.
  preg_match_all('/data-utime=\"([0-9]+)\"/', $sContent, $aOutputDate);
  $aOutputData['date']        = strip_tags($aOutputDate[1][0]);
  preg_match_all('/<div class=\"_5pbx userContent\"(.*)<\/div><div class=\"_3x-2\">/', $sContent, $aOutputText);
  $aOutputData['text']        = strip_tags($aOutputText[0][0]);
  preg_match_all('/<img class=\"scaledImageFitWidth img\" src=\"(.*)\" alt=/', $sContent, $aOutputImage);
  $aOutputData['image']       = strip_tags($aOutputImage[1][0]);
  unset($aOutputData['post']);
  if( empty($aOutputData['author']) || empty($aOutputData['authorImage']) || empty($aOutputData['date']) ||
    empty($aOutputData['text']) || empty($aOutputData['image']) ) {
    return array(
      'error' => array(
        'code'    => '202',
        'data'      => $aOutputData
      )
    );
  }
  return array(
    'success' =>  array(
      'code'      => '200',
      'data'      => $aOutputData
    )
  );
}

/*
* @fGetInstagramPhotoPost
* @param {String} sUrl - Facebook post photo url.
* @return {Array} The output result.
* @example "on error"
* array(
*   'error' => array(
*     'code' => '404|202'
*   )
* )
* @example "on success"
* array(
*   'success' => array(
*     'code' => '202',
*     'data' => array(
*       'url'             => 'String',
*       'author'          => 'String',
*       'authorImage'     => 'String - url',
*       'date'            => 'String - timestamp',
*       'text'            => 'String',
*       'image'           => 'String - url'
*     )
*   )
* )
*/
function fGetInstagramPhotoPost($sUrl) {
  $sContent     = fGetPageContent($sUrl);
  $aOutputData  = array();
  if( empty($sContent) ) {
    return array(
      'error' => array(
        'code' => '404'
      )
    );
  }
  $aOutputData['url']         = $sUrl;
  preg_match_all('/<meta property=\"og:description\" content=\"(.*)@(.*)\)(.*)\" \/>/', $sContent, $aOutputAuthor);
  $aOutputData['author']      = $aOutputAuthor[2][0];
  preg_match_all('/<meta property=\"instapp:owner_user_id\" content=\"([0-9]+)\" \/>/', $sContent, $aOutputAuthorId);
  $aOutputData['authorId']    = $aOutputAuthorId[1][0];
  preg_match_all('/{\"id\": \"'.$aOutputData['authorId'].'\", \"profile_pic_url\": \"(.*)\", \"username\": \"'.$aOutputData['author'].'\"}/', $sContent, $aOutputAuthorImage);
  $aOutputData['authorImage'] = $aOutputAuthorImage[1][0];
  // Timestamp.
  preg_match_all('/\"taken_at_timestamp\": ([0-9]+), \"edge_media_preview_like\":/', $sContent, $aOutputDate);
  $aOutputData['date']        = $aOutputDate[1][0];
  preg_match_all('/<meta property=\"og:image\" content=\"(.*)\" \/>/', $sContent, $aOutputImage);
  $aOutputData['image']       = $aOutputImage[1][0];
  preg_match_all('/{\"text\": \"(.*)\"}}]}, \"caption_is_edited\"/', $sContent, $aOutputText);
  $aOutputData['text']        = $aOutputText[1][0];
  unset($aOutputData['authorId']);
  unset($aOutputData['post']);
  if( empty($aOutputData['author']) || empty($aOutputData['authorImage']) || empty($aOutputData['date']) ||
    empty($aOutputData['text']) || empty($aOutputData['image']) ) {
    return array(
      'error' => array(
        'code'    => '202',
        'data'      => $aOutputData
      )
    );
  }
  return array(
    'success' =>  array(
      'code'      => '200',
      'data'      => $aOutputData
    )
  );
}

/*
* @fGetTwitterPhotoPost
* @param {String} sUrl - Twitter post photo url.
* @return {Array} The output result.
* @example "on error"
* array(
*   'error' => array(
*     'code' => '404|202',
*     'data' => array((...))
*   )
* )
* @example "on success"
* array(
*   'success' => array(
*     'code' => '202',
*     'data' => array(
*       'url'             => 'String',
*       'author'          => 'String',
*       'authorName'      => 'String',
*       'authorImage'     => 'String - url',
*       'date'            => 'String - timestamp',
*       'text'            => 'String',
*       'image'           => 'String - url'
*     )
*   )
* )
*/
function fGetTwitterPhotoPost($sUrl) {
  $sContent     = fGetPageContent($sUrl);
  $aOutputData  = array();
  if( empty($sContent) ) {
    return array(
      'error' => array(
        'code' => '404'
      )
    );
  }
  $aPattern     = array(
    'author'          => '/twitter.com\/(.*)\/status\/(.*)/',
    'authorName'      => '/<meta  property=\"og:title\" content=\"(.*) on Twitter\">/',
    'authorImage'     => '/<img class=\"avatar js-action-profile-avatar\" src=\"(.*)\" alt=\"\">/',
    'date'            => '/data-time=\"(.*)\" data-time-ms=\"(.*)\"/',
    'text'            => '/<meta  property=\"og:description\" content=\"(.*)\">/',
    'image'           => '/<meta  property=\"og:image\" content=\"(.*)\">/',
  );
  preg_match(       $aPattern['author'],        $sUrl,      $aOutputData['author']);
  preg_match_all(   $aPattern['authorName'],        $sContent,  $aOutputData['authorName']);
  preg_match_all(   $aPattern['authorImage'],   $sContent,  $aOutputData['authorImage']);
  preg_match_all(   $aPattern['date'],          $sContent,  $aOutputData['date']);
  preg_match_all(   $aPattern['text'],          $sContent,  $aOutputData['text']);
  preg_match_all(   $aPattern['image'],         $sContent,  $aOutputData['image']);
  $aOutputData = array(
    'url'         => $sUrl,
    'author'      => $aOutputData['author'][1],
    'authorName'  => $aOutputData['authorName'][1][0],
    'authorImage' => $aOutputData['authorImage'][1][0],
    'date'        => $aOutputData['date'][1][0],
    'text'        => $aOutputData['text'][1][0],
    'image'       => $aOutputData['image'][1],
  );
  if( empty($aOutputData['author']) || empty($aOutputData['authorName']) || empty($aOutputData['authorImage']) || 
    empty($aOutputData['date']) || empty($aOutputData['text']) || empty($aOutputData['image']) ) {
    return array(
      'error' => array(
        'code'    => '202',
        'data'      => $aOutputData
      )
    );
  }
  if($aOutputData)
  return array(
    'success' =>  array(
      'code'      => '200',
      'data'      => $aOutputData
    )
  );
}


echo '
<form method="POST" action="#">
<input type="text" name="formUrl" />
<input type="submit" />
</form>
';

if( !empty( $_POST['formUrl'] ) ) {

  var_dump(
    fGetTwitterPhotoPost( $_POST['formUrl'] )['success']['data']
  );
}

exit();
