<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width"/>
  <style>
/**********************************************
* Ink v1.0.0 - Copyright 2013 ZURB Inc        *
**********************************************/

/* Client-specific Styles & Reset */

#outlook a { 
  padding:0; 
} 

body{ 
  width:100% !important; 
  -webkit-text-size-adjust:100%; 
  -ms-text-size-adjust:100%; 
  margin:0; 
  padding:0;
}

.ExternalClass { 
  width:100%;
} 

.ExternalClass, 
.ExternalClass p, 
.ExternalClass span, 
.ExternalClass font, 
.ExternalClass td, 
.ExternalClass div { 
  line-height: 100%; 
} 

#backgroundTable { 
  margin:0; 
  padding:0; 
  width:100% !important; 
  line-height: 100% !important; 
}

img { 
  outline:none; 
  text-decoration:none; 
  -ms-interpolation-mode: bicubic;
  width: auto;
  max-width: 100%; 
  float: left; 
  clear: both; 
  display: block;
}

center {
  width: 100%;
}

a img { 
  border: none;
}

p {
  margin: 0 0 0 10px;
}

table {
  border-spacing: 0;
  border-collapse: collapse;
}

td { 
  word-break: break-word;
  -webkit-hyphens: auto;
  -moz-hyphens: auto;
  hyphens: auto;
  border-collapse: collapse !important; 
}

table, tr, td {
  padding: 0;
  vertical-align: top;
  text-align: left;
}

hr {
  color: #d9d9d9; 
  background-color: #d9d9d9; 
  height: 1px; 
  border: none;
}

/* Responsive Grid */

table.body {
  height: 100%;
  width: 100%;
}

table.container {
  width: 580px;
  margin: 0 auto;
  text-align: inherit;
}

table.row { 
  padding: 0px; 
  width: 100%;
  position: relative;
}

table.container table.row {
  display: block;
}

td.wrapper {
  padding: 10px 20px 0px 0px;
  position: relative;
}

table.columns,
table.column {
  margin: 0 auto;
}

table.columns td,
table.column td {
  padding: 0px 0px 10px; 
}

table.columns td.sub-columns,
table.column td.sub-columns,
table.columns td.sub-column,
table.column td.sub-column {
  padding-right: 3.448276%;
}

table.row td.last,
table.container td.last {
  padding-right: 0px;
}

table.one { width: 30px; }
table.two { width: 80px; }
table.three { width: 130px; }
table.four { width: 180px; }
table.five { width: 230px; }
table.six { width: 280px; }
table.seven { width: 330px; }
table.eight { width: 380px; }
table.nine { width: 430px; }
table.ten { width: 480px; }
table.eleven { width: 530px; }
table.twelve { width: 580px; }

td.one { width: 8.333333% !important; }
td.two { width: 16.666666% !important; }
td.three { width: 25% !important; }
td.four { width: 33.333333% !important; }
td.five { width: 41.666666% !important; }
td.six { width: 50% !important; }
td.seven { width: 58.333333% !important; }
td.eight { width: 66.666666% !important; }
td.nine { width: 75% !important; }
td.ten { width: 83.333333% !important; }
td.eleven { width: 91.666666% !important; }
td.twelve { width: 100% !important; }

td.offset-by-one { padding-left: 50px; }
td.offset-by-two { padding-left: 100px; }
td.offset-by-three { padding-left: 150px; }
td.offset-by-four { padding-left: 200px; }
td.offset-by-five { padding-left: 250px; }
td.offset-by-six { padding-left: 300px; }
td.offset-by-seven { padding-left: 350px; }
td.offset-by-eight { padding-left: 400px; }
td.offset-by-nine { padding-left: 450px; }
td.offset-by-ten { padding-left: 500px; }
td.offset-by-eleven { padding-left: 550px; }

td.sub-offset-by-one { padding-left: 5.172413% !important; }
td.sub-offset-by-two { padding-left: 13.793102% !important; }
td.sub-offset-by-three { padding-left: 22.413791% !important; }
td.sub-offset-by-four { padding-left: 31.034480% !important; }
td.sub-offset-by-five { padding-left: 39.655169% !important; }
td.sub-offset-by-six { padding-left: 48.275858% !important; }
td.sub-offset-by-seven { padding-left: 56.896547% !important; }
td.sub-offset-by-eight { padding-left: 65.517236% !important; }
td.sub-offset-by-nine { padding-left: 74.137925% !important; }
td.sub-offset-by-ten { padding-left: 82.758614% !important; }
td.sub-offset-by-eleven { padding-left: 91.379303% !important; }

td.expander {
  visibility: hidden;
  width: 0px;
  padding: 0 !important;
}

/* Block Grid */

.block-grid {
  width: 100%;
  max-width: 580px;
}

.block-grid td {
  display: inline-block;
  padding:10px;
}

.two-up td {
  width:270px;
}

.three-up td {
  width:173px;
}

.four-up td {
  width:125px;
}

.five-up td {
  width:96px;
}

.six-up td {
  width:76px;
}

.seven-up td {
  width:62px;
}

.eight-up td {
  width:52px;
}

/* Alignment & Visibility Classes */

table.center, td.center {
  text-align: center;
}

h1.center,
h2.center,
h3.center,
h4.center,
h5.center,
h6.center {
  text-align: center;
}

span.center {
  display: block;
  width: 100%;
  text-align: center;
}

img.center {
  margin: 0 auto;
  float: none;
}

.show-for-small,
.hide-for-desktop {
  display: none;
}

/* Typography */

body, h1, h2, h3, h4, h5, h6, p { 
  color: #222222;
  display: block; 
  font-family: "Helvetica", "Arial", sans-serif; 
  font-weight: normal; 
  padding:0; 
  margin: 0;
  text-align: left; 
  line-height: 1.3;
}

h1, h2, h3, h4, h5, h6 {
  word-break: normal;
}

h1 {font-size: 59px;}
h2 {font-size: 45px;}
h3 {font-size: 37px;}
h4 {font-size: 28px;}
h5 {font-size: 23px;}
h6 {font-size: 17px;}
body, p {font-size: 14px;line-height:19px;}

p { 
  padding-bottom: 10px;
}

small {
  font-size: 10px;
}

a {
  color: #2ba6cb; 
  text-decoration: none !important;
}

a:hover { 
  color: #2795b6 !important;
}

a:active { 
  color: #2795b6 !important;
}

a:visited { 
  color: #2ba6cb !important;
}

h1 a, 
h2 a, 
h3 a, 
h4 a, 
h5 a, 
h6 a {
  color: #2ba6cb !important;
}

h1 a:active, 
h2 a:active,  
h3 a:active, 
h4 a:active, 
h5 a:active, 
h6 a:active { 
  color: #2ba6cb !important; 
} 

h1 a:visited, 
h2 a:visited,  
h3 a:visited, 
h4 a:visited, 
h5 a:visited, 
h6 a:visited { 
  color: #2ba6cb !important; 
} 

/* Panels */

td.panel {
  background: #f2f2f2;
  border: 1px solid #d9d9d9;
  padding: 10px !important;
}

/* Buttons */

.button table,
.tiny-button table,
.small-button table,
.medium-button table,
.large-button table {
  width: 100%;
  overflow: hidden;
}

.button table td,
.tiny-button table td,
.small-button table td,
.medium-button table td,
.large-button table td {
  display: block;
  width: auto !important;
  text-align: center;
  font-weight: bold;
  text-decoration: none;
  font-family: Helvetica, Arial, sans-serif;
  color: #ffffff;
  background: #2ba6cb;
  border: 1px solid #2284a1;
}

.tiny-button table td {
  padding: 5px 10px;
  font-size: 12px;
  font-weight: normal;
}

.button table td,
.small-button table td {
  padding: 8px 15px;
  font-size: 16px;
}

.medium-button table td {
  padding: 12px 24px;
  font-size: 20px;
}

.large-button table td {
  padding: 21px 30px;
  font-size: 24px;
}

.button:hover table td,
.tiny-button:hover table td,
.small-button:hover table td,
.medium-button:hover table td,
.large-button:hover table td {
  background: #2795b6 !important;
}

.button,
.button:hover,
.button:active,
.button:visited,
.tiny-button,
.tiny-button:hover,
.tiny-button:active,
.tiny-button:visited,
.small-button,
.small-button:hover,
.small-button:active,
.small-button:visited,
.medium-button,
.medium-button:hover,
.medium-button:active,
.medium-button:visited,
.large-button,
.large-button:hover,
.large-button:active,
.large-button:visited {
  color: #ffffff !important; 
  font-family: Helvetica, Arial, sans-serif; 
  text-decoration: none;
}

.secondary table td {
  background: #e9e9e9;
  border-color: #d0d0d0;
}

.secondary:hover table td {
  background: #d0d0d0 !important;
}

.success table td {
  background: #5da423;
  border-color: #457a1a;
}

.success:hover table td {
  background: #457a1a !important;
}

.alert table td {
  background: #c60f13;
  border-color: #970b0e;
}

.alert:hover table td {
  background: #970b0e !important;
}

.radius table td {
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
}

.round table td {
  -webkit-border-radius: 500px;
  -moz-border-radius: 500px;
  border-radius: 500px;
}


/* CUSTOM */
.top-spacing{
  padding-top:7.5px;
}
.details-block-heading{
  text-transform: uppercase;
  font-size:11px;
  margin-bottom:0px;
  padding-bottom:0px;
  text-align:center;
  border-bottom:1px solid #ccc;
}
.details-block{
  list-style:none;
  list-style-type:none;
  padding-left:0;
  margin-left:0 !important;
  padding-top:0px;
  margin-top:5px;
  margin-bottom:0;
  padding-bottom:0;
  font-size:15px;
  text-decoration: none;
  border-bottom-width:0px;
}
.details-block,
.details-block li{
  text-align:left;
  width:100%;
  display:block;
  border-bottom-width:0px;
  text-decoration: none;
}
.details-block li.details-block-heading{
  padding:5px 0px 0px 0px;
  border-bottom-width:0px;
  font-size:10px;
  text-transform: uppercase;
  text-decoration: none;
}
.details-block li.details-block-data{
  padding:0px 0px 0px 0px;
  text-decoration: none;
  font-weight:bold;
  border-bottom-width:0px;
}


/* Outlook First */

body.outlook img {
  width: auto !important;
  max-width: none !important;
}

/*  Media Queries */

@media only screen and (max-width: 600px) {

  table[class="body"] img {
    width: auto !important;
    height: auto !important;
  }

  table[class="body"] .container {
    width: 95% !important;
  }

  table[class="body"] .row {
    width: 100% !important;
    display: block !important;
  }

  table[class="body"] .wrapper {
    display: block !important;
    padding-right: 0 !important;
  }

  table[class="body"] .columns,
  table[class="body"] .column {
    table-layout: fixed !important;
    float: none !important;
    width: 100% !important;
    padding-right: 0px !important;
    padding-left: 0px !important;
    display: block !important;
  }

  table[class="body"] .wrapper.first .columns,
  table[class="body"] .wrapper.first .column {
    display: table !important;
  }

  table[class="body"] table.columns td,
  table[class="body"] table.column td {
    width: 100%;
  }

  table[class="body"] td.offset-by-one,
  table[class="body"] td.offset-by-two,
  table[class="body"] td.offset-by-three,
  table[class="body"] td.offset-by-four,
  table[class="body"] td.offset-by-five,
  table[class="body"] td.offset-by-six,
  table[class="body"] td.offset-by-seven,
  table[class="body"] td.offset-by-eight,
  table[class="body"] td.offset-by-nine,
  table[class="body"] td.offset-by-ten,
  table[class="body"] td.offset-by-eleven {
    padding-left: 0 !important;
  }

  table[class="body"] .expander {
    width: 9999px !important;
  }

  table[class="body"] .hide-for-small,
  table[class="body"] .show-for-desktop {
    display: none !important;
  }

  table[class="body"] .show-for-small,
  table[class="body"] .hide-for-desktop {
    display: inherit !important;
  }
}
  </style>
  <style>
    
    .facebook table td {
      background: #3b5998;
      border-color: #2d4473;
    }

    .facebook:hover table td {
      background: #2d4473 !important;
    }

    .twitter table td {
      background: #00acee;
      border-color: #0087bb;
    }

    .twitter:hover table td {
      background: #0087bb !important;
    }

    .google-plus table td {
      background-color: #DB4A39;
      border-color: #CC0000;
    }

    .google-plus:hover table td {
      background: #CC0000 !important;
    }

    .template-label {
      color: #ffffff;
      font-weight: bold;
      font-size: 11px;
    }

    .callout .panel {
      background: #ECF8FF;
      border-color: #b9e5ff;
    }

    .header {
      background: #2A3542;
    }

    .footer .wrapper {
      background: #ebebeb;
    }

    .footer h5 {
      padding-bottom: 10px;
    }

    table.columns .text-pad {
      padding-left: 10px;
      padding-right: 10px;
    }

    table.columns .left-text-pad {
      padding-left: 10px;
    }

    table.columns .right-text-pad {
      padding-right: 10px;
    }

    @media only screen and (max-width: 600px) {

      table[class="body"] .right-text-pad {
        padding-left: 10px !important;
      }

      table[class="body"] .left-text-pad {
        padding-right: 10px !important;
      }
    }

  </style>
</head>
<body>
<?php echo $this->fetch('content');?>
</body>
</html>