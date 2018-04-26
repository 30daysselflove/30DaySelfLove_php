{extends file='user-email-wrapper.tpl'}

{block name="subject"}Reset your 30 Days Self Love password{/block}
{block name="metatitle"}Reset your 30 Days Self Love password{/block}
{block name="title"}{/block}
{block name="subTitle"}Reset your 30 Days Self Love password{/block}
{block name="topText"}Hi {$user->username}!{/block}
{block name="welcomeText"}Reset Password{/block}
{block name="buttonText"}Reset Password{/block}
{block name="button"}
    <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
        <!----------------- Button Center ----------------->
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td align="center" height="45"bgcolor="#ffffff" style="border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; padding-left: 30px; padding-right: 30px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; color: rgb(42, 42, 42); text-transform: uppercase; background-color: rgb(255, 255, 255);">
                            <!--[if !mso]><!--><span style="font-family: 'proxima_nova_rgbold', Helvetica; font-weight: normal;"><!--<![endif]-->
																<a href="{$link}" style="color: rgb(42, 42, 42); font-size: 15px; text-decoration: none; line-height: 34px; width: 100%;">{block name="buttonText"}Go to the Site{/block}</a>
															<!--[if !mso]><!--></span><!--<![endif]-->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!----------------- End Button Center ----------------->
    </table>
{/block}
{block name="mainText"}Click the button below to reset your password.{/block}