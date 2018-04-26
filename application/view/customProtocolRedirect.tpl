<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>30 Days Self Love Invite</title>
    <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>

</head>
<body>

<!-- iframe used for attempting to load a custom protocol -->
<iframe style="display:none" height="0" width="0" id="loader"></iframe>

<script>
    $(function()
    {
        // For desktop browser, remember to pass though any metadata on the link for deep linking
        var fallbackLink = 'http://example.com/my-web-app/'+window.location.search+window.location.hash;
        window.location = "{$redirect}";

        window.setTimeout(function (){ window.location.replace(fallbackLink); }, 25);

        /*
         Q&A

         I have a native desktop app as well, how do I link to a custom protocol handler on the desktop?
         IE Only: http://msdn.microsoft.com/en-us/library/ms537512.aspx#Version_Vectors
         All Other Browsers: Use a custom plugin like iTunes does: http://ax.itunes.apple.com/detection/itmsCheck.js

         */

    });
</script>

hello
</body>
</html>