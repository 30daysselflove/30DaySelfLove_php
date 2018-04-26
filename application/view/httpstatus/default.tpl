<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<!--
 index.html
 NewApplication

 Created by You on February 22, 2011.
 Copyright 2011, Your Company All rights reserved.
-->
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, chrome=1" />

        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />

        <link rel="apple-touch-icon" href="Resources/icon.png" />
        <link rel="apple-touch-startup-image" href="Resources/default.png" />
		
        <title>PhantomTweets.com - Woops! {$statusCode} Error</title>
    {literal}
        <style type="text/css">

    *{margin:0;padding:0}
    html,code{font:15px/22px arial,sans-serif}
    html{background:#fff;color:#222;padding:15px}
    body{margin:40px auto 0 auto; max-width:750px;min-height:180px;padding:30px 0 15px}
    
    p{margin:11px 0 22px;overflow:hidden}
    ins{color:#777;text-decoration:none}
    a img{border:0}

    h1 {
        line-height: 1.1;
        font-size: 16px;
    }

    ul
    {
        padding-left: 20px;
    }

    #tech
    {
        margin-top:20px;
        display: block;
        padding: 45px;
        background-color: #F7F7F7;
        font-size: 12px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }

    a {
        color:#507786;
        text-decoration: none;
    }

        a:hover {
        text-decoration: underline;
    }



        </style>
    {/literal}
    </head>

    <body>
    <img src="/www/img/phantom-logo.jpg" alt="Error"></a>
    <p><b>{$statusCode}.</b> <ins>{if $statusCode < 500 || $statusCode > 399}There was a problem processing your request.{else}There was an error.{/if}</ins></p>
    <p>Error : {$statusMessage['message']}.<br/><ins>{if $statusCode < 500 || $statusCode > 399}Please alter the parameters of your request.{else}We were notified of the error and are working on correcting it.{/if}</ins>
    </p>

    <a href="#" id="showtech">Show technical details</a>
    <div style="display:none" id="tech">
        
    <h1>{$statusCode} - {$statusMessage['message']}</h1>
        <ul>
    	{foreach from=$statusMessage key=k item=v}
    		{if is_array($v)}
    		
    		{foreach from=$v key=k1 item=v1}
    		<li>{$k1}: {$v1}</li>
    		{/foreach}


    		{else}
    		<li>{$k}: {$v}</li>
    		{/if}
    	   
    	{/foreach}
    	</ul>
    </div>
    
    </body>

</html>

{literal}
<!--
{extends file='$smarty.const.SMARTY_COMMON_BASE_PATH}/wrapper.tpl'}


{block name=pagetitle}
    Blizzfull - Woops! {$statusCode} Error
{/block}

   

{block name=pagecontent}
    <h1>{$statusCode} - {$statusMessage['message']}</h1>
    <ul>
    {foreach from=$statusMessage key=k item=v}
        {if is_array($v)}
        
        {foreach from=$v key=k1 item=v1}
        <li>{$k1}: {$v1}</li>
        {/foreach}
    
        {else}
        <li>{$k}: {$v}</li>
        {/if}
       
    {/foreach}
    </ul>
{/block}
-->

<script type="text/javascript">
        document.getElementById('showtech').onclick = function(e){
          document.getElementById('tech').style.display = "block";
          e.preventDefault();
          return false;
        }
</script>


{/literal}



