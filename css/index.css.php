<?php

header( 'Content-type: text/css' );

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

	$layout = "
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
    ";

    $layout_inline = "
        display: -ms-inline-flexbox;
        display: -webkit-inline-flex;
        display: inline-flex;
    ";

    $layout_horizontal = "
      $layout

        -ms-flex-direction: row;
        -webkit-flex-direction: row;
        flex-direction: row;
    ";

    $layout_horizontal_reverse = "
      $layout

        -ms-flex-direction: row-reverse;
        -webkit-flex-direction: row-reverse;
        flex-direction: row-reverse;
    ";

    $layout_vertical = "
      $layout

        -ms-flex-direction: column;
        -webkit-flex-direction: column;
        flex-direction: column;
    ";

    $layout_vertical_reverse = "
      $layout

        -ms-flex-direction: column-reverse;
        -webkit-flex-direction: column-reverse;
        flex-direction: column-reverse;
    ";

    $layout_wrap = "
        -ms-flex-wrap: wrap;
        -webkit-flex-wrap: wrap;
        flex-wrap: wrap;
    ";

    $layout_wrap_reverse = "
        -ms-flex-wrap: wrap-reverse;
        -webkit-flex-wrap: wrap-reverse;
        flex-wrap: wrap-reverse;
    ";

    $layout_flex_auto = "
        -ms-flex: 1 1 auto;
        -webkit-flex: 1 1 auto;
        flex: 1 1 auto;
    ";

    $layout_flex_none = "
        -ms-flex: none;
        -webkit-flex: none;
        flex: none;
    ";

    $layout_flex = "
        -ms-flex: 1 1 0.000000001px;
        -webkit-flex: 1;
        flex: 1;
        -webkit-flex-basis: 0.000000001px;
        flex-basis: 0.000000001px;
    ";

    $layout_flex_2 = "
        -ms-flex: 2;
        -webkit-flex: 2;
        flex: 2;
    ";

    $layout_flex_3 = "
        -ms-flex: 3;
        -webkit-flex: 3;
        flex: 3;
    ";

    $layout_flex_4 = "
        -ms-flex: 4;
        -webkit-flex: 4;
        flex: 4;
    ";

    $layout_flex_5 = "
        -ms-flex: 5;
        -webkit-flex: 5;
        flex: 5;
    ";

    $layout_flex_6 = "
        -ms-flex: 6;
        -webkit-flex: 6;
        flex: 6;
    ";

    $layout_flex_7 = "
        -ms-flex: 7;
        -webkit-flex: 7;
        flex: 7;
    ";

    $layout_flex_8 = "
        -ms-flex: 8;
        -webkit-flex: 8;
        flex: 8;
    ";

    $layout_flex_9 = "
        -ms-flex: 9;
        -webkit-flex: 9;
        flex: 9;
    ";

    $layout_flex_10 = "
        -ms-flex: 10;
        -webkit-flex: 10;
        flex: 10;
    ";

    $layout_flex_11 = "
        -ms-flex: 11;
        -webkit-flex: 11;
        flex: 11;
    ";

    $layout_flex_12 = "
        -ms-flex: 12;
        -webkit-flex: 12;
        flex: 12;
    ";

      /* alignment in cross axis */

    $layout_start = "
        -ms-flex-align: start;
        -webkit-align-items: flex-start;
        align-items: flex-start;
    ";

    $layout_center = "
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    ";

    $layout_end = "
        -ms-flex-align: end;
        -webkit-align-items: flex-end;
        align-items: flex-end;
    ";

    $layout_baseline = "
        -ms-flex-align: baseline;
        -webkit-align-items: baseline;
        align-items: baseline;
    ";

      /* alignment in main axis */

    $layout_start_justified = "
        -ms-flex-pack: start;
        -webkit-justify-content: flex-start;
        justify-content: flex-start;
    ";

    $layout_center_justified = "
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
    ";

    $layout_end_justified = "
        -ms-flex-pack: end;
        -webkit-justify-content: flex-end;
        justify-content: flex-end;
    ";

    $layout_around_justified = "
        -ms-flex-pack: distribute;
        -webkit-justify-content: space-around;
        justify-content: space-around;
    ";

    $layout_justified = "
        -ms-flex-pack: justify;
        -webkit-justify-content: space-between;
        justify-content: space-between;
    ";

    $layout_center_center = "
      $layout_center
      $layout_center_justified
    ";

      /* self alignment */

    $layout_self_start = "
        -ms-align-self: flex-start;
        -webkit-align-self: flex-start;
        align-self: flex-start;
    ";

    $layout_self_center = "
        -ms-align-self: center;
        -webkit-align-self: center;
        align-self: center;
    ";

    $layout_self_end = "
        -ms-align-self: flex-end;
        -webkit-align-self: flex-end;
        align-self: flex-end;
    ";

    $layout_self_stretch = "
        -ms-align-self: stretch;
        -webkit-align-self: stretch;
        align-self: stretch;
    ";

    $layout_self_baseline = "
        -ms-align-self: baseline;
        -webkit-align-self: baseline;
        align-self: baseline;
    ";

      /* multi-line alignment in main axis */

    $layout_start_aligned = "
        -ms-flex-line-pack: start;  /* IE10 */
        -ms-align-content: flex-start;
        -webkit-align-content: flex-start;
        align-content: flex-start;
    ";

    $layout_end_aligned = "
        -ms-flex-line-pack: end;  /* IE10 */
        -ms-align-content: flex-end;
        -webkit-align-content: flex-end;
        align-content: flex-end;
    ";

    $layout_center_aligned = "
        -ms-flex-line-pack: center;  /* IE10 */
        -ms-align-content: center;
        -webkit-align-content: center;
        align-content: center;
    ";

    $layout_between_aligned = "
        -ms-flex-line-pack: justify;  /* IE10 */
        -ms-align-content: space-between;
        -webkit-align-content: space-between;
        align-content: space-between;
    ";

    $layout_around_aligned = "
        -ms-flex-line-pack: distribute;  /* IE10 */
        -ms-align-content: space-around;
        -webkit-align-content: space-around;
        align-content: space-around;
    ";

      /*******************************
                * Other Layout
      *******************************/

    $layout_block = "
        display: block;
    ";

    $layout_invisible = "
        visibility: hidden !important;
    ";

    $layout_relative = "
        position: relative;
    ";

    $layout_fit = "
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    ";

    $layout_scroll = "
        -webkit-overflow-scrolling: touch;
        overflow: auto;
    ";

    $layout_fullbleed = "
        margin: 0;
        height: 100vh;
    ";

      /* fixed position */

    $layout_fixed_top = "
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
    ";

    $layout_fixed_right = "
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
    ";

    $layout_fixed_bottom = "
        position: fixed;
        right: 0;
        bottom: 0;
        left: 0;
    ";

    $layout_fixed_left = "
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
    ";


?>

/* shell-controller */

body {

    font-family: sans-serif;
    font-weight: 400;
    font-size: 1vw;
    
    padding: 1em;
    
	<?=$layout_horizontal?>

}

h1 { font-size: 110%; margin: 0 0 0.25em 0; }

h1:not(:first-child) { margin-top: 0.5em; }

/* form */

li {
    box-sizing: border-box;
    list-style: none;
    padding: 0.25em;
    font-size: 100%;
    
    <?=$layout_horizontal?>
    
}

label {
    width: 10em;
    max-width: 10em;
    
    text-align: right;
    cursor: default;
    padding: 0.2em;
}

label.small {
    width: auto;
}

input,
select {
    
    
    font-size: 100%;
    font-weight: bold;
    padding: 0.2em;
    min-width: 4em;

    margin-left: 0.25em;
    
    border: none;
    border-radius: 0;
}

input[disabled] { color: #999; }

select {
    margin-left: 0
}

input {
    <?=$layout_flex?>
}

button {
    font-size: 100%;
    font-weight: bold;
    padding: 0.25em;
}

select {
    font-size: 100%;
    font-weight: bold;
    padding: 0.5em;
    border: none;
}

.file-container {
    overflow: hidden;
    position: relative;
}

.file-container form { display: inline; }

.file-container [type=file] {
    cursor: inherit;
    display: block;
    font-size: 999px;
    filter: alpha(opacity=0);
    min-height: 100%;
    min-width: 100%;
    opacity: 0;
    position: absolute;
    right: 0;
    text-align: right;
    top: 0;
}

a.reset { text-align: right; font-size: 80%;
    height: 0;
    position: relative; top: -2em;
    overflow: visible; display: block; }


.preview select { width: 100%; }


/* images */

body:not(.multiple-images) *.multiple-images { display: none; }

/* panel */

div.panel {
    
    margin: 0.1em;
    
    padding: 16px;
    min-width: 12em;
    
    background: #D0D0D0;
    
}

div.images {
    
    <?=$layout_vertical?>
    
}

div.panel img {
    width: 100%;
    max-width: 20em;
    height: auto;
    border: 1px solid grey;
}

div.panel div.image-container {
    position: relative;
}

div.panel div.image-container span {
    position: absolute;
    left: 0; top: 0;
    font-size: 75%;
    background: rgba( 255,255,128, 0.5);
    padding: 0.2em;
}

.image-container img.empty {
    background: white;
    min-width: 100%;
}

.panel.preview {
    <?=$layout_flex?>
}

.status {
    background: lightgoldenrodyellow;
    padding: 0.25em;
    margin: 0 0 0.1em 0;
    
    <?=$layout_horizontal?>
}

div.images.panel {
    max-width: 10em;
}

#btnStart, #btnStop { width: 100%; margin: 0;}

#btnStop { background: #d27977; }


@media all and (max-width: 500px){
    
    body {
        <?=$layout_vertical?>
        font-size: 4vw;
    }
    
    .panel.preview {
        <?=$layout_flex_none?>
    }
    
    div.images.panel {
        max-width: unset;
    }
