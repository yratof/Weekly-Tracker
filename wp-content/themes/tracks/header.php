<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

  <!-- Styles -->
  <?php wp_head(); ?>
  <style media="" data-href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">button,hr,input{overflow:visible}audio,canvas,progress,video{display:inline-block}progress,sub,sup{vertical-align:baseline}html{font-family:sans-serif;line-height:1.15;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0} menu,article,aside,details,footer,header,nav,section{display:block}h1{font-size:2em;margin:.67em 0}figcaption,figure,main{display:block}figure{margin:1em 40px}hr{box-sizing:content-box;height:0}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em}a{background-color:transparent;-webkit-text-decoration-skip:objects}a:active,a:hover{outline-width:0}abbr[title]{border-bottom:none;text-decoration:underline;text-decoration:underline dotted}b,strong{font-weight:bolder}dfn{font-style:italic}mark{background-color:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}audio:not([controls]){display:none;height:0}img{border-style:none}svg:not(:root){overflow:hidden}button,input,optgroup,select,textarea{font-family:sans-serif;font-size:100%;line-height:1.15;margin:0}button,input{}button,select{text-transform:none}[type=submit], [type=reset],button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0}[type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring{outline:ButtonText dotted 1px}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}legend{box-sizing:border-box;color:inherit;display:table;max-width:100%;padding:0;white-space:normal}progress{}textarea{overflow:auto}[type=checkbox],[type=radio]{box-sizing:border-box;padding:0}[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-cancel-button,[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}[hidden],template{display:none}/*# sourceMappingURL=normalize.min.css.map */</style>
  <style media="" data-href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">.ui-timepicker-wrapper{overflow-y:auto;height:150px;width:6.5em;background:#fff;border:1px solid #ddd;-webkit-box-shadow:0 5px 10px rgba(0,0,0,.2);-moz-box-shadow:0 5px 10px rgba(0,0,0,.2);box-shadow:0 5px 10px rgba(0,0,0,.2);outline:0;z-index:10001;margin:0}.ui-timepicker-wrapper.ui-timepicker-with-duration{width:13em}.ui-timepicker-wrapper.ui-timepicker-with-duration.ui-timepicker-step-30,.ui-timepicker-wrapper.ui-timepicker-with-duration.ui-timepicker-step-60{width:11em}.ui-timepicker-list{margin:0;padding:0;list-style:none}.ui-timepicker-duration{margin-left:5px;color:#888}.ui-timepicker-list:hover .ui-timepicker-duration{color:#888}.ui-timepicker-list li{padding:3px 0 3px 5px;cursor:pointer;white-space:nowrap;color:#000;list-style:none;margin:0}.ui-timepicker-list:hover .ui-timepicker-selected{background:#fff;color:#000}.ui-timepicker-list .ui-timepicker-selected:hover,.ui-timepicker-list li:hover,li.ui-timepicker-selected{background:#1980EC;color:#fff}.ui-timepicker-list li:hover .ui-timepicker-duration,li.ui-timepicker-selected .ui-timepicker-duration{color:#ccc}.ui-timepicker-list li.ui-timepicker-disabled,.ui-timepicker-list li.ui-timepicker-disabled:hover,.ui-timepicker-list li.ui-timepicker-selected.ui-timepicker-disabled{color:#888;cursor:default}.ui-timepicker-list li.ui-timepicker-disabled:hover,.ui-timepicker-list li.ui-timepicker-selected.ui-timepicker-disabled{background:#f2f2f2}/*# sourceMappingURL=jquery.timepicker.min.css.map */</style>
</head>

<body <?php body_class(); ?> itemscope="" itemtype="http://schema.org/WebPage">

<?php // Current user
$current_user    = wp_get_current_user();

if ( is_user_logged_in() ) {?>

  <header class="header">
    <strong><?php echo date( 'l jS F Y', current_time( 'timestamp', 1 ) ); ?></strong>
    <span><?php echo $current_user->display_name; ?> — <a class="logout" href="<?php echo wp_logout_url(); ?>">Logout</a></span>
  </header>

<?php } ?>
