<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <link href="//vjs.zencdn.net/5.10.4/video-js.css" rel="stylesheet">

    <!-- If you'd like to support IE8 -->
    <script src="//vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <style>
        html, body, form{
            height: 100%;
            margin:0px;
            padding:0px;
            background-color:#000;
        }
        #my-video {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <video id="my-video" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="100%" height="100%" data-setup="{}">
        <source src="<?php echo $_GET['url']; ?>" type='video/mp4'>
    </video>

    <script src="js/videojs.js"></script>
    <script>
        //videojs.options.flash.swf = "video-js.swf";
    </script>

</body>
</html>