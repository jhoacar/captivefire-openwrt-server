<html>

<head>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Refresh" content="0; URL=<?php echo file_get_contents(dirname(__DIR__) . '/build/url.txt')?>">
  <style type="text/css">
    body {
      background: white;
      font-family: arial, helvetica, sans-serif;
    }

    a {
      color: black;
    }

    @media (prefers-color-scheme: dark) {
      body {
        background: black;
      }

      a {
        color: white;
      }
    }
  </style>
</head>

<body>
  <a href="<?php echo file_get_contents(dirname(__DIR__) . '/build/url.txt')?>">Captivefire - Splash Page</a>
</body>

</html>