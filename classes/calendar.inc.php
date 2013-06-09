<?php

#####################################################################################
#
# Perpetual Calendar
# calendar.inc.php 
# ver 1.0.1
# 3-104-03
#
# ZaireWeb Solutions
# By MT Jordan
# mtjo@netzero.net
# 
#####################################################################################

include_once('calendar.class.php');
$cal = new calendar;

##############################################################
# Edit calendar table attributes
##############################################################

$cal->height      = '160';    	// Calendar height
$cal->width       = '180';    	// Calendar width - width should be ~20 > height for a symmetrical calendar table
$cal->border      = '#cccccc';  // Calendar border color
$cal->border_size = 1;          // Calendar border size
$cal->bg          = '#cccccc';  // Calendar bgcolor
$cal->pad         = 0;          // Calendar cellpadding
$cal->space       = 1;          // Calendar cellspacing

##############################################################
# Edit calendar month title attributes
##############################################################

$cal->title_font  = 'font-family: verdana,helvetica,arial,sans-serif; color: #000000; font-size: 10pt; font-weight: bold;'; // Month and year text style
$cal->title_bg    = '#ffffff';  // Month and year bgcolor

##############################################################
# Edit weekday header attributes
##############################################################

$cal->week_bg     = '#3399cc';  // Weekday header bgcolor
$cal->week_font   = 'font-family: verdana,helvetica,arial,sans-serif; font-size: 9px; color: #ffffff; font-weight: bold'; // Weekday header font style
$cal->sun         = 'S';      // Weekday header name abbreviations
$cal->mon         = 'M';
$cal->tue         = 'T';
$cal->wed         = 'W';
$cal->thu         = 'T';
$cal->fri         = 'F';
$cal->sat         = 'S';

##############################################################
# Edit current weekday style settings
##############################################################

$cal->cur_font    = 'font-family: verdana,helvetica,arial,sans-serif; color: #ffffff; font-size: 8px; font-weight: bold'; // Font color highlight for current calender day
$cal->cur_bg      = 'orange';   // Current calendar day bgcolor

##############################################################
# Edit weekday style settings
##############################################################

$cal->v_align     = 'top';      // Vertical position of weekday number - ie: top, middle, bottom
$cal->h_align     = 'left';     // Horizontal position of weekday number - ie: left, center, right
$cal->day_bg      = '#ffffe7';  // Calendar day bgcolor
$cal->day_font    = 'font-family: verdana,helvetica,arial,sans-serif; color: #000000; font-size: 8px; font-weight: normal'; // Calendar day font style
$cal->cal_empty   = '';         // Empty calendar day bgcolor - leave blank to default to calendar bgcolor $bg

##############################################################
# Edit calendar start day: 0=sunday 1=monday
##############################################################

$cal->cal_type = 0;

#############################################################
# Edit time offset for user time zone
#############################################################

$cal->user_offset = 0;  // See zone.php for more info

#############################################################
# Generate calendar table - Do Not alter!
#############################################################

$cal->gen_cal();

?>

