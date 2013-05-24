<?php
#####################################################################################
#
# Perpetual Calendar
# calendar.class.php 
# ver 1.0.1
# 3-10-03
#
# ZaireWeb Solutions
# By MT Jordan
# mtjo@netzero.net
# 
#####################################################################################

class calendar
{
    ##############################################################
    # Calendar style/offset variables
    ##############################################################
    
    var $height;
    var $width;
    var $border;
    var $border_size;
    var $bg;
    var $pad;
    var $space;
    var $title_font;
    var $title_bg;
    var $week_bg;
    var $week_font;
    var $sun;
    var $mon;
    var $tue;
    var $wed;
    var $thu;
    var $fri;
    var $sat;
    var $v_align;
    var $h_align;
    var $day_bg;
    var $day_font;
    var $cal_empty;
    var $cur_font;
    var $cur_bg;
    var $cal_type;
    var $user_offset;

    #############################################################
    # Time offset and table offset functions
    #############################################################
    
    /** calculate users local time offset  */
    
    function setOffset()
    {
        $this->cal_offset =  (time() + (($this->user_offset) * 3600));
    }
    
    /** find # of days in month */
    
    function setCheckcell()
    {
        $this->check_cell = date('t', $this->cal_offset);
    }
    
    /** find which day the 1st falls on and calculate # of empty cells at beginning of month */
    
    function setCol()
    {	
	    if($this->cal_type == 0)
        {
        	$this->col = date('w', mktime(0,0,0,date('n', $this->cal_offset),1,date('y', $this->cal_offset)));
        }
        elseif($this->cal_type == 1)
        {
        	if(date('w', mktime(0,0,0,date('n', $this->cal_offset),1,date('y', $this->cal_offset))) == 0)
        	{
	        	/** prevent colspan from being a neg integer */
	        	
        		$this->col = 0;
        	}
        	else
        	{
	        	$this->col = date('w', mktime(0,0,0,date('n', $this->cal_offset),1,date('y', $this->cal_offset))) - 1;
        	}
        		
        }
    }
    
    /** numeric representation of current month */
    
    function setGetmonth()
    {
        $this->get_month = date('n', $this->cal_offset);
    }

    #############################################################
    # Generate calendar function
    #############################################################
    
    function gen_cal()
    {
	    $this->setOffset();
        $this->setCheckcell();
        $this->setCol();
               
        /** generate calendar table */
        
        ?>
        
        <table cellpadding="<?php echo($this->border_size); ?>" cellspacing="0" border="0" summary="calendar" width="<?php echo($this->width); ?>" height="<?php echo($this->height); ?>">
        <tr>
        <td bgcolor="<?php echo($this->border); ?>">
        <table cellpadding="0" cellspacing="0" border="0" summary="calendar" width="<?php echo($this->width); ?>" height="<?php echo($this->height); ?>">
        <tr>
        <td>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
        <td align="center" bgcolor="<?php echo($this->title_bg); ?>" width="100%"><span style="<?php echo($this->title_font); ?>"><?php echo(date('F',$this->cal_offset) . ' ' . date('Y',$this->cal_offset)); ?></span></td>
        </tr>
        </table>
        </td>
        </tr>
        <tr>
        <td bgcolor="<?php echo($this->bg); ?>">
        <table cellpadding="<?php echo($this->pad); ?>" cellspacing="<?php echo($this->space); ?>" border="0" width="<?php echo($this->width); ?>" height="<?php echo($this->height); ?>" summary="calendar">
        <tr>
        
        <?php
        
        /** generate weekday header */
        
        if($this->cal_type == 0)
        {
        	$week = array($this->sun,$this->mon,$this->tue,$this->wed,$this->thu,$this->fri,$this->sat);
        }
        elseif($this->cal_type == 1)
        {
       		$week = array($this->mon,$this->tue,$this->wed,$this->thu,$this->fri,$this->sat,$this->sun);
        }
        else
        {
	        $week = array($this->sun,$this->mon,$this->tue,$this->wed,$this->thu,$this->fri,$this->sat);
        }
        
        for($w = 0; $w <= 6; $w++)
        {
            ?>
            <td bgcolor="<?php echo($this->week_bg); ?>" height="15" align="center" width="14%"><span style="<?php echo($this->week_font); ?>"><?php echo($week[$w]); ?></span></td>
            <?php
        }
        ?>
        
        </tr>
        <tr>
        
        <?php
        
        $a = 8 - $this->col;
        $b = 15 - $this->col;
        $c = 22 - $this->col;
        $d = 29 - $this->col;
        $e = 36 - $this->col;
        
        /** generate empty cells */
        
        if($this->col != 0)
        {
            ?>
            <td colspan="<?php echo($this->col); ?>" style="background: <?php echo($this->cal_empty); ?>;"></td>
            <?php
        }
        
        /** generate calendar days */
        
        for($i = 1; $i <= $this->check_cell; $i++)
        {
	        /** force table to generate new table row after 7 days  */	        
	        
            if(($i == $a) || ($i == $b) || ($i == $c) || ($i == $d) || ($i == $e))
            {
                echo '</tr><tr>';
            }
            
            if($i > ($this->check_cell) + 1)
            {
                echo '<td></td>';
            }
            
            /** if current day, add style attributes */
            
            elseif(($i < ($this->check_cell) + 1) && ($i == date('j', $this->cal_offset)))
            {
                ?>
                <td align="<?php echo($this->h_align); ?>" valign="<?php echo($this->v_align); ?>" bgcolor="<?php echo($this->cur_bg); ?>"><span style="<?php echo($this->day_font); ?>"><?php echo($i); ?></span></td>
                <?php
            }
            elseif($i < ($this->check_cell) + 1)
            {   ?>
                <td align="<?php echo($this->h_align); ?>" valign="<?php echo($this->v_align); ?>" bgcolor="<?php echo($this->day_bg); ?>"><span style="<?php echo($this->day_font); ?>"><?php echo($i); ?></span></td>
                <?php
            }
        }
        
        /* Clean up empty cells at end of month */
        
        $day_28 = array('1','2','3','4','5','6');
        $day_28_span = array('6','5','4','3','2','1');
        $day_29 = array('0','1','2','3','4','5');
        $day_29_span = array('6','5','4','3','2','1');
        $day_30 = array('0','1','2','3','4','6');
        $day_30_span = array('5','4','3','2','1','6');
        $day_31 = array('0','1','2','3','5','6');
        $day_31_span = array('4','3','2','1','6','5');

        if($this->check_cell == 31)
        {
            for($i = 0; $i <= 5; $i++)
            {
                if($this->col == $day_31[$i])
                {
                    echo("<td colspan=\"$day_31_span[$i]\" bgcolor=\"$this->cal_empty\"></td>");
                }
            }
        }
        
        if($this->check_cell == 30)
        {
            for($i = 0; $i <= 5; $i++)
            {
                if($this->col == $day_30[$i])
                {
                    echo("<td colspan=\"$day_30_span[$i]\" bgcolor=\"$this->cal_empty\"></td>");
                }
            }
        }
        
        if($this->check_cell == 29)
        {
            for($i = 0; $i <= 5; $i++)
            {
                if($this->col == $day_29[$i])
                {
                    echo("<td colspan=\"$day_29_span[$i]\" bgcolor=\"$this->cal_empty\"></td>");
                }
            }
        }
        
        if($this->check_cell == 28)
        {
            for($i = 0; $i <= 5; $i++)
            {
                if($this->col == $day_28[$i])
                {
                    echo("<td colspan=\"$day_28_span[$i]\" bgcolor=\"$this->cal_empty\"></td>");
                }
            }
        }
        
        ?>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        <?php
    }
}
?>
