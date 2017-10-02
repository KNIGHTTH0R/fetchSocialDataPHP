<?php

$sUserAgent     = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36';
$sUrlFacebook   = 'https://www.facebook.com/nytimes/photos/a.283559809998.33779.5281959998/10151320714864999/?type=1&theater';
$sUrlInsta      = 'https://www.instagram.com/p/BYqrvBfhNjP/';
$urlFake        = 'http://docs.phpdoc.org/references/phpdoc/tags/global.html';
$urlNotFound    = 'fgdgsdfgdfgdf.kom';


// Success 202
echo 'ON SUCCESS';
echo fPre(
  fGetFacebookPhotoPost($sUrlFacebook)
);
echo fPre(
  fGetInstagramPhotoPost($sUrlInsta)
);

// Error 404
echo 'ON ERROR 404';
echo fPre(
  fGetFacebookPhotoPost($urlNotFound)
);
echo fPre(
  fGetInstagramPhotoPost($urlNotFound)
);

// Error 202
echo 'ON ERROR 200';
echo fPre(
  fGetFacebookPhotoPost($urlFake)
);
echo fPre(
  fGetInstagramPhotoPost($urlFake)
);

exit();